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
    ALUMNI: "alumni",
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
const ALUMNI_ACTIVATED_MESSAGE =
    "Account activated. Login details sent to your email";
const RESET_LINK_RESENT_MESSAGE = "Reset link sent again";
const DOCUMENT_REQUIRED_MESSAGE = "Choose the document you need first.";
const OTHER_DOCUMENT_REQUIRED_MESSAGE = "Describe the document you need.";
const PURPOSE_REQUIRED_MESSAGE =
    "Enter what you need it for, for example Employment.";

const BLOCKING_ISSUES = [
    "Outstanding balance",
    "Pending clearance",
    "Unresolved violation",
    "Missing requirement",
];

const ALL_CATEGORIES = "all";
const CONTENT_PADDING_PX = 16;
const DOCUMENT_UNIT_SET = "set";
const REGISTRAR_STAFF_LABEL = "Registrar staff";
const DEMO_STUDENT_PROGRAM = "BS Computer Science, 3rd Year";

const rootElement = document.documentElement;
const userRole = document.body.dataset.role;
const isStaffView = userRole === ROLE.STAFF;

const selectOne = (selector, scope = document) =>
    scope.querySelector(selector);
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
const CLOSE_ICON = createIcon('<path d="M18 6L6 18M6 6l12 12"/>');

function getInitials(text) {
    // Skips the word "of" so "Transcript of Records" gives "TR", not "TO".
    const wordInitials = text.match(/\b(?!of\b)\w/gi) || [];
    return wordInitials.slice(0, 2).join("").toUpperCase();
}

const PHOTO_STORAGE_PREFIX = "academix.photo.";
const PROFILE_STORAGE_PREFIX = "academix.profile.";
const PHOTO_MAX_BYTES = 5 * 1024 * 1024;
const PHOTO_OUTPUT_PX = 160;
const PHOTO_OUTPUT_QUALITY = 0.85;
const PHOTO_TYPES = ["image/png", "image/jpeg"];
const MIN_PASSWORD_LENGTH = 8;
const PASSWORD_STRENGTH_LABELS = ["Enter a new password", "Weak", "Fair", "Good", "Strong"];
const PHONE_PATTERN = /^(\+63|0)9\d{9}$/;
const EMAIL_PATTERN = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
const EYE_ICON = createIcon(
    '<path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/>' +
        '<circle cx="12" cy="12" r="3"/>',
);
const EYE_OFF_ICON = createIcon(
    '<path d="M17.9 17.9A10.9 10.9 0 0 1 12 19C5 19 1 12 1 12a18.5 18.5 0 0 1 5.1-5.9"/>' +
        '<path d="M9.9 4.2A9.7 9.7 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.2 3.2"/>' +
        '<path d="M14.1 14.1a3 3 0 1 1-4.2-4.2M1 1l22 22"/>',
);

const HTML_ESCAPES = {
    "&": "&amp;",
    "<": "&lt;",
    ">": "&gt;",
    '"': "&quot;",
    "'": "&#39;",
};

function escapeHtml(text) {
    return text.replace(/[&<>"']/g, (character) => HTML_ESCAPES[character]);
}

const BYTES_PER_KB = 1024;
const BYTES_PER_MB = 1024 * 1024;

function formatFileSize(bytes) {
    if (bytes < BYTES_PER_MB) {
        return `${Math.max(1, Math.round(bytes / BYTES_PER_KB))} KB`;
    }
    return `${(bytes / BYTES_PER_MB).toFixed(1)} MB`;
}

function getFileExtension(fileName) {
    return fileName.includes(".")
        ? fileName.split(".").pop().toUpperCase()
        : "FILE";
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

function isAlumniSelected() {
    const alumniRadio = selectOne(`[name=role][value="${ROLE.ALUMNI}"]`);
    return Boolean(alumniRadio && alumniRadio.checked);
}

function showActivationStep(step) {
    selectAll(".activation-step").forEach((section) => {
        section.hidden = section.id !== `activation-step-${step}`;
    });
}

function finishActivation() {
    const isAlumni = isAlumniSelected();

    showToast(isAlumni ? ALUMNI_ACTIVATED_MESSAGE : "Account activated");
    setTimeout(
        () => (location.href = "index.php"),
        isAlumni ? TOAST_DURATION_MS : REDIRECT_DELAY_MS,
    );
}

function getActivationNextStep(button) {
    // Alumni receive their login details by email, so they skip the step
    // where students choose their own password.
    const useAlumniStep = isAlumniSelected() && button.dataset.alumniNextStep;

    return useAlumniStep
        ? button.dataset.alumniNextStep
        : button.dataset.nextStep;
}

function initActivationFlow() {
    selectAll("[data-next-step]").forEach((button) => {
        button.addEventListener("click", (event) => {
            event.preventDefault();

            const nextStep = getActivationNextStep(button);
            if (nextStep === "done") finishActivation();
            else showActivationStep(nextStep);
        });
    });
}

function applyAlumniActivationCopy() {
    const isAlumni = isAlumniSelected();

    selectAll("[data-alumni-text]").forEach((element) => {
        // Remember the original copy so switching tabs restores it.
        if (!("defaultText" in element.dataset)) {
            element.dataset.defaultText = element.textContent.trim();
        }
        element.textContent = isAlumni
            ? element.dataset.alumniText
            : element.dataset.defaultText;
    });

    selectAll("[data-alumni-placeholder]").forEach((input) => {
        if (!("defaultPlaceholder" in input.dataset)) {
            input.dataset.defaultPlaceholder = input.placeholder;
        }
        input.placeholder = isAlumni
            ? input.dataset.alumniPlaceholder
            : input.dataset.defaultPlaceholder;
    });

    selectAll("[data-alumni-only]").forEach((element) => {
        element.hidden = !isAlumni;
    });
}

function initAlumniActivation() {
    if (!selectOne(".activation-step")) return;

    applyAlumniActivationCopy();

    selectAll("[name=role]").forEach((roleRadio) => {
        roleRadio.addEventListener("change", () => {
            applyAlumniActivationCopy();
            showActivationStep(1);
        });
    });
}

function maskEmail(email) {
    const atIndex = email.lastIndexOf("@");
    return `${email.charAt(0)}***${email.slice(atIndex)}`;
}

function initForgotPasswordFlow() {
    const forgotPasswordForm = selectOne("#forgot-password-form");
    if (!forgotPasswordForm) return;

    const emailInput = selectOne("#reset-email-input");

    // A submit handler (not a click handler) so the browser validates the
    // required fields first and pressing Enter also sends the link.
    forgotPasswordForm.addEventListener("submit", (event) => {
        event.preventDefault();

        selectOne("#reset-email-display").textContent = maskEmail(
            emailInput.value.trim(),
        );
        selectOne("#forgot-step-request").hidden = true;
        selectOne("#forgot-step-sent").hidden = false;
    });

    selectOne("#resend-reset-link-button").addEventListener("click", () => {
        showToast(RESET_LINK_RESENT_MESSAGE);
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

function renderDocumentRequirementRow(requirement) {
    return `
        <li class="requirement-row">
            <span class="icon-container">${DOCUMENT_ICON}</span>
            <span class="requirement-text">
                <b>${escapeHtml(requirement.label)}</b>
                <small class="text-muted">${escapeHtml(requirement.note)}</small>
            </span>
        </li>`;
}

function renderIncludedDocumentRow(name) {
    return `
        <li class="requirement-row">
            <span class="icon-container">${CHECK_ICON}</span>
            <span class="requirement-text">
                <b>${escapeHtml(name)}</b>
            </span>
        </li>`;
}

function readDocumentCard(card) {
    const { dataset } = card;

    return {
        label: dataset.label,
        categoryName: dataset.categoryName,
        description: dataset.description,
        fee: Number(dataset.fee),
        turnaround: dataset.turnaround,
        unit: dataset.unit,
        allowsUpload: dataset.allowsUpload === "true",
        isFreeText: dataset.freeText === "true",
        requirements: JSON.parse(dataset.requirements),
        uploadRequirements: JSON.parse(dataset.uploadRequirements),
        includes: JSON.parse(dataset.includes),
    };
}

function getScrollBehavior() {
    return window.matchMedia("(prefers-reduced-motion: reduce)").matches
        ? "auto"
        : "smooth";
}

// Search box and category tabs over the document cards. Everything is
// filtered in the page, so there is no request while the student types.
function initCatalogFilters() {
    const searchInput = selectOne("#catalog-search");
    const clearButton = selectOne("#catalog-search-clear");
    const tabs = selectAll(".category-tab");
    const cards = selectAll(".document-card");
    const groups = selectAll(".document-group");
    const countLabel = selectOne("#catalog-count");
    const emptyState = selectOne("#catalog-empty");

    const getActiveCategory = () =>
        selectOne('.category-tab[aria-pressed="true"]').dataset.category;

    function setActiveCategory(category) {
        tabs.forEach((tab) => {
            tab.setAttribute(
                "aria-pressed",
                String(tab.dataset.category === category),
            );
        });
    }

    function formatCount(visibleCount, isFiltered) {
        if (visibleCount === 0) return "No documents found";
        if (!isFiltered) return `${cards.length} documents`;
        return `Showing ${visibleCount} of ${cards.length} documents`;
    }

    function applyFilters() {
        const terms = searchInput.value
            .toLowerCase()
            .split(/\s+/)
            .filter(Boolean);
        const activeCategory = getActiveCategory();
        const searchMatchesByCategory = {};
        let searchMatchCount = 0;
        let visibleCount = 0;

        cards.forEach((card) => {
            const { category, search } = card.dataset;
            const matchesSearch = terms.every((term) => search.includes(term));
            const matchesCategory =
                activeCategory === ALL_CATEGORIES || category === activeCategory;

            if (matchesSearch) {
                searchMatchCount++;
                searchMatchesByCategory[category] =
                    (searchMatchesByCategory[category] || 0) + 1;
            }
            card.hidden = !(matchesSearch && matchesCategory);
            if (!card.hidden) visibleCount++;
        });

        groups.forEach((group) => {
            group.hidden = !group.querySelector(".document-card:not([hidden])");
        });

        // Tab counts follow the search, so an empty category is visible
        // before the student clicks it.
        tabs.forEach((tab) => {
            const count =
                tab.dataset.category === ALL_CATEGORIES
                    ? searchMatchCount
                    : searchMatchesByCategory[tab.dataset.category] || 0;

            tab.querySelector(".category-tab-count").textContent = count;
            tab.classList.toggle("category-tab-empty", count === 0);
        });

        clearButton.hidden = !searchInput.value;
        emptyState.hidden = visibleCount > 0;
        countLabel.textContent = formatCount(
            visibleCount,
            terms.length > 0 || activeCategory !== ALL_CATEGORIES,
        );
    }

    function reset() {
        searchInput.value = "";
        setActiveCategory(ALL_CATEGORIES);
        applyFilters();
    }

    searchInput.addEventListener("input", applyFilters);
    searchInput.addEventListener("keydown", (event) => {
        if (event.key === "Escape" && searchInput.value) {
            event.preventDefault();
            reset();
        }
    });
    clearButton.addEventListener("click", () => {
        reset();
        searchInput.focus();
    });
    selectOne("#catalog-reset").addEventListener("click", () => {
        reset();
        searchInput.focus();
    });
    tabs.forEach((tab) => {
        tab.addEventListener("click", () => {
            setActiveCategory(tab.dataset.category);
            applyFilters();
        });
    });

    applyFilters();

    return { searchInput };
}

function initRequestForm() {
    const requestForm = selectOne("#request-form");
    if (!requestForm) return;

    const documentCards = selectAll(".document-card");
    const catalogPanel = selectOne("#catalog-panel");
    const cancelCatalogButton = selectOne("#catalog-cancel");
    const catalogError = selectOne("#catalog-error");
    const selectedBanner = selectOne("#selected-document");
    const detailsSection = selectOne("#request-details");
    const documentTypeInput = selectOne("#document-type-input");
    const copiesInput = selectOne("#copies-input");
    const stepperButtons = selectAll(".stepper-button");
    const purposeInput = selectOne("#purpose-input");
    const otherDocumentField = selectOne("#other-document-field");
    const otherDocumentInput = selectOne("#other-document-input");
    const fileInput = selectOne("#file-input");
    const fileGrid = selectOne("#file-grid");
    const addFileButton = selectOne("#add-file-button");
    const submitButton = selectOne("#submit-button");

    const attachedFiles = [];
    const previewUrlByFile = new Map();
    const { searchInput } = initCatalogFilters();

    let selectedDocument = null;

    function getCopies() {
        const minCopies = Number(copiesInput.min);
        const maxCopies = Number(copiesInput.max);
        const copies = Math.floor(Number(copiesInput.value));

        // Blank or out-of-range input falls back to the nearest valid number
        // so the fee never shows NaN while the student is typing.
        if (!(copies >= minCopies)) return minCopies;
        return Math.min(copies, maxCopies);
    }

    function updateStepper() {
        const copies = getCopies();

        // aria-disabled instead of disabled, so the button keeps keyboard
        // focus when the student steps up to the limit.
        stepperButtons.forEach((button) => {
            const nextCopies = copies + Number(button.dataset.step);
            const isOutOfRange =
                nextCopies < Number(copiesInput.min) ||
                nextCopies > Number(copiesInput.max);
            button.setAttribute("aria-disabled", String(isOutOfRange));
        });
    }

    function updateFee() {
        const feeAmount = selectOne("#fee-amount");
        const feeBreakdown = selectOne("#fee-breakdown");
        const copies = getCopies();

        updateStepper();

        if (!selectedDocument) {
            selectOne("#fee-amount").textContent = "—";
            feeBreakdown.hidden = true;
            return;
        }

        const { fee, unit } = selectedDocument;
        const unitLabel = unit === DOCUMENT_UNIT_SET ? "sets" : "copies";

        feeAmount.textContent = formatFee(fee * copies);
        feeAmount.classList.remove("text-muted");
        feeBreakdown.textContent = `${formatFee(fee)} × ${copies} ${unitLabel}`;
        feeBreakdown.hidden = !(fee > 0 && copies > 1);
    }

    function updateCopiesLabels({ unit }) {
        const isSet = unit === DOCUMENT_UNIT_SET;
        const [fewerButton, moreButton] = stepperButtons;

        selectOne("#copies-label").textContent = isSet ? "Sets" : "Copies";
        fewerButton.setAttribute(
            "aria-label",
            isSet ? "Fewer sets" : "Fewer copies",
        );
        moreButton.setAttribute(
            "aria-label",
            isSet ? "More sets" : "More copies",
        );
    }

    // A category with one document of the same name would read twice.
    function setEyebrow(selector, { categoryName, label }) {
        const eyebrow = selectOne(selector);

        eyebrow.textContent = categoryName;
        eyebrow.hidden = categoryName === label;
    }

    function updateSummary(documentType) {
        selectOne("#summary-empty").hidden = true;
        selectOne("#summary-filled").hidden = false;
        setEyebrow("#summary-category", documentType);
        selectOne("#summary-title").textContent = documentType.label;
        selectOne("#summary-turnaround").textContent = documentType.turnaround;
        selectOne("#summary-description").textContent =
            documentType.description;

        selectOne("#summary-includes").hidden =
            documentType.includes.length === 0;
        selectOne("#includes-list").innerHTML = documentType.includes
            .map(renderIncludedDocumentRow)
            .join("");
        selectOne("#requirements-list").innerHTML = documentType.requirements
            .map(renderDocumentRequirementRow)
            .join("");
    }

    function updateSelectedBanner(documentType) {
        setEyebrow("#selected-document-category", documentType);
        selectOne("#selected-document-name").textContent = documentType.label;
        selectOne("#selected-document-meta").textContent =
            `${documentType.turnaround} · ${formatFee(documentType.fee)}`;
    }

    function updateUploadArea(documentType) {
        const { allowsUpload, uploadRequirements } = documentType;
        const uploadHint = selectOne("#upload-hint");

        selectOne("#upload-area").hidden = !allowsUpload;
        selectOne("#no-upload-notice").hidden = allowsUpload;
        uploadHint.textContent = `Needed: ${uploadRequirements.join(", ")}`;
        uploadHint.hidden = uploadRequirements.length === 0;
    }

    function showCatalogError(message) {
        catalogError.querySelector("span").textContent = message;
        catalogError.hidden = false;
    }

    function showFieldError(input, message) {
        const errorElement = selectOne(`#${input.id}-error`);

        errorElement.querySelector("span").textContent = message;
        errorElement.hidden = false;
        input.setAttribute("aria-invalid", "true");
    }

    function clearFieldError(input) {
        selectOne(`#${input.id}-error`).hidden = true;
        input.removeAttribute("aria-invalid");
    }

    function findInvalidFields() {
        const invalidFields = [];

        if (!otherDocumentField.hidden && !otherDocumentInput.value.trim()) {
            invalidFields.push({
                input: otherDocumentInput,
                message: OTHER_DOCUMENT_REQUIRED_MESSAGE,
            });
        }
        if (!purposeInput.value.trim()) {
            invalidFields.push({
                input: purposeInput,
                message: PURPOSE_REQUIRED_MESSAGE,
            });
        }
        return invalidFields;
    }

    // The catalog and the chosen document never share the screen: picking
    // collapses the long list down to one row so the form below stays close.
    function showSelection() {
        catalogPanel.hidden = true;
        selectedBanner.hidden = false;
        detailsSection.hidden = false;
    }

    function showCatalog() {
        catalogPanel.hidden = false;
        selectedBanner.hidden = true;
        detailsSection.hidden = true;
        cancelCatalogButton.hidden = !selectedDocument;
    }

    // Scrolls the page content only. scrollIntoView would also nudge the
    // outer window, which moves the sidebar and the top bar.
    function scrollToDocumentSection() {
        const content = selectOne(".content");
        const headingTop = selectOne("#document-heading").getBoundingClientRect().top;
        const contentTop = content.getBoundingClientRect().top;

        content.scrollTo({
            top: content.scrollTop + headingTop - contentTop - CONTENT_PADDING_PX,
            behavior: getScrollBehavior(),
        });
    }

    function chooseDocument(card) {
        documentCards.forEach((documentCard) => {
            documentCard.setAttribute("aria-pressed", String(documentCard === card));
        });

        selectedDocument = readDocumentCard(card);
        documentTypeInput.value = selectedDocument.label;
        catalogError.hidden = true;
        otherDocumentField.hidden = !selectedDocument.isFreeText;
        if (!selectedDocument.isFreeText) clearFieldError(otherDocumentInput);

        updateSelectedBanner(selectedDocument);
        updateSummary(selectedDocument);
        updateUploadArea(selectedDocument);
        updateCopiesLabels(selectedDocument);
        updateFee();

        showSelection();
        selectedBanner.focus({ preventScroll: true });
        scrollToDocumentSection();
    }

    function renderAttachedFileTile(file, index) {
        const fileName = escapeHtml(file.name);
        const previewUrl = previewUrlByFile.get(file);
        const preview = previewUrl
            ? `<img src="${previewUrl}" alt="">`
            : escapeHtml(getFileExtension(file.name));

        return `
            <div class="file-tile file-tile-attached">
                <span class="file-preview">${preview}</span>
                <span class="file-name" title="${fileName}">${fileName}</span>
                <span class="file-meta">${formatFileSize(file.size)}</span>
                <button class="file-remove" type="button"
                    data-file-index="${index}"
                    aria-label="Remove ${fileName}">${CLOSE_ICON}</button>
            </div>`;
    }

    function renderAttachedFiles() {
        selectAll(".file-tile-attached").forEach((tile) => tile.remove());
        addFileButton.insertAdjacentHTML(
            "beforebegin",
            attachedFiles.map(renderAttachedFileTile).join(""),
        );
    }

    function attachFile(file) {
        const isDuplicate = attachedFiles.some(
            (attachedFile) =>
                attachedFile.name === file.name &&
                attachedFile.size === file.size &&
                attachedFile.lastModified === file.lastModified,
        );
        if (isDuplicate) return;

        attachedFiles.push(file);
        if (file.type.startsWith("image/")) {
            previewUrlByFile.set(file, URL.createObjectURL(file));
        }
    }

    function removeAttachedFile(index) {
        const [removedFile] = attachedFiles.splice(index, 1);
        const previewUrl = previewUrlByFile.get(removedFile);

        if (previewUrl) URL.revokeObjectURL(previewUrl);
        previewUrlByFile.delete(removedFile);
    }

    // Delegated: the cards are plain buttons, so arrow keys do not change the
    // choice and collapse the list while the student is still browsing.
    selectOne("#document-groups").addEventListener("click", (event) => {
        const card = event.target.closest(".document-card");
        if (card) chooseDocument(card);
    });
    selectOne("#change-document-button").addEventListener("click", () => {
        showCatalog();
        scrollToDocumentSection();
        searchInput.focus({ preventScroll: true });
    });
    cancelCatalogButton.addEventListener("click", () => {
        showSelection();
        selectedBanner.focus({ preventScroll: true });
    });

    stepperButtons.forEach((button) => {
        button.addEventListener("click", () => {
            if (button.getAttribute("aria-disabled") === "true") return;

            copiesInput.value = getCopies() + Number(button.dataset.step);
            updateFee();
        });
    });
    copiesInput.addEventListener("input", updateFee);
    copiesInput.addEventListener("change", () => {
        copiesInput.value = getCopies();
        updateFee();
    });

    selectAll(".suggestion-chip").forEach((chip) => {
        chip.addEventListener("click", () => {
            purposeInput.value = chip.textContent.trim();
            clearFieldError(purposeInput);
            purposeInput.focus();
        });
    });
    [otherDocumentInput, purposeInput].forEach((input) => {
        input.addEventListener("input", () => clearFieldError(input));
    });

    addFileButton.addEventListener("click", () => fileInput.click());
    fileInput.addEventListener("change", () => {
        [...fileInput.files].forEach(attachFile);
        // Clearing the input lets the student pick the same file again after
        // removing it.
        fileInput.value = "";
        renderAttachedFiles();
    });
    fileGrid.addEventListener("click", (event) => {
        const removeButton = event.target.closest(".file-remove");
        if (!removeButton) return;

        removeAttachedFile(Number(removeButton.dataset.fileIndex));
        renderAttachedFiles();
        addFileButton.focus();
    });

    requestForm.addEventListener("submit", (event) => {
        event.preventDefault();

        if (!selectedDocument) {
            showCatalogError(DOCUMENT_REQUIRED_MESSAGE);
            scrollToDocumentSection();
            searchInput.focus({ preventScroll: true });
            return;
        }
        // Submitting with the list open means the student is happy with the
        // document they already picked.
        if (!catalogPanel.hidden) showSelection();

        [otherDocumentInput, purposeInput].forEach(clearFieldError);
        const invalidFields = findInvalidFields();
        invalidFields.forEach(({ input, message }) => {
            showFieldError(input, message);
        });
        if (invalidFields.length) {
            invalidFields[0].input.focus();
            return;
        }

        submitButton.disabled = true;
        showToast("Request submitted");
        setTimeout(
            () => (location.href = "my-requests.php"),
            REDIRECT_DELAY_MS,
        );
    });

    updateFee();
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
    const requestedDocument =
        request.copies > 1
            ? `${request.copies} copies of my ${request.documentType}`
            : `my ${request.documentType}`;
    const text =
        `Hello, I would like to request ${requestedDocument}. ` +
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

function renderCopiesValue(request) {
    if (request.fee > 0 && request.copies > 1) {
        const feePerCopy = formatFee(request.fee / request.copies);
        return `${request.copies} <span class="text-muted">at ${feePerCopy} each</span>`;
    }

    return `${request.copies}`;
}

function renderRequestDetails(request) {
    const rows = [
        renderDetailRow("Purpose", request.purpose),
        renderDetailRow("Copies", renderCopiesValue(request)),
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

const HEX_COLOR_PATTERN = /^#[0-9a-f]{6}$/;
const MIN_TEXT_CONTRAST = 4.5;
const DERIVED_ALPHA_BY_MODE = Object.freeze({
    light: { "tint-accent": "1c", "divider-color": "14" },
    dark: { "tint-accent": "2e", "divider-color": "1f" },
});

function normalizeHexColor(value) {
    let hex = value.trim().toLowerCase().replace(/^#/, "");
    if (/^[0-9a-f]{3}$/.test(hex)) {
        hex = [...hex].map((digit) => digit + digit).join("");
    }
    return `#${hex}`.match(HEX_COLOR_PATTERN) ? `#${hex}` : null;
}

function getRelativeLuminance(hex) {
    const [red, green, blue] = [1, 3, 5].map((start) => {
        const channel = parseInt(hex.slice(start, start + 2), 16) / 255;
        return channel <= 0.03928
            ? channel / 12.92
            : ((channel + 0.055) / 1.055) ** 2.4;
    });
    return 0.2126 * red + 0.7152 * green + 0.0722 * blue;
}

function getContrastRatio(firstHex, secondHex) {
    const luminances = [firstHex, secondHex].map(getRelativeLuminance);
    const lighter = Math.max(...luminances);
    const darker = Math.min(...luminances);
    return (lighter + 0.05) / (darker + 0.05);
}

function initThemeEditor(editor) {
    const mode = editor.dataset.mode;
    const preview = selectOne(".theme-preview", editor);
    const fields = [...editor.querySelectorAll(".color-field")];

    const fieldFor = (token) =>
        fields.find((field) => field.dataset.token === token);
    const hexInputOf = (field) => field.querySelector(".color-hex");
    const swatchOf = (field) => field.querySelector(".color-swatch");

    const readColor = (token) => {
        const field = fieldFor(token);
        return normalizeHexColor(hexInputOf(field).value) || swatchOf(field).value;
    };

    function updateContrastBadges() {
        fields.forEach((field) => {
            const againstToken = field.dataset.against;
            const badge = field.querySelector(".contrast-badge");
            if (!againstToken || !badge) return;

            const ratio = getContrastRatio(
                readColor(field.dataset.token),
                readColor(againstToken),
            );
            const isLow = ratio < MIN_TEXT_CONTRAST;
            badge.textContent = `${isLow ? "Low " : ""}${ratio.toFixed(1)}:1`;
            badge.title = `Contrast against ${againstToken.replace(/-/g, " ")}`;
            badge.classList.toggle("contrast-badge-low", isLow);
        });
    }

    function applyToPreview(token, color) {
        preview.style.setProperty(`--${token}`, color);
        if (token !== "accent") return;

        Object.entries(DERIVED_ALPHA_BY_MODE[mode]).forEach(([name, alpha]) => {
            preview.style.setProperty(`--${name}`, color + alpha);
        });
    }

    fields.forEach((field) => {
        const token = field.dataset.token;
        const hexInput = hexInputOf(field);
        const swatch = swatchOf(field);

        hexInput.addEventListener("input", () => {
            const color = normalizeHexColor(hexInput.value);
            hexInput.setAttribute("aria-invalid", color ? "false" : "true");
            if (!color) return;

            swatch.value = color;
            applyToPreview(token, color);
            updateContrastBadges();
        });

        hexInput.addEventListener("blur", () => {
            hexInput.value = normalizeHexColor(hexInput.value) || swatch.value;
            hexInput.setAttribute("aria-invalid", "false");
        });

        swatch.addEventListener("input", () => {
            hexInput.value = swatch.value;
            hexInput.dispatchEvent(new Event("input"));
        });
    });

    updateContrastBadges();
}

function initAppearanceEditor() {
    selectAll(".theme-editor").forEach(initThemeEditor);
}

function initFlashMessage() {
    const flash = selectOne("[data-flash-message]");
    if (flash) showToast(flash.dataset.flashMessage);
}

// Profile settings

function readStorage(key) {
    try {
        return localStorage.getItem(key);
    } catch {
        return null;
    }
}

function writeStorage(key, value) {
    try {
        if (value === null) localStorage.removeItem(key);
        else localStorage.setItem(key, value);
    } catch {
        // Storage can be full or blocked. The page still works without it.
    }
}

function getPhotoStorageKey(userKey) {
    return PHOTO_STORAGE_PREFIX + userKey;
}

function applyProfilePhoto(dataUrl) {
    selectAll("[data-profile-avatar]").forEach((avatar) => {
        avatar.classList.toggle("has-photo", Boolean(dataUrl));
        avatar.style.backgroundImage = dataUrl ? `url("${dataUrl}")` : "";
    });
}

function resizePhoto(file) {
    return new Promise((resolve, reject) => {
        const image = new Image();
        const url = URL.createObjectURL(file);

        image.onload = () => {
            const side = Math.min(image.width, image.height);
            const canvas = document.createElement("canvas");
            canvas.width = canvas.height = PHOTO_OUTPUT_PX;
            canvas.getContext("2d").drawImage(
                image,
                (image.width - side) / 2,
                (image.height - side) / 2,
                side,
                side,
                0,
                0,
                PHOTO_OUTPUT_PX,
                PHOTO_OUTPUT_PX,
            );
            URL.revokeObjectURL(url);
            resolve(canvas.toDataURL("image/jpeg", PHOTO_OUTPUT_QUALITY));
        };
        image.onerror = () => {
            URL.revokeObjectURL(url);
            reject(new Error("Unreadable image"));
        };
        image.src = url;
    });
}

function showInlineError(errorElement, message) {
    errorElement.querySelector("span").textContent = message;
    errorElement.hidden = false;
}

function setFieldError(input, message) {
    const errorElement = selectOne(`#${input.id}-error`);
    input.setAttribute("aria-invalid", message ? "true" : "false");
    if (!errorElement) return;
    if (message) showInlineError(errorElement, message);
    else errorElement.hidden = true;
}

function initProfilePhoto(userKey) {
    const photoInput = selectOne("#photo-input");
    const changeButton = selectOne("#photo-change");
    const removeButton = selectOne("#photo-remove");
    const photoError = selectOne("#photo-error");
    const storageKey = getPhotoStorageKey(userKey);

    removeButton.hidden = !readStorage(storageKey);
    changeButton.addEventListener("click", () => photoInput.click());

    photoInput.addEventListener("change", async () => {
        const [file] = photoInput.files;
        photoInput.value = "";
        if (!file) return;

        photoError.hidden = true;
        if (!PHOTO_TYPES.includes(file.type)) {
            showInlineError(photoError, "Choose a JPG or PNG image.");
            return;
        }
        if (file.size > PHOTO_MAX_BYTES) {
            showInlineError(photoError, "Choose an image under 5 MB.");
            return;
        }

        try {
            const dataUrl = await resizePhoto(file);
            writeStorage(storageKey, dataUrl);
            applyProfilePhoto(dataUrl);
            removeButton.hidden = false;
            showToast("Photo updated");
        } catch {
            showInlineError(photoError, "That image could not be read.");
        }
    });

    removeButton.addEventListener("click", () => {
        writeStorage(storageKey, null);
        applyProfilePhoto(null);
        removeButton.hidden = true;
        showToast("Photo removed");
    });
}

function getFieldError(input) {
    const value = input.value.trim();

    if (!value) return "required" in input.dataset ? "Enter this detail." : "";

    const validator = input.dataset.validate;
    if (validator === "phone" && !PHONE_PATTERN.test(value.replace(/[\s-]/g, ""))) {
        return "Use a PH mobile number, like 0917 123 4567.";
    }
    if (validator === "email" && !EMAIL_PATTERN.test(value)) {
        return "Enter a valid email address.";
    }
    if (validator === "digits" && !/^\d+$/.test(value)) {
        return "Use digits only.";
    }
    return "";
}

function initProfileForm(userKey) {
    const form = selectOne("#profile-form");
    const saveButton = selectOne("#profile-save");
    const discardButton = selectOne("#profile-discard");
    const dirtyNote = selectOne("#profile-dirty-note");
    const fields = selectAll("[data-profile-field]");
    const storageKey = PROFILE_STORAGE_PREFIX + userKey;

    let savedValues = Object.fromEntries(
        fields.map((field) => [field.name, field.value]),
    );

    try {
        const stored = JSON.parse(readStorage(storageKey) || "{}");
        fields.forEach((field) => {
            if (typeof stored[field.name] === "string") {
                field.value = stored[field.name];
                savedValues[field.name] = field.value;
            }
        });
    } catch {
        // Ignore a corrupted saved profile and show the defaults.
    }

    function isDirty() {
        return fields.some((field) => field.value !== savedValues[field.name]);
    }

    function syncDirtyState() {
        const dirty = isDirty();
        saveButton.disabled = !dirty;
        discardButton.disabled = !dirty;
        dirtyNote.textContent = dirty ? "Unsaved changes" : "";
    }

    fields.forEach((field) => {
        field.addEventListener("input", () => {
            if (field.getAttribute("aria-invalid") === "true") {
                setFieldError(field, getFieldError(field));
            }
            syncDirtyState();
        });
        field.addEventListener("blur", () => {
            setFieldError(field, getFieldError(field));
        });
    });

    discardButton.addEventListener("click", () => {
        fields.forEach((field) => {
            field.value = savedValues[field.name];
            setFieldError(field, "");
        });
        syncDirtyState();
    });

    form.addEventListener("submit", (event) => {
        event.preventDefault();

        const invalidFields = fields.filter((field) => {
            const message = getFieldError(field);
            setFieldError(field, message);
            return message;
        });
        if (invalidFields.length) {
            invalidFields[0].focus();
            return;
        }

        fields.forEach((field) => {
            field.value = field.value.trim();
            savedValues[field.name] = field.value;
        });
        writeStorage(storageKey, JSON.stringify(savedValues));
        syncDirtyState();
        showToast("Profile saved");
    });

    syncDirtyState();
}

function getPasswordRules(newPassword, currentPassword) {
    return {
        length: newPassword.length >= MIN_PASSWORD_LENGTH,
        case: /[a-z]/.test(newPassword) && /[A-Z]/.test(newPassword),
        number: /\d/.test(newPassword),
        different: newPassword !== "" && newPassword !== currentPassword,
    };
}

function getPasswordStrength(newPassword, rules) {
    if (!newPassword) return 0;

    const passedRules = Object.values(rules).filter(Boolean).length;
    const hasSymbol = /[^A-Za-z0-9]/.test(newPassword);
    const isLong = newPassword.length >= 12;
    const score = Math.min(passedRules - 1 + Number(hasSymbol || isLong), 4);

    return Math.max(score, 1);
}

function initPasswordForm() {
    const form = selectOne("#password-form");
    const currentInput = selectOne("#current-password");
    const newInput = selectOne("#new-password");
    const confirmInput = selectOne("#confirm-password");
    const strengthElement = selectOne("#password-strength");
    const strengthLabel = selectOne("#strength-label");
    const lastChanged = selectOne("#password-last-changed");

    selectAll("[data-toggle-password]").forEach((button) => {
        button.addEventListener("click", () => {
            const input = selectOne(`#${button.dataset.togglePassword}`);
            const isHidden = input.type === "password";

            input.type = isHidden ? "text" : "password";
            button.innerHTML = isHidden ? EYE_OFF_ICON : EYE_ICON;
            button.setAttribute("aria-pressed", String(isHidden));
            button.setAttribute(
                "aria-label",
                isHidden ? "Hide password" : "Show password",
            );
        });
    });

    function updateRules() {
        const rules = getPasswordRules(newInput.value, currentInput.value);
        const strength = getPasswordStrength(newInput.value, rules);

        selectAll("[data-rule]").forEach((item) => {
            item.classList.toggle("met", rules[item.dataset.rule]);
        });
        strengthElement.dataset.level = strength;
        strengthLabel.textContent = PASSWORD_STRENGTH_LABELS[strength];
        return rules;
    }

    [currentInput, newInput, confirmInput].forEach((input) => {
        input.addEventListener("input", () => {
            updateRules();
            if (input.getAttribute("aria-invalid") === "true") {
                setFieldError(input, "");
            }
            if (input !== confirmInput && confirmInput.value) {
                setFieldError(confirmInput, "");
            }
        });
    });

    form.addEventListener("submit", (event) => {
        event.preventDefault();

        const rules = updateRules();
        const errors = [
            [currentInput, currentInput.value ? "" : "Enter your current password."],
            [
                newInput,
                Object.values(rules).every(Boolean)
                    ? ""
                    : "Your new password does not meet every requirement.",
            ],
            [
                confirmInput,
                confirmInput.value === newInput.value
                    ? ""
                    : "The passwords do not match.",
            ],
        ];

        errors.forEach(([input, message]) => setFieldError(input, message));
        const firstError = errors.find(([, message]) => message);
        if (firstError) {
            firstError[0].focus();
            return;
        }

        form.reset();
        selectAll("[data-toggle-password]").forEach((button) => {
            const input = selectOne(`#${button.dataset.togglePassword}`);
            input.type = "password";
            button.innerHTML = EYE_ICON;
            button.setAttribute("aria-pressed", "false");
            button.setAttribute("aria-label", "Show password");
        });
        updateRules();
        lastChanged.textContent = "Last changed just now";
        showToast("Password updated");
    });
}

function initSessionList() {
    const sessionList = selectOne("#session-list");
    const endOthersButton = selectOne("#end-other-sessions");

    function syncEndOthersButton() {
        endOthersButton.disabled = !selectOne(
            ".session-row:not([data-current-session])",
            sessionList,
        );
    }

    sessionList.addEventListener("click", (event) => {
        const button = event.target.closest("[data-end-session]");
        if (!button) return;

        button.closest(".session-row").remove();
        syncEndOthersButton();
        showToast("Device signed out");
    });

    endOthersButton.addEventListener("click", () => {
        sessionList
            .querySelectorAll(".session-row:not([data-current-session])")
            .forEach((row) => row.remove());
        syncEndOthersButton();
        showToast("Signed out of other devices");
    });

    syncEndOthersButton();
}

function initSettingsSections() {
    const content = selectOne("#settings-content");
    const sections = selectAll("[data-settings-section]");
    const links = selectAll("[data-settings-link]");
    const sectionIds = sections.map((section) => section.dataset.settingsSection);

    content.classList.add("settings-js");
    selectOne("#profile-settings").classList.add("settings-js");

    function showSection(id) {
        const activeId = sectionIds.includes(id) ? id : sectionIds[0];

        sections.forEach((section) => {
            section.classList.toggle(
                "active",
                section.dataset.settingsSection === activeId,
            );
        });
        links.forEach((link) => {
            const isActive = link.dataset.settingsLink === activeId;
            link.classList.toggle("active", isActive);
            if (isActive) link.setAttribute("aria-current", "page");
            else link.removeAttribute("aria-current");
        });
    }

    window.addEventListener("hashchange", () => {
        showSection(location.hash.slice(1));
    });
    showSection(location.hash.slice(1));
}

function initProfileSettings() {
    const settings = selectOne("#profile-settings");
    if (!settings) return;

    initSettingsSections();
    initProfilePhoto(userRole);
    initProfileForm(userRole);
    initPasswordForm();
    initSessionList();
}

function initStoredProfilePhoto() {
    if (!userRole) return;

    const storedPhoto = readStorage(getPhotoStorageKey(userRole));
    if (storedPhoto) applyProfilePhoto(storedPhoto);
}

initLayoutControls();
initToastTriggers();
initDefaultUserIdAutofill();
initActivationFlow();
initAlumniActivation();
initForgotPasswordFlow();
initIntegrationActions();
initRequestForm();
initRequestWorkspace();
initAppearanceEditor();
initFlashMessage();
initStoredProfilePhoto();
initProfileSettings();
