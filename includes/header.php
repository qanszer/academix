<?php

if (($_SESSION['role'] ?? '') !== $requiredRole) {
    header('Location: ../index.php');
    exit;
}

$basePath = '../';
$userName = $userDisplayNameByRole[$requiredRole];
$userRoleLabel = ROLE_LABELS[$requiredRole];
?>
<!doctype html>
<html lang="en">

<?php require __DIR__ . '/document-head.php' ?>

<body data-role="<?= $requiredRole ?>">
    <aside class="sidebar">
        <div class="sidebar-brand">
            <b class="brand-logo">A</b>
            <span class="sidebar-label">Academix</span>
        </div>
        <?= renderNavigationLinks($requiredRole, $currentPage) ?>
        <a class="nav-link logout-link" href="../index.php?logout=1">
            <?= renderIcon('logout') ?>
            <span class="sidebar-label">Log out</span>
        </a>
    </aside>
    <div class="main-column">
        <header class="top-bar">
            <button class="icon-button" id="sidebar-toggle"
                title="Collapse sidebar">
                <?= renderIcon('menu') ?>
            </button>
            <h1><?= $pageTitle ?></h1>
            <span class="spacer"></span>
            <?php if ($requiredRole === ROLE_STUDENT && $currentPage !== 'new-request'): ?>
                <a class="button" href="new-request.php">
                    <?= renderIcon('edit') ?>New request
                </a>
            <?php endif ?>
            <button class="icon-button"
                data-toast-message="No new notifications">
                <?= renderIcon('bell') ?>
            </button>
            <button class="icon-button" id="theme-toggle"
                title="Toggle theme">
                <?= renderIcon('moon') ?>
            </button>
            <span class="user-chip">
                <span class="avatar"><?= getInitials($userName) ?></span>
                <span class="user-chip-details">
                    <b class="user-chip-name"><?= $userName ?></b>
                    <small class="user-chip-role text-muted">
                        <?= $userRoleLabel ?>
                    </small>
                </span>
            </span>
        </header>
        <main class="content">
