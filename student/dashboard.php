<?php

require __DIR__ . '/../includes/bootstrap.php';

$requiredRole = ROLE_STUDENT;
$currentPage = 'dashboard';
$pageTitle = 'Home';
require __DIR__ . '/../includes/header.php';

$inProgressStatuses = array_merge(
    AWAITING_REVIEW_STATUSES,
    PROCESSING_AND_CLEARANCE_STATUSES
);
$requestNeedingCorrection = findFirstRequestWithStatus(
    $currentStudentRequests,
    STATUS_NEEDS_CORRECTION
);
$studentDetailFieldByLabel = [
    'Student ID' => 'studentId',
    'Name' => 'name',
    'Email' => 'email',
    'Program' => 'program',
    'Year level' => 'yearLevel',
    'Status' => 'status',
];
?>
<?php if ($requestNeedingCorrection): ?>
    <?php $correctionRequestId = $requestNeedingCorrection['requestId'] ?>
    <?php $correctionRemark = $requestNeedingCorrection['registrarRemark'] ?>
    <?php $correctionLink = "my-requests.php#$correctionRequestId" ?>
    <div class="alert">
        <b>Action needed on <?= $correctionRequestId ?></b><br>
        <?= $correctionRemark ?>
        <a class="link-accent" href="<?= $correctionLink ?>">Open request</a>
    </div>
<?php endif ?>
<div class="layout-main-side">
    <div class="panel">
        <h3>Recent requests</h3>
        <?php foreach ($currentStudentRequests as $request): ?>
            <a class="recent-request-row"
                href="my-requests.php#<?= $request['requestId'] ?>">
                <span class="avatar">
                    <?= getInitials($request['documentType']) ?>
                </span>
                <span class="recent-request-body">
                    <b><?= $request['documentType'] ?></b>
                    <span class="text-muted">
                        <?= $request['requestId'] ?>,
                        <?= $request['requestedDate'] ?>
                    </span>
                </span>
                <?= renderStatusPill($request['status']) ?>
            </a>
        <?php endforeach ?>
    </div>
    <div class="side-column">
        <div class="panel">
            <h3>Your details</h3>
            <?php foreach ($studentDetailFieldByLabel as $label => $field): ?>
                <div class="key-value-row">
                    <span class="text-muted"><?= $label ?></span>
                    <b><?= $currentStudent[$field] ?></b>
                </div>
            <?php endforeach ?>
            <small class="text-muted">Read from the university system.</small>
        </div>
        <div class="panel">
            <h3>Pickup hours</h3>
            <p class="text-muted">
                Registrar window, Monday to Friday, 8 AM to 4 PM.
            </p>
        </div>
    </div>
</div>
<?php require __DIR__ . '/../includes/footer.php' ?>
