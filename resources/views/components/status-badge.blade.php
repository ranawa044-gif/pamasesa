@props(['status'])

@php
    $badgeClasses = [
        'APPROVED' => 'bg-success-subtle text-success border border-success-subtle',
        'REVISION' => 'bg-warning-subtle text-dark border border-warning-subtle',
        'REJECTED' => 'bg-danger-subtle text-danger border border-danger-subtle',
        'SUBMITTED' => 'bg-primary-subtle text-primary border border-primary-subtle',
        'REQUESTED' => 'bg-primary-subtle text-primary border border-primary-subtle',
        'WAITING_APPROVAL' => 'bg-info-subtle text-dark border border-info-subtle',
        'WAITING_EXAMINER' => 'bg-warning-subtle text-dark border border-warning-subtle',
        'READY_TO_SCHEDULE' => 'bg-primary-subtle text-primary border border-primary-subtle',
        'SCHEDULED' => 'bg-primary-subtle text-primary border border-primary-subtle',
        'FINISHED' => 'bg-success-subtle text-success border border-success-subtle',
        'REVIEW' => 'bg-info-subtle text-dark border border-info-subtle',
        'DRAFT' => 'bg-light text-muted border',
        'PUBLISHED' => 'bg-success-subtle text-success border border-success-subtle',
        'ONGOING' => 'bg-info-subtle text-dark border border-info-subtle',
        'WAITING' => 'bg-light text-muted border',
        'PENDING' => 'bg-light text-muted border',
        'APPROVED_BY_SUPERVISORS' => 'bg-success-subtle text-success border border-success-subtle',
        'PASSED' => 'bg-success-subtle text-success border border-success-subtle',
        'PASSED_WITH_REVISION' => 'bg-warning-subtle text-dark border border-warning-subtle',
        'REPEAT' => 'bg-danger-subtle text-danger border border-danger-subtle',
        'SEMINAR_PASSED' => 'bg-success-subtle text-success border border-success-subtle',
        'SEMINAR_REPEAT' => 'bg-danger-subtle text-danger border border-danger-subtle',
        'OPEN' => 'bg-warning-subtle text-dark border border-warning-subtle',
        'DONE' => 'bg-success-subtle text-success border border-success-subtle',
        'WAITING_VALIDATION' => 'bg-warning-subtle text-dark border border-warning-subtle',
        'READY_FOR_DEFENSE' => 'bg-primary-subtle text-primary border border-primary-subtle',
        'REVISION_AFTER_SEMINAR' => 'bg-warning-subtle text-dark border border-warning-subtle',
        'FINAL_REPORT' => 'bg-primary-subtle text-primary border border-primary-subtle',
    ];

    $icons = [
        'APPROVED' => 'bi-check-circle-fill',
        'WAITING_VALIDATION' => 'bi-hourglass-split',
        'READY_FOR_DEFENSE' => 'bi-flag-fill',
        'REVISION_AFTER_SEMINAR' => 'bi-pencil-square',
        'FINAL_REPORT' => 'bi-file-earmark-text-fill',
        'REVISION' => 'bi-exclamation-circle-fill',
        'REJECTED' => 'bi-x-circle-fill',
        'SUBMITTED' => 'bi-send-fill',
        'REQUESTED' => 'bi-send-fill',
        'WAITING_APPROVAL' => 'bi-clock-fill',
        'WAITING_EXAMINER' => 'bi-hourglass-split',
        'READY_TO_SCHEDULE' => 'bi-calendar-check-fill',
        'SCHEDULED' => 'bi-calendar-event-fill',
        'FINISHED' => 'bi-check-square-fill',
        'REVIEW' => 'bi-search',
        'DRAFT' => 'bi-pencil-fill',
        'PUBLISHED' => 'bi-calendar-check-fill',
        'ONGOING' => 'bi-play-circle-fill',
        'WAITING' => 'bi-clock',
        'PENDING' => 'bi-clock',
        'APPROVED_BY_SUPERVISORS' => 'bi-check-circle-fill',
        'PASSED' => 'bi-award-fill',
        'PASSED_WITH_REVISION' => 'bi-pencil-square',
        'REPEAT' => 'bi-x-circle-fill',
        'SEMINAR_PASSED' => 'bi-award-fill',
        'SEMINAR_REPEAT' => 'bi-x-circle-fill',
        'OPEN' => 'bi-card-text',
        'DONE' => 'bi-check-circle-fill',
    ];
@endphp
<span {{ $attributes->merge(['class' => 'badge ' . ($badgeClasses[$status] ?? 'bg-light text-dark border')]) }}>
    <i class="bi {{ $icons[$status] ?? 'bi-dot' }} me-1"></i>{{ $status }}
</span>

