<?php

$currentStaff = [
    'employeeId' => DEMO_USER_ID_BY_ROLE[ROLE_STAFF],
    'name' => $userDisplayNameByRole[ROLE_STAFF],
    'email' => 'm.santos@univ.edu.ph',
    'office' => 'Registrar',
    'position' => 'Records Officer',
    'status' => 'Active',
];

$profileByRole = [
    ROLE_STUDENT => [
        'headline' => $currentStudent['program'] . ', '
            . $currentStudent['yearLevel'],
        'universityFields' => [
            'Student ID' => $currentStudent['studentId'],
            'Full name' => $currentStudent['name'],
            'University email' => $currentStudent['email'],
            'Program' => $currentStudent['program'],
            'Year level' => $currentStudent['yearLevel'],
            'Enrollment status' => $currentStudent['status'],
        ],
        'contactFields' => [
            [
                'name' => 'preferredName',
                'label' => 'Preferred name',
                'type' => 'text',
                'value' => 'Eldric',
                'placeholder' => 'How staff address you',
                'autocomplete' => 'nickname',
                'optional' => true,
                'hint' => 'Your legal name on documents does not change.',
            ],
            [
                'name' => 'mobile',
                'label' => 'Mobile number',
                'type' => 'tel',
                'value' => '0917 555 0123',
                'placeholder' => '0917 123 4567',
                'autocomplete' => 'tel',
                'validate' => 'phone',
                'required' => true,
                'hint' => 'The Registrar calls or texts this number about your requests.',
            ],
            [
                'name' => 'alternateEmail',
                'label' => 'Alternate email',
                'type' => 'email',
                'value' => 'eldric.ponce@example.com',
                'placeholder' => 'name@example.com',
                'autocomplete' => 'email',
                'validate' => 'email',
                'optional' => true,
                'hint' => 'Used if your university email is unavailable.',
            ],
            [
                'name' => 'emergencyName',
                'label' => 'Emergency contact',
                'type' => 'text',
                'value' => 'Rosa Ponce',
                'placeholder' => 'Full name',
                'autocomplete' => 'off',
                'optional' => true,
            ],
            [
                'name' => 'emergencyMobile',
                'label' => 'Emergency contact number',
                'type' => 'tel',
                'value' => '0918 555 0147',
                'placeholder' => '0917 123 4567',
                'autocomplete' => 'off',
                'validate' => 'phone',
                'optional' => true,
            ],
            [
                'name' => 'address',
                'label' => 'Mailing address',
                'type' => 'textarea',
                'value' => 'Blk 4 Lot 12, Mabini St., Brgy. San Roque, Quezon City',
                'placeholder' => 'House number, street, barangay, city',
                'autocomplete' => 'street-address',
                'optional' => true,
                'wide' => true,
                'hint' => 'Used when you choose delivery for a document.',
            ],
        ],
        'visibility' => [
            'Registrar office' => 'Name, ID, program, request files',
            'Cashier' => 'Name, ID, payment status',
            'Administrators' => 'Account status only',
        ],
        'privacyPreferences' => [
            'Show my preferred name to Registrar staff' => true,
            'Hide my contact number from other offices' => true,
        ],
    ],
    ROLE_STAFF => [
        'headline' => $currentStaff['position'] . ', '
            . $currentStaff['office'],
        'universityFields' => [
            'Employee ID' => $currentStaff['employeeId'],
            'Full name' => $currentStaff['name'],
            'University email' => $currentStaff['email'],
            'Office' => $currentStaff['office'],
            'Position' => $currentStaff['position'],
            'Employment status' => $currentStaff['status'],
        ],
        'contactFields' => [
            [
                'name' => 'preferredName',
                'label' => 'Preferred name',
                'type' => 'text',
                'value' => 'Maria',
                'placeholder' => 'How students see your name',
                'autocomplete' => 'nickname',
                'optional' => true,
                'hint' => 'Shown beside your replies in request messages.',
            ],
            [
                'name' => 'mobile',
                'label' => 'Mobile number',
                'type' => 'tel',
                'value' => '0917 555 0188',
                'placeholder' => '0917 123 4567',
                'autocomplete' => 'tel',
                'validate' => 'phone',
                'required' => true,
            ],
            [
                'name' => 'extension',
                'label' => 'Office extension',
                'type' => 'text',
                'value' => '2104',
                'placeholder' => '4 digits',
                'autocomplete' => 'off',
                'validate' => 'digits',
                'optional' => true,
            ],
            [
                'name' => 'alternateEmail',
                'label' => 'Alternate email',
                'type' => 'email',
                'value' => '',
                'placeholder' => 'name@example.com',
                'autocomplete' => 'email',
                'validate' => 'email',
                'optional' => true,
            ],
        ],
        'visibility' => [
            'Students' => 'Name, office, position',
            'Registrar office' => 'Full profile',
            'Administrators' => 'Account status and last login',
        ],
        'privacyPreferences' => [
            'Show my preferred name to students' => true,
            'Show my office extension to students' => false,
        ],
    ],
];

$signedInSessions = [
    [
        'device' => 'Ubuntu 22.04, Firefox',
        'place' => 'Manila, PH',
        'lastActive' => 'Active now',
        'icon' => 'monitor',
        'isCurrent' => true,
    ],
    [
        'device' => 'Windows 11, Edge',
        'place' => 'Manila, PH',
        'lastActive' => '2 days ago',
        'icon' => 'monitor',
        'isCurrent' => false,
    ],
    [
        'device' => 'Android, Chrome',
        'place' => 'Quezon City, PH',
        'lastActive' => 'Oct 20, 2026',
        'icon' => 'smartphone',
        'isCurrent' => false,
    ],
];

const PASSWORD_LAST_CHANGED_LABEL = 'Last changed 3 months ago';
