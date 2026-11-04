<?php
require __DIR__ . '/../includes/bootstrap.php';
header('Content-Type: application/json; charset=utf-8');

if (($_SESSION['role'] ?? '') !== ROLE_STUDENT || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(403);
    echo json_encode(['error' => 'Request not allowed.']);
    exit;
}

$payload = json_decode(file_get_contents('php://input'), true) ?? [];
$payload = is_array($payload) ? $payload : [];
$documentName = is_string($payload['documentType'] ?? null)
    ? trim($payload['documentType']) : '';
$purpose = is_string($payload['purpose'] ?? null)
    ? trim($payload['purpose']) : '';
$quantity = filter_var($payload['quantity'] ?? null, FILTER_VALIDATE_INT);
$description = is_string($payload['description'] ?? null)
    ? trim($payload['description']) : '';
$notes = is_string($payload['notes'] ?? null)
    ? trim($payload['notes']) : '';
if (
    $documentName === '' || $purpose === '' || $quantity === false ||
    $quantity < 1 || $quantity > MAX_COPIES_PER_REQUEST ||
    ($documentName === 'Others' && $description === '') ||
    strlen($purpose) > 2000 || strlen($description) > 1000 ||
    strlen($notes) > 2000
) {
    http_response_code(422);
    echo json_encode([
        'error' => $documentName === 'Others' && $description === ''
            ? 'Describe the document you need.'
            : 'Check the document, purpose, and number of copies.',
    ]);
    exit;
}

try {
    $student = $pdo->query("SELECT user_id FROM users WHERE role_id = (SELECT role_id FROM roles WHERE role_name = 'student') AND student_id = '202510875' LIMIT 1")->fetch();
    $document = $pdo->prepare('SELECT document_type_id FROM document_types WHERE document_name = ? AND is_active = 1 LIMIT 1');
    $document->execute([$documentName]);
    $documentType = $document->fetch();
    if (!$student || !$documentType) {
        http_response_code(422);
        echo json_encode(['error' => 'The student or selected document is not available in the database.']);
        exit;
    }
    if ($documentName === 'Others') $purpose .= ' - ' . $description;
    if ($notes !== '') $purpose .= "\nNotes: " . $notes;

    $pdo->beginTransaction();
    $insert = $pdo->prepare("INSERT INTO document_requests (student_user_id, document_type_id, assigned_staff_user_id, request_status, purpose, quantity, submitted_at, updated_at) VALUES (?, ?, NULL, 'Pending', ?, ?, NOW(), NOW())");
    $insert->execute([$student['user_id'], $documentType['document_type_id'], $purpose, $quantity]);
    $requestId = (int) $pdo->lastInsertId();
    $history = $pdo->prepare("INSERT INTO request_status_history (request_id, changed_by_user_id, previous_status, new_status, change_note, changed_at) VALUES (?, ?, NULL, 'Pending', 'Request submitted by student.', NOW())");
    $history->execute([$requestId, $student['user_id']]);
    $pdo->commit();
    echo json_encode(['ok' => true, 'requestId' => $requestId]);
} catch (Throwable $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    error_log($e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Could not save the request. Please try again.']);
}
