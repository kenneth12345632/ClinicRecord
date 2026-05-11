@include('partials.material-calendar-flatpickr-assets')
@if(auth()->check() && strtolower((string) (auth()->user()->role ?? '')) === 'admin')
<script>window.__FP_MEDICINE_NEUTRAL_CALENDAR__ = true;</script>
<style id="fp-medicine-neutral-admin-calendar">
    /* Admin medicine inventory + create/edit: align with app blues / sans-serif (same flatpickr behavior). */
    .flatpickr-calendar.fp-material-calendar.fp-medicine-neutral-admin {
        --fp-material-purple: #2563eb;
        --fp-material-purple-rgb: 37, 99, 235;
        font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif !important;
    }
    .flatpickr-calendar.fp-material-calendar.fp-medicine-neutral-admin .fp-minv-material-banner {
        background: linear-gradient(180deg, #1d4ed8 0%, #2563eb 100%);
    }
    .flatpickr-calendar.fp-material-calendar.fp-medicine-neutral-admin .fp-minv-headline {
        font-weight: 700;
        letter-spacing: -0.02em;
    }
    .flatpickr-calendar.fp-material-calendar.fp-medicine-neutral-admin .flatpickr-months .flatpickr-prev-month:hover,
    .flatpickr-calendar.fp-material-calendar.fp-medicine-neutral-admin .flatpickr-months .flatpickr-next-month:hover {
        background: rgba(37, 99, 235, 0.1) !important;
    }
    .flatpickr-calendar.fp-material-calendar.fp-medicine-neutral-admin .flatpickr-current-month .flatpickr-monthDropdown-months {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12' fill='none'%3E%3Cpath d='M2.5 4.25L6 7.75L9.5 4.25' stroke='%232563eb' stroke-width='1.6' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E") !important;
    }
    .flatpickr-calendar.fp-material-calendar.fp-medicine-neutral-admin .flatpickr-weekdays {
        background: #eff6ff !important;
    }
    .flatpickr-calendar.fp-material-calendar.fp-medicine-neutral-admin span.flatpickr-weekday {
        color: #1e40af !important;
    }
    .flatpickr-calendar.fp-material-calendar.fp-medicine-neutral-admin .flatpickr-day:hover:not(.flatpickr-disabled):not(.selected) {
        background: #dbeafe !important;
    }
</style>
@endif
