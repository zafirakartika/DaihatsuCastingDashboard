<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/daihatsu-logo.png') }}">
    <title>Traceability System - Casting SMART Factory</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <style>
        body {
            overflow: hidden;
            height: 100vh;
        }

        .traceability-page {
            margin-left: 250px;
            margin-top: 64px;
            height: calc(100vh - 64px);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 0 20px;
            transition: margin-left 0.3s ease;
            overflow: hidden;
        }

        /* Sidebar collapsed state - using class on body */
        body.sidebar-collapsed .traceability-page {
            margin-left: 0;
        }

        .page-title {
            font-size: 36px;
            font-weight: 600;
            color: var(--accent-navy);
            margin-bottom: 80px;
            text-align: center;
            letter-spacing: -0.5px;
        }

        .parts-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0;
            margin-bottom: 60px;
            flex-wrap: nowrap;
        }

        .part-box {
            background: var(--white);
            width: 140px;
            height: 140px;
            border-radius: 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 14px;
            box-shadow: 0 2px 8px rgba(61, 74, 92, 0.08);
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            border: 1px solid var(--gray-border);
        }

        .part-box:hover {
            transform: translateY(-6px);
            box-shadow: 0 8px 20px rgba(74, 111, 165, 0.2);
            border-color: var(--accent-blue);
        }

        .gear-icon {
            width: 64px;
            height: 64px;
            background: var(--gray-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            transition: all 0.25s ease;
        }

        .part-box:hover .gear-icon {
            background: linear-gradient(135deg, var(--accent-blue), var(--accent-navy));
        }

        .gear-icon::before {
            content: '⚙️';
            font-size: 36px;
            filter: grayscale(0.3);
            transition: filter 0.25s ease;
        }

        .part-box:hover .gear-icon::before {
            filter: grayscale(0) brightness(1.2);
        }

        .part-label {
            font-size: 22px;
            font-weight: 600;
            color: var(--accent-navy);
            letter-spacing: 0.5px;
            transition: color 0.25s ease;
        }

        .part-box:hover .part-label {
            color: var(--accent-blue);
        }

        .connector-line {
            width: 70px;
            height: 2px;
            background: var(--gray-border);
            position: relative;
            margin: 0 -8px;
        }

        .connector-line::before,
        .connector-line::after {
            content: '';
            position: absolute;
            width: 8px;
            height: 8px;
            background: var(--accent-blue);
            border-radius: 50%;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0.6;
        }

        .connector-line::before {
            left: 0;
        }

        .connector-line::after {
            right: 0;
        }

        .instruction-bubble {
            background: var(--white);
            padding: 14px 40px;
            border-radius: 40px;
            box-shadow: 0 2px 8px rgba(61, 74, 92, 0.08);
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 15px;
            color: var(--text-light);
            border: 1px solid var(--gray-border);
        }

        .hand-icon {
            font-size: 20px;
        }

        @media (max-width: 1024px) {
            .parts-container {
                flex-wrap: wrap;
                gap: 24px;
            }

            .connector-line {
                display: none;
            }

            .part-box {
                width: 130px;
                height: 130px;
            }
        }

        @media (max-width: 640px) {
            .page-title {
                font-size: 28px;
                margin-bottom: 50px;
            }

            .part-box {
                width: 110px;
                height: 110px;
            }

            .gear-icon {
                width: 50px;
                height: 50px;
            }

            .gear-icon::before {
                font-size: 28px;
            }

            .part-label {
                font-size: 18px;
            }

            .instruction-bubble {
                padding: 12px 28px;
                font-size: 13px;
            }
        }

        @media (max-width: 768px) {
            .traceability-page {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Top Header -->
    <div class="top-header">
        <div class="logo-section">
            <div class="hamburger-menu" onclick="toggleSidebar()">
                <div class="hamburger-line"></div>
                <div class="hamburger-line"></div>
                <div class="hamburger-line"></div>
            </div>
            <img src="{{ asset('assets/images/daihatsu-logo.png') }}" alt="Daihatsu Logo" class="company-logo">
        </div>
        <div class="header-center">
            <div class="monitoring-title">
                <span class="monitoring-text">Traceability</span>
                <div class="monitoring-subtitle">CASTING SMART FACTORY SYSTEM</div>
            </div>
        </div>
        <div class="header-right">
            <div class="header-logos">
                <img src="{{ asset('assets/images/icare.png') }}" alt="I-CARE" class="company-logo">
                <img src="{{ asset('assets/images/adm-unity.png') }}" alt="ADM" class="company-logo">
            </div>
            <div class="datetime-display">
                <div class="date-text" id="current-date"></div>
                <div class="time-text" id="current-time"></div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    @include('includes.sidebar')

    <!-- Main Content -->
    <div class="traceability-page">
        <h1 class="page-title">Traceability System</h1>

        <div class="parts-container">
            <a href="{{ route('traceability-kr') }}" class="part-box">
                <div class="gear-icon"></div>
                <div class="part-label">KR</div>
            </a>

            <div class="connector-line"></div>

            <a href="{{ route('traceability-tr') }}" class="part-box">
                <div class="gear-icon"></div>
                <div class="part-label">TR</div>
            </a>

            <div class="connector-line"></div>

            <a href="{{ route('traceability-3sz') }}" class="part-box">
                <div class="gear-icon"></div>
                <div class="part-label">3SZ</div>
            </a>

            <div class="connector-line"></div>

            <a href="{{ route('traceability-nr') }}" class="part-box">
                <div class="gear-icon"></div>
                <div class="part-label">NR</div>
            </a>

            <div class="connector-line"></div>

            <a href="{{ route('traceability-wa') }}" class="part-box">
                <div class="gear-icon"></div>
                <div class="part-label">WA</div>
            </a>
        </div>

        <div class="instruction-bubble">
            <span class="hand-icon">👆</span>
            <span>Click on a part to view detailed traceability data</span>
        </div>
    </div>

    <script src="{{ asset('js/main.js') }}"></script>
    <script>
        // Override toggleSidebar to add body class for proper page margin
        const originalToggleSidebar = window.toggleSidebar;
        window.toggleSidebar = function() {
            if (originalToggleSidebar) {
                originalToggleSidebar();
            }

            // Toggle body class for page margin adjustment
            const sidebar = document.querySelector('.sidebar');
            if (sidebar && sidebar.classList.contains('hidden')) {
                document.body.classList.add('sidebar-collapsed');
            } else {
                document.body.classList.remove('sidebar-collapsed');
            }
        };

        // Check sidebar state on page load
        window.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.sidebar');
            if (sidebar && sidebar.classList.contains('hidden')) {
                document.body.classList.add('sidebar-collapsed');
            }
        });
    </script>
</body>
</html>
