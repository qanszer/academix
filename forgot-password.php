<?php

require __DIR__ . '/includes/bootstrap.php';

$pageTitle = 'Forgot password';
$basePath = '';
?>
<!doctype html>
<html lang="en">

<?php require __DIR__ . '/includes/document-head.php' ?>

<body class="auth-page">
    <form class="card" id="forgot-password-form">
        <h1>Forgot password</h1>
        <div id="forgot-step-request">
            <p class="text-muted">
                Enter your ID and email. We send you a link to reset your
                password.
            </p>
            <?= renderRoleTabs([
                ROLE_STUDENT => 'Student',
                ROLE_STAFF => 'Staff',
                ROLE_ADMIN => 'Admin',
            ]) ?>
            <label>ID
                <input class="form-control" id="user-id-input" required
                    value="<?= $currentStudent['studentId'] ?>">
            </label>
            <label>Email
                <input class="form-control" id="reset-email-input"
                    type="email" required
                    placeholder="Email on file with the university">
            </label>
            <p class="text-muted form-note">
                Alumni: use the email you gave when you activated your
                account, and the Student tab.
            </p>
            <button class="button button-full-width">Send reset link</button>
        </div>
        <div id="forgot-step-sent" hidden>
            <p class="text-muted form-note">Check your email.</p>
            <p class="form-note">
                If the ID and email match an account, we sent a password reset
                link to <b id="reset-email-display"></b>. Open it to choose a
                new password.
            </p>
            <p class="text-muted form-note">
                If you do not see it, check your spam folder.
            </p>
            <button class="button button-secondary button-full-width"
                type="button" id="resend-reset-link-button">
                Resend link
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
