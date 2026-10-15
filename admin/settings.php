<?php

require __DIR__ . '/../includes/bootstrap.php';

$requiredRole = ROLE_ADMIN;
$currentPage = 'settings';
$pageTitle = 'Settings';
require __DIR__ . '/../includes/header.php';

$columnHeaders = ['Document', 'Fee (PHP)', 'Allow upload', 'Enabled'];
?>
<div class="panel panel-scrollable">
    <div class="document-types-table">
        <h3>Document types</h3>
        <?= renderTableRow($columnHeaders, true) ?>
        <?php foreach ($documentTypes as $documentType): ?>
            <?php $feeInput = renderFeeInput($documentType['fee']) ?>
            <?= renderTableRow([
                renderAvatarWithName($documentType['name']),
                $feeInput,
                renderToggleSwitch($documentType['allowsUpload']),
                renderToggleSwitch(true),
            ]) ?>
        <?php endforeach ?>
        <button class="button button-secondary add-document-type-button"
            data-toast-message="Document type added">
            Add document type
        </button>
    </div>
</div>
<div class="panel">
    <h3>General</h3>
    <label>Cancel requests that need correction after (days)
        <input class="form-control" type="number"
            value="<?= $autoCancelAfterDays ?>">
    </label>
    <label>Default release method
        <select class="form-control">
            <option>On-site pickup</option>
            <option>Delivery</option>
        </select>
    </label>
    <button class="button" data-toast-message="Settings saved">
        Save settings
    </button>
</div>
<?php require __DIR__ . '/../includes/footer.php' ?>
