<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $pageTitle ?> | Academix</title>
    <link rel="stylesheet" href="<?= $basePath ?>assets/style.css">
    <script>
        // Inline so the saved theme and sidebar state apply before the first
        // paint. Loading this from app.js would flash the default layout.
        document.documentElement.dataset.theme = localStorage.theme || "light";
        document.documentElement.classList.toggle(
            "sidebar-collapsed",
            localStorage.sidebarCollapsed === "true",
        );
    </script>
</head>
