<?php

$currentStudent = [
    'studentId' => DEMO_USER_ID_BY_ROLE[ROLE_STUDENT],
    'name' => 'Juan Dela Cruz',
    'email' => 'jua***@univ.edu.ph',
    'program' => 'BS Computer Science',
    'yearLevel' => '3rd Year',
    'status' => 'Regular',
];

$userDisplayNameByRole = [
    ROLE_STUDENT => $currentStudent['name'],
    ROLE_STAFF => 'Maria Santos',
    ROLE_ADMIN => 'System Admin',
];

const MAX_COPIES_PER_REQUEST = 10;

// Sample figure shown next to the submit button as social proof.
const REQUESTS_RELEASED_THIS_TERM = 68;

const PURPOSE_SUGGESTIONS = ['Employment', 'Scholarship', 'Transfer'];

$allRequests = [
    [
        'requestId' => 'AX-1048',
        'studentName' => 'Juan Dela Cruz',
        'studentId' => '2021-00123',
        'documentType' =>
            'Transcript of Records - For Employment OTR (Undergraduate)',
        'requestedDate' => 'Oct 08, 2026',
        'status' => STATUS_UNDER_REVIEW,
        'releaseStatus' => RELEASE_NOT_APPLICABLE,
        'paymentStatus' => PAYMENT_UNPAID,
        'registrarRemark' => '',
    ],
    [
        'requestId' => 'AX-1042',
        'studentName' => 'Juan Dela Cruz',
        'studentId' => '2021-00123',
        'documentType' => 'Certificate of Good Moral Character',
        'requestedDate' => 'Oct 06, 2026',
        'status' => STATUS_NEEDS_CORRECTION,
        'releaseStatus' => RELEASE_NOT_APPLICABLE,
        'paymentStatus' => PAYMENT_UNPAID,
        'registrarRemark' => 'Please upload a clearer copy of your valid ID.',
    ],
    [
        'requestId' => 'AX-1031',
        'studentName' => 'Juan Dela Cruz',
        'studentId' => '2021-00123',
        'documentType' => 'True Copy of Grades',
        'requestedDate' => 'Oct 01, 2026',
        'status' => STATUS_READY_FOR_RELEASE,
        'releaseStatus' => RELEASE_READY_FOR_PICKUP,
        'paymentStatus' => PAYMENT_PAID,
        'registrarRemark' => 'Pick up at the Registrar window, 8 AM to 4 PM.',
    ],
    [
        'requestId' => 'AX-1007',
        'studentName' => 'Juan Dela Cruz',
        'studentId' => '2021-00123',
        'documentType' => 'Transcript of Records - For Scholarship Application',
        'requestedDate' => 'Sep 20, 2026',
        'status' => STATUS_COMPLETED,
        'releaseStatus' => RELEASE_PICKED_UP,
        'paymentStatus' => PAYMENT_WAIVED,
        'registrarRemark' => '',
    ],
    [
        'requestId' => 'AX-1050',
        'studentName' => 'Ana Reyes',
        'studentId' => '2020-00871',
        'documentType' => 'Transcript of Records - For Evaluation',
        'requestedDate' => 'Oct 09, 2026',
        'status' => STATUS_SUBMITTED,
        'releaseStatus' => RELEASE_NOT_APPLICABLE,
        'paymentStatus' => PAYMENT_UNPAID,
        'registrarRemark' => '',
    ],
    [
        'requestId' => 'AX-1049',
        'studentName' => 'Carlo Mendoza',
        'studentId' => '2022-01544',
        'documentType' => 'Certificate of Good Moral Character',
        'requestedDate' => 'Oct 08, 2026',
        'status' => STATUS_PROCESSING,
        'releaseStatus' => RELEASE_NOT_APPLICABLE,
        'paymentStatus' => PAYMENT_PAID,
        'registrarRemark' => '',
    ],
    [
        'requestId' => 'AX-1045',
        'studentName' => 'Bea Navarro',
        'studentId' => '2019-00322',
        'documentType' => 'Certificate of Registration (Certified True Copy)',
        'requestedDate' => 'Oct 07, 2026',
        'status' => STATUS_APPROVED_CLEARED,
        'releaseStatus' => RELEASE_FOR_PICKUP,
        'paymentStatus' => PAYMENT_PAID,
        'registrarRemark' => '',
    ],
    [
        'requestId' => 'AX-1039',
        'studentName' => 'Liza Tan',
        'studentId' => '2021-00408',
        'documentType' => 'True Copy of Grades',
        'requestedDate' => 'Oct 03, 2026',
        'status' => STATUS_REJECTED,
        'releaseStatus' => RELEASE_NOT_APPLICABLE,
        'paymentStatus' => PAYMENT_UNPAID,
        'registrarRemark' => 'Outstanding balance at the Cashier.',
    ],
];

const DEMO_PURPOSES = ['Employment', 'Scholarship', 'Board exam', 'Transfer'];
const DEMO_REQUEST_TIMES = ['8:15 AM', '9:42 AM', '10:30 AM', '1:05 PM'];
const DEFAULT_RELEASE_METHOD = 'On-site pickup';

function buildDemoRequirements(string $status): array
{
    $requirements = [
        [
            'label' => 'Request letter',
            'status' => REQUIREMENT_RECEIVED,
            'fileName' => 'request-letter.pdf',
            'fileSize' => '182 KB',
        ],
        [
            'label' => 'Valid ID',
            'status' => $status === STATUS_NEEDS_CORRECTION
                ? REQUIREMENT_REPLACEMENT_NEEDED
                : REQUIREMENT_RECEIVED,
            'fileName' => 'valid-id.jpg',
            'fileSize' => '1.2 MB',
        ],
    ];

    if (in_array($status, STATUSES_WITH_PENDING_ORIGINAL_FORM, true)) {
        $requirements[] = [
            'label' => 'Original clearance form',
            'status' => REQUIREMENT_MISSING,
            'note' => 'Submit in person at the Registrar',
            'fileName' => null,
            'fileSize' => null,
        ];
    }

    return $requirements;
}

$feeByDocumentType = array_column($documentTypes, 'fee', 'label');

// Requests not listed here are for a single copy.
$copiesByRequestId = [
    'AX-1048' => 2,
    'AX-1031' => 3,
    'AX-1050' => 5,
];

$allRequests = array_map(
    function (array $request) use ($feeByDocumentType, $copiesByRequestId) {
        $requestNumber = (int) substr($request['requestId'], strlen('AX-'));
        $purpose = DEMO_PURPOSES[$requestNumber % count(DEMO_PURPOSES)];
        $requestTime =
            DEMO_REQUEST_TIMES[$requestNumber % count(DEMO_REQUEST_TIMES)];

        $copies = $copiesByRequestId[$request['requestId']] ?? 1;
        $feePerCopy = $feeByDocumentType[$request['documentType']] ?? 0;

        return $request + [
            'copies' => $copies,
            'fee' => $feePerCopy * $copies,
            'purpose' => $purpose,
            'requestedTime' => $requestTime,
            'preferredRelease' => DEFAULT_RELEASE_METHOD,
            'supportingInfo' => 'I need this document for my '
                . strtolower($purpose)
                . ' application. Please process it as soon as possible.',
            'requirements' => buildDemoRequirements($request['status']),
        ];
    },
    $allRequests
);

$currentStudentRequests = array_values(array_filter(
    $allRequests,
    fn($request) => $request['studentId'] === $currentStudent['studentId']
));

$employees = [
    [
        'name' => 'Maria Santos',
        'employeeId' => 'EMP-0101',
        'office' => 'Registrar',
        'email' => 'm.santos@univ.edu.ph',
        'lastLogin' => 'Today, 8:12 AM',
        'hasAccess' => true,
    ],
    [
        'name' => 'Paolo Cruz',
        'employeeId' => 'EMP-0102',
        'office' => 'Records',
        'email' => 'p.cruz@univ.edu.ph',
        'lastLogin' => 'Yesterday',
        'hasAccess' => true,
    ],
    [
        'name' => 'Grace Lim',
        'employeeId' => 'EMP-0103',
        'office' => 'Cashier',
        'email' => 'g.lim@univ.edu.ph',
        'lastLogin' => 'Oct 05, 2026',
        'hasAccess' => true,
    ],
    [
        'name' => 'Ramon Diaz',
        'employeeId' => 'EMP-0104',
        'office' => 'Registrar',
        'email' => 'r.diaz@univ.edu.ph',
        'lastLogin' => 'Sep 28, 2026',
        'hasAccess' => false,
    ],
];

$universityApi = [
    'baseUrl' => 'http://192.168.1.20/mock-univ/api/v1',
    'apiKey' => 'mock-key-8f3a21',
    'connectionStatus' => 'Connected',
    'latency' => '42 ms',
    'lastSync' => 'Oct 09, 2026 8:00 AM',
];

$retrievedDataFields = [
    'Student ID',
    'Name',
    'Email or contact',
    'Program',
    'Year level',
    'Enrollment status',
    'Grades',
];

$autoCancelAfterDays = 14;
