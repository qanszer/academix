<?php $role = 'staff';
$page = 'queue';
$title = 'Request queue';
require '../inc/top.php';
$n = fn($st) => count(array_filter($R, fn($r) => in_array($r['st'], $st))); ?>
<div class=gr4>
    <div class=pn>
        <p class=mu>Awaiting review</p>
        <h2><?= $n(['Submitted', 'Under Review']) ?></h2>
    </div>
    <div class=pn>
        <p class=mu>Processing and clearance</p>
        <h2><?= $n(['Processing', 'Approved / Cleared']) ?></h2>
    </div>
    <div class=pn>
        <p class=mu>Needs correction</p>
        <h2><?= $n(['Needs Correction']) ?></h2>
    </div>
    <div class=pn>
        <p class=mu>Ready for release</p>
        <h2><?= $n(['Ready for Release']) ?></h2>
    </div>
</div>
<?php md($R, 1);
require '../inc/bottom.php' ?>