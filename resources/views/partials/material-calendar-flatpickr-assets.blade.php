@once('material-calendar-flatpickr-assets-bundle')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css">
<style id="material-calendar-flatpickr-theme">
    /* Shared purple Material-style calendar (#7b42f5) — all app date pickers */
    .flatpickr-calendar.fp-material-calendar {
        --fp-material-purple: #7b42f5;
        --fp-material-purple-rgb: 123, 66, 245;
        border-radius: 16px;
        overflow: hidden;
        border: none;
        box-shadow: 0 20px 50px rgba(15, 23, 42, 0.22);
        font-family: "Times New Roman", Times, serif;
        min-width: 300px;
    }

    .flatpickr-calendar.fp-material-calendar .fp-minv-material-banner {
        background: var(--fp-material-purple);
        color: #fff;
        padding: 14px 12px 16px;
    }

    /* Top row: SELECT DATE left, pencil right (reference) */
    .flatpickr-calendar.fp-material-calendar .fp-minv-top-row {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 0.5rem;
    }

    .flatpickr-calendar.fp-material-calendar .fp-minv-select-label {
        flex: 1;
        padding-top: 4px;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: rgba(255, 255, 255, 0.95);
        text-align: left;
    }

    .flatpickr-calendar.fp-material-calendar .fp-minv-pencil {
        flex-shrink: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        margin: -4px -4px 0 0;
        color: #fff;
        padding-top: 2px;
    }

    .flatpickr-calendar.fp-material-calendar .fp-minv-headline {
        font-size: clamp(1.6rem, 4.5vw, 2.05rem);
        font-weight: 800;
        line-height: 1.22;
        color: #fff;
        margin-top: 8px;
        padding-left: 2px;
        padding-right: 4px;
        letter-spacing: -0.035em;
        min-height: 1.35em;
        text-align: left;
    }

    .flatpickr-calendar.fp-material-calendar .flatpickr-months {
        display: flex !important;
        flex-direction: row !important;
        align-items: center !important;
        justify-content: space-between !important;
        gap: 4px !important;
        background: #fff !important;
        padding: 12px 6px !important;
        border-bottom: 1px solid rgba(var(--fp-material-purple-rgb), 0.12);
    }

    .flatpickr-calendar.fp-material-calendar .flatpickr-month {
        height: auto !important;
        flex: 1 1 auto !important;
        min-width: 0 !important;
        order: 2 !important;
        padding: 0 !important;
        background: transparent !important;
        color: #111 !important;
    }

    .flatpickr-calendar.fp-material-calendar .flatpickr-months .flatpickr-prev-month,
    .flatpickr-calendar.fp-material-calendar .flatpickr-months .flatpickr-next-month {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        position: static !important;
        transform: none !important;
        padding: 6px !important;
        width: 36px !important;
        height: 36px !important;
        border-radius: 9999px !important;
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

    .flatpickr-calendar.fp-material-calendar .flatpickr-months .flatpickr-prev-month:hover,
    .flatpickr-calendar.fp-material-calendar .flatpickr-months .flatpickr-next-month:hover {
        background: rgba(var(--fp-material-purple-rgb), 0.08) !important;
    }

    .flatpickr-calendar.fp-material-calendar .flatpickr-current-month {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 0.2rem !important;
        width: 100% !important;
        padding: 0 !important;
        font-size: 1rem !important;
        color: #111 !important;
        left: 0 !important;
        position: relative !important;
    }

    .flatpickr-calendar.fp-material-calendar .flatpickr-current-month .flatpickr-monthDropdown-months {
        appearance: none;
        -webkit-appearance: none;
        background: transparent !important;
        border: none !important;
        color: #111 !important;
        font-weight: 800 !important;
        font-size: 1rem !important;
        padding: 4px 22px 4px 0 !important;
        cursor: pointer;
        text-transform: capitalize;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12' fill='none'%3E%3Cpath d='M2.5 4.25L6 7.75L9.5 4.25' stroke='%237b42f5' stroke-width='1.6' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E") !important;
        background-repeat: no-repeat !important;
        background-position: right 2px center !important;
        border-radius: 0 !important;
    }

    .flatpickr-calendar.fp-material-calendar .flatpickr-current-month .numInputWrapper {
        padding: 0 !important;
        display: inline-flex !important;
        align-items: center !important;
        margin-left: 2px;
    }

    .flatpickr-calendar.fp-material-calendar .flatpickr-current-month .numInputWrapper .numInput.cur-year {
        background: transparent !important;
        border: none !important;
        color: #111 !important;
        font-weight: 800 !important;
        font-size: 1rem !important;
        width: 3.5rem !important;
        padding: 4px 0 !important;
        -moz-appearance: textfield;
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

    /* Medicine inventory: year combo + month must stay visible (flex was shrinking month <select> to 0) */
    .flatpickr-calendar.fp-material-calendar.fp-medicine-inventory-picker {
        overflow: visible !important;
        /* Wide enough for 7 columns; flatpickr’s innerContainer uses overflow:hidden — too narrow + fixed day max-width clipped Sat */
        min-width: min(100vw - 24px, 292px) !important;
        max-width: min(100vw - 24px, 292px);
        border-radius: 12px;
        box-shadow: 0 12px 32px rgba(15, 23, 42, 0.16);
    }

    .flatpickr-calendar.fp-material-calendar.fp-medicine-inventory-picker .fp-minv-material-banner {
        padding: 8px 10px 10px;
    }

    .flatpickr-calendar.fp-material-calendar.fp-medicine-inventory-picker .fp-minv-select-label {
        font-size: 8px;
        letter-spacing: 0.12em;
        padding-top: 2px;
    }

    .flatpickr-calendar.fp-material-calendar.fp-medicine-inventory-picker .fp-minv-pencil {
        width: 28px;
        height: 28px;
        margin: -2px -2px 0 0;
    }

    .flatpickr-calendar.fp-material-calendar.fp-medicine-inventory-picker .fp-minv-headline {
        font-size: 1.05rem !important;
        margin-top: 4px;
        min-height: 1.25em;
        letter-spacing: -0.025em;
    }

    .flatpickr-calendar.fp-material-calendar.fp-medicine-inventory-picker .flatpickr-months {
        align-items: flex-start !important;
        padding: 6px 4px !important;
        gap: 2px !important;
    }

    .flatpickr-calendar.fp-material-calendar.fp-medicine-inventory-picker .flatpickr-months .flatpickr-prev-month,
    .flatpickr-calendar.fp-material-calendar.fp-medicine-inventory-picker .flatpickr-months .flatpickr-next-month {
        width: 28px !important;
        height: 28px !important;
        padding: 4px !important;
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
        padding: 2px 18px 2px 0 !important;
    }

    .flatpickr-calendar.fp-material-calendar.fp-medicine-inventory-picker .flatpickr-current-month .numInputWrapper {
        position: relative;
        flex: 1 1 auto !important;
        min-width: 3.75rem !important;
        max-width: 48% !important;
        margin-left: 2px !important;
    }

    .flatpickr-calendar.fp-material-calendar.fp-medicine-inventory-picker .flatpickr-current-month .numInputWrapper .numInput.cur-year {
        font-size: 0.8125rem !important;
        width: 3rem !important;
        padding: 2px 0 !important;
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
        display: flex;
        flex-direction: column;
        gap: 6px;
        align-items: stretch;
        position: relative;
        z-index: 2;
    }

    .flatpickr-calendar.fp-material-calendar.fp-medicine-inventory-picker .fp-inv-year-direct {
        width: 100%;
        box-sizing: border-box;
        border: 1px solid #111 !important;
        border-radius: 6px !important;
        background: #fff !important;
        color: #111 !important;
        font-weight: 800 !important;
        font-size: 0.8125rem !important;
        padding: 3px 8px !important;
        text-align: center !important;
        outline: none;
    }

    .flatpickr-calendar.fp-material-calendar.fp-medicine-inventory-picker .fp-inv-year-direct:focus {
        box-shadow: 0 0 0 2px rgba(123, 66, 245, 0.25);
        border-color: var(--fp-material-purple) !important;
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

    .flatpickr-calendar.fp-material-calendar.fp-medicine-inventory-picker span.flatpickr-weekday {
        font-size: 0.6rem !important;
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

    /* flatpickr.min.css fixes .flatpickr-days / .dayContainer at 307.875px — narrower calendar + overflow:hidden clips column 7 */
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

    /* ── Compact calendar (consultation / birthday pickers) ── */
    .flatpickr-calendar.fp-material-calendar.fp-compact-calendar {
        min-width: min(100vw - 24px, 292px) !important;
        max-width: min(100vw - 24px, 292px);
        border-radius: 12px;
        box-shadow: 0 12px 32px rgba(15, 23, 42, 0.16);
    }

    .flatpickr-calendar.fp-material-calendar.fp-compact-calendar .fp-minv-material-banner {
        padding: 8px 10px 10px;
    }

    .flatpickr-calendar.fp-material-calendar.fp-compact-calendar .fp-minv-select-label {
        font-size: 8px;
        letter-spacing: 0.12em;
        padding-top: 2px;
    }

    .flatpickr-calendar.fp-material-calendar.fp-compact-calendar .fp-minv-pencil {
        width: 28px;
        height: 28px;
        margin: -2px -2px 0 0;
    }

    .flatpickr-calendar.fp-material-calendar.fp-compact-calendar .fp-minv-headline {
        font-size: 1.05rem !important;
        margin-top: 4px;
        min-height: 1.25em;
        letter-spacing: -0.025em;
    }

    .flatpickr-calendar.fp-material-calendar.fp-compact-calendar .flatpickr-months {
        padding: 6px 4px !important;
        gap: 2px !important;
    }

    .flatpickr-calendar.fp-material-calendar.fp-compact-calendar .flatpickr-months .flatpickr-prev-month,
    .flatpickr-calendar.fp-material-calendar.fp-compact-calendar .flatpickr-months .flatpickr-next-month {
        width: 28px !important;
        height: 28px !important;
        padding: 4px !important;
    }

    .flatpickr-calendar.fp-material-calendar.fp-compact-calendar .flatpickr-current-month {
        font-size: 0.8125rem !important;
    }

    .flatpickr-calendar.fp-material-calendar.fp-compact-calendar .flatpickr-current-month .flatpickr-monthDropdown-months {
        font-size: 0.8125rem !important;
        padding: 2px 18px 2px 0 !important;
    }

    .flatpickr-calendar.fp-material-calendar.fp-compact-calendar .flatpickr-current-month .numInputWrapper .numInput.cur-year {
        font-size: 0.8125rem !important;
        width: 3rem !important;
        padding: 2px 0 !important;
    }

    .flatpickr-calendar.fp-material-calendar.fp-compact-calendar .flatpickr-weekdays {
        padding-top: 4px !important;
        padding-bottom: 2px !important;
    }

    .flatpickr-calendar.fp-material-calendar.fp-compact-calendar span.flatpickr-weekday {
        font-size: 0.6rem !important;
    }

    .flatpickr-calendar.fp-material-calendar.fp-compact-calendar .flatpickr-rContainer {
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }

    .flatpickr-calendar.fp-material-calendar.fp-compact-calendar .flatpickr-innerContainer {
        padding-bottom: 4px !important;
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

    .flatpickr-calendar.fp-material-calendar .flatpickr-weekdays {
        background: #f3e8ff;
        padding-top: 8px;
        padding-bottom: 4px;
    }

    .flatpickr-calendar.fp-material-calendar span.flatpickr-weekday {
        color: var(--fp-material-purple) !important;
        font-weight: 700 !important;
        font-size: 0.68rem !important;
        letter-spacing: 0.02em;
    }

    .flatpickr-calendar.fp-material-calendar .flatpickr-innerContainer {
        background: #fff;
        padding-bottom: 8px;
    }

    .flatpickr-calendar.fp-material-calendar .flatpickr-days {
        background: #fff;
    }

    .flatpickr-calendar.fp-material-calendar .flatpickr-day {
        border-radius: 9999px !important;
        margin: 2px auto;
        border-color: transparent !important;
        color: #1e293b;
    }

    .flatpickr-calendar.fp-material-calendar .flatpickr-day.prevMonthDay,
    .flatpickr-calendar.fp-material-calendar .flatpickr-day.nextMonthDay {
        color: #cbd5e1 !important;
    }

    .flatpickr-calendar.fp-material-calendar .flatpickr-day.selected,
    .flatpickr-calendar.fp-material-calendar .flatpickr-day.flatpickr-day.selected,
    .flatpickr-calendar.fp-material-calendar .flatpickr-day.startRange,
    .flatpickr-calendar.fp-material-calendar .flatpickr-day.endRange {
        background: var(--fp-material-purple) !important;
        border-color: var(--fp-material-purple) !important;
        color: #fff !important;
        box-shadow: none !important;
    }

    .flatpickr-calendar.fp-material-calendar .flatpickr-day.today:not(.selected) {
        border: 1px solid rgba(var(--fp-material-purple-rgb), 0.4) !important;
        background: rgba(var(--fp-material-purple-rgb), 0.1) !important;
        font-weight: 700;
        color: #1e293b !important;
    }

    .flatpickr-calendar.fp-material-calendar .flatpickr-day.today.selected {
        background: var(--fp-material-purple) !important;
        color: #fff !important;
        border-color: var(--fp-material-purple) !important;
    }

    .flatpickr-calendar.fp-material-calendar .flatpickr-day:hover:not(.flatpickr-disabled):not(.selected) {
        background: #ede7ff !important;
        border-color: transparent !important;
    }

    .flatpickr-calendar.fp-material-calendar .flatpickr-day.flatpickr-disabled,
    .flatpickr-calendar.fp-material-calendar .flatpickr-day.prevMonthDay.flatpickr-disabled,
    .flatpickr-calendar.fp-material-calendar .flatpickr-day.nextMonthDay.flatpickr-disabled {
        color: #e2e8f0 !important;
    }
</style>
@endonce
