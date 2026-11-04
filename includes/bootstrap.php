<?php

session_start();

require __DIR__ . '/roles.php';
require __DIR__ . '/request-status.php';
require __DIR__ . '/document-catalog.php';
require __DIR__ . '/mock-data.php';
require __DIR__ . '/profile-data.php';
require __DIR__ . '/icons.php';
require __DIR__ . '/navigation.php';
require __DIR__ . '/theme.php';
require __DIR__ . '/components.php';
require __DIR__ . '/profile-components.php';
require __DIR__ . '/db.php';

$currentStudent['studentId'] = '202510875';
try {
    $allRequests = loadDatabaseRequests($pdo);
} catch (PDOException $e) {
    error_log('Academix request query failed: ' . $e->getMessage());
    http_response_code(500);
    exit('Could not load requests. Please try again later.');
}
$currentStudentRequests = array_values(array_filter(
    $allRequests,
    fn($request) => $request['studentId'] === $currentStudent['studentId']
));
