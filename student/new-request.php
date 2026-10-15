<?php

require __DIR__ . '/../includes/bootstrap.php';

$requiredRole = ROLE_STUDENT;
$currentPage = 'new-request';
$pageTitle = 'New request';
require __DIR__ . '/../includes/header.php';

$releasedThisTermLabel = number_format(REQUESTS_RELEASED_THIS_TERM);
?>
<form class="layout-main-side new-request-form" id="request-form" novalidate>
    <div class="card form-card">
        <section class="form-section" aria-labelledby="document-heading">
            <h3 id="document-heading">
                <span class="step-badge">1</span>Choose a document
            </h3>

            <div class="selected-document" id="selected-document" tabindex="-1"
                role="group" aria-label="Selected document" hidden>
                <span class="icon-container icon-container-large">
                    <?= renderIcon('file', 18) ?>
                </span>
                <span class="selected-document-text">
                    <small class="eyebrow" id="selected-document-category"></small>
                    <b id="selected-document-name"></b>
                    <small class="text-muted" id="selected-document-meta"></small>
                </span>
                <button class="button button-secondary" id="change-document-button"
                    type="button">
                    <?= renderIcon('edit', 16) ?>Change
                </button>
            </div>

            <div class="catalog-panel" id="catalog-panel">
                <button class="button button-secondary catalog-cancel"
                    id="catalog-cancel" type="button" hidden>
                    Keep current document
                </button>
                <?= renderDocumentCatalog($documentTypesByCategory) ?>
            </div>
            <input type="hidden" name="documentType" id="document-type-input">
        </section>

        <div class="request-details" id="request-details" hidden>
            <section class="form-section">
                <h3><span class="step-badge">2</span>Details</h3>
                <div class="form-field" id="other-document-field" hidden>
                    <label for="other-document-input">Describe the document</label>
                    <input class="form-control" id="other-document-input"
                        autocomplete="off"
                        aria-describedby="other-document-input-error"
                        placeholder="For example, certificate of graduation">
                    <p class="field-error" id="other-document-input-error" hidden>
                        <?= renderIcon('alert', 16) ?><span></span>
                    </p>
                </div>
                <div class="form-grid">
                    <div class="form-field">
                        <label for="copies-input" id="copies-label">Copies</label>
                        <div class="stepper">
                            <button class="stepper-button" type="button"
                                data-step="-1" aria-label="Fewer copies">
                                <?= renderIcon('minus', 18) ?>
                            </button>
                            <input class="stepper-input" id="copies-input"
                                type="number" inputmode="numeric" value="1"
                                min="1" max="<?= MAX_COPIES_PER_REQUEST ?>"
                                aria-describedby="copies-hint" required>
                            <button class="stepper-button" type="button"
                                data-step="1" aria-label="More copies">
                                <?= renderIcon('plus', 18) ?>
                            </button>
                        </div>
                        <p class="field-hint" id="copies-hint">
                            Up to <?= MAX_COPIES_PER_REQUEST ?> per request
                        </p>
                    </div>
                    <div class="form-field">
                        <span class="form-label" id="release-method-label">
                            Release method
                        </span>
                        <div class="segmented-control" role="radiogroup"
                            aria-labelledby="release-method-label">
                            <label class="segmented-option">
                                <input class="visually-hidden" type="radio"
                                    name="releaseMethod" value="On-site pickup"
                                    checked>
                                <span>On-site pickup</span>
                            </label>
                            <label class="segmented-option">
                                <input class="visually-hidden" type="radio"
                                    name="releaseMethod" value="Delivery"
                                    disabled>
                                <span>Delivery <small class="badge">Soon</small></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-field">
                    <label for="purpose-input">Purpose</label>
                    <input class="form-control" id="purpose-input" required
                        autocomplete="off" aria-describedby="purpose-input-error"
                        placeholder="What will you use it for?">
                    <div class="suggestion-list" aria-label="Common purposes">
                        <?php foreach (PURPOSE_SUGGESTIONS as $purpose): ?>
                            <button class="chip suggestion-chip" type="button">
                                <?= $purpose ?>
                            </button>
                        <?php endforeach ?>
                    </div>
                    <p class="field-error" id="purpose-input-error" hidden>
                        <?= renderIcon('alert', 16) ?><span></span>
                    </p>
                </div>
                <div class="form-field">
                    <div class="form-label-row">
                        <label for="notes-input">Notes</label>
                        <span class="text-muted form-optional">Optional</span>
                    </div>
                    <textarea class="form-control" id="notes-input" rows="3"
                        placeholder="Anything the Registrar should know"></textarea>
                </div>
            </section>

            <section class="form-section">
                <h3><span class="step-badge">3</span>Files</h3>
                <div id="upload-area">
                    <p class="field-hint" id="upload-hint"></p>
                    <input class="visually-hidden" id="file-input" type="file"
                        accept=".pdf,.jpg,.jpeg,.png" tabindex="-1" multiple>
                    <div class="file-grid" id="file-grid">
                        <button class="file-tile" id="add-file-button"
                            type="button">
                            <span class="file-preview file-preview-add">
                                <?= renderIcon('plus', 18) ?>
                            </span>
                            <span class="file-name">Add file</span>
                            <span class="file-meta">PDF, JPG or PNG</span>
                        </button>
                    </div>
                </div>
                <p class="field-hint" id="no-upload-notice">
                    No upload needed. Staff will tell you if anything else is
                    required.
                </p>
            </section>
        </div>
    </div>

    <aside class="card summary-card" aria-label="Request summary">
        <div class="summary-body">
        <div class="summary-empty" id="summary-empty">
            <span class="icon-container icon-container-large">
                <?= renderIcon('file', 18) ?>
            </span>
            <h3 id="summary-empty-title">No document yet</h3>
            <p class="text-muted">
                Choose a document to see its fee, turnaround time and what you
                need to bring.
            </p>
        </div>

        <div class="summary-filled" id="summary-filled" hidden>
            <div class="summary-header">
                <span class="icon-container icon-container-large">
                    <?= renderIcon('file', 18) ?>
                </span>
                <div>
                    <small class="eyebrow" id="summary-category"></small>
                    <h3 id="summary-title"></h3>
                    <p class="summary-meta text-muted">
                        <?= renderIcon('clock', 16) ?>
                        <span id="summary-turnaround"></span>
                    </p>
                </div>
            </div>
            <p class="text-muted summary-description" id="summary-description"></p>

            <div class="summary-section" id="summary-includes" hidden>
                <h4>Included documents</h4>
                <ul class="requirement-list" id="includes-list"></ul>
            </div>

            <div class="summary-section">
                <h4>Requirements</h4>
                <ul class="requirement-list" id="requirements-list"></ul>
            </div>
        </div>
        </div>

        <div class="summary-section">
            <div class="summary-total" aria-live="polite">
                <div>
                    <p class="text-muted">Total fee</p>
                    <p class="summary-total-amount" id="fee-amount"></p>
                </div>
                <p class="text-muted summary-breakdown" id="fee-breakdown"
                    hidden></p>
            </div>
            <button class="button button-full-width" id="submit-button"
                type="submit">Submit request</button>
            <ul class="trust-list">
                <li>
                    <?= renderIcon('shield', 16) ?>
                    <span>Reviewed by the Registrar office</span>
                </li>
                <li>
                    <?= renderIcon('cash', 16) ?>
                    <span>Pay at the Cashier. No online payment.</span>
                </li>
                <li>
                    <?= renderIcon('check', 16) ?>
                    <span><?= $releasedThisTermLabel ?> requests released
                        this term</span>
                </li>
            </ul>
        </div>
    </aside>
</form>
<?php require __DIR__ . '/../includes/footer.php' ?>
