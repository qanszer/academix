<?php

function getInitials(string $text): string
{
    // Skips the word "of" so "Transcript of Records" gives "TR", not "TO".
    preg_match_all('/\b(?!of\b)\w/i', $text, $wordInitials);

    return strtoupper(implode('', array_slice($wordInitials[0], 0, 2)));
}

function renderStatusPill(string $label): string
{
    $modifierClasses = STATUS_PILL_CLASSES[$label] ?? '';
    $classes = trim("status-pill $modifierClasses");

    return "<span class=\"$classes\">$label</span>";
}

function renderAvatarWithName(string $name, ?string $initialsSource = null): string
{
    $initials = getInitials($initialsSource ?? $name);

    return <<<HTML
        <span class="avatar-with-name">
            <span class="avatar">$initials</span>
            <b>$name</b>
        </span>
        HTML;
}

function renderFeeInput(int $fee): string
{
    return "<input class=\"form-control\" value=\"$fee\">";
}

function formatFee(int $fee): string
{
    return $fee > 0 ? "₱$fee" : 'No fee';
}

function escapeAttribute(string $text): string
{
    return htmlspecialchars($text, ENT_QUOTES);
}

function encodeJsonAttribute(array $value): string
{
    return escapeAttribute(json_encode($value));
}

function getUploadRequirementLabels(array $documentType): array
{
    $uploadRequirements = array_filter(
        $documentType['requirements'],
        fn($requirement) => $requirement['note'] === REQUIREMENT_NOTE_UPLOAD
    );

    return array_values(array_column($uploadRequirements, 'label'));
}

function renderDocumentCard(array $documentType, array $category): string
{
    $feeClass = $documentType['fee'] > 0 ? '' : ' document-card-fee-none';
    $feeLabel = formatFee($documentType['fee']);
    $clockIcon = renderIcon('clock', 15);
    $checkIcon = renderIcon('check', 14);

    $searchText = strtolower(implode(' ', [
        $documentType['label'],
        $category['name'],
        $documentType['description'],
        $documentType['keywords'],
        implode(' ', $documentType['includes']),
    ]));

    $attributes = [
        'data-document-id' => $documentType['id'],
        'data-category' => $category['id'],
        'data-category-name' => $category['name'],
        'data-label' => $documentType['label'],
        'data-description' => $documentType['description'],
        'data-fee' => (string) $documentType['fee'],
        'data-turnaround' => $documentType['turnaround'],
        'data-unit' => $documentType['unit'],
        'data-allows-upload' => $documentType['allowsUpload'] ? 'true' : 'false',
        'data-free-text' => $documentType['isFreeText'] ? 'true' : 'false',
        'data-search' => $searchText,
    ];
    $attributesHtml = '';
    foreach ($attributes as $name => $value) {
        $attributesHtml .= " $name=\"" . escapeAttribute($value) . '"';
    }

    $requirementsJson = encodeJsonAttribute($documentType['requirements']);
    $uploadLabelsJson = encodeJsonAttribute(
        getUploadRequirementLabels($documentType)
    );
    $includesJson = encodeJsonAttribute($documentType['includes']);

    $name = escapeAttribute($documentType['cardName']);
    $hint = $documentType['hint']
        ? '<span class="document-card-hint">'
            . escapeAttribute($documentType['hint']) . '</span>'
        : '';
    $turnaround = escapeAttribute($documentType['turnaround']);

    return <<<HTML
        <button class="document-card" type="button" aria-pressed="false"
            $attributesHtml
            data-requirements="$requirementsJson"
            data-upload-requirements="$uploadLabelsJson"
            data-includes="$includesJson">
            <span class="document-card-check">$checkIcon</span>
            <span class="document-card-name">$name</span>
            $hint
            <span class="document-card-footer">
                <span class="document-card-turnaround">
                    $clockIcon$turnaround
                </span>
                <span class="document-card-fee$feeClass">$feeLabel</span>
            </span>
        </button>
        HTML;
}

function renderCategoryTab(
    string $id,
    string $label,
    int $count,
    bool $isActive
): string {
    $pressed = $isActive ? 'true' : 'false';
    $label = escapeAttribute($label);

    return <<<HTML
        <button class="category-tab" type="button" aria-pressed="$pressed"
            data-category="$id">
            $label <span class="category-tab-count">$count</span>
        </button>
        HTML;
}

function renderDocumentGroup(array $category): string
{
    $name = escapeAttribute($category['name']);
    $count = count($category['documents']);
    $headingId = 'group-' . $category['id'];
    $cardsHtml = '';

    foreach ($category['documents'] as $documentType) {
        $cardsHtml .= renderDocumentCard($documentType, $category);
    }

    return <<<HTML
        <section class="document-group" data-category="{$category['id']}"
            aria-labelledby="$headingId">
            <h4 class="document-group-heading" id="$headingId">
                $name <span class="document-group-count">$count</span>
            </h4>
            <div class="document-grid">$cardsHtml</div>
        </section>
        HTML;
}

function renderDocumentCatalog(array $documentTypesByCategory): string
{
    $searchIcon = renderIcon('search', 18);
    $closeIcon = renderIcon('close', 16);
    $alertIcon = renderIcon('alert', 16);
    $totalCount = array_sum(array_map(
        fn($category) => count($category['documents']),
        $documentTypesByCategory
    ));

    $tabsHtml = renderCategoryTab('all', 'All', $totalCount, true);
    $groupsHtml = '';
    foreach ($documentTypesByCategory as $category) {
        $tabsHtml .= renderCategoryTab(
            $category['id'],
            $category['tabLabel'],
            count($category['documents']),
            false
        );
        $groupsHtml .= renderDocumentGroup($category);
    }

    return <<<HTML
        <div class="catalog" id="catalog">
            <div class="catalog-toolbar">
                <div class="search-box catalog-search">
                    $searchIcon
                    <input id="catalog-search" type="search"
                        autocomplete="off" aria-label="Search documents"
                        placeholder="Search $totalCount documents, like OJT or good moral">
                    <button class="catalog-search-clear" id="catalog-search-clear"
                        type="button" aria-label="Clear search" hidden>
                        $closeIcon
                    </button>
                </div>
                <div class="category-tabs" role="group"
                    aria-label="Filter by category">
                    $tabsHtml
                </div>
            </div>
            <p class="field-hint catalog-count" id="catalog-count"
                aria-live="polite"></p>
            <p class="field-error" id="catalog-error" hidden>
                {$alertIcon}<span></span>
            </p>
            <div class="document-groups" id="document-groups">
                $groupsHtml
            </div>
            <div class="catalog-empty" id="catalog-empty" hidden>
                <span class="icon-container icon-container-large">
                    $searchIcon
                </span>
                <b>No documents match</b>
                <p class="text-muted">
                    Try a shorter word, or choose Others to describe what
                    you need.
                </p>
                <button class="button button-secondary" id="catalog-reset"
                    type="button">Clear search and filters</button>
            </div>
        </div>
        HTML;
}

function renderToggleSwitch(bool $isOn): string
{
    $checkedAttribute = $isOn ? ' checked' : '';

    return "<input class=\"toggle-switch\" type=\"checkbox\"$checkedAttribute>";
}

function renderTableRow(array $cells, bool $isHeader = false): string
{
    $rowClasses = $isHeader ? 'table-row table-header' : 'table-row';
    $cellsHtml = '';

    foreach ($cells as $cell) {
        $cellsHtml .= "<span>$cell</span>";
    }

    return "<div class=\"$rowClasses\">$cellsHtml</div>";
}

function renderStatsGrid(array $countByLabel): string
{
    $tilesHtml = '';

    foreach ($countByLabel as $label => $count) {
        $tilesHtml .= <<<HTML
            <div class="panel">
                <p class="text-muted">$label</p>
                <h2>$count</h2>
            </div>
            HTML;
    }

    return "<div class=\"stats-grid\">$tilesHtml</div>";
}

function renderRoleTabs(array $tabLabelByRole): string
{
    $tabsHtml = '';

    foreach ($tabLabelByRole as $role => $label) {
        $defaultUserId = DEMO_USER_ID_BY_ROLE[$role];
        $checkedAttribute = $role === array_key_first($tabLabelByRole)
            ? ' checked'
            : '';

        $tabsHtml .= <<<HTML
            <label>
                <input type="radio" name="role" value="$role"
                    data-default-user-id="$defaultUserId"$checkedAttribute>
                <span>$label</span>
            </label>
            HTML;
    }

    return "<div class=\"role-tabs\">$tabsHtml</div>";
}

function renderStatusFilter(): string
{
    $buttonsHtml = '';

    foreach (STATUS_FILTERS as $label => $statuses) {
        $statusList = implode(',', $statuses);
        $activeClass = $label === array_key_first(STATUS_FILTERS)
            ? ' class="active"'
            : '';

        $buttonsHtml .=
            "<button$activeClass data-statuses=\"$statusList\">$label</button>";
    }

    return "<div class=\"status-filter\">$buttonsHtml</div>";
}

function renderRequestListItem(array $request, string $viewerRole): string
{
    $isStaffView = $viewerRole === ROLE_STAFF;
    $attentionStatuses = $isStaffView
        ? STAFF_ATTENTION_STATUSES
        : STUDENT_ATTENTION_STATUSES;
    $needsAttention = in_array($request['status'], $attentionStatuses, true);

    $attentionDot = $needsAttention
        ? '<span class="attention-dot"></span>'
        : '';
    $title = $isStaffView ? $request['studentName'] : $request['documentType'];
    $subtitle = $isStaffView ? $request['documentType'] : $request['requestId'];
    $requestJson = htmlspecialchars(json_encode($request), ENT_QUOTES);
    $statusPill = renderStatusPill($request['status']);

    return <<<HTML
        <div class="request-list-item" data-request="$requestJson">
            <div class="request-list-row">
                <b class="request-list-title">$attentionDot$title</b>
                <small class="text-muted">{$request['requestedDate']}</small>
            </div>
            <div class="request-list-row">
                <span class="text-muted">$subtitle</span>
                <span class="request-status">$statusPill</span>
            </div>
        </div>
        HTML;
}

function renderRequestList(array $requests, string $viewerRole): string
{
    $itemsHtml = '';

    foreach ($requests as $request) {
        $itemsHtml .= renderRequestListItem($request, $viewerRole);
    }

    return "<div class=\"request-list\">$itemsHtml</div>";
}

function renderRequestWorkspace(array $requests, string $viewerRole): string
{
    $searchIcon = renderIcon('search');
    $chevronLeftIcon = renderIcon('chevronLeft');
    $chevronRightIcon = renderIcon('chevronRight');
    $statusFilter = renderStatusFilter();
    $requestList = renderRequestList($requests, $viewerRole);

    return <<<HTML
        <div class="master-detail">
            <div class="detail-pane" id="request-detail"></div>
            <div class="panel list-panel">
                <div class="search-box">
                    $searchIcon
                    <input id="request-search"
                        placeholder="Search by document, ID or date">
                </div>
                $statusFilter
                $requestList
                <div class="list-footer">
                    <span id="result-count"></span>
                    <span>$chevronLeftIcon$chevronRightIcon</span>
                </div>
            </div>
        </div>
        HTML;
}

function renderColorField(
    string $mode,
    string $key,
    array $meta,
    string $value
): string {
    $id = "$mode-$key";
    $label = escapeAttribute($meta['label']);
    $hasContrast = isset($meta['against']);
    $againstAttribute = $hasContrast
        ? ' data-against="' . escapeAttribute($meta['against']) . '"'
        : '';
    $badge = $hasContrast ? '<span class="contrast-badge"></span>' : '';

    return <<<HTML
        <div class="color-field" data-token="$key"$againstAttribute>
            <label for="$id">$label</label>
            $badge
            <span class="color-field-inputs">
                <input class="color-swatch" type="color" value="$value"
                    aria-label="$label picker">
                <input class="form-control color-hex" id="$id"
                    name="{$mode}[{$key}]" value="$value" maxlength="7"
                    pattern="#?([0-9a-fA-F]{3}|[0-9a-fA-F]{6})"
                    spellcheck="false" autocomplete="off">
            </span>
        </div>
        HTML;
}

function renderThemePreview(string $variables): string
{
    $style = escapeAttribute($variables);

    return <<<HTML
        <div class="theme-preview" style="$style" aria-hidden="true">
            <div class="theme-preview-sidebar">
                <b class="brand-logo">A</b>
                <span class="theme-preview-nav theme-preview-nav-active">
                    Home
                </span>
                <span class="theme-preview-nav">Requests</span>
            </div>
            <div class="theme-preview-main">
                <b>Transcript of Records</b>
                <p class="theme-preview-body">
                    Your request is being reviewed.
                </p>
                <p class="theme-preview-muted">Updated 2 hours ago</p>
                <span class="theme-preview-pills">
                    <span class="status-pill">Processing</span>
                    <span class="status-pill status-pill-attention">Ready</span>
                    <span class="status-pill status-pill-muted">Completed</span>
                </span>
                <span class="theme-preview-input">Search requests</span>
                <span class="theme-preview-actions">
                    <span class="button">Submit</span>
                    <span class="button button-secondary">Cancel</span>
                </span>
                <p class="theme-preview-error">Missing requirement</p>
            </div>
        </div>
        HTML;
}

function renderThemeEditor(
    string $mode,
    string $modeLabel,
    array $colors
): string {
    $previewHtml = renderThemePreview(themeVariablesCss($colors, $mode));
    $groupsHtml = '';

    foreach (THEME_TOKEN_GROUPS as $groupName => $tokens) {
        $fieldsHtml = '';

        foreach ($tokens as $key => $meta) {
            $fieldsHtml .= renderColorField($mode, $key, $meta, $colors[$key]);
        }

        $groupsHtml .= <<<HTML
            <div class="theme-group">
                <h4 class="theme-group-title">$groupName</h4>
                $fieldsHtml
            </div>
            HTML;
    }

    return <<<HTML
        <section class="card theme-editor" data-mode="$mode">
            <h3>$modeLabel theme</h3>
            $previewHtml
            $groupsHtml
        </section>
        HTML;
}
