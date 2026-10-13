<?php

require __DIR__ . '/../includes/bootstrap.php';

$requiredRole = ROLE_STUDENT;
$currentPage = 'new-request';
$pageTitle = 'New request';
require __DIR__ . '/../includes/header.php';
?>
<form class="layout-main-side" id="request-form">
    <div class="card">
        <label>Document type
            <select class="form-control" id="document-type-select">
                <?php foreach ($documentTypes as $documentType): ?>
                    <?= renderDocumentTypeOption($documentType) ?>
                <?php endforeach ?>
            </select>
        </label>
        <label id="other-document-field" hidden>Which document do you need?
            <input class="form-control" placeholder="Describe the document">
        </label>
        <label>Purpose of request
            <input class="form-control" required
                placeholder="Employment, scholarship, transfer">
        </label>
        <label>Supporting information
            <textarea class="form-control" rows="3"></textarea>
        </label>
        <label>Preferred release method
            <select class="form-control">
                <option>On-site pickup</option>
                <option disabled>Delivery (coming soon)</option>
            </select>
        </label>
    </div>
    <div class="card">
        <h3>Requirements</h3>
        <div id="upload-section">
            <label class="upload-dropzone">Click to upload files
                <input type="file" id="file-input" multiple hidden>
            </label>
            <p class="text-muted" id="file-list"></p>
            <p class="text-muted form-note">
                Originals and signed forms must be submitted in person at the
                Registrar.
            </p>
        </div>
        <p class="text-muted form-note" id="no-upload-notice">
            No upload needed. Staff will tell you if anything else is required.
        </p>
        <h3>Fee</h3>
        <h2 id="fee-amount"></h2>
        <p class="text-muted form-note">
            Payment is recorded by the Cashier. No online payment.
        </p>
        <button class="button">Submit request</button>
    </div>
</form>
<?php require __DIR__ . '/../includes/footer.php' ?>
