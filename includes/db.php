<?php
$host = 'localhost';
$dbname = 'academix_db';
$username = 'root';
$password = ''; // Default XAMPP password is usually blank

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    error_log('Academix database connection failed: ' . $e->getMessage());
    http_response_code(500);
    exit('Database connection failed. Please try again later.');
}

/** Return a database-backed request in the shape consumed by app.js. */
function mapDatabaseRequest(array $row): array
{
    $statusMap = [
        'Pending' => STATUS_SUBMITTED,
        'Processing' => STATUS_PROCESSING,
        'Ready' => STATUS_READY_FOR_RELEASE,
        'Completed' => STATUS_COMPLETED,
        'Rejected' => STATUS_REJECTED,
    ];
    $status = $statusMap[$row['request_status']] ?? STATUS_SUBMITTED;
    $date = new DateTimeImmutable($row['submitted_at']);
    $fee = (float) $row['fee'] * (int) $row['quantity'];
    return [
        'requestId' => (string) $row['request_id'],
        'studentName' => trim($row['first_name'] . ' ' . $row['last_name']),
        'studentId' => $row['student_id'] ?? '',
        'documentType' => $row['document_name'],
        'requestedDate' => $date->format('M d, Y'),
        'requestedTime' => $date->format('g:i A'),
        'status' => $status,
        'releaseStatus' => $status === STATUS_READY_FOR_RELEASE
            ? RELEASE_READY_FOR_PICKUP : RELEASE_NOT_APPLICABLE,
        'paymentStatus' => PAYMENT_UNPAID,
        'registrarRemark' => '',
        'copies' => (int) $row['quantity'],
        'fee' => $fee,
        'purpose' => $row['purpose'] ?? '',
        'preferredRelease' => DEFAULT_RELEASE_METHOD,
        'supportingInfo' => $row['purpose'] ?? '',
        'requirements' => [],
        'messages' => [],
        'blockingIssue' => '',
    ];
}

function loadDatabaseRequests(PDO $pdo): array
{
    $statement = $pdo->query(
        "SELECT r.request_id, r.request_status, r.purpose, r.quantity,
                r.submitted_at, d.document_name, d.fee,
                u.first_name, u.last_name, u.student_id
         FROM document_requests r
         JOIN document_types d ON d.document_type_id = r.document_type_id
         JOIN users u ON u.user_id = r.student_user_id
         ORDER BY r.submitted_at DESC, r.request_id DESC"
    );
    return array_map('mapDatabaseRequest', $statement->fetchAll());
}
