<?php

require __DIR__ . '/../includes/bootstrap.php';

$requiredRole = ROLE_STAFF;
$currentPage = 'profile';
$pageTitle = 'Profile settings';
require __DIR__ . '/../includes/header.php';

require __DIR__ . '/../includes/profile-page.php';

require __DIR__ . '/../includes/footer.php';
