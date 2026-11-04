<?php
require __DIR__ . '/../includes/bootstrap.php';
header('Content-Type: application/json; charset=utf-8');

if (($_SESSION['role'] ?? '') !== ROLE_STAFF || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(403);
    echo json_encode(['error' => 'Request not allowed.']);
    exit;
}
$payload = json_decode(file_get_contents('php://input'), true) ?? [];
$payload = is_array($payload) ? $payload : [];
$requestId = filter_var($payload['requestId'] ?? null, FILTER_VALIDATE_INT);
$uiStatus = is_string($payload['status'] ?? null)
    ? $payload['status'] : '';
$uiToDatabase = [
    STATUS_SUBMITTED => 'Pending',
    STATUS_PROCESSING => 'Processing',
    STATUS_READY_FOR_RELEASE => 'Ready',
    STATUS_COMPLETED => 'Completed',
    STATUS_REJECTED => 'Rejected',
];
$allowedTransitions = [
    'Pending' => ['Processing', 'Rejected'],
    'Processing' => ['Ready', 'Rejected'],
    'Ready' => ['Completed'],
    'Completed' => [],
    'Rejected' => [],
];
if (!$requestId || !isset($uiToDatabase[$uiStatus])) {
    http_response_code(422);
    echo json_encode(['error' => 'Invalid request or status.']);
    exit;
}
try {
    $staffId = $pdo->query("SELECT user_id FROM users WHERE role_id = (SELECT role_id FROM roles WHERE role_name = 'staff') ORDER BY user_id LIMIT 1")->fetchColumn();
    if (!$staffId) {
        throw new RuntimeException('No staff user is available for the status history.');
    }
    $pdo->beginTransaction();
    $find = $pdo->prepare('SELECT request_status FROM document_requests WHERE request_id = ? FOR UPDATE');
    $find->execute([$requestId]);
    $previous = $find->fetchColumn();
    if ($previous === false) {
        $pdo->rollBack();
        http_response_code(404);
        echo json_encode(['error' => 'Request not found.']);
        exit;
    }
    $next = $uiToDatabase[$uiStatus];
    if (!in_array($next, $allowedTransitions[$previous] ?? [], true)) {
        $pdo->rollBack();
        http_response_code(409);
        echo json_encode(['error' => 'That status transition is not allowed.']);
        exit;
    }
    $update = $pdo->prepare('UPDATE document_requests SET request_status = ?, assigned_staff_user_id = ?, updated_at = NOW(), completed_at = ? WHERE request_id = ?');
    $update->execute([$next, $staffId, $next === 'Completed' ? date('Y-m-d H:i:s') : null, $requestId]);
    if ($previous !== $next) {
        $history = $pdo->prepare('INSERT INTO request_status_history (request_id, changed_by_user_id, previous_status, new_status, change_note, changed_at) VALUES (?, ?, ?, ?, ?, NOW())');
        $history->execute([$requestId, $staffId, $previous, $next, 'Status updated by registrar staff.']);
    }
    $pdo->commit();
    echo json_encode([
        'ok' => true,
        'databaseStatus' => $next,
        'status' => array_search($next, $uiToDatabase, true),
    ]);
} catch (Throwable $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    error_log($e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Could not update the request. Please try again.']);
}
