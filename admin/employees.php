<?php

require __DIR__ . '/../includes/bootstrap.php';

$requiredRole = ROLE_ADMIN;
$currentPage = 'employees';
$pageTitle = 'Employees';
require __DIR__ . '/../includes/header.php';

$columnHeaders = [
    'Name',
    'Employee ID',
    'Office',
    'Email',
    'Last login',
    'Access',
];
?>
<div class="panel panel-scrollable">
    <div class="employees-table">
        <p class="text-muted form-note">
            Accounts come from the university system. You can turn access on or
            off.
        </p>
        <?= renderTableRow($columnHeaders, true) ?>
        <?php foreach ($employees as $employee): ?>
            <?= renderTableRow([
                renderAvatarWithName($employee['name']),
                $employee['employeeId'],
                $employee['office'],
                $employee['email'],
                $employee['lastLogin'],
                renderToggleSwitch($employee['hasAccess']),
            ]) ?>
        <?php endforeach ?>
    </div>
</div>
<?php require __DIR__ . '/../includes/footer.php' ?>
