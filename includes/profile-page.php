<?php

// Shared body of student/profile.php and staff/profile.php. The including
// page has already loaded header.php, so $requiredRole and $userName are set.

$profile = $profileByRole[$requiredRole];
$userId = $requiredRole === ROLE_STUDENT
    ? $currentStudent['studentId']
    : $currentStaff['employeeId'];
$cameraIcon = renderIcon('camera', 18);
$lockIcon = renderIcon('lock', 14);
?>
<div class="settings-layout" id="profile-settings">
    <aside class="card settings-aside">
        <div class="settings-identity">
            <span class="avatar avatar-xl" data-profile-avatar>
                <?= getInitials($userName) ?>
            </span>
            <b class="settings-identity-name"><?= $userName ?></b>
            <small class="text-muted"><?= $profile['headline'] ?></small>
            <small class="text-muted"><?= $userId ?></small>
        </div>
        <?= renderSettingsNav() ?>
    </aside>

    <div class="settings-content" id="settings-content">
        <section class="card settings-section" id="personal"
            data-settings-section="personal">
            <?= renderSettingsSectionHeader(
                'Personal info',
                'Your photo and how the university can reach you.'
            ) ?>

            <div class="settings-block photo-row">
                <span class="avatar avatar-xl" data-profile-avatar>
                    <?= getInitials($userName) ?>
                </span>
                <div class="photo-row-text">
                    <b>Profile photo</b>
                    <small class="text-muted">JPG or PNG, up to 5 MB</small>
                    <p class="field-error" id="photo-error" hidden>
                        <?= renderIcon('alert', 16) ?><span></span>
                    </p>
                </div>
                <div class="photo-row-actions">
                    <button class="button button-secondary" type="button"
                        id="photo-change"><?= $cameraIcon ?>Change</button>
                    <button class="button button-secondary" type="button"
                        id="photo-remove" hidden>Remove</button>
                </div>
                <input class="visually-hidden" type="file" id="photo-input"
                    accept="image/png,image/jpeg" tabindex="-1">
            </div>

            <div class="settings-block">
                <div class="settings-block-title">
                    <h4>University record</h4>
                    <span class="badge badge-icon"><?= $lockIcon ?>Read only</span>
                </div>
                <dl class="readonly-grid">
                    <?php foreach ($profile['universityFields'] as $label => $value): ?>
                        <div>
                            <dt class="text-muted"><?= $label ?></dt>
                            <dd><?= escapeAttribute($value) ?></dd>
                        </div>
                    <?php endforeach ?>
                </dl>
                <p class="field-hint">
                    Read from the university system. To correct a detail,
                    contact the Registrar.
                </p>
            </div>

            <form class="settings-block" id="profile-form" novalidate>
                <div class="settings-block-title">
                    <h4>Contact details</h4>
                </div>
                <div class="form-grid">
                    <?php foreach ($profile['contactFields'] as $field): ?>
                        <?= renderProfileField($field) ?>
                    <?php endforeach ?>
                </div>
                <div class="settings-actions">
                    <button class="button" id="profile-save" disabled>
                        Save changes
                    </button>
                    <button class="button button-secondary" type="button"
                        id="profile-discard" disabled>Discard</button>
                    <small class="text-muted" id="profile-dirty-note"
                        aria-live="polite"></small>
                </div>
            </form>
        </section>

        <section class="card settings-section" id="security"
            data-settings-section="security">
            <?= renderSettingsSectionHeader(
                'Password and security',
                'Keep your account safe and see where you are signed in.'
            ) ?>

            <form class="settings-block" id="password-form" novalidate>
                <div class="settings-block-title">
                    <h4>Change password</h4>
                    <small class="text-muted" id="password-last-changed">
                        <?= PASSWORD_LAST_CHANGED_LABEL ?>
                    </small>
                </div>
                <div class="password-fields">
                    <?= renderPasswordField(
                        'current-password',
                        'Current password',
                        'current-password',
                        '<a class="link-accent form-optional" '
                            . 'href="../forgot-password.php">Forgot it?</a>'
                    ) ?>
                    <?= renderPasswordField(
                        'new-password',
                        'New password',
                        'new-password'
                    ) ?>
                    <?= renderPasswordRules() ?>
                    <?= renderPasswordField(
                        'confirm-password',
                        'Confirm new password',
                        'new-password'
                    ) ?>
                </div>
                <div class="settings-actions">
                    <button class="button" id="password-submit">
                        Update password
                    </button>
                </div>
            </form>

            <div class="settings-block">
                <div class="settings-block-title">
                    <h4>Sign-in alerts</h4>
                </div>
                <?= renderSettingsToggleRow(
                    'Email me when a new device signs in',
                    true,
                    'Sent to your university email.'
                ) ?>
            </div>

            <div class="settings-block">
                <div class="settings-block-title">
                    <h4>Where you are signed in</h4>
                    <button class="button button-secondary button-small"
                        type="button" id="end-other-sessions">
                        Sign out other devices
                    </button>
                </div>
                <ul class="session-list" id="session-list">
                    <?php foreach ($signedInSessions as $session): ?>
                        <?= renderSessionRow($session) ?>
                    <?php endforeach ?>
                </ul>
            </div>
        </section>

        <section class="card settings-section" id="privacy"
            data-settings-section="privacy">
            <?= renderSettingsSectionHeader(
                'Privacy and data',
                'What the university shares here and who can see it.'
            ) ?>

            <div class="settings-block">
                <div class="settings-block-title">
                    <h4>Read from the university system</h4>
                    <small class="text-muted">
                        Synced <?= $universityApi['lastSync'] ?>
                    </small>
                </div>
                <div class="chip-list">
                    <?php foreach ($retrievedDataFields as $fieldLabel): ?>
                        <?php if ($requiredRole === ROLE_STAFF && $fieldLabel === 'Grades') continue ?>
                        <span class="chip"><?= $fieldLabel ?></span>
                    <?php endforeach ?>
                </div>
                <p class="field-hint">
                    Changes made in the university system appear here after
                    the next sync.
                </p>
                <button class="button button-secondary" type="button"
                    data-toast-message="Correction request sent to the Registrar">
                    Request a correction
                </button>
            </div>

            <div class="settings-block">
                <div class="settings-block-title">
                    <h4>Who can see your information</h4>
                </div>
                <div class="visibility-list">
                    <?php foreach ($profile['visibility'] as $who => $sees): ?>
                        <div class="key-value-row">
                            <b><?= $who ?></b>
                            <span class="text-muted"><?= $sees ?></span>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>

            <div class="settings-block">
                <div class="settings-block-title">
                    <h4>Visibility preferences</h4>
                </div>
                <?php foreach ($profile['privacyPreferences'] as $label => $isOn): ?>
                    <?= renderSettingsToggleRow($label, $isOn) ?>
                <?php endforeach ?>
            </div>

            <div class="settings-block">
                <div class="settings-block-title">
                    <h4>Your data</h4>
                </div>
                <p class="field-hint">
                    Get a copy of the profile details Academix keeps about you.
                </p>
                <button class="button button-secondary" type="button"
                    data-toast-message="We will email your data copy shortly">
                    Download my data
                </button>
            </div>
        </section>
    </div>
</div>
