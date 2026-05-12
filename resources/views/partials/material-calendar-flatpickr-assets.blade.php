@once('material-calendar-flatpickr-assets-bundle')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css">
<style id="material-calendar-flatpickr-theme">
    .flatpickr-calendar.fp-material-calendar {
        --fp-green: #16a34a;
        --fp-green-rgb: 22, 163, 74;
        border-radius: 8px;
        overflow: visible;
        border: 1px solid #e5e7eb;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        font-family: inherit;
        min-width: 280px;
    }

    /* Hide the material banner completely */
    .flatpickr-calendar.fp-material-calendar .fp-minv-material-banner {
        display: none !important;
    }

    /* Month navigation row */
    .flatpickr-calendar.fp-material-calendar .flatpickr-months {
        display: flex !important;
        flex-direction: row !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 2px !important;
        background: #fff !important;
        padding: 8px 6px !important;
        border-bottom: 1px solid #f3f4f6;
        height: auto !important;
    }

    .flatpickr-calendar.fp-material-calendar .flatpickr-month {
        height: auto !important;
        flex: 1 1 auto !important;
        min-width: 0 !important;
        order: 2 !important;
        padding: 0 !important;
        background: transparent !important;
        color: #111 !important;
        overflow: visible !important;
    }

    .flatpickr-calendar.fp-material-calendar .flatpickr-months .flatpickr-prev-month,
    .flatpickr-calendar.fp-material-calendar .flatpickr-months .flatpickr-next-month {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        position: static !important;
        transform: none !important;
        padding: 2px !important;
        width: 24px !important;
        height: 24px !important;
        border-radius: 4px !important;
        flex-shrink: 0 !important;
        fill: #374151 !important;
        color: #374151 !important;
        opacity: 1 !important;
    }

    .flatpickr-calendar.fp-material-calendar .flatpickr-months .flatpickr-prev-month {
        order: 1 !important;
    }

    .flatpickr-calendar.fp-material-calendar .flatpickr-months .flatpickr-next-month {
        order: 3 !important;
    }

    .flatpickr-calendar.fp-material-calendar .flatpickr-months .flatpickr-prev-month svg,
    .flatpickr-calendar.fp-material-calendar .flatpickr-months .flatpickr-next-month svg {
        display: none !important;
    }

    .flatpickr-calendar.fp-material-calendar .flatpickr-months .flatpickr-prev-month::after {
        content: '\2039';
        font-size: 1.5rem;
        color: #6b7280;
        line-height: 1;
    }

    .flatpickr-calendar.fp-material-calendar .flatpickr-months .flatpickr-next-month::after {
        content: '\203A';
        font-size: 1.5rem;
        color: #6b7280;
        line-height: 1;
    }

    .flatpickr-calendar.fp-material-calendar .flatpickr-months .flatpickr-prev-month:hover,
    .flatpickr-calendar.fp-material-calendar .flatpickr-months .flatpickr-next-month:hover {
        background: #f0fdf4 !important;
    }

    /* Month & Year display */
    .flatpickr-calendar.fp-material-calendar .flatpickr-current-month {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 0.3rem !important;
        width: 100% !important;
        height: auto !important;
        padding: 0 !important;
        font-size: 0.95rem !important;
        color: #111 !important;
        left: 0 !important;
        position: relative !important;
        overflow: visible !important;
    }

    .flatpickr-calendar.fp-material-calendar .flatpickr-current-month .flatpickr-monthDropdown-months {
        appearance: none !important;
        -webkit-appearance: none !important;
        background: transparent !important;
        border: none !important;
        color: #374151 !important;
        font-weight: 600 !important;
        font-size: 0.9rem !important;
        padding: 2px 0 !important;
        pointer-events: none;
        outline: none !important;
        box-shadow: none !important;
    }

    /* Prevent Select2 from wrapping flatpickr month dropdown */
    .flatpickr-calendar .select2-container,
    .flatpickr-calendar .select2 {
        display: none !important;
    }

    /* Year nav buttons (<<  >>) */
    .flatpickr-calendar.fp-material-calendar .fp-year-nav {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        position: static !important;
        width: 24px !important;
        height: 24px !important;
        padding: 2px !important;
        border: none;
        background: transparent;
        cursor: pointer;
        fill: #374151 !important;
        color: #6b7280;
        font-size: 1.5rem;
        font-weight: 400;
        border-radius: 4px;
        flex-shrink: 0;
        line-height: 1;
    }

    .flatpickr-calendar.fp-material-calendar .fp-year-nav:hover {
        background: #f0fdf4;
    }

    .flatpickr-calendar.fp-material-calendar .flatpickr-current-month .numInputWrapper {
        display: none !important;
    }

    .flatpickr-calendar.fp-material-calendar .fp-year-label {
        color: #374151 !important;
        font-weight: 600 !important;
        font-size: 0.9rem !important;
        line-height: 1 !important;
        user-select: none;
        margin-left: 4px;
        white-space: nowrap;
    }

    .flatpickr-calendar.fp-material-calendar .flatpickr-current-month .numInputWrapper .numInput.cur-year {
        background: transparent !important;
        border: none !important;
        outline: none !important;
        box-shadow: none !important;
        color: #374151 !important;
        font-weight: 600 !important;
        font-size: 0.9rem !important;
        width: 3.5rem !important;
        padding: 2px 0 !important;
        -moz-appearance: textfield;
        pointer-events: none;
        cursor: default;
    }

    .flatpickr-calendar.fp-material-calendar .flatpickr-current-month .numInputWrapper .numInput.cur-year::-webkit-outer-spin-button,
    .flatpickr-calendar.fp-material-calendar .flatpickr-current-month .numInputWrapper .numInput.cur-year::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .flatpickr-calendar.fp-material-calendar .flatpickr-current-month span.arrowUp,
    .flatpickr-calendar.fp-material-calendar .flatpickr-current-month span.arrowDown {
        display: none !important;
    }

    /* Weekday headers */
    .flatpickr-calendar.fp-material-calendar .flatpickr-weekdays {
        background: #fff;
        padding-top: 4px;
        padding-bottom: 4px;
    }

    .flatpickr-calendar.fp-material-calendar span.flatpickr-weekday {
        color: #6b7280 !important;
        font-weight: 600 !important;
        font-size: 0.75rem !important;
    }

    /* Day grid */
    .flatpickr-calendar.fp-material-calendar .flatpickr-innerContainer {
        background: #fff;
        padding-bottom: 4px;
    }

    .flatpickr-calendar.fp-material-calendar .flatpickr-days {
        background: #fff;
    }

    .flatpickr-calendar.fp-material-calendar .flatpickr-day {
        border-radius: 9999px !important;
        margin: 2px auto;
        border-color: transparent !important;
        color: #1e293b;
        font-weight: 500;
    }

    .flatpickr-calendar.fp-material-calendar .flatpickr-day.prevMonthDay,
    .flatpickr-calendar.fp-material-calendar .flatpickr-day.nextMonthDay {
        color: #cbd5e1 !important;
    }

    /* Selected day */
    .flatpickr-calendar.fp-material-calendar .flatpickr-day.selected,
    .flatpickr-calendar.fp-material-calendar .flatpickr-day.flatpickr-day.selected,
    .flatpickr-calendar.fp-material-calendar .flatpickr-day.startRange,
    .flatpickr-calendar.fp-material-calendar .flatpickr-day.endRange {
        background: var(--fp-green) !important;
        border-color: var(--fp-green) !important;
        color: #fff !important;
        box-shadow: none !important;
    }

    /* Today (not selected) */
    .flatpickr-calendar.fp-material-calendar .flatpickr-day.today:not(.selected) {
        border: 1.5px solid rgba(var(--fp-green-rgb), 0.5) !important;
        background: transparent !important;
        font-weight: 700;
        color: #1e293b !important;
    }

    .flatpickr-calendar.fp-material-calendar .flatpickr-day.today.selected {
        background: var(--fp-green) !important;
        color: #fff !important;
        border-color: var(--fp-green) !important;
    }

    /* Hover */
    .flatpickr-calendar.fp-material-calendar .flatpickr-day:hover:not(.flatpickr-disabled):not(.selected) {
        background: #f0fdf4 !important;
        border-color: transparent !important;
    }

    /* Disabled */
    .flatpickr-calendar.fp-material-calendar .flatpickr-day.flatpickr-disabled,
    .flatpickr-calendar.fp-material-calendar .flatpickr-day.prevMonthDay.flatpickr-disabled,
    .flatpickr-calendar.fp-material-calendar .flatpickr-day.nextMonthDay.flatpickr-disabled {
        color: #e2e8f0 !important;
    }

    /* Medicine inventory picker overrides */
    .flatpickr-calendar.fp-material-calendar.fp-medicine-inventory-picker {
        overflow: visible !important;
        min-width: min(100vw - 24px, 280px) !important;
        max-width: min(100vw - 24px, 280px);
    }

    .flatpickr-calendar.fp-material-calendar.fp-medicine-inventory-picker .flatpickr-month {
        overflow: visible !important;
        min-width: 8.75rem !important;
    }

    .flatpickr-calendar.fp-material-calendar.fp-medicine-inventory-picker .flatpickr-current-month {
        display: flex !important;
        flex-wrap: nowrap !important;
        align-items: flex-start !important;
        justify-content: center !important;
        gap: 0.25rem !important;
        width: auto !important;
        max-width: 100% !important;
        font-size: 0.8125rem !important;
    }

    .flatpickr-calendar.fp-material-calendar.fp-medicine-inventory-picker .flatpickr-current-month .flatpickr-monthDropdown-months {
        flex: 0 0 auto !important;
        min-width: 5.25rem !important;
        max-width: 50% !important;
        font-size: 0.8125rem !important;
        padding: 2px 0 !important;
    }

    .flatpickr-calendar.fp-material-calendar.fp-medicine-inventory-picker .flatpickr-current-month .numInputWrapper {
        display: none !important;
    }

    .flatpickr-calendar.fp-material-calendar.fp-medicine-inventory-picker .flatpickr-current-month .numInputWrapper .numInput.cur-year {
        font-size: 0.9rem !important;
        font-weight: 600 !important;
        color: #374151 !important;
        width: 3rem !important;
        padding: 2px 0 !important;
        background: transparent !important;
        border: none !important;
        outline: none !important;
        box-shadow: none !important;
        pointer-events: none;
        cursor: default;
    }

    .flatpickr-calendar.fp-material-calendar.fp-medicine-inventory-picker .flatpickr-current-month .numInputWrapper .numInput.cur-year.fp-inv-year-native-hide {
        position: absolute !important;
        width: 1px !important;
        height: 1px !important;
        padding: 0 !important;
        margin: 0 !important;
        opacity: 0 !important;
        pointer-events: none !important;
        clip: rect(0, 0, 0, 0) !important;
        border: none !important;
        -moz-appearance: none !important;
    }

    .flatpickr-calendar.fp-material-calendar.fp-medicine-inventory-picker .fp-inv-year-stack {
        display: none !important;
        z-index: 2;
    }

    .flatpickr-calendar.fp-material-calendar.fp-medicine-inventory-picker .fp-inv-year-direct {
        width: 100%;
        box-sizing: border-box;
        border: none !important;
        background: transparent !important;
        color: #374151 !important;
        font-weight: 600 !important;
        font-size: 0.9rem !important;
        padding: 2px 0 !important;
        text-align: center !important;
        outline: none;
        box-shadow: none !important;
        pointer-events: none;
        cursor: default;
    }

    .flatpickr-calendar.fp-material-calendar.fp-medicine-inventory-picker .fp-inv-year-direct::-webkit-outer-spin-button,
    .flatpickr-calendar.fp-material-calendar.fp-medicine-inventory-picker .fp-inv-year-direct::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .flatpickr-calendar.fp-material-calendar.fp-medicine-inventory-picker .fp-inv-year-direct {
        -moz-appearance: textfield;
    }

    .flatpickr-calendar.fp-material-calendar.fp-medicine-inventory-picker .flatpickr-weekdays {
        padding-top: 4px !important;
        padding-bottom: 2px !important;
        overflow: visible !important;
    }

    .flatpickr-calendar.fp-material-calendar.fp-medicine-inventory-picker .flatpickr-rContainer {
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }

    .flatpickr-calendar.fp-material-calendar.fp-medicine-inventory-picker .flatpickr-innerContainer {
        padding-bottom: 4px !important;
        overflow: visible !important;
    }

    .flatpickr-calendar.fp-material-calendar.fp-medicine-inventory-picker .flatpickr-days {
        width: 100% !important;
        min-width: 0 !important;
        max-width: 100% !important;
        overflow: visible !important;
    }

    .flatpickr-calendar.fp-material-calendar.fp-medicine-inventory-picker .dayContainer {
        width: 100% !important;
        min-width: 0 !important;
        max-width: none !important;
        box-sizing: border-box !important;
    }

    .flatpickr-calendar.fp-material-calendar.fp-medicine-inventory-picker .flatpickr-day {
        box-sizing: border-box !important;
        flex-basis: 14.2857143% !important;
        max-width: none !important;
        width: 14.2857143% !important;
        height: 30px !important;
        line-height: 28px !important;
        margin: 1px 0 !important;
        font-size: 0.75rem !important;
    }

    /* Compact calendar overrides */
    .flatpickr-calendar.fp-material-calendar.fp-compact-calendar {
        min-width: min(100vw - 24px, 280px) !important;
        max-width: min(100vw - 24px, 280px);
    }

    .flatpickr-calendar.fp-material-calendar.fp-compact-calendar .flatpickr-current-month {
        font-size: 0.8125rem !important;
    }

    .flatpickr-calendar.fp-material-calendar.fp-compact-calendar .flatpickr-current-month .flatpickr-monthDropdown-months {
        font-size: 0.8125rem !important;
        padding: 2px 0 !important;
    }

    .flatpickr-calendar.fp-material-calendar.fp-compact-calendar .flatpickr-current-month .numInputWrapper .numInput.cur-year {
        font-size: 0.8125rem !important;
        width: 3rem !important;
        padding: 2px 0 !important;
    }

    .flatpickr-calendar.fp-material-calendar.fp-compact-calendar .flatpickr-rContainer {
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }

    .flatpickr-calendar.fp-material-calendar.fp-compact-calendar .flatpickr-days {
        width: 100% !important;
        min-width: 0 !important;
        max-width: 100% !important;
    }

    .flatpickr-calendar.fp-material-calendar.fp-compact-calendar .dayContainer {
        width: 100% !important;
        min-width: 0 !important;
        max-width: none !important;
        box-sizing: border-box !important;
    }

    .flatpickr-calendar.fp-material-calendar.fp-compact-calendar .flatpickr-day {
        box-sizing: border-box !important;
        flex-basis: 14.2857143% !important;
        max-width: none !important;
        width: 14.2857143% !important;
        height: 30px !important;
        line-height: 28px !important;
        margin: 1px 0 !important;
        font-size: 0.75rem !important;
    }
</style>
@endonce
