<?php

const STATUS_SUBMITTED = 'Submitted';
const STATUS_UNDER_REVIEW = 'Under Review';
const STATUS_NEEDS_CORRECTION = 'Needs Correction';
const STATUS_PROCESSING = 'Processing';
const STATUS_APPROVED_CLEARED = 'Approved / Cleared';
const STATUS_READY_FOR_RELEASE = 'Ready for Release';
const STATUS_COMPLETED = 'Completed';
const STATUS_REJECTED = 'Rejected';
const STATUS_CANCELLED = 'Cancelled';

const RELEASE_NOT_APPLICABLE = 'Not Applicable';
const RELEASE_FOR_PICKUP = 'For Pickup';
const RELEASE_READY_FOR_PICKUP = 'Ready for Pickup';
const RELEASE_PICKED_UP = 'Picked Up';
const RELEASE_DELIVERED = 'Delivered';

const REQUIREMENT_RECEIVED = 'Received';
const REQUIREMENT_REPLACEMENT_NEEDED = 'Needs replacement';
const REQUIREMENT_MISSING = 'Missing';

const PAYMENT_UNPAID = 'Unpaid';
const PAYMENT_PAID = 'Paid';
const PAYMENT_WAIVED = 'Waived';

const AWAITING_REVIEW_STATUSES = [
    STATUS_SUBMITTED,
    STATUS_UNDER_REVIEW,
];

const PROCESSING_AND_CLEARANCE_STATUSES = [
    STATUS_PROCESSING,
    STATUS_APPROVED_CLEARED,
];

const ACTIVE_STATUSES = [
    STATUS_SUBMITTED,
    STATUS_UNDER_REVIEW,
    STATUS_NEEDS_CORRECTION,
    STATUS_PROCESSING,
    STATUS_APPROVED_CLEARED,
];

const CLOSED_STATUSES = [
    STATUS_COMPLETED,
    STATUS_REJECTED,
    STATUS_CANCELLED,
];

const STATUS_FILTERS = [
    'All' => [],
    'Active' => ACTIVE_STATUSES,
    'Ready' => [STATUS_READY_FOR_RELEASE],
    'Closed' => CLOSED_STATUSES,
];

const STATUSES_WITH_PENDING_ORIGINAL_FORM = [
    STATUS_SUBMITTED,
    STATUS_UNDER_REVIEW,
    STATUS_NEEDS_CORRECTION,
];

const STAFF_ATTENTION_STATUSES = [STATUS_SUBMITTED];

const STUDENT_ATTENTION_STATUSES = [
    STATUS_NEEDS_CORRECTION,
    STATUS_READY_FOR_RELEASE,
];

const STATUS_PILL_CLASSES = [
    STATUS_NEEDS_CORRECTION => 'status-pill-attention',
    STATUS_READY_FOR_RELEASE => 'status-pill-attention',
    RELEASE_READY_FOR_PICKUP => 'status-pill-attention',
    PAYMENT_UNPAID => 'status-pill-attention',
    STATUS_COMPLETED => 'status-pill-muted',
    STATUS_REJECTED => 'status-pill-muted status-pill-terminated',
    STATUS_CANCELLED => 'status-pill-muted status-pill-terminated',
    RELEASE_NOT_APPLICABLE => 'status-pill-muted',
    RELEASE_PICKED_UP => 'status-pill-muted',
    RELEASE_DELIVERED => 'status-pill-muted',
    PAYMENT_PAID => 'status-pill-muted',
];

function countRequestsWithStatus(array $requests, array $statuses): int
{
    $matchingRequests = array_filter(
        $requests,
        fn($request) => in_array($request['status'], $statuses, true)
    );

    return count($matchingRequests);
}

function findFirstRequestWithStatus(array $requests, string $status): ?array
{
    foreach ($requests as $request) {
        if ($request['status'] === $status) {
            return $request;
        }
    }

    return null;
}
