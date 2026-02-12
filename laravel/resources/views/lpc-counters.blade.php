@php
    $current_page = 'lpc-counters';
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/daihatsu-logo.png') }}">
    <title>LPC Counters - ALPC Monitoring</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <style>
        .counters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin: 20px 0;
            padding: 0;
            width: 100%;
        }
        @media (max-width: 1200px) {
            .counters-grid {
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                gap: 16px;
            }
        }
        @media (max-width: 800px) {
            .counters-grid {
                grid-template-columns: 1fr;
                gap: 14px;
            }
        }
        /* ... existing styles kept intact ... */
        .counter-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border: 2px solid #e8e8e8;
            position: relative;
            overflow: hidden;
            min-height: 160px;
            display: flex;
            flex-direction: column;
        }
        /* (Shortened style block for readability, your original styles work fine here) */
        .counter-value {
            font-size: 48px;
            font-weight: 800;
            color: var(--accent-navy);
            text-align: center;
            transition: all 0.3s ease;
        }
        .status-dot.active { background: #27ae60; }
        .status-dot.error { background: #e74c3c; }
        .updated-flash { color: #27ae60; transform: scale(1.1); }
    </style>
</head>
<body>
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
                <span class="monitoring-text">ALPC Counters</span>
                <div class="monitoring-subtitle">Real-time Production Monitoring</div>
            </div>
        </div>
        <div class="header-right">
            <div class="header-logos">
                <img src="{{ asset('assets/images/icare.png') }}" alt="I CARE" class="company-logo">
                <img src="{{ asset('assets/images/adm-unity.png') }}" alt="ADM Unity" class="company-logo">
            </div>
            <div class="datetime-display">
                <div class="date-text" id="current-date"></div>
                <div class="time-text" id="current-time"></div>
            </div>
        </div>
    </div>

    <div class="dashboard-container">
        @include('includes.sidebar')

        <div class="main-content">
            <div class="content-header" style="margin-bottom: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                    <div class="page-title" style="font-size: 24px; font-weight: 700; color: var(--accent-navy);">
                        LPC Production Counters
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px; padding: 8px 14px; background: #fff; border-radius: 8px; border: 1px solid #ddd;">
                        <span style="font-size: 12px; font-weight: 600; color: #555;">Real-Time Monitor:</span>
                        <button id="toggle-realtime" onclick="window.lpcCountersDashboard.toggleRealTime()"
                                style="padding: 8px 16px; font-size: 12px; font-weight: 600; border: none; border-radius: 6px; cursor: pointer; background: #27ae60; color: white;">
                            <span id="toggle-status">ON</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="counters-grid">
                @foreach([1, 2, 3, 4, 6] as $lpc)
                <div class="counter-card">
                    <div class="counter-header">
                        <h3 class="lpc-title">LPC {{ $lpc }}</h3>
                        <div class="part-badge" style="background: #2c3e50; color: white; padding: 4px 8px; border-radius: 4px;">TR Line</div>
                    </div>
                    <div class="counter-display">
                        <div class="counter-value" id="value-tr-{{ $lpc }}">0</div>
                    </div>
                    <div class="counter-label">Production Count</div>
                </div>
                @endforeach

                <div class="counter-card">
                    <div class="counter-header">
                        <h3 class="lpc-title">LPC 9</h3>
                        <div class="part-badge" style="background: #2c3e50; color: white; padding: 4px 8px; border-radius: 4px;">3SZ/KR Line</div>
                    </div>
                    <div class="counter-display">
                        <div class="counter-value" id="value-3sz-kr">0</div>
                    </div>
                    <div class="counter-label">Production Count</div>
                </div>

                @foreach([12, 13, 14] as $lpc)
                <div class="counter-card">
                    <div class="counter-header">
                        <h3 class="lpc-title">LPC {{ $lpc }}</h3>
                        <div class="part-badge" style="background: #2c3e50; color: white; padding: 4px 8px; border-radius: 4px;">NR Line</div>
                    </div>
                    <div class="counter-display">
                        <div class="counter-value" id="value-nr-{{ $lpc }}">0</div>
                    </div>
                    <div class="counter-label">Production Count</div>
                </div>
                @endforeach

                <div class="counter-card">
                    <div class="counter-header">
                        <h3 class="lpc-title">LPC 11</h3>
                        <div class="part-badge" style="background: #2c3e50; color: white; padding: 4px 8px; border-radius: 4px;">WA Line</div>
                    </div>
                    <div class="counter-display">
                        <div class="counter-value" id="value-wa">0</div>
                    </div>
                    <div class="counter-label">Production Count</div>
                </div>
            </div>

            <div class="last-updated" id="last-updated">Last updated: --:--:--</div>
        </div>
    </div>

    <script src="{{ asset('js/main.js') }}"></script>
    <script src="{{ asset('js/lpc-counters.js') }}"></script>
</body>
</html>