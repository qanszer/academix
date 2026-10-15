<?php

require __DIR__ . '/../includes/bootstrap.php';

$requiredRole = ROLE_STAFF;
$currentPage = 'request-queue';
$pageTitle = 'Request queue';
require __DIR__ . '/../includes/header.php';

echo renderStatsGrid([
    'Awaiting review' => countRequestsWithStatus(
        $allRequests,
        AWAITING_REVIEW_STATUSES
    ),
    'Processing and clearance' => countRequestsWithStatus(
        $allRequests,
        PROCESSING_AND_CLEARANCE_STATUSES
    ),
    'Needs correction' => countRequestsWithStatus(
        $allRequests,
        [STATUS_NEEDS_CORRECTION]
    ),
    'Ready for release' => countRequestsWithStatus(
        $allRequests,
        [STATUS_READY_FOR_RELEASE]
    ),
]);
echo renderRequestWorkspace($allRequests, ROLE_STAFF);

require __DIR__ . '/../includes/footer.php';
