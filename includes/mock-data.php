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

$documentTypes = [
    [
        'name' => 'Good Moral Certificate',
        'fee' => 150,
        'allowsUpload' => false,
    ],
    [
        'name' => 'Transcript of Records',
        'fee' => 300,
        'allowsUpload' => true,
    ],
    [
        'name' => 'True Copy of Grades',
        'fee' => 100,
        'allowsUpload' => false,
    ],
    [
        'name' => 'Scholarship-related document',
        'fee' => 0,
        'allowsUpload' => true,
    ],
    [
        'name' => 'Other',
        'fee' => 0,
        'allowsUpload' => false,
    ],
];

$allRequests = [
    [
        'requestId' => 'AX-1048',
        'studentName' => 'Juan Dela Cruz',
        'studentId' => '2021-00123',
        'documentType' => 'Transcript of Records',
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
        'documentType' => 'Good Moral Certificate',
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
        'documentType' => 'Scholarship-related document',
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
        'documentType' => 'Transcript of Records',
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
        'documentType' => 'Good Moral Certificate',
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
        'documentType' => 'Other',
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
