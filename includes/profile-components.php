<?php

const PROFILE_SECTIONS = [
    'personal' => ['label' => 'Personal info', 'icon' => 'user'],
    'security' => ['label' => 'Password and security', 'icon' => 'lock'],
    'privacy' => ['label' => 'Privacy and data', 'icon' => 'database'],
];

const PASSWORD_RULES = [
    'length' => 'At least 8 characters',
    'case' => 'Upper and lower case letters',
    'number' => 'A number',
    'different' => 'Different from current password',
];

function renderSettingsNav(): string
{
    $linksHtml = '';

    foreach (PROFILE_SECTIONS as $id => $section) {
        $icon = renderIcon($section['icon'], 20);
        $label = $section['label'];
        $linksHtml .= <<<HTML
            <a class="settings-nav-link" href="#$id" data-settings-link="$id">
                $icon<span>$label</span>
            </a>
            HTML;
    }

    return <<<HTML
        <nav class="settings-nav" aria-label="Profile settings sections">
            $linksHtml
        </nav>
        HTML;
}

function renderSettingsSectionHeader(string $title, string $description): string
{
    return <<<HTML
        <header class="settings-section-header">
            <h3>$title</h3>
            <p class="text-muted">$description</p>
        </header>
        HTML;
}

function renderProfileField(array $field): string
{
    $name = $field['name'];
    $id = "profile-$name";
    $label = escapeAttribute($field['label']);
    $value = escapeAttribute($field['value']);
    $placeholder = escapeAttribute($field['placeholder'] ?? '');
    $autocomplete = escapeAttribute($field['autocomplete'] ?? 'off');
    $validate = escapeAttribute($field['validate'] ?? '');
    $isRequired = !empty($field['required']);
    $wideClass = !empty($field['wide']) ? ' form-field-wide' : '';
    $alertIcon = renderIcon('alert', 16);

    $optionalLabel = !empty($field['optional'])
        ? '<small class="form-optional text-muted">Optional</small>'
        : '';
    $requiredAttribute = $isRequired ? ' data-required' : '';
    $hintHtml = isset($field['hint'])
        ? '<p class="field-hint" id="' . $id . '-hint">'
            . escapeAttribute($field['hint']) . '</p>'
        : '';
    $describedBy = isset($field['hint'])
        ? "$id-hint $id-error"
        : "$id-error";

    $attributes = <<<HTML
        class="form-control" id="$id" name="$name"
        placeholder="$placeholder" autocomplete="$autocomplete"
        data-profile-field data-validate="$validate"$requiredAttribute
        aria-describedby="$describedBy"
        HTML;

    $controlHtml = $field['type'] === 'textarea'
        ? "<textarea $attributes rows=\"3\">$value</textarea>"
        : "<input $attributes type=\"{$field['type']}\" value=\"$value\">";

    return <<<HTML
        <div class="form-field$wideClass">
            <div class="form-label-row">
                <label for="$id">$label</label>
                $optionalLabel
            </div>
            $controlHtml
            $hintHtml
            <p class="field-error" id="$id-error" hidden>
                $alertIcon<span></span>
            </p>
        </div>
        HTML;
}

function renderPasswordField(
    string $id,
    string $label,
    string $autocomplete,
    string $labelExtra = ''
): string {
    $eyeIcon = renderIcon('eye', 20);
    $alertIcon = renderIcon('alert', 16);

    return <<<HTML
        <div class="form-field">
            <div class="form-label-row">
                <label for="$id">$label</label>
                $labelExtra
            </div>
            <span class="password-control">
                <input class="form-control" id="$id" type="password"
                    autocomplete="$autocomplete"
                    aria-describedby="$id-error">
                <button class="password-toggle" type="button"
                    data-toggle-password="$id" aria-label="Show password"
                    aria-pressed="false">$eyeIcon</button>
            </span>
            <p class="field-error" id="$id-error" hidden>
                $alertIcon<span></span>
            </p>
        </div>
        HTML;
}

function renderPasswordRules(): string
{
    $checkIcon = renderIcon('check', 14);
    $itemsHtml = '';

    foreach (PASSWORD_RULES as $rule => $label) {
        $itemsHtml .= <<<HTML
            <li data-rule="$rule">
                <span class="rule-mark">$checkIcon</span>$label
            </li>
            HTML;
    }

    return <<<HTML
        <div class="password-strength" id="password-strength" data-level="0">
            <span class="strength-bars" aria-hidden="true">
                <span></span><span></span><span></span><span></span>
            </span>
            <small class="text-muted" id="strength-label"
                aria-live="polite">Enter a new password</small>
        </div>
        <ul class="rule-list">$itemsHtml</ul>
        HTML;
}

function renderSessionRow(array $session): string
{
    $icon = renderIcon($session['icon'], 20);
    $device = escapeAttribute($session['device']);
    $detail = escapeAttribute($session['place'] . ', ' . $session['lastActive']);
    $action = $session['isCurrent']
        ? '<span class="badge">This device</span>'
        : '<button class="button button-secondary button-small" '
            . 'type="button" data-end-session>Sign out</button>';
    $currentAttribute = $session['isCurrent'] ? ' data-current-session' : '';

    return <<<HTML
        <li class="session-row"$currentAttribute>
            <span class="icon-container">$icon</span>
            <span class="session-text">
                <b>$device</b>
                <small class="text-muted">$detail</small>
            </span>
            $action
        </li>
        HTML;
}

function renderSettingsToggleRow(string $label, bool $isOn, string $hint = ''): string
{
    $toggle = renderToggleSwitch($isOn);
    $label = escapeAttribute($label);
    $hintHtml = $hint !== ''
        ? '<small class="text-muted">' . escapeAttribute($hint) . '</small>'
        : '';

    return <<<HTML
        <label class="settings-row">
            <span class="settings-row-text">
                <b>$label</b>
                $hintHtml
            </span>
            $toggle
        </label>
        HTML;
}
