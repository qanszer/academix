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

function renderAvatarWithName(string $name): string
{
    $initials = getInitials($name);

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

function renderDocumentTypeOption(array $documentType): string
{
    $allowsUpload = $documentType['allowsUpload'] ? 'true' : 'false';

    return <<<HTML
        <option data-fee="{$documentType['fee']}"
            data-allows-upload="$allowsUpload">{$documentType['name']}</option>
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
            <div class="detail-pane" id="request-detail"></div>
        </div>
        HTML;
}
