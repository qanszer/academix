<?php

const THEME_FILE = __DIR__ . '/../data/theme.json';

const THEME_MODES = [
    'light' => 'Light',
    'dark' => 'Dark',
];

// Alpha suffixes that turn the accent into its tint and divider colors, so
// the admin only picks one accent per mode.
const THEME_DERIVED_ALPHA = [
    'light' => ['tint-accent' => '1c', 'divider-color' => '14'],
    'dark' => ['tint-accent' => '2e', 'divider-color' => '1f'],
];

// Each token maps to the CSS variable of the same name. "against" names the
// token whose background a text color sits on, used for the contrast badge.
const THEME_TOKEN_GROUPS = [
    'Brand' => [
        'accent' => ['label' => 'Accent', 'against' => 'background'],
        'text-color-accent' => ['label' => 'Text on accent', 'against' => 'accent'],
    ],
    'Surfaces' => [
        'background' => ['label' => 'Page background'],
        'panel-surface' => ['label' => 'Panels and sidebar'],
        'tonal-surface' => ['label' => 'Inputs and rows'],
    ],
    'Text' => [
        'text-color-main' => ['label' => 'Headings', 'against' => 'background'],
        'text-color-body' => ['label' => 'Body', 'against' => 'background'],
        'text-color-muted' => ['label' => 'Muted', 'against' => 'background'],
    ],
    'Feedback' => [
        'color-danger' => ['label' => 'Error', 'against' => 'background'],
    ],
    'Status pills' => [
        'pill-default-bg' => ['label' => 'Active background'],
        'pill-default-text' => ['label' => 'Active text', 'against' => 'pill-default-bg'],
        'pill-attention-bg' => ['label' => 'Attention background'],
        'pill-attention-text' => ['label' => 'Attention text', 'against' => 'pill-attention-bg'],
        'pill-muted-bg' => ['label' => 'Closed background'],
        'pill-muted-text' => ['label' => 'Closed text', 'against' => 'pill-muted-bg'],
    ],
];

const THEME_DEFAULTS = [
    'light' => [
        'accent' => '#12307a',
        'text-color-accent' => '#ffffff',
        'background' => '#ffffff',
        'panel-surface' => '#ffffff',
        'tonal-surface' => '#f3f5f9',
        'text-color-main' => '#0f1730',
        'text-color-body' => '#2b3550',
        'text-color-muted' => '#667088',
        'color-danger' => '#b3261e',
        'pill-default-bg' => '#e5e8f0',
        'pill-default-text' => '#12307a',
        'pill-attention-bg' => '#12307a',
        'pill-attention-text' => '#ffffff',
        'pill-muted-bg' => '#f3f5f9',
        'pill-muted-text' => '#667088',
    ],
    'dark' => [
        'accent' => '#8ea8ff',
        'text-color-accent' => '#0a1030',
        'background' => '#080b17',
        'panel-surface' => '#080b17',
        'tonal-surface' => '#0b0f1f',
        'text-color-main' => '#e8ebf7',
        'text-color-body' => '#c9cfe6',
        'text-color-muted' => '#8a93b0',
        'color-danger' => '#ff9b92',
        'pill-default-bg' => '#202741',
        'pill-default-text' => '#8ea8ff',
        'pill-attention-bg' => '#8ea8ff',
        'pill-attention-text' => '#0a1030',
        'pill-muted-bg' => '#0b0f1f',
        'pill-muted-text' => '#8a93b0',
    ],
];

function normalizeHexColor(string $value): ?string
{
    $hex = strtolower(ltrim(trim($value), '#'));

    if (preg_match('/^[0-9a-f]{3}$/', $hex)) {
        $hex = preg_replace('/./', '$0$0', $hex);
    }

    return preg_match('/^[0-9a-f]{6}$/', $hex) ? "#$hex" : null;
}

function getThemeTokenLabels(): array
{
    $labels = [];

    foreach (THEME_TOKEN_GROUPS as $tokens) {
        foreach ($tokens as $key => $meta) {
            $labels[$key] = $meta['label'];
        }
    }

    return $labels;
}

function loadTheme(): array
{
    $theme = THEME_DEFAULTS;

    if (!is_readable(THEME_FILE)) {
        return $theme;
    }

    $saved = json_decode((string) file_get_contents(THEME_FILE), true);

    if (!is_array($saved)) {
        return $theme;
    }

    foreach (array_keys(THEME_MODES) as $mode) {
        $savedColors = is_array($saved[$mode] ?? null) ? $saved[$mode] : [];

        foreach (array_keys(getThemeTokenLabels()) as $key) {
            $savedValue = $savedColors[$key] ?? null;
            $color = is_string($savedValue)
                ? normalizeHexColor($savedValue)
                : null;

            if ($color !== null) {
                $theme[$mode][$key] = $color;
            }
        }
    }

    return $theme;
}

function saveTheme(array $theme): bool
{
    $directory = dirname(THEME_FILE);

    if (!is_dir($directory) && !mkdir($directory, 0755, true)) {
        return false;
    }

    $json = json_encode($theme, JSON_PRETTY_PRINT) . "\n";

    return file_put_contents(THEME_FILE, $json, LOCK_EX) !== false;
}

// Returns the validated theme and the first invalid field, if any.
function parseSubmittedTheme(array $formData): array
{
    $theme = loadTheme();

    foreach (THEME_MODES as $mode => $modeLabel) {
        foreach (getThemeTokenLabels() as $key => $label) {
            $submitted = $formData[$mode][$key] ?? null;
            $color = is_string($submitted)
                ? normalizeHexColor($submitted)
                : null;

            if ($color === null) {
                return [$theme, "$modeLabel $label"];
            }

            $theme[$mode][$key] = $color;
        }
    }

    return [$theme, null];
}

// Returns the message to show the admin after a save or reset.
function applyThemeSubmission(array $formData): string
{
    if (($formData['action'] ?? '') === 'reset') {
        return saveTheme(THEME_DEFAULTS)
            ? 'Theme reset to defaults'
            : 'Could not save theme';
    }

    [$theme, $invalidField] = parseSubmittedTheme($formData);

    if ($invalidField !== null) {
        return "Invalid hex color: $invalidField";
    }

    return saveTheme($theme) ? 'Theme saved' : 'Could not save theme';
}

function themeVariablesCss(array $colors, string $mode): string
{
    $declarations = '';

    foreach ($colors as $key => $value) {
        $declarations .= "--$key:$value;";
    }

    foreach (THEME_DERIVED_ALPHA[$mode] as $key => $alpha) {
        $declarations .= "--$key:{$colors['accent']}$alpha;";
    }

    return $declarations;
}

function renderThemeStyle(): string
{
    $theme = loadTheme();
    $lightCss = themeVariablesCss($theme['light'], 'light');
    $darkCss = themeVariablesCss($theme['dark'], 'dark');

    return '<style id="theme-overrides">'
        . ':root{' . $lightCss . '}'
        . '[data-theme="dark"]{' . $darkCss . '}'
        . '</style>';
}
