<?php

require __DIR__ . '/../includes/bootstrap.php';

$requiredRole = ROLE_STUDENT;
$currentPage = 'my-requests';
$pageTitle = 'My requests';
require __DIR__ . '/../includes/header.php';

echo renderRequestWorkspace($currentStudentRequests, ROLE_STUDENT);

require __DIR__ . '/../includes/footer.php';
