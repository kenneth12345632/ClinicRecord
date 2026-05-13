<!DOCTYPE html>
<html lang="en"
    x-data='{
        darkMode: localStorage.getItem("darkMode") === "true",
        themeTogglePending: false,
        scheduleDarkToggle() {
            if (this.themeTogglePending) return;
            this.themeTogglePending = true;
            setTimeout(() => {
                this.darkMode = !this.darkMode;
                localStorage.setItem("darkMode", this.darkMode);
                document.documentElement.classList.toggle("dark", this.darkMode);
                this.themeTogglePending = false;
            }, 200);
        }
    }'
    :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $pageTitle = 'Dashboard';

        if (request()->routeIs(
            'dashboard',
            'admin.dashboard',
            'doctor.dashboard',
            'nurse.dashboard',
            'bhw.dashboard'
        )) {
            $pageTitle = 'Dashboard';
        } elseif (request()->routeIs(
            'record.create',
            'doctor.record.create',
            'nurse.record.create',
            'bhw.record.create'
        )) {
            $pageTitle = 'Add New Consultation';
        } elseif (request()->routeIs(
            'record.*',
            'doctor.record.*',
            'nurse.record.*',
            'bhw.record.*'
        )) {
            $pageTitle = 'Patient Records';
        } elseif (request()->routeIs('doctor.pending.*', 'nurse.pending.*')) {
            $pageTitle = 'Pending Patient';
        } elseif (request()->routeIs('medicines.*', 'bhw.medicines.*')) {
            $pageTitle = 'Medicine Inventory';
        } elseif (request()->routeIs('bhw.dispensing.*', 'admin.dispensing.*')) {
            $pageTitle = 'Medicine Queue';
        } elseif (request()->routeIs('reports.diagnosis', 'bhw.reports.diagnosis')) {
            $pageTitle = 'Diagnosis Reports';
        } elseif (request()->routeIs('reports.patient_records', 'bhw.reports.patient_records')) {
            $pageTitle = 'Patient Records Report';
        } elseif (request()->routeIs('admin.reports.*')) {
            $pageTitle = 'Reports';
        } elseif (request()->routeIs('admin.users.*')) {
            $pageTitle = 'User Management';
        } elseif (request()->routeIs('admin.activity-logs.*')) {
            $pageTitle = 'Activity Logs';
        } elseif (request()->routeIs('admin.inventory.*')) {
            $pageTitle = 'Inventory Ledger';
        } elseif (request()->routeIs('profile.*')) {
            $pageTitle = 'Profile';
        }
    @endphp
    <title>{{ $pageTitle }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon-logo2.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/favicon-logo2.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                fontFamily: {
                    sans: ['"Segoe UI"', '"Segoe UI Variable"', 'Tahoma', 'Geneva', 'Verdana', 'sans-serif'],
                    serif: ['"Segoe UI"', '"Segoe UI Variable"', 'Tahoma', 'Geneva', 'Verdana', 'sans-serif'],
                    mono: ['ui-monospace', 'SFMono-Regular', 'Menlo', 'Monaco', 'Consolas', 'Liberation Mono', 'Courier New', 'monospace'],
                },
            },
        };
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/paginationjs@2.6.0/dist/pagination.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/paginationjs@2.6.0/dist/pagination.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
        }
    </script>
    
    <style>
        html, body {
            font-family: "Segoe UI", "Segoe UI Variable", Tahoma, Geneva, Verdana, sans-serif;
        }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-down { animation: fadeInDown 0.3s ease-out; }

        /* Unified PaginationJS (Clinic OS): summary left, dark segmented controls right */
        .clinic-os-pagination {
            display: flex !important;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem 1rem;
            width: 100%;
            padding: 0.5rem 0;
        }
        .clinic-os-pagination::after { display: none !important; content: none !important; }
        .clinic-os-pagination .paginationjs-nav {
            float: none !important;
            margin: 0 !important;
            font-size: 0.875rem;
            line-height: 1.5;
            color: #16a34a;
            font-weight: 500;
        }
        .clinic-os-pagination .paginationjs-pages {
            float: none !important;
            margin-left: 0 !important;
        }
        .clinic-os-pagination .paginationjs-pages ul {
            float: none !important;
            display: flex;
            margin: 0;
            padding: 0;
            list-style: none;
            border-radius: 0;
            overflow: visible;
            box-shadow: none;
            gap: 0.25rem;
        }
        .clinic-os-pagination .paginationjs-pages li {
            float: none !important;
            display: flex;
            margin: 0;
            border: none !important;
        }
        .clinic-os-pagination .paginationjs-pages li:last-child {
            border-right: none !important;
        }
        .clinic-os-pagination .paginationjs-pages li > a {
            width: 2.25rem;
            height: 2.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            background: transparent !important;
            color: #374151 !important;
            font-size: 0.875rem;
            font-weight: 500;
            border: none !important;
            border-radius: 50%;
            cursor: pointer;
            box-sizing: border-box;
            padding: 0;
            line-height: 1;
            transition: background 0.15s, color 0.15s;
        }
        .clinic-os-pagination .paginationjs-pages li.J-paginationjs-page > a {
            color: #374151 !important;
        }
        .clinic-os-pagination .paginationjs-pages li.paginationjs-prev > a,
        .clinic-os-pagination .paginationjs-pages li.paginationjs-next > a {
            color: #6b7280 !important;
            font-weight: 600;
        }
        .clinic-os-pagination .paginationjs-pages li.paginationjs-prev > a:hover,
        .clinic-os-pagination .paginationjs-pages li.paginationjs-next > a:hover {
            color: #16a34a !important;
            background: #dcfce7 !important;
        }
        .clinic-os-pagination .paginationjs-pages li > a:hover {
            background: #dcfce7 !important;
            color: #16a34a !important;
        }
        .clinic-os-pagination .paginationjs-pages li.active > a,
        .clinic-os-pagination .paginationjs-pages li.active > a:hover {
            background: #22c55e !important;
            color: #fff !important;
            cursor: default;
            box-shadow: none;
        }
        .clinic-os-pagination .paginationjs-pages li.disabled > a,
        .clinic-os-pagination .paginationjs-pages li.disabled > a:hover {
            background: transparent !important;
            color: #d1d5db !important;
            cursor: not-allowed;
        }
        .clinic-os-pagination .paginationjs-pages li.disabled.paginationjs-prev > a,
        .clinic-os-pagination .paginationjs-pages li.disabled.paginationjs-next > a,
        .clinic-os-pagination .paginationjs-pages li.disabled.paginationjs-prev > a:hover,
        .clinic-os-pagination .paginationjs-pages li.disabled.paginationjs-next > a:hover {
            color: #d1d5db !important;
        }
        .clinic-os-pagination .paginationjs-pages li.paginationjs-ellipsis {
            border-right: none !important;
        }
        .clinic-os-pagination .paginationjs-pages li.paginationjs-ellipsis a {
            background: transparent !important;
            color: #9ca3af !important;
        }
        @media (max-width: 640px) {
            .clinic-os-pagination { flex-direction: column; align-items: stretch !important; }
            .clinic-os-pagination .paginationjs-pages { align-self: center; }
        }

        /* Show/Hide toggles — shared pill layout (light + dark themes below) */
        #togglePatientsBtn,
        #togglePatientReportBtn,
        #toggleDiagnosisRowsBtn,
        #toggleMedicineBtn,
        #toggleUsersBtn {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            border-radius: 9999px !important;
            font-weight: 700 !important;
            font-size: 0.875rem !important;
            line-height: 1.45 !important;
            padding: 0.65rem 1.35rem !important;
            min-height: 2.75rem !important;
            box-sizing: border-box !important;
            overflow: visible !important;
        }
        /* Light mode: white pill, navy label, light gray border */
        html:not(.dark) #togglePatientsBtn,
        html:not(.dark) #togglePatientReportBtn,
        html:not(.dark) #toggleDiagnosisRowsBtn,
        html:not(.dark) #toggleMedicineBtn,
        html:not(.dark) #toggleUsersBtn {
            background-color: #ffffff !important;
            border: 1px solid #d1d5db !important;
            color: #0f172a !important;
            box-shadow: none !important;
        }
        html:not(.dark) #togglePatientsBtn:hover,
        html:not(.dark) #togglePatientReportBtn:hover,
        html:not(.dark) #toggleDiagnosisRowsBtn:hover,
        html:not(.dark) #toggleMedicineBtn:hover,
        html:not(.dark) #toggleUsersBtn:hover {
            background-color: #f8fafc !important;
            border-color: #cbd5e1 !important;
            color: #0f172a !important;
        }
        /* Dark mode: slate pill, white label */
        html.dark #togglePatientsBtn,
        html.dark #togglePatientReportBtn,
        html.dark #toggleDiagnosisRowsBtn,
        html.dark #toggleMedicineBtn,
        html.dark #toggleUsersBtn {
            background-color: #1e293b !important;
            border: 1px solid rgba(125, 211, 252, 0.22) !important;
            color: #ffffff !important;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.06) !important;
        }
        html.dark #togglePatientsBtn:hover,
        html.dark #togglePatientReportBtn:hover,
        html.dark #toggleDiagnosisRowsBtn:hover,
        html.dark #toggleMedicineBtn:hover,
        html.dark #toggleUsersBtn:hover {
            background-color: #334155 !important;
            border-color: rgba(148, 163, 184, 0.55) !important;
            color: #ffffff !important;
        }
        /* Select2 light theme — white pill, subtle gray border (matches Hide/Show toggles) */
        .select2-container--default .select2-selection--single {
            background: #ffffff !important;
            border: 1px solid #d1d5db !important;
            border-radius: 9999px !important;
            height: 42px !important;
            display: flex !important;
            align-items: center !important;
            transition: background 0.15s, border-color 0.15s;
        }
        .select2-container--default.select2-container--open .select2-selection--single {
            background: #f8fafc !important;
            border-color: #cbd5e1 !important;
        }
        .select2-container--default .select2-selection__rendered {
            color: #0f172a !important;
            font-weight: 700 !important;
            font-size: 0.875rem !important;
            padding-left: 16px !important;
        }
        .select2-container--default .select2-selection__placeholder {
            color: #64748b !important;
        }
        .select2-container--default .select2-selection__arrow {
            height: 42px !important;
        }
        .select2-dropdown {
            background-color: #ffffff !important;
            border: 1px solid #d1d5db !important;
            border-radius: 12px !important;
        }
        .select2-results__option {
            padding: 8px 12px !important;
            font-weight: 600 !important;
            color: #0f172a !important;
        }
        .select2-results__option--highlighted {
            background-color: #f1f5f9 !important;
            color: #0f172a !important;
        }
        .select2-results__option--selected {
            background-color: #e2e8f0 !important;
            color: #0f172a !important;
        }
        .select2-results__option[aria-disabled="true"] {
            display: none !important;
        }

        /* Global table theme (system green palette) */
        main table {
            border-collapse: separate;
            border-spacing: 0;
        }
        main table thead th {
            background: linear-gradient(180deg, #ecfdf5 0%, #d1fae5 100%);
            color: #1a1a1a !important;
            border-bottom: 1px solid #a7f3d0;
            font-weight: 900 !important;
            font-size: 0.72rem !important;
            letter-spacing: 0.08em !important;
            text-transform: uppercase;
        }
        main table tbody td {
            border-bottom: 1px solid #d1fae5;
            color: #1a1a1a;
            font-weight: 600;
        }
        main table tbody tr:nth-child(even) {
            background: #f9fefb;
        }
        main table tbody tr:nth-child(odd) {
            background: #F8FAFC;
        }
        main table tbody tr:hover {
            background: #f0fdf4 !important;
        }

        /* Light mode: cool off-white (#F8FAFC) instead of pure white (panels, cards, utilities) */
        html:not(.dark) .bg-white {
            background-color: #F8FAFC !important;
        }

        /* Light mode: remove muddy gray — cooler borders & neutrals on off-white shell */
        html:not(.dark) .border-gray-100,
        html:not(.dark) .border-gray-200,
        html:not(.dark) .border-slate-100,
        html:not(.dark) .border-slate-200 {
            border-color: #e2eaf0 !important;
        }
        html:not(.dark) .divide-gray-100 > :not([hidden]) ~ :not([hidden]),
        html:not(.dark) .divide-slate-100 > :not([hidden]) ~ :not([hidden]),
        html:not(.dark) .divide-gray-200 > :not([hidden]) ~ :not([hidden]),
        html:not(.dark) .divide-slate-200 > :not([hidden]) ~ :not([hidden]) {
            border-color: #e8eef4 !important;
        }
        html:not(.dark) .border-dashed.border-gray-200,
        html:not(.dark) .border-dashed.border-slate-200,
        html:not(.dark) .border-dashed.border-gray-300,
        html:not(.dark) .border-dashed.border-slate-300 {
            border-color: #c5e8d8 !important;
        }
        html:not(.dark) .bg-gray-50,
        html:not(.dark) .bg-slate-50 {
            background-color: #f0f7f4 !important;
        }

        /* ===== Dark mode overrides ===== */
        .dark body,
        .dark main {
            background-color: #0f172a !important;
        }

        /* Main wrapper & cards */
        .dark .bg-slate-50,
        .dark .bg-gray-50 {
            background-color: #1e293b !important;
        }
        .dark .bg-white {
            background-color: #1e293b !important;
        }
        .dark .border-slate-200,
        .dark .border-slate-100,
        .dark .border-gray-200,
        .dark .border-gray-100 {
            border-color: #334155 !important;
        }
        .dark .divide-slate-100 > :not([hidden]) ~ :not([hidden]),
        .dark .divide-gray-100 > :not([hidden]) ~ :not([hidden]) {
            border-color: #334155 !important;
        }

        /* Text colors */
        .dark .text-slate-800,
        .dark .text-gray-800 {
            color: #f1f5f9 !important;
        }
        .dark .text-slate-700,
        .dark .text-gray-700 {
            color: #e2e8f0 !important;
        }
        .dark .text-slate-600,
        .dark .text-gray-600 {
            color: #cbd5e1 !important;
        }
        .dark .text-slate-500,
        .dark .text-gray-500 {
            color: #94a3b8 !important;
        }
        .dark .text-slate-400,
        .dark .text-gray-400 {
            color: #64748b !important;
        }
        /* —— App sidebar: light = mint + forest green; dark = charcoal + white + mint icons (design refs) —— */
        .app-sidebar {
            /* Light sidebar base: Tailwind emerald-50 #ecfdf5 (green-50 is #f0fdf4) */
            color: #166534;
            box-shadow: 4px 0 24px rgba(15, 23, 42, 0.06);
        }
        html.dark .app-sidebar {
            background-color: #111827 !important;
            color: #f8fafc;
            box-shadow: 4px 0 28px rgba(0, 0, 0, 0.45);
        }
        .app-sidebar-head {
            border-bottom: 1px solid rgba(22, 101, 52, 0.18);
            color: #14532d;
        }
        html.dark .app-sidebar-head {
            border-bottom-color: #1f2937;
            color: #ffffff;
        }
        .app-sidebar-nav-link {
            display: flex;
            align-items: center;
            width: 100%;
            padding: 0.75rem 1rem;
            border-radius: 0.75rem;
            transition: background-color 0.15s ease, color 0.15s ease, border-color 0.15s ease;
            border-left: 4px solid transparent;
            color: #166534;
            font-weight: 500;
        }
        .app-sidebar-nav-link--sub {
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
            font-size: 0.875rem;
            border-radius: 0.625rem;
        }
        html.dark .app-sidebar-nav-link {
            color: #ffffff !important;
        }
        .app-sidebar-nav-link:hover {
            background-color: rgba(220, 252, 231, 0.65);
            color: #14532d;
        }
        html.dark .app-sidebar-nav-link:hover {
            background-color: rgba(255, 255, 255, 0.06);
            color: #ffffff !important;
        }
        .app-sidebar-nav-link.is-active {
            background-color: #dcfce7;
            border-left-color: #22c55e;
            color: #14532d;
            font-weight: 600;
        }
        /*
         * Dark mode active pill: emerald-50 (#ecfdf5) reads as “white” on #111827.
         * Use solid green-100 (#dcfce7) — same mint as light sidebar active — so it visibly reads as green.
         */
        html.dark .app-sidebar-nav-link.is-active {
            background-color: #dcfce7 !important;
            background-image: none !important;
            border-left-color: #16a34a;
            color: #14532d !important;
        }
        html.dark .app-sidebar-nav-link:not(.is-active) svg {
            color: #4ade80;
        }
        html.dark .app-sidebar-nav-link.is-active svg {
            color: #166534 !important;
        }
        /* .dark span sets #e2e8f0 globally — breaks “Medicine queue” on mint active chip; inherit link color */
        html.dark .app-sidebar .app-sidebar-nav-link span:not(.text-white) {
            color: inherit !important;
        }
        .app-sidebar-nav-link svg {
            stroke: currentColor;
        }
        .dark .text-slate-900,
        .dark .text-gray-900 {
            color: #f8fafc !important;
        }

        /* Headings */
        .dark h1, .dark h2, .dark h3, .dark h4 {
            color: #f1f5f9 !important;
        }

        /* Stat card backgrounds & icons */
        .dark .bg-indigo-50 { background-color: #312e81 !important; }
        .dark .text-indigo-500 { color: #818cf8 !important; }
        .dark .bg-blue-50 { background-color: #1e3a5f !important; }
        .dark .text-blue-500,
        .dark .text-blue-600 { color: #60a5fa !important; }
        .dark .bg-rose-50 { background-color: #4c1d1d !important; }

        /* Notification bell (orange accent like screenshot) */
        .dark .ring-blue-100 { --tw-ring-color: #f59e0b !important; }
        .dark .bg-blue-50.text-blue-600 {
            background-color: #78350f !important;
            color: #fbbf24 !important;
        }

        /* Recent activity hover */
        .dark .hover\:bg-slate-50:hover,
        .dark .hover\:bg-gray-50:hover {
            background-color: #334155 !important;
        }

        /* Avatar circles */
        .dark .bg-slate-200 {
            background-color: #334155 !important;
        }
        .dark .text-slate-600.shrink-0,
        .dark .bg-slate-200 .text-slate-600 {
            color: #94a3b8 !important;
        }

        /* Chart container */
        .dark .border-slate-100.bg-white,
        .dark .rounded-xl.border.border-slate-100.bg-white {
            background-color: #1e293b !important;
            border-color: #334155 !important;
        }
        .dark .border-dashed.border-slate-200 {
            border-color: #475569 !important;
        }

        /* Table dark mode */
        .dark main table thead th {
            background: linear-gradient(180deg, #1e293b 0%, #1e293b 100%) !important;
            color: #94a3b8 !important;
            border-bottom: 1px solid #334155 !important;
        }
        .dark main table tbody td {
            border-bottom: 1px solid #1e293b !important;
            color: #e2e8f0 !important;
        }
        .dark main table tbody tr:nth-child(even) {
            background: #162032 !important;
        }
        .dark main table tbody tr:nth-child(odd) {
            background: #0f172a !important;
        }
        .dark main table tbody tr:hover {
            background: #1e293b !important;
        }

        /* Form inputs */
        .dark input,
        .dark textarea,
        .dark select {
            background-color: #1e293b !important;
            border-color: #334155 !important;
            color: #e2e8f0 !important;
        }
        .dark input::placeholder,
        .dark textarea::placeholder {
            color: #ffffff !important;
            opacity: 1;
        }
        .dark .select2-container--default .select2-selection__placeholder {
            color: #ffffff !important;
        }
        .dark input:focus,
        .dark textarea:focus,
        .dark select:focus {
            border-color: #22c55e !important;
            box-shadow: 0 0 0 2px rgba(34,197,94,0.15) !important;
        }

        /* Labels */
        .dark label {
            color: #94a3b8 !important;
        }

        /* Pagination dark */
        .dark .clinic-os-pagination .paginationjs-pages li > a {
            color: #94a3b8 !important;
        }
        .dark .clinic-os-pagination .paginationjs-pages li > a:hover {
            background: #334155 !important;
            color: #22c55e !important;
        }
        .dark .clinic-os-pagination .paginationjs-nav {
            color: #22c55e !important;
        }

        /* Select2 dark */
        .dark .select2-container--default .select2-selection--single {
            background: #1e293b !important;
            border: 1px solid #334155 !important;
        }
        .dark .select2-container--default.select2-container--open .select2-selection--single {
            background: #1e293b !important;
            border-color: #475569 !important;
        }
        .dark .select2-container--default .select2-selection__rendered {
            color: #e2e8f0 !important;
        }
        .dark .select2-dropdown {
            background: #1e293b !important;
            border: 1px solid #334155 !important;
        }
        .dark .select2-results__option {
            color: #e2e8f0 !important;
        }
        .dark .select2-results__option--highlighted {
            background-color: #334155 !important;
            color: #22c55e !important;
        }
        .dark .select2-results__option--selected {
            background-color: #0f172a !important;
            color: #e2e8f0 !important;
        }

        /* Rounded panels / card wrappers */
        .dark .rounded-2xl.border.border-slate-200.bg-slate-50,
        .dark .rounded-2xl.border.border-slate-200.bg-white {
            background-color: #1e293b !important;
            border-color: #334155 !important;
        }
        .dark .rounded-xl.border.border-slate-200.bg-white {
            background-color: #0f172a !important;
            border-color: #334155 !important;
        }

        /* Success alert */
        .dark .bg-green-100 {
            background-color: #14532d !important;
            color: #86efac !important;
        }
        .dark .border-green-500 {
            border-color: #22c55e !important;
        }

        /* Emerald text in stat cards */
        .dark .text-emerald-600 {
            color: #34d399 !important;
        }

        /* Amber / warning panels (e.g. Medicines Expiring Soon) */
        .dark .bg-amber-50 {
            background-color: #1c1917 !important;
        }
        .dark .border-amber-200,
        .dark .border-amber-100 {
            border-color: #44403c !important;
        }
        .dark .text-amber-800 {
            color: #fbbf24 !important;
        }
        .dark .text-amber-700 {
            color: #f59e0b !important;
        }
        .dark .bg-amber-100 {
            background-color: #44403c !important;
        }
        .dark .bg-white.border.border-amber-100 {
            background-color: #292524 !important;
            border-color: #44403c !important;
        }

        /* Scrollbar */
        .dark ::-webkit-scrollbar-track { background: #1e293b; }
        .dark ::-webkit-scrollbar-thumb { background: #475569; }
        .dark ::-webkit-scrollbar-thumb:hover { background: #64748b; }

        /* General text & paragraph readability */
        .dark p,
        .dark span,
        .dark li,
        .dark td,
        .dark th,
        .dark dd,
        .dark dt,
        .dark small {
            color: #e2e8f0;
        }
        /* Exclude sidebar nav: would otherwise inherit light-blue link color (#93c5fd) over forest/white tones */
        .dark a:not([class*="bg-"]):not([class*="text-green"]):not([class*="text-rose"]):not([class*="text-red"]):not([class*="text-amber"]):not([class*="text-blue"]):not([class*="text-emerald"]):not(.app-sidebar-nav-link) {
            color: #93c5fd;
        }
        .dark .text-black {
            color: #f1f5f9 !important;
        }
        .dark .text-gray-900,
        .dark .text-slate-900 {
            color: #f8fafc !important;
        }

        /* Explicitly dark bg-white panels/modals */
        .dark .bg-white {
            background-color: #1e293b !important;
        }
        .dark .bg-gray-50,
        .dark .bg-slate-50,
        .dark .bg-gray-50\/30 {
            background-color: #0f172a !important;
        }
        .dark .bg-gray-100,
        .dark .bg-slate-100 {
            background-color: #1e293b !important;
        }

        /* Hover states for list items */
        .dark .hover\:bg-slate-50:hover,
        .dark .hover\:bg-gray-50:hover {
            background-color: #334155 !important;
        }

        /* Borders */
        .dark .border-gray-50,
        .dark .border-slate-50 {
            border-color: #1e293b !important;
        }

        /* Rose/red panels & text */
        .dark .bg-rose-50 { background-color: #3f1219 !important; }
        .dark .text-rose-500 { color: #fb7185 !important; }
        .dark .text-rose-600 { color: #fb7185 !important; }
        .dark .bg-red-50 { background-color: #3b1114 !important; }
        .dark .border-red-200 { border-color: #7f1d1d !important; }
        .dark .text-red-700 { color: #fca5a5 !important; }
        .dark .text-red-500 { color: #f87171 !important; }
        /* Sidebar keeps html.dark .app-sidebar bg; avoid remapping emerald-50 on aside.app-sidebar */
        .dark .bg-emerald-50:not(.app-sidebar) {
            background-color: #064e3b !important;
        }

        /* Modals */
        .dark .bg-white.rounded-3xl,
        .dark .bg-white.rounded-\\[2\\.5rem\\] {
            background-color: #1e293b !important;
        }
        .dark .border-gray-100 {
            border-color: #334155 !important;
        }
        .dark .text-gray-300 {
            color: #94a3b8 !important;
        }
        .dark .text-gray-400 {
            color: #64748b !important;
        }

        /* Green button/badge tones */
        .dark .bg-green-50 { background-color: #064e3b !important; }
        .dark .bg-green-100 { background-color: #14532d !important; }

        /* BHW report info strip: bg-green-50 + text-green-900 becomes unreadable in dark mode */
        .dark .border-green-200.bg-green-50.text-green-900 {
            background-color: #14532d !important;
            border-color: #4ade80 !important;
            color: #ecfdf5 !important;
        }
        .dark .border-green-200.bg-green-50.text-green-900 strong {
            color: #ffffff !important;
        }

        /* Smooth transition for all elements */
        .dark * {
            transition: background-color 0.15s ease, border-color 0.15s ease, color 0.15s ease;
        }
    </style>
</head>
<body class="bg-[#F8FAFC] dark:bg-gray-900 antialiased transition-colors duration-300">
    <div class="flex h-screen overflow-hidden">
        
        <aside class="app-sidebar bg-emerald-50 w-64 flex flex-col z-10">
            <div class="app-sidebar-head p-6 text-xl font-bold flex items-center gap-2">
                @php
                    $clinicName = config('clinic.name');
                    $clinicLogoPath = config('clinic.logo_path');
                    $clinicLogoUrl = $clinicLogoPath ? asset('storage/' . ltrim($clinicLogoPath, '/')) : null;
                @endphp
                @if($clinicLogoUrl)
                    <img src="{{ $clinicLogoUrl }}" alt="Clinic logo" class="w-7 h-7 rounded-md object-cover border border-emerald-300 dark:border-gray-600">
                @else
                    <img src="{{ asset('images/login-clinic-logo.png') }}" alt="Clinic logo" class="w-10 h-10 rounded-md object-cover border border-transparent dark:border-gray-600">
                @endif
                <span class="leading-tight">{{ $clinicName }}</span>
            </div>
            
            @php
                $role = auth()->check() ? strtolower(trim((string) (auth()->user()->role ?? 'bhw'))) : 'guest';
                $isDoctor = $role === 'doctor';
                $isNurse = $role === 'nurse';
                $isBhw = $role === 'bhw';
                $isAdmin = $role === 'admin';
                $authUser = auth()->user();
                $displayName = $authUser?->full_name ?: trim(implode(' ', array_filter([$authUser?->first_name, $authUser?->last_name]))) ?: 'Clinic User';
                $roleLabel = $authUser ? strtoupper((string) $authUser->role) : 'GUEST';
                $initials = strtoupper(substr((string) ($authUser?->first_name ?? 'C'), 0, 1) . substr((string) ($authUser?->last_name ?? 'U'), 0, 1));
                $profilePhotoUrl = (!empty($authUser?->profile_photo_path))
                    ? asset('storage/'.$authUser->profile_photo_path)
                    : null;
            @endphp

            <nav class="mt-6 flex-1 px-4 space-y-1">
                <a href="{{ $isAdmin ? route('admin.dashboard') : ($isNurse ? route('nurse.dashboard') : ($role === 'doctor' ? route('doctor.dashboard') : route('bhw.dashboard'))) }}"
                   class="app-sidebar-nav-link {{ request()->routeIs('dashboard') || request()->routeIs('bhw.dashboard') || request()->routeIs('nurse.dashboard') || request()->routeIs('doctor.dashboard') || request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">
                    <span class="mr-2 inline-flex align-middle">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10.5L12 3l9 7.5M5.25 9.75V20a1 1 0 001 1h4.5v-5.25a1 1 0 011-1h.5a1 1 0 011 1V21h4.5a1 1 0 001-1V9.75"/>
                        </svg>
                    </span> Dashboard
                </a>
                
                {{-- Clinic Records (Using wildcard * to stay active when viewing specific records) --}}
                <a href="{{ $role === 'doctor' ? route('doctor.record.index') : ($isNurse ? route('nurse.record.index') : ($isBhw ? route('bhw.record.index') : route('record.index'))) }}" 
                   class="app-sidebar-nav-link {{ ($role === 'doctor' && request()->routeIs('doctor.record.*') && !request()->routeIs('doctor.record.create')) || ($isNurse && request()->routeIs('nurse.record.*') && !request()->routeIs('nurse.record.create')) || ($isBhw && request()->routeIs('bhw.record.*') && !request()->routeIs('bhw.record.create')) || (!$isDoctor && !$isNurse && !$isBhw && request()->routeIs('record.*') && !request()->routeIs('record.create')) ? 'is-active' : '' }}">
                    <span class="mr-2 inline-flex align-middle">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </span> Patient Records
                </a>

                @if($isBhw)
                    @php
                        $medicineQueueCount = \App\Models\ClinicRecord::awaitingMedicineDispensing()->count();
                    @endphp
                    <a href="{{ route('bhw.dispensing.index') }}"
                       class="app-sidebar-nav-link flex items-center justify-between gap-2 {{ request()->routeIs('bhw.dispensing.*') ? 'is-active' : '' }}">
                        <span class="flex items-center min-w-0">
                            <span class="mr-2 inline-flex align-middle shrink-0 relative">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                                @if($medicineQueueCount > 0)
                                    <span class="absolute -top-1.5 -right-2 min-w-[1rem] h-4 px-0.5 rounded-full bg-rose-500 text-white text-[9px] font-black leading-4 text-center">{{ $medicineQueueCount > 99 ? '99+' : $medicineQueueCount }}</span>
                                @endif
                            </span>
                            <span class="truncate">Medicine queue</span>
                        </span>
                    </a>
                @endif

                @unless($isBhw)
                    <a href="{{ route('medicines.index') }}"
                       class="app-sidebar-nav-link {{ request()->routeIs('medicines.*') ? 'is-active' : '' }}">
                        <span class="mr-2 inline-flex align-middle">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 8.5l7 7m-9.5 1a3.5 3.5 0 010-5l5.5-5.5a3.5 3.5 0 115 5L11 16.5a3.5 3.5 0 01-5 0z"/>
                            </svg>
                        </span> Medicine Inventory
                    </a>
                @endunless

                @if($isDoctor || $isNurse)
                    <a href="{{ $isDoctor ? route('doctor.pending.index') : route('nurse.pending.index') }}"
                       class="app-sidebar-nav-link {{ request()->routeIs('doctor.pending.*') || request()->routeIs('nurse.pending.*') ? 'is-active' : '' }}">
                        <span class="mr-2 inline-flex align-middle">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5h6m-6 14h6M9 5a1 1 0 000 2h.5a1 1 0 011 1v1.5a3.5 3.5 0 01-1.025 2.475L8.8 13.15a2 2 0 000 2.828l1.175 1.175A3.5 3.5 0 0111 19.628V20a1 1 0 01-1 1H9m6-16a1 1 0 010 2h-.5a1 1 0 00-1 1v1.5a3.5 3.5 0 001.025 2.475L15.2 13.15a2 2 0 010 2.828l-1.175 1.175A3.5 3.5 0 0013 19.628V20a1 1 0 001 1h1"/>
                            </svg>
                        </span> Pending Patient
                    </a>
                @endif

                @if($isAdmin)
                    <a href="{{ route('admin.users.index') }}" class="app-sidebar-nav-link {{ request()->routeIs('admin.users.*') ? 'is-active' : '' }}"><span class="mr-2 inline-flex align-middle"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2a3 3 0 00-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg></span> User Management</a>
                    <a href="{{ route('admin.reports.index') }}" class="app-sidebar-nav-link {{ request()->routeIs('admin.reports.*') ? 'is-active' : '' }}"><span class="mr-2 inline-flex align-middle"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6m4 6V7m4 10v-3M5 20h14a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v14a1 1 0 001 1z"/></svg></span> Reports</a>
                    <a href="{{ route('admin.activity-logs.index') }}" class="app-sidebar-nav-link {{ request()->routeIs('admin.activity-logs.*') ? 'is-active' : '' }}"><span class="mr-2 inline-flex align-middle"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></span> Activity Logs</a>
                    <a href="{{ route('admin.inventory.ledger') }}" class="app-sidebar-nav-link {{ request()->routeIs('admin.inventory.*') ? 'is-active' : '' }}"><span class="mr-2 inline-flex align-middle"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0v10l-8 4m8-14l-8 4m-8-4v10l8 4m-8-14l8 4m0 0v10"/></svg></span> Inventory Ledger</a>
                @endif

                @unless($isDoctor || $isAdmin || $isNurse)
                    {{-- Reports Dropdown --}}
                    @if($isBhw)
                    <div class="pt-1">
                        <button type="button"
                            onclick="toggleReportsMenu()"
                            class="app-sidebar-nav-link w-full flex justify-between items-center">
                            <span>Reports</span>
                            <svg id="reports-arrow" class="w-4 h-4 transition-transform {{ request()->routeIs('reports.*') || request()->routeIs('bhw.reports.*') || request()->routeIs('bhw.medicines.*') ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div id="reports-menu" class="ml-4 mt-1 space-y-1 {{ request()->routeIs('reports.*') || request()->routeIs('bhw.reports.*') || request()->routeIs('bhw.medicines.*') ? '' : 'hidden' }}">
                            <a href="{{ route('bhw.reports.diagnosis') }}"
                               class="app-sidebar-nav-link app-sidebar-nav-link--sub {{ request()->routeIs('reports.diagnosis') || request()->routeIs('bhw.reports.diagnosis') ? 'is-active' : '' }}">
                                <span class="mr-2 inline-flex align-middle"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6m4 6V7m4 10v-3M5 20h14a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v14a1 1 0 001 1z"/></svg></span>Diagnosis Reports
                            </a>
                            <a href="{{ route('bhw.reports.patient_records') }}"
                               class="app-sidebar-nav-link app-sidebar-nav-link--sub {{ request()->routeIs('reports.patient_records') || request()->routeIs('bhw.reports.patient_records') ? 'is-active' : '' }}">
                                <span class="mr-2 inline-flex align-middle"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></span>Patient Records Report
                            </a>
                            <a href="{{ route('bhw.medicines.index') }}"
                               class="app-sidebar-nav-link app-sidebar-nav-link--sub {{ request()->routeIs('bhw.medicines.*') ? 'is-active' : '' }}">
                                <span class="mr-2 inline-flex align-middle"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.5 8.5l7 7m-9.5 1a3.5 3.5 0 010-5l5.5-5.5a3.5 3.5 0 115 5L11 16.5a3.5 3.5 0 01-5 0z"/></svg></span>Medicine Inventory
                            </a>
                        </div>
                    </div>
                    @endif

                    <div class="pt-4 mt-4 border-t border-emerald-200/70 dark:border-gray-700">
                        <a href="{{ route('bhw.record.create') }}" 
                           class="app-sidebar-nav-link {{ request()->routeIs('record.create') || request()->routeIs('bhw.record.create') ? 'is-active' : '' }}">
                            <span class="mr-2 inline-flex align-middle text-emerald-600 dark:text-green-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                            </span> Add New Consultation
                        </a>
                    </div>
                @endunless

            </nav>

            <div class="app-sidebar-footer p-4 border-t border-emerald-200/70 dark:border-gray-700 relative" x-data="{ openUserMenu: false }">
                <div class="flex items-center gap-3">
                    @if($profilePhotoUrl)
                        <img src="{{ $profilePhotoUrl }}" alt="Profile photo" class="w-9 h-9 rounded-full object-cover border border-emerald-300 dark:border-gray-600">
                    @else
                        <div class="w-9 h-9 rounded-full bg-emerald-200 text-green-900 dark:bg-gray-700 dark:text-emerald-200 flex items-center justify-center text-xs font-black">
                            {{ $initials }}
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-green-900 dark:text-white truncate">{{ $displayName }}</p>
                        <p class="text-xs text-green-800/70 dark:text-slate-400 truncate">{{ $roleLabel }}</p>
                    </div>
                    <button type="button"
                        class="w-8 h-8 rounded-lg text-green-800/80 hover:text-green-950 hover:bg-emerald-100/90 dark:text-slate-400 dark:hover:text-white dark:hover:bg-white/10 transition flex items-center justify-center"
                        @click="openUserMenu = !openUserMenu"
                        aria-label="Open user menu">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 6a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm0 5.5A1.5 1.5 0 1010 8a1.5 1.5 0 000 3.5zM11.5 15a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
                        </svg>
                    </button>
                </div>

                <div x-show="openUserMenu"
                    @click.away="openUserMenu = false"
                    x-transition
                    style="display:none;"
                    class="absolute left-4 right-4 bottom-16 bg-white border border-emerald-100 rounded-xl shadow-2xl overflow-hidden z-50 dark:bg-gray-800 dark:border-gray-600">
                    <a href="{{ route('profile.show') }}" class="w-full text-left py-3 px-4 text-green-900 hover:bg-emerald-50 flex items-center gap-2 transition border-b border-emerald-100 dark:text-slate-200 dark:hover:bg-gray-700 dark:border-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A9 9 0 1118.88 17.8M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        View Profile
                    </a>
                    <a href="{{ route('logout.get') }}" class="w-full text-left py-3 px-4 text-green-900 hover:bg-emerald-50 flex items-center gap-2 transition dark:text-slate-200 dark:hover:bg-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                        Log Out
                    </a>
                </div>
            </div>
        </aside>

        <main class="flex-1 p-10 overflow-y-auto relative bg-[#F8FAFC] dark:bg-gray-900 transition-colors duration-300">
            {{-- Dark mode toggle: dark pill + outline sun (dark UI); off-white pill + outline moon (light UI) --}}
            <button
                type="button"
                @click.prevent="scheduleDarkToggle()"
                :disabled="themeTogglePending"
                class="fixed top-5 right-6 z-50 w-11 h-11 rounded-full flex items-center justify-center border transition-all duration-300 disabled:opacity-60 disabled:cursor-wait"
                :class="darkMode
                    ? 'bg-[#0f172a] border-slate-600/70 text-slate-200 shadow-[inset_0_1px_0_rgba(255,255,255,0.05),0_1px_2px_rgba(0,0,0,0.35)] hover:bg-slate-900'
                    : 'bg-[#F8FAFC] border-slate-300/60 text-slate-700 shadow-sm hover:border-slate-300 hover:bg-slate-100/70'"
                :aria-busy="themeTogglePending"
                aria-label="Toggle dark mode"
                :title="themeTogglePending ? 'Applying theme…' : 'Toggle dark mode'">
                {{-- Outline sun (shown in dark mode → click to use light) --}}
                <svg x-show="darkMode" x-transition class="w-[1.15rem] h-[1.15rem] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                {{-- Outline moon (shown in light mode → click to use dark) --}}
                <svg x-show="!darkMode" x-transition class="w-[1.15rem] h-[1.15rem] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" />
                </svg>
            </button>
            @if(session('success'))
                <div id="alert-msg" class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 shadow-sm rounded-r-lg flex justify-between items-center animate-fade-in-down">
                    <span>{{ session('success') }}</span>
                    <button onclick="document.getElementById('alert-msg').remove()" class="text-green-900 font-bold">&times;</button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        function toggleReportsMenu() {
            const menu = document.getElementById('reports-menu');
            const arrow = document.getElementById('reports-arrow');
            if (!menu || !arrow) return;

            menu.classList.toggle('hidden');
            arrow.classList.toggle('rotate-180');
        }

        setTimeout(() => {
            const alert = document.getElementById('alert-msg');
            if (alert) {
                alert.style.opacity = '0';
                alert.style.transition = 'opacity 0.5s ease';
                setTimeout(() => alert.remove(), 500);
            }
        }, 3000);
    </script>
    <script src="{{ asset('js/pagination.js') }}"></script>
    <script>
        $(function() {
            $('select').not('.no-select2').not('.select2-hidden-accessible').not('.flatpickr-monthDropdown-months').each(function() {
                $(this).select2({
                    minimumResultsForSearch: -1,
                    dropdownCssClass: 'green-select2-dropdown',
                    width: $(this).css('min-width') ? undefined : 'resolve'
                });
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
