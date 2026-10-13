<?php

require __DIR__ . '/includes/bootstrap.php';

$pageTitle = 'Activate';
$basePath = '';
?>
<!doctype html>
<html lang="en">

<?php require __DIR__ . '/includes/document-head.php' ?>

<body class="auth-page">
    <form class="card">
        <h1>Activate account</h1>
        <p class="text-muted">Your details come from the university system.</p>
        <?= renderRoleTabs([
            ROLE_STUDENT => 'Student',
            ROLE_STAFF => 'Employee',
        ]) ?>
        <div class="activation-step" id="activation-step-1">
            <label>Student or employee ID
                <input class="form-control" id="user-id-input"
                    value="<?= $currentStudent['studentId'] ?>">
            </label>
            <label>Registered email
                <input class="form-control" type="email"
                    placeholder="Email on file with the university">
            </label>
            <p class="text-muted form-note">
                An ID alone is not enough. We send a code to the email on file.
            </p>
            <button class="button" data-next-step="2">Send code</button>
        </div>
        <div class="activation-step" id="activation-step-2" hidden>
            <label>6-digit code
                <input class="form-control" maxlength="6"
                    placeholder="Any 6 digits in this demo">
            </label>
            <button class="button" data-next-step="3">Verify code</button>
        </div>
        <div class="activation-step" id="activation-step-3" hidden>
            <label>New password
                <input class="form-control" type="password">
            </label>
            <label>Confirm password
                <input class="form-control" type="password">
            </label>
            <button class="button" data-next-step="done">
                Activate account
            </button>
        </div>
        <p class="text-muted auth-prompt">
            <a class="link-accent" href="index.php">Back to log in</a>
        </p>
    </form>
    <div class="toast" id="toast"></div>
    <script src="assets/app.js"></script>
</body>

</html>
