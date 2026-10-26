<?php

require __DIR__ . '/../includes/bootstrap.php';

// Checked here as well as in header.php so a save can redirect before any
// output is sent.
if (($_SESSION['role'] ?? '') !== ROLE_ADMIN) {
    header('Location: ../index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['flash'] = applyThemeSubmission($_POST);
    header('Location: appearance.php');
    exit;
}

$requiredRole = ROLE_ADMIN;
$currentPage = 'appearance';
$pageTitle = 'Appearance';
require __DIR__ . '/../includes/header.php';

$theme = loadTheme();
$flashMessage = $_SESSION['flash'] ?? '';
unset($_SESSION['flash']);
?>
<form class="appearance-form" method="post">
    <div class="appearance-toolbar">
        <p class="text-muted">
            Colors apply to every page for all users. Light and dark are saved
            separately.
        </p>
        <span class="spacer"></span>
        <button class="button button-secondary" form="reset-theme-form">
            Reset to defaults
        </button>
        <button class="button" name="action" value="save">Save theme</button>
    </div>
    <div class="layout-equal-columns">
        <?php foreach (THEME_MODES as $mode => $modeLabel): ?>
            <?= renderThemeEditor($mode, $modeLabel, $theme[$mode]) ?>
        <?php endforeach ?>
    </div>
</form>
<form id="reset-theme-form" method="post">
    <input type="hidden" name="action" value="reset">
</form>
<?php if ($flashMessage !== ''): ?>
    <span hidden data-flash-message="<?= escapeAttribute($flashMessage) ?>"></span>
<?php endif ?>
<?php require __DIR__ . '/../includes/footer.php' ?>
