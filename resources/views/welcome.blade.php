<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>StageTracker</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link
        href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700|inter:400,500,600|jetbrains-mono:400,500"
        rel="stylesheet" />
    <style>
        /* ===========================================
       1. DESIGN TOKENS
       =========================================== */
        :root {
            --bg: #fafaf9;
            --bg-card: #ffffff;
            --bg-hover: #f5f5f4;
            --bg-input: #ffffff;
            --border: #e7e5e4;
            --border-hover: #d6d3d1;
            --text: #1c1917;
            --text-secondary: #57534e;
            --text-muted: #a8a29e;
            --brand: #e05a38;
            --brand-hover: #cc4424;
            --brand-light: #fef5f2;
            --brand-border: #fdd0c3;
            --success: #16a34a;
            --success-light: #f0fdf4;
            --success-border: #bbf7d0;
            --warning: #d97706;
            --warning-light: #fffbeb;
            --warning-border: #fde68a;
            --danger: #dc2626;
            --danger-hover: #b91c1c;
            --danger-light: #fef2f2;
            --danger-border: #fecaca;
            --info: #2563eb;
            --info-light: #eff6ff;
            --info-border: #bfdbfe;
            --shadow-sm: 0 1px 2px rgba(0, 0, 0, .04);
            --shadow: 0 1px 3px rgba(0, 0, 0, .08), 0 1px 2px rgba(0, 0, 0, .04);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, .08), 0 2px 4px -2px rgba(0, 0, 0, .04);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, .08), 0 4px 6px -4px rgba(0, 0, 0, .04);
            --radius-sm: 6px;
            --radius: 10px;
            --radius-lg: 14px;
            --radius-xl: 20px;
            --font-sans: 'Inter', system-ui, -apple-system, sans-serif;
            --font-heading: 'Space Grotesk', var(--font-sans);
            --font-mono: 'JetBrains Mono', ui-monospace, monospace;
            --topbar-h: 60px;
        }

        /* ===========================================
       2. RESET & BASE
       =========================================== */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
        }

        body {
            font-family: var(--font-sans);
            color: var(--text);
            background: var(--bg);
            min-height: 100vh;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }

        h1,
        h2,
        h3,
        h4 {
            font-family: var(--font-heading);
            line-height: 1.25;
            letter-spacing: -0.02em;
        }

        a {
            color: var(--brand);
            text-decoration: none;
        }

        .hidden {
            display: none !important;
        }

        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            clip: rect(0, 0, 0, 0);
            overflow: hidden;
        }

        /* ===========================================
       3. AUTH SCREEN
       =========================================== */
        .auth-screen {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background:
                radial-gradient(ellipse at 20% 0%, rgba(224, 90, 56, .08) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 100%, rgba(37, 99, 235, .06) 0%, transparent 50%),
                var(--bg);
        }

        .auth-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-lg);
            width: 100%;
            max-width: 420px;
            padding: 36px 32px 32px;
            animation: fadeUp .4s ease-out;
        }

        .auth-logo {
            font-family: var(--font-heading);
            font-size: 1.65rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 4px;
        }

        .auth-logo span {
            color: var(--brand);
        }

        .auth-subtitle {
            text-align: center;
            color: var(--text-muted);
            font-size: .9rem;
            margin-bottom: 24px;
        }

        .auth-tabs {
            display: flex;
            background: var(--bg-hover);
            border-radius: var(--radius);
            padding: 3px;
            margin-bottom: 24px;
        }

        .auth-tab {
            flex: 1;
            padding: 9px 0;
            border: 0;
            border-radius: 8px;
            background: transparent;
            font: 500 .875rem var(--font-sans);
            color: var(--text-secondary);
            cursor: pointer;
            transition: all .2s;
        }

        .auth-tab.active {
            background: var(--bg-card);
            color: var(--text);
            box-shadow: var(--shadow-sm);
        }

        /* ===========================================
       4. DASHBOARD LAYOUT
       =========================================== */
        .topbar {
            position: sticky;
            top: 0;
            z-index: 30;
            height: var(--topbar-h);
            background: rgba(250, 250, 249, .85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            padding: 0 24px;
            gap: 16px;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo {
            font-family: var(--font-heading);
            font-size: 1.15rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .logo span {
            color: var(--brand);
        }

        .topbar-center {
            flex: 1;
            max-width: 440px;
            margin: 0 auto;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-name {
            font-size: .85rem;
            font-weight: 500;
            color: var(--text-secondary);
        }

        .main-content {
            max-width: 960px;
            margin: 0 auto;
            padding: 24px;
        }

        /* ===========================================
       5. BUTTONS
       =========================================== */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font: 500 .875rem var(--font-sans);
            padding: 9px 16px;
            border: 1px solid transparent;
            border-radius: var(--radius);
            cursor: pointer;
            transition: all .15s ease;
            white-space: nowrap;
        }

        .btn:focus-visible {
            outline: 2px solid var(--brand);
            outline-offset: 2px;
        }

        .btn:disabled {
            opacity: .55;
            cursor: not-allowed;
        }

        .btn-primary {
            background: var(--brand);
            color: #fff;
        }

        .btn-primary:hover:not(:disabled) {
            background: var(--brand-hover);
        }

        .btn-ghost {
            background: transparent;
            color: var(--text-secondary);
            border-color: var(--border);
        }

        .btn-ghost:hover:not(:disabled) {
            background: var(--bg-hover);
            border-color: var(--border-hover);
        }

        .btn-danger-fill {
            background: var(--danger);
            color: #fff;
        }

        .btn-danger-fill:hover:not(:disabled) {
            background: var(--danger-hover);
        }

        .btn-danger-ghost {
            background: transparent;
            color: var(--danger);
            border-color: var(--danger-border);
        }

        .btn-danger-ghost:hover:not(:disabled) {
            background: var(--danger-light);
        }

        .btn-sm {
            padding: 6px 10px;
            font-size: .8rem;
            border-radius: var(--radius-sm);
        }

        .btn-full {
            width: 100%;
            justify-content: center;
        }

        .btn-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            padding: 0;
            border: 0;
            border-radius: var(--radius);
            background: transparent;
            color: var(--text-secondary);
            cursor: pointer;
            font-size: 1.15rem;
            transition: all .15s;
        }

        .btn-icon:hover {
            background: var(--bg-hover);
            color: var(--text);
        }

        .mobile-menu-btn {
            display: none;
        }

        /* ===========================================
       6. FORM ELEMENTS
       =========================================== */
        .form-group {
            margin-bottom: 14px;
        }

        .form-group label {
            display: block;
            font-size: .8rem;
            font-weight: 500;
            color: var(--text-secondary);
            margin-bottom: 5px;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 10px 12px;
            font: 400 .9rem var(--font-sans);
            color: var(--text);
            background: var(--bg-input);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            outline: none;
            transition: border-color .15s, box-shadow .15s;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: var(--brand);
            box-shadow: 0 0 0 3px rgba(224, 90, 56, .1);
        }

        input::placeholder {
            color: var(--text-muted);
        }

        textarea {
            resize: vertical;
            min-height: 64px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        /* ===========================================
       7. SEARCH
       =========================================== */
        .search-box {
            position: relative;
        }

        .search-box input {
            padding-left: 36px;
            border-radius: 999px;
            background: var(--bg-hover);
            border-color: transparent;
            font-size: .85rem;
        }

        .search-box input:focus {
            background: var(--bg-card);
            border-color: var(--brand);
        }

        .search-box::before {
            content: '\2315';
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 1rem;
            pointer-events: none;
        }

        /* ===========================================
       8. STATS
       =========================================== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 16px;
            box-shadow: var(--shadow-sm);
            transition: box-shadow .2s;
        }

        .stat-card:hover {
            box-shadow: var(--shadow);
        }

        .stat-label {
            display: block;
            font-size: .75rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: var(--text-muted);
            margin-bottom: 4px;
        }

        .stat-value {
            display: block;
            font-family: var(--font-heading);
            font-size: 1.6rem;
            font-weight: 700;
        }

        .stat-applied .stat-value {
            color: var(--warning);
        }

        .stat-interview .stat-value {
            color: var(--info);
        }

        .stat-offer .stat-value {
            color: var(--success);
        }

        .stat-rejected .stat-value {
            color: var(--danger);
        }

        /* ===========================================
       9. TOOLBAR
       =========================================== */
        .toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 16px;
            flex-wrap: wrap;
        }

        .filter-pills {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .pill {
            padding: 6px 14px;
            border: 1px solid var(--border);
            border-radius: 999px;
            background: var(--bg-card);
            font: 500 .8rem var(--font-sans);
            color: var(--text-secondary);
            cursor: pointer;
            transition: all .15s;
            white-space: nowrap;
        }

        .pill:hover {
            border-color: var(--border-hover);
            background: var(--bg-hover);
        }

        .pill.active {
            background: var(--brand-light);
            border-color: var(--brand-border);
            color: var(--brand);
        }

        .pill .pill-count {
            display: inline-block;
            min-width: 18px;
            padding: 0 5px;
            margin-left: 4px;
            background: var(--bg-hover);
            border-radius: 999px;
            font-family: var(--font-mono);
            font-size: .7rem;
            text-align: center;
        }

        .pill.active .pill-count {
            background: var(--brand-border);
        }

        .toolbar-actions {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .sort-select {
            width: auto;
            min-width: 150px;
            padding: 7px 10px;
            font-size: .8rem;
            border-radius: 999px;
        }

        /* ===========================================
       10. APPLICATION CARDS
       =========================================== */
        .app-list {
            display: grid;
            gap: 10px;
        }

        .app-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-left: 3px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 16px 18px;
            box-shadow: var(--shadow-sm);
            transition: box-shadow .2s, border-color .2s;
            animation: cardEnter .3s ease-out both;
        }

        .app-card:hover {
            box-shadow: var(--shadow-md);
        }

        .app-card[data-status="applied"] {
            border-left-color: var(--warning);
        }

        .app-card[data-status="interview"] {
            border-left-color: var(--info);
        }

        .app-card[data-status="offer"] {
            border-left-color: var(--success);
        }

        .app-card[data-status="rejected"] {
            border-left-color: var(--danger);
        }

        .app-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 6px;
        }

        .app-card-company {
            font-family: var(--font-heading);
            font-size: 1.05rem;
            font-weight: 600;
        }

        .app-card-position {
            font-size: .875rem;
            color: var(--text-secondary);
        }

        .app-card-meta {
            display: flex;
            gap: 14px;
            font-family: var(--font-mono);
            font-size: .75rem;
            color: var(--text-muted);
            margin: 8px 0;
            flex-wrap: wrap;
        }

        .app-card-notes {
            font-size: .85rem;
            color: var(--text-secondary);
            margin-bottom: 10px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .app-card-actions {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        /* ===========================================
       11. BADGES
       =========================================== */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-family: var(--font-mono);
            font-size: .72rem;
            font-weight: 500;
            padding: 4px 10px;
            border-radius: 999px;
            border: 1px solid;
            white-space: nowrap;
        }

        .badge::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }

        .badge-applied {
            color: #92400e;
            background: var(--warning-light);
            border-color: var(--warning-border);
        }

        .badge-applied::before {
            background: var(--warning);
        }

        .badge-interview {
            color: #1e40af;
            background: var(--info-light);
            border-color: var(--info-border);
        }

        .badge-interview::before {
            background: var(--info);
        }

        .badge-offer {
            color: #065f46;
            background: var(--success-light);
            border-color: var(--success-border);
        }

        .badge-offer::before {
            background: var(--success);
        }

        .badge-rejected {
            color: #991b1b;
            background: var(--danger-light);
            border-color: var(--danger-border);
        }

        .badge-rejected::before {
            background: var(--danger);
        }

        /* ===========================================
       12. MODALS
       =========================================== */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .35);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 100;
            opacity: 0;
            visibility: hidden;
            transition: opacity .2s, visibility .2s;
            padding: 16px;
        }

        .modal-overlay.open {
            opacity: 1;
            visibility: visible;
        }

        .modal {
            background: var(--bg-card);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-lg);
            width: 100%;
            max-width: 520px;
            max-height: 90vh;
            overflow-y: auto;
            transform: scale(.96) translateY(8px);
            transition: transform .25s cubic-bezier(.4, 0, .2, 1);
        }

        .modal-overlay.open .modal {
            transform: scale(1) translateY(0);
        }

        .modal-sm {
            max-width: 400px;
        }

        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 24px 0;
        }

        .modal-header h2 {
            font-size: 1.15rem;
        }

        .modal-body {
            padding: 20px 24px;
        }

        .modal-body>p {
            color: var(--text-secondary);
            font-size: .9rem;
            line-height: 1.6;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            padding: 0 24px 20px;
        }

        /* ===========================================
       13. SLIDE PANEL (followups)
       =========================================== */
        .slide-panel {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            width: 420px;
            max-width: 100vw;
            background: var(--bg-card);
            border-left: 1px solid var(--border);
            box-shadow: var(--shadow-lg);
            transform: translateX(100%);
            transition: transform .3s cubic-bezier(.4, 0, .2, 1);
            z-index: 50;
            display: flex;
            flex-direction: column;
        }

        .slide-panel.open {
            transform: translateX(0);
        }

        .slide-panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 20px;
            border-bottom: 1px solid var(--border);
        }

        .slide-panel-header h2 {
            font-size: 1.05rem;
        }

        .slide-panel-body {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
        }

        .panel-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .2);
            z-index: 40;
            opacity: 0;
            visibility: hidden;
            transition: opacity .3s, visibility .3s;
        }

        .panel-overlay.open {
            opacity: 1;
            visibility: visible;
        }

        .followup-list {
            margin-top: 16px;
            display: grid;
            gap: 10px;
        }

        .followup-card {
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 12px;
            background: var(--bg);
            animation: cardEnter .25s ease-out;
        }

        .followup-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 4px;
        }

        .followup-type {
            font-family: var(--font-mono);
            font-size: .8rem;
            font-weight: 500;
            text-transform: capitalize;
        }

        .followup-date {
            font-family: var(--font-mono);
            font-size: .75rem;
            color: var(--text-muted);
        }

        .followup-notes {
            font-size: .85rem;
            color: var(--text-secondary);
            margin: 4px 0 8px;
        }

        /* ===========================================
       14. TOASTS
       =========================================== */
        .toast-container {
            position: fixed;
            top: 16px;
            right: 16px;
            z-index: 200;
            display: flex;
            flex-direction: column;
            gap: 8px;
            pointer-events: none;
        }

        .toast {
            padding: 12px 18px;
            border-radius: var(--radius);
            font: 500 .85rem var(--font-sans);
            box-shadow: var(--shadow-lg);
            transform: translateX(calc(100% + 24px));
            transition: transform .3s cubic-bezier(.4, 0, .2, 1), opacity .2s;
            pointer-events: auto;
            max-width: 380px;
            color: #fff;
        }

        .toast.show {
            transform: translateX(0);
        }

        .toast.hide {
            opacity: 0;
            transform: translateX(calc(100% + 24px));
        }

        .toast-success {
            background: #16a34a;
        }

        .toast-error {
            background: #dc2626;
        }

        .toast-info {
            background: #2563eb;
        }

        /* ===========================================
       15. LOADING & EMPTY STATES
       =========================================== */
        .skeleton-card {
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 18px;
            background: var(--bg-card);
        }

        .skeleton-line {
            height: 12px;
            border-radius: 6px;
            background: linear-gradient(90deg, var(--bg-hover) 30%, var(--border) 50%, var(--bg-hover) 70%);
            background-size: 200% 100%;
            animation: shimmer 1.6s ease infinite;
        }

        .skeleton-line+.skeleton-line {
            margin-top: 10px;
        }

        .skeleton-line-lg {
            width: 60%;
            height: 16px;
        }

        .skeleton-line-md {
            width: 40%;
        }

        .skeleton-line-sm {
            width: 80%;
        }

        .empty-state {
            text-align: center;
            padding: 48px 24px;
            color: var(--text-muted);
        }

        .empty-state-icon {
            font-size: 2.5rem;
            margin-bottom: 12px;
            opacity: .6;
        }

        .empty-state h3 {
            font-size: 1rem;
            color: var(--text-secondary);
            margin-bottom: 6px;
        }

        .empty-state p {
            font-size: .875rem;
            max-width: 280px;
            margin: 0 auto;
        }

        /* ===========================================
       16. PAGINATION
       =========================================== */
        .pagination {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-top: 20px;
            font-size: .85rem;
        }

        .pagination button {
            padding: 6px 12px;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            background: var(--bg-card);
            font: 500 .8rem var(--font-sans);
            cursor: pointer;
            transition: all .15s;
        }

        .pagination button:hover:not(:disabled) {
            background: var(--bg-hover);
        }

        .pagination button:disabled {
            opacity: .4;
            cursor: not-allowed;
        }

        .pagination span {
            color: var(--text-muted);
            font-size: .8rem;
        }

        /* ===========================================
       17. ANIMATIONS
       =========================================== */
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes cardEnter {
            from {
                opacity: 0;
                transform: translateY(6px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes shimmer {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, .3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin .6s linear infinite;
        }

        /* ===========================================
       18. RESPONSIVE
       =========================================== */
        @media (max-width: 768px) {
            .topbar {
                padding: 0 16px;
            }

            .topbar-center {
                display: none;
            }

            .mobile-menu-btn {
                display: flex;
                background: transparent;
                border: 0;
                font-size: 1.3rem;
                cursor: pointer;
                color: var(--text-secondary);
            }

            .mobile-search {
                display: block;
                margin-bottom: 16px;
            }

            .main-content {
                padding: 16px;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 8px;
            }

            .toolbar {
                flex-direction: column;
                align-items: stretch;
            }

            .toolbar-actions {
                justify-content: space-between;
            }

            .filter-pills {
                overflow-x: auto;
                flex-wrap: nowrap;
                padding-bottom: 4px;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .slide-panel {
                width: 100%;
            }

            .user-name {
                display: none;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr 1fr;
            }

            .toolbar-actions {
                flex-wrap: wrap;
            }

            .sort-select {
                min-width: auto;
                flex: 1;
            }

            .app-card {
                padding: 14px;
            }

            .app-card-actions {
                flex-direction: column;
            }

            .app-card-actions .btn {
                width: 100%;
                justify-content: center;
            }
        }

        @media (min-width: 769px) {
            .mobile-search {
                display: none;
            }
        }
    </style>
</head>

<body>

    <!-- ==========================================
     TOAST CONTAINER
     ========================================== -->
    <div id="toastContainer" class="toast-container" aria-live="polite"></div>

    <!-- ==========================================
     AUTH SCREEN
     ========================================== -->
    <div id="authScreen" class="auth-screen">
        <div class="auth-card">
            <h1 class="auth-logo">Stage<span>Tracker</span></h1>
            <p class="auth-subtitle">Track your internship applications</p>

            <div class="auth-tabs" role="tablist">
                <button class="auth-tab active" role="tab" aria-selected="true" data-tab="login">Sign in</button>
                <button class="auth-tab" role="tab" aria-selected="false" data-tab="register">Create account</button>
            </div>

            <form id="loginForm" class="auth-form" autocomplete="on">
                <div class="form-group">
                    <label for="login-email">Email</label>
                    <input type="email" id="login-email" name="email" placeholder="you@example.com" required
                        autocomplete="email">
                </div>
                <div class="form-group">
                    <label for="login-password">Password</label>
                    <input type="password" id="login-password" name="password" placeholder="Your password" required
                        autocomplete="current-password">
                </div>
                <button type="submit" class="btn btn-primary btn-full" id="loginBtn">Sign in</button>
            </form>

            <form id="registerForm" class="auth-form hidden" autocomplete="on">
                <div class="form-group">
                    <label for="reg-name">Name</label>
                    <input type="text" id="reg-name" name="name" placeholder="Your name" required autocomplete="name">
                </div>
                <div class="form-group">
                    <label for="reg-email">Email</label>
                    <input type="email" id="reg-email" name="email" placeholder="you@example.com" required
                        autocomplete="email">
                </div>
                <div class="form-group">
                    <label for="reg-password">Password</label>
                    <input type="password" id="reg-password" name="password" placeholder="Min. 8 characters"
                        minlength="8" required autocomplete="new-password">
                </div>
                <button type="submit" class="btn btn-primary btn-full" id="registerBtn">Create account</button>
            </form>
        </div>
    </div>

    <!-- ==========================================
     DASHBOARD
     ========================================== -->
    <div id="dashboard" class="hidden">
        <header class="topbar">
            <div class="topbar-left">
                <button class="mobile-menu-btn btn-icon" id="mobileSearchToggle" type="button"
                    aria-label="Toggle search">&#8981;</button>
                <h1 class="logo">Stage<span>Tracker</span></h1>
            </div>
            <div class="topbar-center">
                <div class="search-box">
                    <input type="search" id="searchInput" placeholder="Search company, position, location..."
                        aria-label="Search applications">
                </div>
            </div>
            <div class="topbar-right">
                <span class="user-name" id="userName"></span>
                <button class="btn btn-ghost btn-sm" id="logoutBtn" type="button">Sign out</button>
            </div>
        </header>

        <main class="main-content">
            <!-- Mobile search (hidden on desktop) -->
            <div class="mobile-search hidden" id="mobileSearch">
                <div class="search-box">
                    <input type="search" id="searchInputMobile" placeholder="Search applications..."
                        aria-label="Search applications">
                </div>
            </div>

            <!-- Stats -->
            <section class="stats-grid" id="statsGrid" aria-label="Application statistics"></section>

            <!-- Toolbar -->
            <section class="toolbar">
                <div class="filter-pills" id="filterPills" role="tablist" aria-label="Filter by status">
                    <button class="pill active" role="tab" data-status="">All</button>
                    <button class="pill" role="tab" data-status="applied">Applied</button>
                    <button class="pill" role="tab" data-status="interview">Interview</button>
                    <button class="pill" role="tab" data-status="offer">Offer</button>
                    <button class="pill" role="tab" data-status="rejected">Rejected</button>
                </div>
                <div class="toolbar-actions">
                    <select id="sortSelect" class="sort-select" aria-label="Sort applications">
                        <option value="applied_at:desc">Newest first</option>
                        <option value="applied_at:asc">Oldest first</option>
                        <option value="company:asc">Company A–Z</option>
                        <option value="company:desc">Company Z–A</option>
                    </select>
                    <button class="btn btn-ghost btn-sm" id="exportBtn" type="button">&#8615; CSV</button>
                    <button class="btn btn-primary btn-sm" id="newAppBtn" type="button">+ New</button>
                </div>
            </section>

            <!-- Application list -->
            <section class="app-list" id="appList" aria-label="Applications"></section>

            <!-- Pagination -->
            <nav class="pagination hidden" id="pagination" aria-label="Pagination"></nav>
        </main>

        <!-- Followup slide panel -->
        <div class="panel-overlay" id="panelOverlay"></div>
        <aside class="slide-panel" id="followupPanel" aria-label="Followups">
            <div class="slide-panel-header">
                <h2 id="followupTitle">Followups</h2>
                <button class="btn-icon" id="closeFollowups" type="button" aria-label="Close">&times;</button>
            </div>
            <div class="slide-panel-body">
                <form id="followupForm" autocomplete="off">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="fu-type">Type</label>
                            <select id="fu-type" name="type">
                                <option value="email">Email</option>
                                <option value="call">Call</option>
                                <option value="linkedin">LinkedIn</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="fu-date">Date</label>
                            <input type="date" id="fu-date" name="done_at">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="fu-notes">Notes</label>
                        <textarea id="fu-notes" name="notes" rows="2" placeholder="Add details..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-full btn-sm">Add followup</button>
                </form>
                <div id="followupList" class="followup-list"></div>
            </div>
        </aside>
    </div>

    <!-- ==========================================
     CREATE / EDIT APPLICATION MODAL
     ========================================== -->
    <div class="modal-overlay" id="appModal">
        <div class="modal" role="dialog" aria-labelledby="appModalTitle">
            <div class="modal-header">
                <h2 id="appModalTitle">New Application</h2>
                <button class="btn-icon" data-close-modal="appModal" type="button" aria-label="Close">&times;</button>
            </div>
            <form id="appForm" class="modal-body" autocomplete="off">
                <div class="form-group">
                    <label for="app-company">Company *</label>
                    <input id="app-company" name="company" placeholder="e.g. Google" required>
                </div>
                <div class="form-group">
                    <label for="app-position">Position *</label>
                    <input id="app-position" name="position" placeholder="e.g. Frontend Intern" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="app-location">Location</label>
                        <input id="app-location" name="location" placeholder="e.g. Paris">
                    </div>
                    <div class="form-group">
                        <label for="app-status">Status</label>
                        <select id="app-status" name="status">
                            <option value="applied">Applied</option>
                            <option value="interview">Interview</option>
                            <option value="offer">Offer</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="app-date">Applied date</label>
                    <input type="date" id="app-date" name="applied_at">
                </div>
                <div class="form-group">
                    <label for="app-notes">Notes</label>
                    <textarea id="app-notes" name="notes" rows="3" placeholder="Any details..."></textarea>
                </div>
                <input type="hidden" name="_id" value="">
                <div class="modal-footer" style="padding: 0; margin-top: 8px;">
                    <button type="button" class="btn btn-ghost" data-close-modal="appModal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="appFormSubmit">Create</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ==========================================
     CONFIRM DELETE MODAL
     ========================================== -->
    <div class="modal-overlay" id="confirmModal">
        <div class="modal modal-sm" role="alertdialog" aria-labelledby="confirmTitle" aria-describedby="confirmMsg">
            <div class="modal-header">
                <h2 id="confirmTitle">Confirm deletion</h2>
            </div>
            <div class="modal-body">
                <p id="confirmMsg">Are you sure? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-ghost" data-close-modal="confirmModal" type="button">Cancel</button>
                <button class="btn btn-danger-fill" id="confirmBtn" type="button">Delete</button>
            </div>
        </div>
    </div>

    <!-- ==========================================
     JAVASCRIPT
     ========================================== -->
    <script>
        /* ===========================================
           STATE
           =========================================== */
        const App = {
            token: localStorage.getItem('api_token') || '',
            applications: [],
            statusFilter: '',
            searchQuery: '',
            sortKey: 'applied_at',
            sortDir: 'desc',
            selectedAppId: null,
            editingId: null,
            paginationMeta: null,
            confirmCallback: null,
        };

        /* ===========================================
           DOM REFS
           =========================================== */
        const $ = (sel) => document.querySelector(sel);
        const $$ = (sel) => document.querySelectorAll(sel);
        const dom = {
            authScreen: $('#authScreen'),
            dashboard: $('#dashboard'),
            loginForm: $('#loginForm'),
            registerForm: $('#registerForm'),
            statsGrid: $('#statsGrid'),
            appList: $('#appList'),
            searchInput: $('#searchInput'),
            searchInputMobile: $('#searchInputMobile'),
            filterPills: $('#filterPills'),
            sortSelect: $('#sortSelect'),
            followupPanel: $('#followupPanel'),
            followupTitle: $('#followupTitle'),
            followupList: $('#followupList'),
            followupForm: $('#followupForm'),
            panelOverlay: $('#panelOverlay'),
            appModal: $('#appModal'),
            appForm: $('#appForm'),
            appModalTitle: $('#appModalTitle'),
            appFormSubmit: $('#appFormSubmit'),
            confirmModal: $('#confirmModal'),
            confirmMsg: $('#confirmMsg'),
            confirmBtn: $('#confirmBtn'),
            toastContainer: $('#toastContainer'),
            userName: $('#userName'),
            pagination: $('#pagination'),
        };

        /* ===========================================
           API CLIENT
           =========================================== */
        async function api(path, { raw, ...opts } = {}) {
            const headers = {
                'Accept': 'application/json',
                ...(opts.body ? { 'Content-Type': 'application/json' } : {}),
                ...(App.token ? { 'Authorization': `Bearer ${App.token}` } : {}),
            };

            const res = await fetch(`/api${path}`, { ...opts, headers });

            if (res.status === 401 && App.token) {
                App.token = '';
                localStorage.removeItem('api_token');
                showAuthScreen();
                throw new Error('Session expired — please sign in again');
            }

            if (raw) {
                if (!res.ok) throw new Error('Request failed');
                return res;
            }

            const json = await res.json().catch(() => null);
            if (!res.ok) {
                const msg = json?.message
                    || (json?.errors && Object.values(json.errors).flat()[0])
                    || 'Request failed';
                throw new Error(msg);
            }
            return json;
        }

        /* ===========================================
           TOAST NOTIFICATIONS
           =========================================== */
        function toast(message, type = 'success') {
            const el = document.createElement('div');
            el.className = `toast toast-${type}`;
            el.textContent = message;
            el.setAttribute('role', 'status');
            dom.toastContainer.appendChild(el);
            requestAnimationFrame(() => el.classList.add('show'));
            setTimeout(() => {
                el.classList.replace('show', 'hide');
                el.addEventListener('transitionend', () => el.remove(), { once: true });
                setTimeout(() => el.remove(), 400);
            }, 3000);
        }

        /* ===========================================
           MODAL MANAGEMENT
           =========================================== */
        function openModal(id) {
            const overlay = $(`#${id}`);
            overlay.classList.add('open');
            overlay.querySelector('input, select, textarea, button')?.focus();
            document.body.style.overflow = 'hidden';
        }
        function closeModal(id) {
            if (id === 'confirmModal' && App.confirmCallback) {
                App.confirmCallback(false);
                App.confirmCallback = null;
            }
            $(`#${id}`).classList.remove('open');
            if (!$('.modal-overlay.open') && !dom.followupPanel.classList.contains('open')) {
                document.body.style.overflow = '';
            }
        }
        function confirmAction(message) {
            return new Promise((resolve) => {
                dom.confirmMsg.textContent = message;
                App.confirmCallback = resolve;
                openModal('confirmModal');
            });
        }

        /* ===========================================
           BUTTON LOADING STATE
           =========================================== */
        function setLoading(btn, loading) {
            if (!btn) return;
            btn.disabled = loading;
            if (loading) {
                btn.dataset.text = btn.textContent;
                btn.innerHTML = '<span class="spinner"></span>';
            } else {
                btn.textContent = btn.dataset.text || btn.textContent;
            }
        }

        /* ===========================================
           AUTH SCREEN / DASHBOARD SWITCH
           =========================================== */
        function showAuthScreen() {
            dom.authScreen.classList.remove('hidden');
            dom.dashboard.classList.add('hidden');
            App.applications = [];
            App.selectedAppId = null;
        }
        function showDashboard() {
            dom.authScreen.classList.add('hidden');
            dom.dashboard.classList.remove('hidden');
        }

        /* ===========================================
           AUTH HANDLERS
           =========================================== */
        async function handleLogin(e) {
            e.preventDefault();
            const btn = $('#loginBtn');
            const data = Object.fromEntries(new FormData(e.currentTarget));
            setLoading(btn, true);
            try {
                const json = await api('/login', { method: 'POST', body: JSON.stringify(data) });
                App.token = json.token;
                localStorage.setItem('api_token', App.token);
                toast('Welcome back!');
                showDashboard();
                loadApplications();
            } catch (err) {
                toast(err.message, 'error');
            } finally {
                setLoading(btn, false);
            }
        }

        async function handleRegister(e) {
            e.preventDefault();
            const btn = $('#registerBtn');
            const data = Object.fromEntries(new FormData(e.currentTarget));
            setLoading(btn, true);
            try {
                const json = await api('/register', { method: 'POST', body: JSON.stringify(data) });
                App.token = json.token;
                localStorage.setItem('api_token', App.token);
                toast('Account created!');
                showDashboard();
                loadApplications();
            } catch (err) {
                toast(err.message, 'error');
            } finally {
                setLoading(btn, false);
            }
        }

        async function handleLogout() {
            try { if (App.token) await api('/logout', { method: 'POST' }); } catch (_) { }
            App.token = '';
            localStorage.removeItem('api_token');
            closeFollowupPanel();
            showAuthScreen();
            toast('Signed out', 'info');
        }

        /* ===========================================
           STATS
           =========================================== */
        function renderStats(items) {
            const c = { total: items.length, applied: 0, interview: 0, offer: 0, rejected: 0 };
            items.forEach(a => c[a.status]++);
            dom.statsGrid.innerHTML = [
                { key: 'total', label: 'Total', cls: '' },
                { key: 'interview', label: 'Interviews', cls: 'stat-interview' },
                { key: 'offer', label: 'Offers', cls: 'stat-offer' },
                { key: 'rejected', label: 'Rejected', cls: 'stat-rejected' },
            ].map(s => `
        <div class="stat-card ${s.cls}">
            <span class="stat-label">${s.label}</span>
            <span class="stat-value">${c[s.key]}</span>
        </div>
    `).join('');
        }

        /* ===========================================
           APPLICATIONS: LOAD, FILTER, SORT, RENDER
           =========================================== */
        function showLoadingState() {
            dom.appList.innerHTML = Array(3).fill(`
        <div class="skeleton-card">
            <div class="skeleton-line skeleton-line-lg"></div>
            <div class="skeleton-line skeleton-line-md"></div>
            <div class="skeleton-line skeleton-line-sm"></div>
        </div>
    `).join('');
        }

        async function loadApplications() {
            if (!App.token) return;
            showLoadingState();
            try {
                // API is paginated by default; fetch all pages so the UI always shows the full dataset.
                const buildQuery = (page) => {
                    const params = new URLSearchParams({
                        page: String(page),
                        per_page: '100',
                    });
                    if (App.statusFilter) params.set('status', App.statusFilter);
                    return `?${params.toString()}`;
                };

                let page = 1;
                let lastPage = 1;
                const allItems = [];
                let firstMeta = null;

                do {
                    const json = await api(`/applications${buildQuery(page)}`);
                    const items = Array.isArray(json?.data) ? json.data : [];
                    allItems.push(...items);

                    const meta = json?.meta || null;
                    if (page === 1) firstMeta = meta;
                    lastPage = meta?.last_page || 1;
                    page += 1;
                } while (page <= lastPage);

                App.applications = allItems;
                App.paginationMeta = firstMeta;
                renderAll();
            } catch (err) {
                dom.appList.innerHTML = `
            <div class="empty-state">
                <div class="empty-state-icon">&#9888;</div>
                <h3>Failed to load</h3>
                <p>${err.message}</p>
            </div>`;
                toast(err.message, 'error');
            }
        }

        function getFilteredAndSorted() {
            let items = [...App.applications];
            if (App.searchQuery) {
                const q = App.searchQuery.toLowerCase();
                items = items.filter(a =>
                    (a.company || '').toLowerCase().includes(q) ||
                    (a.position || '').toLowerCase().includes(q) ||
                    (a.location || '').toLowerCase().includes(q)
                );
            }
            const { sortKey, sortDir } = App;
            items.sort((a, b) => {
                let va = a[sortKey] || '';
                let vb = b[sortKey] || '';
                if (typeof va === 'string') va = va.toLowerCase();
                if (typeof vb === 'string') vb = vb.toLowerCase();
                if (va < vb) return sortDir === 'asc' ? -1 : 1;
                if (va > vb) return sortDir === 'asc' ? 1 : -1;
                return 0;
            });
            return items;
        }

        function renderAll() {
            renderStats(App.applications);
            updatePillCounts();
            const items = getFilteredAndSorted();
            renderApplications(items);
        }

        function updatePillCounts() {
            const counts = { '': App.applications.length, applied: 0, interview: 0, offer: 0, rejected: 0 };
            App.applications.forEach(a => counts[a.status]++);
            dom.filterPills.querySelectorAll('.pill').forEach(pill => {
                const s = pill.dataset.status;
                const countEl = pill.querySelector('.pill-count');
                if (countEl) countEl.textContent = counts[s] ?? 0;
                else {
                    const span = document.createElement('span');
                    span.className = 'pill-count';
                    span.textContent = counts[s] ?? 0;
                    pill.appendChild(span);
                }
            });
        }

        function renderApplications(items) {
            if (!items.length) {
                const isSearch = App.searchQuery;
                dom.appList.innerHTML = `
            <div class="empty-state">
                <div class="empty-state-icon">${isSearch ? '&#128269;' : '&#128203;'}</div>
                <h3>${isSearch ? 'No results' : 'No applications yet'}</h3>
                <p>${isSearch ? 'Try a different search term.' : 'Click "+ New" to track your first application.'}</p>
            </div>`;
                return;
            }
            dom.appList.innerHTML = items.map((a, i) => `
        <article class="app-card" data-id="${a.id}" data-status="${a.status}" style="animation-delay:${i * 40}ms">
            <div class="app-card-header">
                <div>
                    <div class="app-card-company">${esc(a.company)}</div>
                    <div class="app-card-position">${esc(a.position)}</div>
                </div>
                <span class="badge badge-${a.status}">${a.status}</span>
            </div>
            <div class="app-card-meta">
                ${a.location ? `<span>&#9906; ${esc(a.location)}</span>` : ''}
                ${a.applied_at ? `<span>&#128197; ${a.applied_at}</span>` : ''}
                <span>&#128172; ${a.followups_count ?? 0} followup${(a.followups_count ?? 0) !== 1 ? 's' : ''}</span>
            </div>
            ${a.notes ? `<p class="app-card-notes">${esc(a.notes)}</p>` : ''}
            <div class="app-card-actions">
                <button class="btn btn-sm btn-ghost" data-action="edit" data-id="${a.id}">Edit</button>
                <button class="btn btn-sm btn-ghost" data-action="followups" data-id="${a.id}">Followups</button>
                <button class="btn btn-sm btn-danger-ghost" data-action="delete" data-id="${a.id}">Delete</button>
            </div>
        </article>
    `).join('');
        }

        function esc(str) {
            if (!str) return '';
            const d = document.createElement('div');
            d.textContent = str;
            return d.innerHTML;
        }

        /* ===========================================
           APPLICATION: CREATE / EDIT
           =========================================== */
        function openAppModal(app = null) {
            const form = dom.appForm;
            if (app) {
                dom.appModalTitle.textContent = 'Edit Application';
                dom.appFormSubmit.textContent = 'Save changes';
                form.company.value = app.company || '';
                form.position.value = app.position || '';
                form.location.value = app.location || '';
                form.status.value = app.status || 'applied';
                form.applied_at.value = app.applied_at || '';
                form.notes.value = app.notes || '';
                form._id.value = app.id;
                App.editingId = app.id;
            } else {
                dom.appModalTitle.textContent = 'New Application';
                dom.appFormSubmit.textContent = 'Create';
                form.reset();
                form._id.value = '';
                App.editingId = null;
            }
            openModal('appModal');
        }

        async function handleAppForm(e) {
            e.preventDefault();
            const form = e.currentTarget;
            const btn = dom.appFormSubmit;
            const raw = Object.fromEntries(new FormData(form));
            const id = raw._id;
            delete raw._id;
            setLoading(btn, true);
            try {
                if (id) {
                    await api(`/applications/${id}`, { method: 'PATCH', body: JSON.stringify(raw) });
                    toast('Application updated');
                } else {
                    await api('/applications', { method: 'POST', body: JSON.stringify(raw) });
                    toast('Application created');
                }
                closeModal('appModal');
                await loadApplications();
            } catch (err) {
                toast(err.message, 'error');
            } finally {
                setLoading(btn, false);
            }
        }

        /* ===========================================
           APPLICATION: DELETE
           =========================================== */
        async function deleteApplication(id) {
            const ok = await confirmAction('This application and all its followups will be permanently deleted.');
            if (!ok) return;
            try {
                await api(`/applications/${id}`, { method: 'DELETE' });
                if (App.selectedAppId === id) closeFollowupPanel();
                toast('Application deleted');
                await loadApplications();
            } catch (err) {
                toast(err.message, 'error');
            }
        }

        /* ===========================================
           FOLLOWUPS
           =========================================== */
        function openFollowupPanel(appId) {
            App.selectedAppId = appId;
            const app = App.applications.find(a => a.id === appId);
            dom.followupTitle.textContent = app ? `Followups — ${app.company}` : 'Followups';
            dom.followupPanel.classList.add('open');
            dom.panelOverlay.classList.add('open');
            document.body.style.overflow = 'hidden';
            loadFollowups(appId);
        }

        function closeFollowupPanel() {
            App.selectedAppId = null;
            dom.followupPanel.classList.remove('open');
            dom.panelOverlay.classList.remove('open');
            dom.followupList.innerHTML = '';
            dom.followupForm.reset();
            document.body.style.overflow = '';
        }

        async function loadFollowups(appId) {
            dom.followupList.innerHTML = '<div style="text-align:center;padding:16px;color:var(--text-muted)">Loading...</div>';
            try {
                const json = await api(`/applications/${appId}/followups`);
                const items = json.data || json || [];
                renderFollowups(items);
            } catch (err) {
                dom.followupList.innerHTML = `<p style="color:var(--danger);font-size:.85rem">${err.message}</p>`;
            }
        }

        function renderFollowups(items) {
            if (!items.length) {
                dom.followupList.innerHTML = `
            <div class="empty-state" style="padding:24px 0">
                <div class="empty-state-icon">&#128172;</div>
                <h3>No followups yet</h3>
                <p>Add your first followup above.</p>
            </div>`;
                return;
            }
            dom.followupList.innerHTML = items.map(f => `
        <div class="followup-card" data-id="${f.id}">
            <div class="followup-card-header">
                <span class="followup-type">${esc(f.type)}</span>
                <span class="followup-date">${f.done_at || '—'}</span>
            </div>
            ${f.notes ? `<p class="followup-notes">${esc(f.notes)}</p>` : ''}
            <button class="btn btn-sm btn-danger-ghost" data-action="delete-followup" data-id="${f.id}">Delete</button>
        </div>
    `).join('');
        }

        async function handleFollowupForm(e) {
            e.preventDefault();
            if (!App.selectedAppId) return;
            const form = e.currentTarget;
            const data = Object.fromEntries(new FormData(form));
            const btn = form.querySelector('[type="submit"]');
            setLoading(btn, true);
            try {
                await api(`/applications/${App.selectedAppId}/followups`, {
                    method: 'POST',
                    body: JSON.stringify(data),
                });
                form.reset();
                toast('Followup added');
                await loadFollowups(App.selectedAppId);
                await loadApplications();
            } catch (err) {
                toast(err.message, 'error');
            } finally {
                setLoading(btn, false);
            }
        }

        async function deleteFollowup(id) {
            const ok = await confirmAction('Delete this followup?');
            if (!ok) return;
            try {
                await api(`/followups/${id}`, { method: 'DELETE' });
                toast('Followup deleted');
                if (App.selectedAppId) {
                    await loadFollowups(App.selectedAppId);
                    await loadApplications();
                }
            } catch (err) {
                toast(err.message, 'error');
            }
        }

        /* ===========================================
           CSV EXPORT
           =========================================== */
        async function exportCsv() {
            try {
                const res = await api('/applications/export.csv', { raw: true });
                const blob = await res.blob();
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'applications.csv';
                document.body.appendChild(a);
                a.click();
                a.remove();
                URL.revokeObjectURL(url);
                toast('CSV downloaded');
            } catch (err) {
                toast(err.message, 'error');
            }
        }

        /* ===========================================
           SEARCH & SORT HANDLERS
           =========================================== */
        let searchTimeout;
        function handleSearch(value) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                App.searchQuery = value.trim();
                renderAll();
            }, 200);
        }

        function handleSort() {
            const [key, dir] = dom.sortSelect.value.split(':');
            App.sortKey = key;
            App.sortDir = dir;
            renderAll();
        }

        function handleFilterClick(e) {
            const pill = e.target.closest('.pill');
            if (!pill) return;
            dom.filterPills.querySelectorAll('.pill').forEach(p => p.classList.remove('active'));
            pill.classList.add('active');
            App.statusFilter = pill.dataset.status;
            loadApplications();
        }

        /* ===========================================
           EVENT BINDINGS
           =========================================== */
        // Auth tabs
        $$('.auth-tab').forEach(tab => {
            tab.addEventListener('click', () => {
                $$('.auth-tab').forEach(t => { t.classList.remove('active'); t.setAttribute('aria-selected', 'false'); });
                tab.classList.add('active');
                tab.setAttribute('aria-selected', 'true');
                const isLogin = tab.dataset.tab === 'login';
                dom.loginForm.classList.toggle('hidden', !isLogin);
                dom.registerForm.classList.toggle('hidden', isLogin);
            });
        });

        // Auth forms
        dom.loginForm.addEventListener('submit', handleLogin);
        dom.registerForm.addEventListener('submit', handleRegister);
        $('#logoutBtn').addEventListener('click', handleLogout);

        // App form
        dom.appForm.addEventListener('submit', handleAppForm);
        $('#newAppBtn').addEventListener('click', () => openAppModal());

        // Followup form
        dom.followupForm.addEventListener('submit', handleFollowupForm);
        $('#closeFollowups').addEventListener('click', closeFollowupPanel);
        dom.panelOverlay.addEventListener('click', closeFollowupPanel);

        // Search
        dom.searchInput.addEventListener('input', e => {
            handleSearch(e.target.value);
            dom.searchInputMobile.value = e.target.value;
        });
        dom.searchInputMobile.addEventListener('input', e => {
            handleSearch(e.target.value);
            dom.searchInput.value = e.target.value;
        });

        // Mobile search toggle
        $('#mobileSearchToggle').addEventListener('click', () => {
            const el = $('#mobileSearch');
            el.classList.toggle('hidden');
            if (!el.classList.contains('hidden')) dom.searchInputMobile.focus();
        });

        // Sort
        dom.sortSelect.addEventListener('change', handleSort);

        // Filter pills
        dom.filterPills.addEventListener('click', handleFilterClick);

        // Export
        $('#exportBtn').addEventListener('click', exportCsv);

        // Close modals
        $$('[data-close-modal]').forEach(btn => {
            btn.addEventListener('click', () => closeModal(btn.dataset.closeModal));
        });
        $$('.modal-overlay').forEach(overlay => {
            overlay.addEventListener('click', e => {
                if (e.target === overlay) closeModal(overlay.id);
            });
        });

        // Confirm dialog
        dom.confirmBtn.addEventListener('click', () => {
            if (App.confirmCallback) App.confirmCallback(true);
            App.confirmCallback = null;
            closeModal('confirmModal');
        });

        // Application list delegation
        dom.appList.addEventListener('click', e => {
            const btn = e.target.closest('[data-action]');
            if (!btn) return;
            const id = Number(btn.dataset.id);
            const action = btn.dataset.action;
            if (action === 'edit') {
                const app = App.applications.find(a => a.id === id);
                if (app) openAppModal(app);
            } else if (action === 'followups') {
                openFollowupPanel(id);
            } else if (action === 'delete') {
                deleteApplication(id);
            }
        });

        // Followup list delegation
        dom.followupList.addEventListener('click', e => {
            const btn = e.target.closest('[data-action="delete-followup"]');
            if (btn) deleteFollowup(Number(btn.dataset.id));
        });

        // Keyboard: Escape closes modals/panels
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                if ($('.modal-overlay.open')) {
                    const open = document.querySelector('.modal-overlay.open');
                    if (open.id === 'confirmModal' && App.confirmCallback) {
                        App.confirmCallback(false);
                        App.confirmCallback = null;
                    }
                    closeModal(open.id);
                } else if (dom.followupPanel.classList.contains('open')) {
                    closeFollowupPanel();
                }
            }
        });

        /* ===========================================
           INITIALIZATION
           =========================================== */
        if (App.token) {
            showDashboard();
            loadApplications();
        } else {
            showAuthScreen();
        }
    </script>
</body>

</html>
