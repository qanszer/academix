const THEME_STORAGE_KEY = "theme";
const SIDEBAR_COLLAPSED_STORAGE_KEY = "sidebarCollapsed";

const TOAST_DURATION_MS = 2200;
const REDIRECT_DELAY_MS = 1000;
const SIMULATED_NETWORK_DELAY_MS = 900;
const MIN_LATENCY_MS = 30;
const LATENCY_RANGE_MS = 40;

const ROLE = Object.freeze({
    STUDENT: "student",
    STAFF: "staff",
});

const STATUS = Object.freeze({
    SUBMITTED: "Submitted",
    UNDER_REVIEW: "Under Review",
    NEEDS_CORRECTION: "Needs Correction",
    PROCESSING: "Processing",
    APPROVED_CLEARED: "Approved / Cleared",
    READY_FOR_RELEASE: "Ready for Release",
    COMPLETED: "Completed",
    REJECTED: "Rejected",
    CANCELLED: "Cancelled",
});

const RELEASE_STATUS = Object.freeze({
    NOT_APPLICABLE: "Not Applicable",
    FOR_PICKUP: "For Pickup",
    READY_FOR_PICKUP: "Ready for Pickup",
    FOR_DELIVERY: "For Delivery",
    IN_TRANSIT: "In Transit",
    DELIVERED: "Delivered",
    PICKED_UP: "Picked Up",
});

const PAYMENT_STATUS = Object.freeze({
    UNPAID: "Unpaid",
    PAID: "Paid",
    WAIVED: "Waived",
});

const REQUEST_PROGRESS_STEPS = [
    STATUS.SUBMITTED,
    STATUS.UNDER_REVIEW,
    STATUS.PROCESSING,
    STATUS.APPROVED_CLEARED,
    STATUS.READY_FOR_RELEASE,
    STATUS.COMPLETED,
];

const ALLOWED_STATUS_TRANSITIONS = {
    [STATUS.SUBMITTED]: [STATUS.UNDER_REVIEW],
    [STATUS.UNDER_REVIEW]: [
        STATUS.PROCESSING,
        STATUS.NEEDS_CORRECTION,
        STATUS.REJECTED,
    ],
    [STATUS.NEEDS_CORRECTION]: [STATUS.UNDER_REVIEW],
    [STATUS.PROCESSING]: [STATUS.APPROVED_CLEARED],
    [STATUS.APPROVED_CLEARED]: [STATUS.READY_FOR_RELEASE],
    [STATUS.READY_FOR_RELEASE]: [STATUS.COMPLETED],
};

const TERMINATED_STATUSES = [STATUS.REJECTED, STATUS.CANCELLED];

const PICKUP_PENDING_STATUSES = [
    RELEASE_STATUS.FOR_PICKUP,
    RELEASE_STATUS.READY_FOR_PICKUP,
];

const STATUS_PILL_CLASSES = {
    [STATUS.NEEDS_CORRECTION]: "status-pill-attention",
    [STATUS.READY_FOR_RELEASE]: "status-pill-attention",
    [RELEASE_STATUS.READY_FOR_PICKUP]: "status-pill-attention",
    [PAYMENT_STATUS.UNPAID]: "status-pill-attention",
    [STATUS.COMPLETED]: "status-pill-muted",
    [STATUS.REJECTED]: "status-pill-muted status-pill-terminated",
    [STATUS.CANCELLED]: "status-pill-muted status-pill-terminated",
    [RELEASE_STATUS.NOT_APPLICABLE]: "status-pill-muted",
    [RELEASE_STATUS.PICKED_UP]: "status-pill-muted",
    [RELEASE_STATUS.DELIVERED]: "status-pill-muted",
    [PAYMENT_STATUS.PAID]: "status-pill-muted",
};

const REQUIREMENT_STATUS = Object.freeze({
    RECEIVED: "Received",
    REPLACEMENT_NEEDED: "Needs replacement",
    MISSING: "Missing",
});

// Statuses during which the student can still add or replace files.
const FILE_UPLOAD_STATUSES = [
    STATUS.SUBMITTED,
    STATUS.UNDER_REVIEW,
    STATUS.NEEDS_CORRECTION,
];

const FILE_PICKER_MESSAGE = "File picker opens here";
const FILE_PREVIEW_MESSAGE = "Preview opens here";

const BLOCKING_ISSUES = [
    "Outstanding balance",
    "Pending clearance",
    "Unresolved violation",
    "Missing requirement",
];

const OTHER_DOCUMENT_TYPE = "Other";
const REGISTRAR_STAFF_LABEL = "Registrar staff";
const DEMO_STUDENT_PROGRAM = "BS Computer Science, 3rd Year";

const rootElement = document.documentElement;
const userRole = document.body.dataset.role;
const isStaffView = userRole === ROLE.STAFF;

const selectOne = (selector) => document.querySelector(selector);
const selectAll = (selector) => [...document.querySelectorAll(selector)];

const createIcon = (paths) =>
    `<svg viewBox="0 0 24 24" width="18" height="18" fill="none" ` +
    `stroke="currentColor" stroke-width="1.9" stroke-linecap="round" ` +
    `stroke-linejoin="round">${paths}</svg>`;

const PAPERCLIP_ICON = createIcon(
    '<path d="M21 11l-9 9a5 5 0 0 1-7-7l9-9a3.5 3.5 0 0 1 5 5l-9 9' +
        'a2 2 0 0 1-3-3l8-8"/>',
);
const SEND_ICON = createIcon('<path d="M22 2L11 13M22 2l-7 20-4-9-9-4z"/>');
const CHECK_ICON = createIcon('<path d="M20 6L9 17l-5-5"/>');
const ALERT_ICON = createIcon(
    '<circle cx="12" cy="12" r="9"/><path d="M12 8v5M12 16h.01"/>',
);
const DOCUMENT_ICON = createIcon(
    '<path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z"/>' +
        '<path d="M14 3v5h5M9 13h6M9 17h6"/>',
);
const PLUS_ICON = createIcon('<path d="M12 5v14M5 12h14"/>');

function getInitials(text) {
    // Skips the word "of" so "Transcript of Records" gives "TR", not "TO".
    const wordInitials = text.match(/\b(?!of\b)\w/gi) || [];
    return wordInitials.slice(0, 2).join("").toUpperCase();
}

function renderStatusPill(label) {
    const modifierClasses = STATUS_PILL_CLASSES[label] || "";
    return `<span class="status-pill ${modifierClasses}">${label}</span>`;
}

function showToast(message) {
    const toast = selectOne("#toast");
    if (!toast) return;

    toast.textContent = message;
    toast.classList.add("visible");
    setTimeout(() => toast.classList.remove("visible"), TOAST_DURATION_MS);
}

function toggleTheme() {
    const nextTheme = rootElement.dataset.theme === "dark" ? "light" : "dark";
    rootElement.dataset.theme = nextTheme;
    localStorage.setItem(THEME_STORAGE_KEY, nextTheme);
}

function toggleSidebar() {
    const isCollapsed = rootElement.classList.toggle("sidebar-collapsed");
    localStorage.setItem(SIDEBAR_COLLAPSED_STORAGE_KEY, isCollapsed);
}

function initLayoutControls() {
    const themeToggle = selectOne("#theme-toggle");
    const sidebarToggle = selectOne("#sidebar-toggle");

    if (themeToggle) themeToggle.addEventListener("click", toggleTheme);
    if (sidebarToggle) sidebarToggle.addEventListener("click", toggleSidebar);
}

function initToastTriggers() {
    selectAll(".toggle-switch").forEach((toggleSwitch) => {
        toggleSwitch.addEventListener("change", () => showToast("Saved"));
    });

    // Delegated so buttons rendered later (like the detail pane) also work.
    document.addEventListener("click", (event) => {
        const trigger = event.target.closest("[data-toast-message]");
        if (trigger) showToast(trigger.dataset.toastMessage);
    });
}

function initDefaultUserIdAutofill() {
    const userIdInput = selectOne("#user-id-input");

    selectAll("[name=role]").forEach((roleRadio) => {
        roleRadio.addEventListener("change", () => {
            userIdInput.value = roleRadio.dataset.defaultUserId || "";
        });
    });
}

function showActivationStep(step) {
    selectAll(".activation-step").forEach((section) => {
        section.hidden = section.id !== `activation-step-${step}`;
    });
}

function finishActivation() {
    showToast("Account activated");
    setTimeout(() => (location.href = "index.php"), REDIRECT_DELAY_MS);
}

function initActivationFlow() {
    selectAll("[data-next-step]").forEach((button) => {
        button.addEventListener("click", (event) => {
            event.preventDefault();

            const nextStep = button.dataset.nextStep;
            if (nextStep === "done") finishActivation();
            else showActivationStep(nextStep);
        });
    });
}

function simulateConnectionAction(button, successMessage, onSuccess) {
    const connectionStatus = selectOne("#connection-status");

    button.addEventListener("click", () => {
        connectionStatus.textContent = "Working...";

        setTimeout(() => {
            connectionStatus.textContent = "Connected";
            onSuccess();
            showToast(successMessage);
        }, SIMULATED_NETWORK_DELAY_MS);
    });
}

function initIntegrationActions() {
    const testConnectionButton = selectOne("#test-connection-button");
    if (!testConnectionButton) return;

    simulateConnectionAction(testConnectionButton, "Connection OK", () => {
        const latencyMs = Math.floor(
            MIN_LATENCY_MS + Math.random() * LATENCY_RANGE_MS,
        );
        selectOne("#connection-latency").textContent = `${latencyMs} ms`;
    });

    simulateConnectionAction(selectOne("#sync-button"), "Sync complete", () => {
        selectOne("#last-sync").textContent = "Last sync: just now";
    });
}

function initRequestForm() {
    const requestForm = selectOne("#request-form");
    if (!requestForm) return;

    const documentTypeSelect = selectOne("#document-type-select");
    const fileInput = selectOne("#file-input");

    function updateFormForSelectedDocumentType() {
        const selectedOption = documentTypeSelect.selectedOptions[0];
        const allowsUpload = selectedOption.dataset.allowsUpload === "true";
        const fee = Number(selectedOption.dataset.fee);

        selectOne("#other-document-field").hidden =
            selectedOption.text !== OTHER_DOCUMENT_TYPE;
        selectOne("#upload-section").hidden = !allowsUpload;
        selectOne("#no-upload-notice").hidden = allowsUpload;
        selectOne("#fee-amount").textContent = fee > 0 ? `₱${fee}` : "No fee";
    }

    documentTypeSelect.addEventListener(
        "change",
        updateFormForSelectedDocumentType,
    );
    updateFormForSelectedDocumentType();

    fileInput.addEventListener("change", () => {
        const fileNames = [...fileInput.files].map((file) => file.name);
        selectOne("#file-list").textContent = fileNames.join(", ");
    });

    requestForm.addEventListener("submit", (event) => {
        event.preventDefault();
        showToast("Request submitted");
        setTimeout(
            () => (location.href = "my-requests.php"),
            REDIRECT_DELAY_MS,
        );
    });
}

let selectedRequest = null;
let selectedListItem = null;

function getProgressIndex(request) {
    // A request needing correction goes back to review, so its history stops
    // at that step.
    return request.status === STATUS.NEEDS_CORRECTION
        ? REQUEST_PROGRESS_STEPS.indexOf(STATUS.UNDER_REVIEW)
        : REQUEST_PROGRESS_STEPS.indexOf(request.status);
}

function renderOptions(labels, selectedLabel) {
    return labels
        .map((label) => {
            const selectedAttribute =
                label === selectedLabel ? " selected" : "";
            return `<option${selectedAttribute}>${label}</option>`;
        })
        .join("");
}

function renderMessage({ isOwnMessage, text, caption }) {
    const ownMessageClass = isOwnMessage ? " own-message" : "";
    return [
        `<div class="message-wrapper${ownMessageClass}">`,
        `<small>${caption}</small>`,
        `<div class="message-bubble">${text}</div>`,
        "</div>",
    ].join("");
}

function renderStatusEvent(label) {
    return `<div class="status-event">${label}</div>`;
}

function renderInitialRequestMessage(request) {
    const text =
        `Hello, I would like to request my ${request.documentType}. ` +
        `Purpose: ${request.purpose}. ` +
        "I attached my request letter and valid ID.";
    const studentCaption = isStaffView ? request.studentName : "You";

    return renderMessage({
        isOwnMessage: !isStaffView,
        text,
        caption: `${studentCaption}, ${request.requestedDate}`,
    });
}

function renderProgressEvents(request) {
    const reachedSteps = REQUEST_PROGRESS_STEPS.slice(
        1,
        getProgressIndex(request) + 1,
    );
    const eventLabels = TERMINATED_STATUSES.includes(request.status)
        ? [...reachedSteps, request.status]
        : reachedSteps;

    return eventLabels.map(renderStatusEvent).join("");
}

function renderRegistrarRemark(request) {
    if (!request.registrarRemark) return "";

    return renderMessage({
        isOwnMessage: isStaffView,
        text: request.registrarRemark,
        caption: isStaffView ? "You" : REGISTRAR_STAFF_LABEL,
    });
}

function renderConversation(request) {
    const otherPartyLabel = isStaffView
        ? request.studentName
        : REGISTRAR_STAFF_LABEL;

    return (request.messages || [])
        .map((message) => {
            const isOwnMessage = message.sender === userRole;
            return renderMessage({
                isOwnMessage,
                text: message.text,
                caption: isOwnMessage ? "You" : otherPartyLabel,
            });
        })
        .join("");
}

function renderThread(request) {
    return [
        renderInitialRequestMessage(request),
        renderProgressEvents(request),
        renderRegistrarRemark(request),
        renderConversation(request),
    ].join("");
}

function formatFee(fee) {
    return fee > 0 ? `₱${fee}` : "No fee";
}

function getRemarkTitle(request) {
    if (request.status === STATUS.NEEDS_CORRECTION) {
        return isStaffView ? "Waiting for corrections" : "Action needed";
    }
    if (request.status === STATUS.REJECTED) return "Reason for rejection";
    return "Registrar note";
}

function renderProgress(request) {
    if (TERMINATED_STATUSES.includes(request.status)) {
        const closedLabel = request.status.toLowerCase();
        return `<p class="progress-caption">Request ${closedLabel}</p>`;
    }

    const reachedIndex = getProgressIndex(request);
    const segments = REQUEST_PROGRESS_STEPS.map((step, index) => {
        const reachedClass = index <= reachedIndex ? " reached" : "";
        return `<span class="progress-step${reachedClass}"></span>`;
    }).join("");
    const caption =
        request.status === STATUS.NEEDS_CORRECTION
            ? "Waiting for corrections"
            : `Step ${reachedIndex + 1} of ${REQUEST_PROGRESS_STEPS.length}, ${request.status}`;

    return (
        `<div class="progress-steps">${segments}</div>` +
        `<p class="progress-caption">${caption}</p>`
    );
}

function renderRemarkCallout(request) {
    if (!request.registrarRemark) return "";

    return `
        <div class="detail-callout">
            <b>${getRemarkTitle(request)}</b>
            <p>${request.registrarRemark}</p>
        </div>`;
}

function renderDetailHeader(request) {
    return `
        <section class="detail-section">
            <div class="detail-title-row">
                <span class="icon-container icon-container-large">
                    ${DOCUMENT_ICON}
                </span>
                <div class="detail-title-block">
                    <div class="detail-title-line">
                        <h2 class="detail-title">${request.documentType}</h2>
                        ${renderStatusPill(request.status)}
                    </div>
                    <div class="text-muted">
                        ${request.requestId}, requested
                        ${request.requestedDate} at ${request.requestedTime}
                    </div>
                </div>
                <div class="detail-fee">
                    <div class="detail-fee-amount">
                        ${formatFee(request.fee)}
                    </div>
                    ${renderStatusPill(request.paymentStatus)}
                </div>
            </div>
            ${renderProgress(request)}
            ${renderRemarkCallout(request)}
        </section>`;
}

function renderDetailRow(label, valueHtml) {
    return `<dt>${label}</dt><dd>${valueHtml}</dd>`;
}

function renderRequestDetails(request) {
    const rows = [
        renderDetailRow("Purpose", request.purpose),
        renderDetailRow("Release method", request.preferredRelease),
        renderDetailRow(
            "Release status",
            renderStatusPill(request.releaseStatus),
        ),
        renderDetailRow(
            "Requested on",
            `${request.requestedDate}, ${request.requestedTime}`,
        ),
    ];

    if (isStaffView) {
        const student =
            `${request.studentName}, ${request.studentId}<br>` +
            `<span class="text-muted">${DEMO_STUDENT_PROGRAM}</span>`;
        rows.unshift(renderDetailRow("Student", student));
    }

    return `
        <div>
            <h3 class="detail-section-title">Request details</h3>
            <dl class="detail-list">${rows.join("")}</dl>
            <h3 class="detail-section-title detail-subsection-title">
                Supporting information
            </h3>
            <p class="detail-paragraph">${request.supportingInfo}</p>
        </div>`;
}

function renderRequirementRow(requirement) {
    const isReceived = requirement.status === REQUIREMENT_STATUS.RECEIVED;
    const pendingClass = isReceived ? "" : " requirement-row-pending";

    return `
        <li class="requirement-row${pendingClass}">
            <span class="icon-container">
                ${isReceived ? CHECK_ICON : ALERT_ICON}
            </span>
            <span class="requirement-text">
                <b>${requirement.label}</b>
                <small class="text-muted">
                    ${requirement.note || requirement.status}
                </small>
            </span>
        </li>`;
}

function renderRequirements(request) {
    const isPending = (requirement) =>
        requirement.status !== REQUIREMENT_STATUS.RECEIVED;
    const pendingCount = request.requirements.filter(isPending).length;
    const summaryClass = pendingCount
        ? "status-pill-attention"
        : "status-pill-muted";
    const summaryLabel = pendingCount ? `${pendingCount} left` : "Complete";
    // Pending requirements go first so they are seen before the done ones.
    const sortedRequirements = [...request.requirements].sort(
        (a, b) => Number(isPending(b)) - Number(isPending(a)),
    );

    return `
        <div>
            <div class="detail-section-heading">
                <h3 class="detail-section-title">Requirements</h3>
                <span class="status-pill ${summaryClass}">${summaryLabel}</span>
            </div>
            <ul class="requirement-list">
                ${sortedRequirements.map(renderRequirementRow).join("")}
            </ul>
        </div>`;
}

function renderFileTile(requirement) {
    const extension = requirement.fileName.split(".").pop().toUpperCase();
    const isReceived = requirement.status === REQUIREMENT_STATUS.RECEIVED;

    return `
        <button class="file-tile"
            data-toast-message="${FILE_PREVIEW_MESSAGE}">
            <span class="file-preview">${extension}</span>
            <span class="file-name">${requirement.fileName}</span>
            <span class="file-meta">
                ${isReceived ? requirement.fileSize : requirement.status}
            </span>
        </button>`;
}

function renderAddFileTile() {
    return `
        <button class="file-tile"
            data-toast-message="${FILE_PICKER_MESSAGE}">
            <span class="file-preview file-preview-add">${PLUS_ICON}</span>
            <span class="file-name">Add file</span>
            <span class="file-meta">PDF, JPG or PNG</span>
        </button>`;
}

function renderAttachedFiles(request) {
    const files = request.requirements.filter(
        (requirement) => requirement.fileName,
    );
    const canAddFiles =
        !isStaffView && FILE_UPLOAD_STATUSES.includes(request.status);

    return `
        <section class="detail-section">
            <div class="detail-section-heading detail-section-heading-start">
                <h3 class="detail-section-title">Attached files</h3>
                <span class="chip">${files.length}</span>
            </div>
            <div class="file-grid">
                ${files.map(renderFileTile).join("")}
                ${canAddFiles ? renderAddFileTile() : ""}
            </div>
        </section>`;
}

function renderStaffControls(request) {
    return `
        <section class="detail-section">
            <h3 class="detail-section-title">Clearance and release</h3>
            <div class="staff-controls">
                <label>Blocking issue
                    <select class="form-control"
                        data-request-field="blockingIssue">
                        <option value="">None</option>
                        ${renderOptions(BLOCKING_ISSUES, request.blockingIssue)}
                    </select>
                </label>
                <label>Payment
                    <select class="form-control"
                        data-request-field="paymentStatus">
                        ${renderOptions(
                            Object.values(PAYMENT_STATUS),
                            request.paymentStatus,
                        )}
                    </select>
                </label>
                <label>Release
                    <select class="form-control"
                        data-request-field="releaseStatus">
                        ${renderOptions(
                            Object.values(RELEASE_STATUS),
                            request.releaseStatus,
                        )}
                    </select>
                </label>
            </div>
        </section>`;
}

function renderComposer() {
    const placeholder = isStaffView
        ? "Write a message to the student"
        : "Write a message to the registrar";

    return `
        <div class="composer">
            <textarea id="message-input" rows="2"
                placeholder="${placeholder}"></textarea>
            <div class="composer-footer">
                <button class="button button-secondary"
                    data-toast-message="${FILE_PICKER_MESSAGE}">
                    ${PAPERCLIP_ICON} Upload
                </button>
                <span class="spacer"></span>
                <button class="button" data-action="send-message">
                    Send ${SEND_ICON}
                </button>
            </div>
        </div>`;
}

function renderMessagesSection(request) {
    const messageCount =
        1 +
        (request.registrarRemark ? 1 : 0) +
        (request.messages || []).length;

    return `
        <section class="detail-section">
            <div class="detail-section-heading detail-section-heading-start">
                <h3 class="detail-section-title">Messages</h3>
                <span class="chip">${messageCount}</span>
            </div>
            <div class="message-thread">${renderThread(request)}</div>
            ${renderComposer()}
        </section>`;
}

function renderActionButton(label, attributes, isPrimary) {
    const variantClass = isPrimary ? "" : " button-secondary";
    return `<button class="button${variantClass}" ${attributes}>${label}</button>`;
}

function renderStudentActions(request) {
    if (request.status === STATUS.NEEDS_CORRECTION) {
        return renderActionButton(
            "Upload corrected files",
            `data-toast-message="${FILE_PICKER_MESSAGE}"`,
            true,
        );
    }
    if (request.status === STATUS.SUBMITTED) {
        return renderActionButton(
            "Cancel request",
            `data-action="change-status" data-status="${STATUS.CANCELLED}"`,
            false,
        );
    }
    return "";
}

function renderStatusActions(request) {
    if (!isStaffView) return renderStudentActions(request);

    const transitions = ALLOWED_STATUS_TRANSITIONS[request.status] || [];

    // The first transition is the normal next step and is the primary
    // button. The rest are exceptions. Reversed so the primary sits last.
    return transitions
        .map((newStatus, index) => {
            const isStartingReview =
                request.status === STATUS.SUBMITTED &&
                newStatus === STATUS.UNDER_REVIEW;
            return renderActionButton(
                isStartingReview ? "Start review" : newStatus,
                `data-action="change-status" data-status="${newStatus}"`,
                index === 0,
            );
        })
        .reverse()
        .join("");
}

function renderActionBar(request) {
    const actions = renderStatusActions(request);
    const remarkInput =
        isStaffView && actions
            ? `<input class="form-control action-remark" id="remark-input"
                placeholder="Remark for the student, optional">`
            : "";

    return `
        <div class="action-bar">
            ${remarkInput}
            <div class="action-total">
                <span class="action-total-amount">
                    ${formatFee(request.fee)}
                </span>
                ${renderStatusPill(request.paymentStatus)}
            </div>
            <span class="spacer"></span>
            <div class="action-buttons">
                ${actions || '<span class="text-muted">No action needed</span>'}
            </div>
        </div>`;
}

function renderRequestDetail(request) {
    return `
        <div class="detail-card">
            ${renderDetailHeader(request)}
            <section class="detail-section detail-grid">
                ${renderRequestDetails(request)}
                ${renderRequirements(request)}
            </section>
            ${renderAttachedFiles(request)}
            ${isStaffView ? renderStaffControls(request) : ""}
            ${renderMessagesSection(request)}
            ${renderActionBar(request)}
        </div>`;
}

function readInputValue(selector) {
    const input = selectOne(selector);
    return input ? input.value.trim() : "";
}

function changeStatus(newStatus) {
    const blockingIssue = selectedRequest.blockingIssue || "";

    if (newStatus === STATUS.APPROVED_CLEARED && blockingIssue) {
        showToast(`Cannot clear: ${blockingIssue}`);
        return;
    }

    const remarkText = readInputValue("#remark-input") || blockingIssue;
    if (remarkText) addMessage(remarkText);

    selectedRequest.status = newStatus;
    updateReleaseStatusFor(newStatus);
    syncSelectedListItem();
    renderSelectedRequest();
    showToast(`Status: ${newStatus}`);
}

function sendMessage() {
    const messageText = readInputValue("#message-input");
    if (!messageText) {
        showToast("Write a message first");
        return;
    }

    addMessage(messageText);
    syncSelectedListItem();
    renderSelectedRequest();
    showToast("Message sent");
}

function renderSelectedRequest() {
    selectOne("#request-detail").innerHTML =
        renderRequestDetail(selectedRequest);

    const thread = selectOne(".message-thread");
    thread.scrollTop = thread.scrollHeight;
}

function syncSelectedListItem() {
    selectedListItem.dataset.request = JSON.stringify(selectedRequest);

    const statusCell = selectedListItem.querySelector(".request-status");
    if (statusCell)
        statusCell.innerHTML = renderStatusPill(selectedRequest.status);
}

function addMessage(text) {
    selectedRequest.messages = selectedRequest.messages || [];
    selectedRequest.messages.push({ sender: userRole, text });
}

function updateReleaseStatusFor(newStatus) {
    const { releaseStatus } = selectedRequest;

    if (
        newStatus === STATUS.READY_FOR_RELEASE &&
        releaseStatus === RELEASE_STATUS.NOT_APPLICABLE
    ) {
        selectedRequest.releaseStatus = RELEASE_STATUS.READY_FOR_PICKUP;
    } else if (
        newStatus === STATUS.COMPLETED &&
        PICKUP_PENDING_STATUSES.includes(releaseStatus)
    ) {
        selectedRequest.releaseStatus = RELEASE_STATUS.PICKED_UP;
    }
}

function selectListItem(listItem) {
    selectAll(".request-list-item").forEach((item) => {
        item.classList.remove("active");
    });
    listItem.classList.add("active");

    selectedRequest = JSON.parse(listItem.dataset.request);
    selectedListItem = listItem;
    renderSelectedRequest();
}

function formatResultCount(count) {
    const range = count ? `1-${count}` : 0;
    return `Displaying ${range} of ${count} results.`;
}

function applyListFilters() {
    const searchText = selectOne("#request-search").value.toLowerCase();
    const activeFilterButton = selectOne(".status-filter .active");
    const allowedStatuses = activeFilterButton.dataset.statuses
        ? activeFilterButton.dataset.statuses.split(",")
        : [];
    let visibleCount = 0;

    selectAll(".request-list-item").forEach((item) => {
        const { status } = JSON.parse(item.dataset.request);
        const matchesSearch = item.textContent
            .toLowerCase()
            .includes(searchText);
        const matchesStatus =
            allowedStatuses.length === 0 || allowedStatuses.includes(status);

        item.hidden = !(matchesSearch && matchesStatus);
        if (!item.hidden) visibleCount++;
    });

    selectOne("#result-count").textContent = formatResultCount(visibleCount);
}

function selectInitialListItem(listItems) {
    const requestIdFromUrl = location.hash.slice(1);
    const linkedItem = listItems.find(
        (item) =>
            JSON.parse(item.dataset.request).requestId === requestIdFromUrl,
    );

    selectListItem(linkedItem || listItems[0]);
}

function initRequestWorkspace() {
    const listItems = selectAll(".request-list-item");
    if (listItems.length === 0) return;

    const detailPane = selectOne("#request-detail");

    listItems.forEach((listItem) => {
        listItem.addEventListener("click", () => selectListItem(listItem));
    });

    detailPane.addEventListener("click", (event) => {
        const actionButton = event.target.closest("[data-action]");
        if (!actionButton) return;

        if (actionButton.dataset.action === "change-status") {
            changeStatus(actionButton.dataset.status);
        } else if (actionButton.dataset.action === "send-message") {
            sendMessage();
        }
    });

    detailPane.addEventListener("change", (event) => {
        const fieldName = event.target.dataset.requestField;
        if (!fieldName) return;

        selectedRequest[fieldName] = event.target.value;
        syncSelectedListItem();
        renderSelectedRequest();
    });

    selectOne("#request-search").addEventListener("input", applyListFilters);

    selectAll(".status-filter button").forEach((filterButton) => {
        filterButton.addEventListener("click", () => {
            selectAll(".status-filter button").forEach((button) => {
                button.classList.remove("active");
            });
            filterButton.classList.add("active");
            applyListFilters();
        });
    });

    applyListFilters();
    selectInitialListItem(listItems);
}

initLayoutControls();
initToastTriggers();
initDefaultUserIdAutofill();
initActivationFlow();
initIntegrationActions();
initRequestForm();
initRequestWorkspace();
