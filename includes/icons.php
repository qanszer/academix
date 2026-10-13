<?php

const ICON_PATHS = [
    'home' =>
        '<path d="M3 11l9-8 9 8v10a1 1 0 0 1-1 1h-5v-7H9v7H4a1 1 0 0 1-1-1z"/>',
    'edit' =>
        '<path d="M12 20h9"/>' .
        '<path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/>',
    'list' =>
        '<path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/>',
    'link' =>
        '<path d="M10 13a5 5 0 0 0 7 0l3-3a5 5 0 0 0-7-7l-1 1"/>' .
        '<path d="M14 11a5 5 0 0 0-7 0l-3 3a5 5 0 0 0 7 7l1-1"/>',
    'users' =>
        '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>' .
        '<circle cx="9" cy="7" r="4"/>' .
        '<path d="M23 21v-2a4 4 0 0 0-3-3.9M16 3.1a4 4 0 0 1 0 7.8"/>',
    'sliders' =>
        '<path d="M4 21v-7M4 10V3M12 21v-9M12 8V3M20 21v-5M20 12V3' .
        'M1 14h6M9 8h6M17 16h6"/>',
    'logout' =>
        '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4' .
        'M16 17l5-5-5-5M21 12H9"/>',
    'bell' =>
        '<path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9' .
        'M13.7 21a2 2 0 0 1-3.4 0"/>',
    'search' =>
        '<circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/>',
    'menu' =>
        '<path d="M3 6h18M3 12h18M3 18h18"/>',
    'moon' =>
        '<path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/>',
    'chevronLeft' =>
        '<path d="M15 18l-6-6 6-6"/>',
    'chevronRight' =>
        '<path d="M9 18l6-6-6-6"/>',
];

function renderIcon(string $iconName): string
{
    $paths = ICON_PATHS[$iconName];

    return '<svg viewBox="0 0 24 24" width="22" height="22" fill="none" ' .
        'stroke="currentColor" stroke-width="1.9" stroke-linecap="round" ' .
        'stroke-linejoin="round">' . $paths . '</svg>';
}
