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
    'palette' =>
        '<circle cx="13.5" cy="6.5" r=".5"/>' .
        '<circle cx="17.5" cy="10.5" r=".5"/>' .
        '<circle cx="8.5" cy="7.5" r=".5"/>' .
        '<circle cx="6.5" cy="12.5" r=".5"/>' .
        '<path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.9 0 1.6-.7 1.6-1.7 ' .
        '0-.4-.2-.8-.4-1.1-.3-.3-.4-.7-.4-1.1a1.6 1.6 0 0 1 1.7-1.7h2' .
        'c3 0 5.5-2.5 5.5-5.5C22 6 17.5 2 12 2z"/>',
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
    'file' =>
        '<path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z"/>' .
        '<path d="M14 3v5h5M9 13h6M9 17h6"/>',
    'check' =>
        '<path d="M20 6L9 17l-5-5"/>',
    'plus' =>
        '<path d="M12 5v14M5 12h14"/>',
    'close' =>
        '<path d="M18 6L6 18M6 6l12 12"/>',
    'minus' =>
        '<path d="M5 12h14"/>',
    'alert' =>
        '<circle cx="12" cy="12" r="9"/><path d="M12 8v5M12 16h.01"/>',
    'clock' =>
        '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
    'shield' =>
        '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>' .
        '<path d="M9 12l2 2 4-4"/>',
    'cash' =>
        '<rect x="2" y="6" width="20" height="12" rx="2"/>' .
        '<circle cx="12" cy="12" r="2.5"/><path d="M6 12h.01M18 12h.01"/>',
    'user' =>
        '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>' .
        '<circle cx="12" cy="7" r="4"/>',
    'lock' =>
        '<rect x="4" y="11" width="16" height="10" rx="2"/>' .
        '<path d="M8 11V7a4 4 0 0 1 8 0v4"/>',
    'eye' =>
        '<path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/>' .
        '<circle cx="12" cy="12" r="3"/>',
    'eyeOff' =>
        '<path d="M17.9 17.9A10.9 10.9 0 0 1 12 19C5 19 1 12 1 12a18.5 18.5 0 0 1 5.1-5.9"/>' .
        '<path d="M9.9 4.2A9.7 9.7 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.2 3.2"/>' .
        '<path d="M14.1 14.1a3 3 0 1 1-4.2-4.2M1 1l22 22"/>',
    'camera' =>
        '<path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>' .
        '<circle cx="12" cy="13" r="4"/>',
    'database' =>
        '<ellipse cx="12" cy="5" rx="9" ry="3"/>' .
        '<path d="M21 12c0 1.7-4 3-9 3s-9-1.3-9-3M3 5v14c0 1.7 4 3 9 3s9-1.3 9-3V5"/>',
    'monitor' =>
        '<rect x="2" y="3" width="20" height="14" rx="2"/>' .
        '<path d="M8 21h8M12 17v4"/>',
    'smartphone' =>
        '<rect x="6" y="2" width="12" height="20" rx="2"/><path d="M12 18h.01"/>',
];

function renderIcon(string $iconName, int $size = 22): string
{
    $paths = ICON_PATHS[$iconName];

    return '<svg viewBox="0 0 24 24" width="' . $size . '" height="' . $size .
        '" fill="none" ' .
        'stroke="currentColor" stroke-width="1.9" stroke-linecap="round" ' .
        'stroke-linejoin="round">' . $paths . '</svg>';
}