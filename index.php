<?php

require __DIR__ . '/includes/bootstrap.php';

function resolveRole(array $formData): string
{
    $submittedRole = $formData['role'] ?? '';

    return isset(LANDING_PAGE_BY_ROLE[$submittedRole])
        ? $submittedRole
        : ROLE_STUDENT;
}

function logInAndRedirect(string $role): void
{
    $_SESSION['role'] = $role;
    header('Location: ' . LANDING_PAGE_BY_ROLE[$role]);
    exit;
}

if (isset($_GET['logout'])) {
    session_destroy();
}

if ($_POST) {
    logInAndRedirect(resolveRole($_POST));
}

$pageTitle = 'Log in';
$basePath = '';
?>
<!doctype html>
<html lang="en">

<?php require __DIR__ . '/includes/document-head.php' ?>

<body class="auth-page">
    <form class="card" method="post">
        <h1>Academix</h1>
        <p class="text-muted">Request and track university documents.</p>
        <?= renderRoleTabs([
            ROLE_STUDENT => 'Student',
            ROLE_STAFF => 'Staff',
            ROLE_ADMIN => 'Admin',
        ]) ?>
        <label>ID
            <input class="form-control" id="user-id-input"
                value="<?= $currentStudent['studentId'] ?>">
        </label>
        <label>Password
            <input class="form-control" type="password" value="demo1234">
        </label>
        <button class="button button-full-width">Log in</button>
        <p class="text-muted auth-prompt">
            First time here?
            <a class="link-accent" href="activate-account.php">
                Activate your account
            </a>
        </p>
    </form>
    <script src="assets/app.js"></script>
</body>

</html>
