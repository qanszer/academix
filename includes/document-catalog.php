<?php

// Mock catalog of requestable documents. Every fee, turnaround and
// requirement below is sample data.

const REQUIREMENT_NOTE_UPLOAD = 'Upload as PDF, JPG or PNG';
const REQUIREMENT_NOTE_IN_PERSON = 'Submit in person at the Registrar';
const REQUIREMENT_NOTE_SHOW_ID = 'Show it when you pick up the document';
const REQUIREMENT_NOTE_CASHIER = 'Checked by the Cashier';
const REQUIREMENT_NOTE_REGISTRAR = 'Checked by the Registrar';
const REQUIREMENT_NOTE_DESCRIBE = 'Describe it in the Details section';

const DOCUMENT_UNIT_COPY = 'copy';
const DOCUMENT_UNIT_SET = 'set';

const TURNAROUND_1_DAY = '1 working day';
const TURNAROUND_2_DAYS = '2 working days';
const TURNAROUND_3_DAYS = '3 working days';
const TURNAROUND_5_DAYS = '5 working days';
const TURNAROUND_7_DAYS = '7 working days';
const TURNAROUND_10_DAYS = '10 working days';

const CATEGORY_DOCUMENT = 'document';
const CATEGORY_TRANSFER_PACKAGE = 'transfer-package';
const CATEGORY_TRANSCRIPT = 'transcript';
const CATEGORY_CERTIFICATES = 'certificates';
const CATEGORY_AUTHENTICATION = 'authentication';
const CATEGORY_CHED = 'ched';
const CATEGORY_CERTIFIED_COPY = 'certified-copy';
const CATEGORY_OTHERS = 'others';

const TRANSCRIPT_NAME_PREFIX = 'Transcript of Records - ';

$documentCategories = [
    [
        'id' => CATEGORY_DOCUMENT,
        'name' => 'Document',
        'tabLabel' => 'Document',
    ],
    [
        'id' => CATEGORY_TRANSFER_PACKAGE,
        'name' => 'Transfer Credential Package',
        'tabLabel' => 'Transfer',
    ],
    [
        'id' => CATEGORY_TRANSCRIPT,
        'name' => 'Transcript of Records',
        'tabLabel' => 'Transcripts',
    ],
    [
        'id' => CATEGORY_CERTIFICATES,
        'name' => 'Certificates',
        'tabLabel' => 'Certificates',
    ],
    [
        'id' => CATEGORY_AUTHENTICATION,
        'name' => 'Authentication / CAV / Apostille',
        'tabLabel' => 'Authentication',
    ],
    [
        'id' => CATEGORY_CHED,
        'name' => 'For CHED Only',
        'tabLabel' => 'CHED only',
    ],
    [
        'id' => CATEGORY_CERTIFIED_COPY,
        'name' => 'Certified True Copy of the Original Records',
        'tabLabel' => 'Certified copies',
    ],
    [
        'id' => CATEGORY_OTHERS,
        'name' => 'Others',
        'tabLabel' => 'Others',
    ],
];

function makeRequirement(string $label, string $note): array
{
    return ['label' => $label, 'note' => $note];
}

function uploadRequirement(string $label): array
{
    return makeRequirement($label, REQUIREMENT_NOTE_UPLOAD);
}

function showAtPickupRequirement(string $label): array
{
    return makeRequirement($label, REQUIREMENT_NOTE_SHOW_ID);
}

function inPersonRequirement(string $label): array
{
    return makeRequirement($label, REQUIREMENT_NOTE_IN_PERSON);
}

function noBalanceRequirement(): array
{
    return makeRequirement(
        'No outstanding balance',
        REQUIREMENT_NOTE_CASHIER
    );
}

// Documents the student collects in person with nothing to upload.
function walkInRequirements(): array
{
    return [
        showAtPickupRequirement('Valid school ID'),
        noBalanceRequirement(),
    ];
}

// Documents that need a letter and ID upfront. $extraUploads are specific to
// the document and come before the original clearance form.
function standardUploadRequirements(array $extraUploads = []): array
{
    return [
        uploadRequirement('Request letter'),
        uploadRequirement('Valid ID'),
        ...array_map('uploadRequirement', $extraUploads),
        inPersonRequirement('Original clearance form'),
    ];
}

function slugify(string $text): string
{
    return trim(strtolower(preg_replace('/[^a-z0-9]+/i', '-', $text)), '-');
}

/**
 * Options:
 *   label       Unique name used on requests, when $name repeats elsewhere.
 *   cardName    Shorter name for the catalog, when the category gives context.
 *   hint        One line under the name on the catalog card.
 *   keywords    Extra words the catalog search should match.
 *   unit        'copy' (default) or 'set', for bundles.
 *   includes    Names of the documents a bundle contains.
 *   isFreeText  The student describes the document themselves.
 */
function makeDocumentType(
    string $categoryId,
    string $name,
    int $fee,
    string $turnaround,
    array $requirements,
    string $description,
    array $options = []
): array {
    $hasUploads = count(array_filter(
        $requirements,
        fn($requirement) => $requirement['note'] === REQUIREMENT_NOTE_UPLOAD
    )) > 0;

    return [
        'id' => slugify("$categoryId $name"),
        'categoryId' => $categoryId,
        'name' => $name,
        'cardName' => $options['cardName'] ?? $name,
        'label' => $options['label'] ?? $name,
        'description' => $description,
        'fee' => $fee,
        'turnaround' => $turnaround,
        'allowsUpload' => $hasUploads,
        'requirements' => $requirements,
        'unit' => $options['unit'] ?? DOCUMENT_UNIT_COPY,
        'includes' => $options['includes'] ?? [],
        'hint' => $options['hint'] ?? '',
        'keywords' => $options['keywords'] ?? '',
        'isFreeText' => $options['isFreeText'] ?? false,
    ];
}

function makeTranscriptType(
    string $reason,
    int $fee,
    string $turnaround,
    array $extraUploads,
    string $description,
    string $keywords = ''
): array {
    $name = TRANSCRIPT_NAME_PREFIX . $reason;

    return makeDocumentType(
        CATEGORY_TRANSCRIPT,
        $name,
        $fee,
        $turnaround,
        standardUploadRequirements($extraUploads),
        $description,
        ['cardName' => $reason, 'keywords' => "transcript tor otr $keywords"]
    );
}

function makeCertificateType(
    string $name,
    int $fee,
    string $turnaround,
    string $description,
    array $extraUploads = [],
    string $keywords = ''
): array {
    $requirements = $extraUploads
        ? [
            uploadRequirement('Valid ID'),
            ...array_map('uploadRequirement', $extraUploads),
            noBalanceRequirement(),
        ]
        : walkInRequirements();

    return makeDocumentType(
        CATEGORY_CERTIFICATES,
        $name,
        $fee,
        $turnaround,
        $requirements,
        $description,
        ['keywords' => $keywords]
    );
}

$documentTypes = [
    // Document
    makeDocumentType(
        CATEGORY_DOCUMENT,
        'True Copy of Grades',
        100,
        TURNAROUND_1_DAY,
        walkInRequirements(),
        'A copy of your grades, certified as a true copy by the Registrar.',
        ['keywords' => 'tcg grades']
    ),
    makeDocumentType(
        CATEGORY_DOCUMENT,
        'AESAR (Automated Evaluation of Students Academic Records)',
        150,
        TURNAROUND_3_DAYS,
        walkInRequirements(),
        'An automated summary of your academic standing and the subjects ' .
            'you still need to complete.',
        [
            'cardName' => 'AESAR',
            'hint' => 'Automated Evaluation of Students Academic Records',
            'keywords' => 'aesar evaluation standing',
        ]
    ),

    // Transfer Credential Package
    makeDocumentType(
        CATEGORY_TRANSFER_PACKAGE,
        'Transfer Credential Package (Honorable Dismissal) Undergraduate',
        400,
        TURNAROUND_5_DAYS,
        [
            ...standardUploadRequirements(),
            noBalanceRequirement(),
        ],
        'Everything a receiving school asks for when you transfer, ' .
            'issued together as one set.',
        [
            'unit' => DOCUMENT_UNIT_SET,
            'hint' => 'Bundle of 3 documents',
            'keywords' => 'transfer honorable dismissal package bundle',
            'includes' => [
                'Certificate of Good Moral Character',
                'Transfer Credential Certificate (Honorable Dismissal)',
                'True Copy of Grades',
            ],
        ]
    ),

    // Transcript of Records
    makeTranscriptType(
        'For Abroad OTR (Undergraduate)',
        350,
        TURNAROUND_7_DAYS,
        ['Admission or visa letter'],
        'Official transcript for study or work outside the Philippines.',
        'abroad overseas foreign'
    ),
    makeTranscriptType(
        'For Employment OTR (Undergraduate)',
        300,
        TURNAROUND_5_DAYS,
        [],
        'Official transcript to submit to an employer.',
        'employment job work'
    ),
    makeTranscriptType(
        'For Evaluation',
        300,
        TURNAROUND_5_DAYS,
        [],
        'Transcript for evaluation by another school or office.',
        'evaluation'
    ),
    makeTranscriptType(
        'For Evaluation (Application for Bachelor of Laws Entrance Exam)',
        320,
        TURNAROUND_5_DAYS,
        ['Law entrance exam application form'],
        'Transcript for the Bachelor of Laws entrance exam application.',
        'law llb entrance exam'
    ),
    makeTranscriptType(
        'For Evaluation (Application for Graduate Studies)',
        320,
        TURNAROUND_5_DAYS,
        ['Graduate school application form'],
        'Transcript for a graduate school application.',
        'graduate masters doctorate'
    ),
    makeTranscriptType(
        'For International Evaluation',
        450,
        TURNAROUND_10_DAYS,
        ['Evaluating agency request form'],
        'Transcript sent to an international credential evaluation agency.',
        'international foreign credential wes'
    ),
    makeTranscriptType(
        'For OJT',
        200,
        TURNAROUND_3_DAYS,
        ['OJT endorsement letter'],
        'Transcript for on-the-job training or internship placement.',
        'ojt internship practicum'
    ),
    makeTranscriptType(
        'For Professional Regulations Commission (PRC - Undergraduate)',
        300,
        TURNAROUND_5_DAYS,
        ['PRC application reference'],
        'Transcript for a PRC licensure exam application.',
        'prc board exam licensure'
    ),
    makeTranscriptType(
        'For Scholarship Application',
        150,
        TURNAROUND_3_DAYS,
        ['Scholarship notice or grant letter'],
        'Transcript to submit with a scholarship application.',
        'scholarship grant'
    ),

    // Certificates
    makeCertificateType(
        'Certificate of Class Ranking (Undergraduate)',
        120,
        TURNAROUND_3_DAYS,
        'States your rank within your graduating class or program.',
        [],
        'rank honors'
    ),
    makeCertificateType(
        'Certificate of Clock Hours (Undergraduate)',
        100,
        TURNAROUND_3_DAYS,
        'Lists the contact hours of the subjects you took.',
        [],
        'hours'
    ),
    makeCertificateType(
        'Certificate of Correction of Name/Birth Date/Place of Birth',
        250,
        TURNAROUND_7_DAYS,
        'Confirms a corrected entry in your school records.',
        ['PSA birth certificate', 'Request letter'],
        'correction amend'
    ),
    makeCertificateType(
        'Certificate of Eligibility (C1) for Law Students',
        150,
        TURNAROUND_5_DAYS,
        'Confirms you meet the requirements to enter law school.',
        ['Request letter'],
        'law c1 eligibility'
    ),
    makeCertificateType(
        'Certificate of English Course Taken',
        100,
        TURNAROUND_3_DAYS,
        'Lists the English courses you completed.',
        [],
        'english'
    ),
    makeCertificateType(
        'Certificate of Equivalency (UAE)',
        300,
        TURNAROUND_7_DAYS,
        'States the equivalency of your program for use in the UAE.',
        ['Letter from the receiving office'],
        'uae emirates equivalency abroad'
    ),
    makeCertificateType(
        'Certificate of General Weighted Average (Undergraduate)',
        100,
        TURNAROUND_2_DAYS,
        'States your general weighted average.',
        [],
        'gwa gpa average'
    ),
    makeCertificateType(
        'Certificate of Good Moral Character',
        150,
        TURNAROUND_2_DAYS,
        'States that you have no record of a disciplinary offense.',
        [],
        'gmc good moral conduct'
    ),
    makeCertificateType(
        'Certificate of Grading System',
        80,
        TURNAROUND_2_DAYS,
        'Explains the grading scale the university uses.',
        [],
        'grading scale'
    ),
    makeCertificateType(
        'Certificate of Medium of Instruction',
        100,
        TURNAROUND_3_DAYS,
        'States the language your courses were taught in.',
        [],
        'moi language english'
    ),
    makeCertificateType(
        'Certificate of Medium of Instruction (with Percentage)',
        130,
        TURNAROUND_3_DAYS,
        'Like the standard certificate, with the share of courses taught ' .
            'in each language.',
        [],
        'moi language percentage'
    ),
    makeCertificateType(
        'Certificate of NSTP Serial Number',
        80,
        TURNAROUND_2_DAYS,
        'States the serial number of your NSTP completion.',
        [],
        'nstp rotc cwts lts serial'
    ),
    makeCertificateType(
        'Certificate of Student Status',
        100,
        TURNAROUND_1_DAY,
        'States whether you are currently enrolled and in what program.',
        [],
        'enrolled regular status'
    ),
    makeCertificateType(
        'Certificate of Translation to English for Filipino Students',
        150,
        TURNAROUND_5_DAYS,
        'English translation of a record issued in Filipino.',
        [],
        'translation filipino english'
    ),
    makeCertificateType(
        'Certificate of Units Earned (Undergraduate)',
        100,
        TURNAROUND_2_DAYS,
        'States how many units you have completed so far.',
        [],
        'units credits'
    ),
    makeCertificateType(
        'Course Descriptions (Undergraduate)',
        250,
        TURNAROUND_7_DAYS,
        'Official descriptions of the courses in your program.',
        [],
        'syllabus curriculum subjects'
    ),
    makeCertificateType(
        'Related Learning Experiences (RLE - Undergraduate)',
        150,
        TURNAROUND_5_DAYS,
        'Records the hours and areas of your related learning experience.',
        [],
        'rle nursing clinical'
    ),

    // Authentication / CAV / Apostille
    makeDocumentType(
        CATEGORY_AUTHENTICATION,
        'For Abroad - Undergraduate',
        500,
        TURNAROUND_10_DAYS,
        [
            ...standardUploadRequirements(['Passport copy']),
            inPersonRequirement('Original documents to authenticate'),
        ],
        'Authentication or apostille of your records for use abroad.',
        [
            'label' => 'Authentication - For Abroad - Undergraduate',
            'keywords' => 'cav apostille abroad authenticate',
        ]
    ),
    makeDocumentType(
        CATEGORY_AUTHENTICATION,
        'Certificate of Enrollment',
        200,
        TURNAROUND_5_DAYS,
        standardUploadRequirements(),
        'Authenticated certificate of enrollment.',
        [
            'label' => 'Authentication - Certificate of Enrollment',
            'keywords' => 'cav apostille authenticate',
        ]
    ),
    makeDocumentType(
        CATEGORY_AUTHENTICATION,
        'Certified Copy of Transcript of Records',
        350,
        TURNAROUND_7_DAYS,
        standardUploadRequirements(),
        'Certified copy of your transcript, ready for authentication.',
        [
            'label' => 'Authentication - Certified Copy of Transcript of ' .
                'Records',
            'keywords' => 'cav apostille authenticate transcript tor',
        ]
    ),
    makeDocumentType(
        CATEGORY_AUTHENTICATION,
        'CHED Endorsement',
        250,
        TURNAROUND_7_DAYS,
        standardUploadRequirements(),
        'School endorsement to submit for CHED authentication.',
        [
            'label' => 'Authentication - CHED Endorsement',
            'keywords' => 'cav apostille authenticate ched',
        ]
    ),

    // For CHED Only
    makeDocumentType(
        CATEGORY_CHED,
        'Certificate of Enrollment',
        100,
        TURNAROUND_3_DAYS,
        [
            uploadRequirement('CHED request form'),
            uploadRequirement('Valid ID'),
            noBalanceRequirement(),
        ],
        'Certificate of enrollment issued for CHED use only.',
        ['label' => 'For CHED - Certificate of Enrollment']
    ),
    makeDocumentType(
        CATEGORY_CHED,
        'CHED Endorsement',
        0,
        TURNAROUND_5_DAYS,
        [
            uploadRequirement('CHED request form'),
            uploadRequirement('Valid ID'),
        ],
        'Endorsement of your records to CHED. No fee when issued to CHED.',
        ['label' => 'For CHED - CHED Endorsement']
    ),
    makeDocumentType(
        CATEGORY_CHED,
        'Transcript of Records',
        300,
        TURNAROUND_5_DAYS,
        standardUploadRequirements(['CHED request form']),
        'Transcript of records issued for CHED use only.',
        [
            'label' => 'For CHED - Transcript of Records',
            'keywords' => 'tor otr',
        ]
    ),

    // Certified True Copy of the Original Records
    makeDocumentType(
        CATEGORY_CERTIFIED_COPY,
        'Academic Calendar',
        50,
        TURNAROUND_1_DAY,
        walkInRequirements(),
        'Certified copy of the academic calendar for your school year.',
        ['label' => 'Academic Calendar (Certified True Copy)']
    ),
    makeDocumentType(
        CATEGORY_CERTIFIED_COPY,
        'Certificate of Registration',
        80,
        TURNAROUND_1_DAY,
        walkInRequirements(),
        'Certified copy of your registration for a given term.',
        [
            'label' => 'Certificate of Registration (Certified True Copy)',
            'keywords' => 'cor registration',
        ]
    ),
    makeDocumentType(
        CATEGORY_CERTIFIED_COPY,
        'Computerized Grades',
        100,
        TURNAROUND_2_DAYS,
        walkInRequirements(),
        'Certified copy of your grades as printed from the system.',
        ['label' => 'Computerized Grades (Certified True Copy)']
    ),

    // Others
    makeDocumentType(
        CATEGORY_OTHERS,
        'Others',
        0,
        'Varies by document',
        [
            makeRequirement(
                'Description of the document',
                REQUIREMENT_NOTE_DESCRIBE
            ),
            showAtPickupRequirement('Valid school ID'),
        ],
        'Not in the list? Describe what you need and the Registrar will ' .
            'confirm the fee.',
        [
            'hint' => 'Not in the list? Describe what you need.',
            'isFreeText' => true,
            'keywords' => 'other not listed custom',
        ]
    ),
];

function groupDocumentTypesByCategory(
    array $categories,
    array $documentTypes
): array {
    return array_map(
        function (array $category) use ($documentTypes) {
            $category['documents'] = array_values(array_filter(
                $documentTypes,
                fn($documentType) =>
                    $documentType['categoryId'] === $category['id']
            ));

            return $category;
        },
        $categories
    );
}

$documentTypesByCategory = groupDocumentTypesByCategory(
    $documentCategories,
    $documentTypes
);
