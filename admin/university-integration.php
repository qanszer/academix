<?php

require __DIR__ . '/../includes/bootstrap.php';

$requiredRole = ROLE_ADMIN;
$currentPage = 'university-integration';
$pageTitle = 'Integration';
require __DIR__ . '/../includes/header.php';
?>
<div class="layout-equal-columns">
    <div class="card">
        <h3>University API connection</h3>
        <label>Base URL
            <input class="form-control"
                value="<?= $universityApi['baseUrl'] ?>">
        </label>
        <label>API key
            <input class="form-control" type="password"
                value="<?= $universityApi['apiKey'] ?>">
        </label>
        <div class="key-value-row">
            <span>Status</span>
            <span>
                <span class="status-pill" id="connection-status">
                    <?= $universityApi['connectionStatus'] ?>
                </span>
                <span class="text-muted" id="connection-latency">
                    <?= $universityApi['latency'] ?>
                </span>
            </span>
        </div>
        <p class="text-muted form-note" id="last-sync">
            Last sync: <?= $universityApi['lastSync'] ?>
        </p>
        <button class="button" id="test-connection-button">
            Test connection
        </button>
        <button class="button button-secondary" id="sync-button">
            Sync now
        </button>
    </div>
    <div class="card">
        <h3>Data retrieved (read only)</h3>
        <?php foreach ($retrievedDataFields as $field): ?>
            <label class="retrieved-field">
                <input type="checkbox" checked disabled> <?= $field ?>
            </label>
        <?php endforeach ?>
        <p class="text-muted retrieved-data-note">
            Academix only reads from the university system. It never changes the
            source database.
        </p>
    </div>
</div>
<?php require __DIR__ . '/../includes/footer.php' ?>
