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
            ROLE_ALUMNI => 'Alumni',
        ]) ?>
        <div class="activation-step" id="activation-step-1">
            <label>
                <span data-alumni-text="Student ID">Student or employee ID</span>
                <input class="form-control" id="user-id-input"
                    value="<?= $currentStudent['studentId'] ?>">
            </label>
            <label>
                <span data-alumni-text="Any active email">Registered email</span>
                <input class="form-control" type="email"
                    placeholder="Email on file with the university"
                    data-alumni-placeholder="Any email you can access">
            </label>
            <p class="text-muted form-note"
                data-alumni-text="Your university email has expired, so use any email you can access. We send a code to verify it.">
                An ID alone is not enough. We send a code to the email on file.
            </p>
            <button class="button" data-next-step="2">Send code</button>
        </div>
        <div class="activation-step" id="activation-step-2" hidden>
            <label>6-digit code
                <input class="form-control" maxlength="6"
                    placeholder="Any 6 digits in this demo">
            </label>
            <p class="text-muted form-note" data-alumni-only hidden>
                After verification, your login ID and password are sent to
                this email.
            </p>
            <button class="button" data-next-step="3"
                data-alumni-next-step="done"
                data-alumni-text="Verify and send login details">Verify code</button>
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
