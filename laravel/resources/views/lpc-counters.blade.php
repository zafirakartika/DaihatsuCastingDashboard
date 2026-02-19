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

        .counter-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--accent-blue) 0%, var(--accent-navy) 100%);
            border-radius: 12px 12px 0 0;
        }

        .counter-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 2px solid #e8e8e8;
        }

        .lpc-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--accent-navy);
            margin: 0;
        }

        .part-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: linear-gradient(135deg, var(--accent-blue) 0%, var(--accent-navy) 100%);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .counter-display {
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 1;
            margin-bottom: 16px;
        }

        .counter-value {
            font-size: 48px;
            font-weight: 800;
            color: var(--accent-navy);
            text-align: center;
            font-variant-numeric: tabular-nums;
            transition: all 0.3s ease;
        }

        .counter-label {
            font-size: 12px;
            color: #666;
            text-align: center;
            margin-top: 8px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-weight: 600;
        }

        .status-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: auto;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .status-dot.active {
            background: #27ae60;
            box-shadow: 0 0 8px rgba(39, 174, 96, 0.4);
        }

        .status-dot.updating {
            background: #f39c12;
            box-shadow: 0 0 8px rgba(243, 156, 18, 0.4);
            animation: pulse 1s infinite;
        }

        .status-dot.error {
            background: #e74c3c;
            box-shadow: 0 0 8px rgba(231, 76, 60, 0.4);
        }

        .status-text {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        .status-text.active {
            color: #27ae60;
        }

        .status-text.updating {
            color: #f39c12;
        }

        .status-text.error {
            color: #e74c3c;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .page-description {
            background: linear-gradient(135deg, var(--accent-blue) 0%, var(--accent-navy) 100%);
            color: white;
            padding: 24px;
            border-radius: 12px;
            margin-bottom: 24px;
            box-shadow: 0 4px 16px rgba(74, 111, 165, 0.2);
            position: relative;
            overflow: hidden;
        }

        .page-description::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            pointer-events: none;
        }

        .description-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 8px;
            position: relative;
            z-index: 1;
        }

        .description-text {
            font-size: 13px;
            opacity: 0.95;
            line-height: 1.6;
            position: relative;
            z-index: 1;
        }

        .last-updated {
            text-align: center;
            font-size: 11px;
            color: #666;
            margin-top: 16px;
            font-style: italic;
        }

        /* ── History Button ─────────────────────────────── */
        .btn-history {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 11px 24px;
            font-size: 14px;
            font-weight: 700;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #0ea5e9 0%, #2563eb 60%, #1e3a8a 100%);
            color: white;
            box-shadow: 0 4px 14px rgba(14, 165, 233, 0.5), 0 1px 3px rgba(0,0,0,0.15);
            letter-spacing: 0.4px;
        }
        .btn-history:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(14, 165, 233, 0.65), 0 2px 6px rgba(0,0,0,0.15);
            background: linear-gradient(135deg, #38bdf8 0%, #3b82f6 60%, #1d4ed8 100%);
        }
        .btn-history svg {
            width: 17px;
            height: 17px;
        }

        /* ── Modal Overlay ──────────────────────────────── */
        .history-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(13, 59, 102, 0.55);
            backdrop-filter: blur(3px);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .history-overlay.open { display: flex; }

        .history-modal {
            background: #f0f2f5;
            border-radius: 16px;
            width: 100%;
            max-width: 1100px;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
            box-shadow: 0 20px 60px rgba(13, 59, 102, 0.3);
            overflow: hidden;
        }

        /* Modal Header */
        .history-modal-header {
            background: linear-gradient(135deg, var(--accent-blue) 0%, var(--accent-navy) 100%);
            color: white;
            padding: 20px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
        }
        .history-modal-header h2 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 0.3px;
        }
        .history-modal-header p {
            margin: 4px 0 0;
            font-size: 12px;
            opacity: 0.8;
        }
        .btn-close-modal {
            background: rgba(255,255,255,0.15);
            border: none;
            color: white;
            width: 34px;
            height: 34px;
            border-radius: 8px;
            font-size: 18px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
            flex-shrink: 0;
        }
        .btn-close-modal:hover { background: rgba(255,255,255,0.28); }

        /* Filter Bar */
        .history-filters {
            background: white;
            padding: 16px 24px;
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: flex-end;
            border-bottom: 1px solid #e8e8e8;
            flex-shrink: 0;
        }
        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .filter-group label {
            font-size: 11px;
            font-weight: 600;
            color: #555;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .filter-group select,
        .filter-group input[type="date"] {
            padding: 7px 10px;
            border: 1.5px solid #e0e0e0;
            border-radius: 7px;
            font-size: 13px;
            color: var(--accent-navy);
            background: #fafafa;
            cursor: pointer;
            transition: border-color 0.2s;
            min-width: 130px;
        }
        .filter-group select:focus,
        .filter-group input[type="date"]:focus {
            outline: none;
            border-color: var(--accent-blue);
        }
        .btn-apply {
            padding: 8px 20px;
            background: linear-gradient(135deg, var(--accent-blue) 0%, var(--accent-navy) 100%);
            color: white;
            border: none;
            border-radius: 7px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 2px 6px rgba(74,111,165,0.3);
            align-self: flex-end;
        }
        .btn-apply:hover { transform: translateY(-1px); box-shadow: 0 4px 10px rgba(74,111,165,0.4); }
        .btn-reset {
            padding: 8px 14px;
            background: #f0f2f5;
            color: #666;
            border: 1.5px solid #e0e0e0;
            border-radius: 7px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            align-self: flex-end;
        }
        .btn-reset:hover { background: #e8e8e8; }

        /* Table Area */
        .history-body {
            flex: 1;
            overflow-y: auto;
            padding: 20px 24px;
        }
        .history-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            font-size: 12px;
            color: #666;
        }
        .history-meta strong { color: var(--accent-navy); }
        .shift-badge {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.3px;
        }
        .shift-morning { background: #fff3cd; color: #856404; }
        .shift-night   { background: #cfe2ff; color: #084298; }
        .shift-unknown { background: #e9ecef; color: #6c757d; }

        .history-table-wrap {
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #e0e0e0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .history-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        .history-table thead tr {
            background: linear-gradient(135deg, var(--accent-blue) 0%, var(--accent-navy) 100%);
            color: white;
        }
        .history-table th {
            padding: 11px 14px;
            text-align: left;
            font-weight: 600;
            letter-spacing: 0.3px;
            white-space: nowrap;
        }
        .history-table tbody tr { background: white; }
        .history-table tbody tr:nth-child(even) { background: #f8f9fa; }
        .history-table tbody tr:hover { background: #eef2fb; }
        .history-table td {
            padding: 10px 14px;
            border-bottom: 1px solid #f0f0f0;
            color: var(--accent-navy);
            white-space: nowrap;
        }
        .history-table td.num {
            font-weight: 700;
            font-variant-numeric: tabular-nums;
        }
        .history-table td.total-col {
            font-weight: 800;
            color: var(--accent-blue);
        }

        .history-empty {
            text-align: center;
            padding: 60px 20px;
            color: #aaa;
        }
        .history-empty svg { margin-bottom: 12px; opacity: 0.4; }
        .history-empty p { font-size: 14px; margin: 0; }

        .history-loading {
            text-align: center;
            padding: 60px 20px;
            color: var(--accent-blue);
            font-size: 14px;
        }

        /* Hover effects */
        .counter-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(74, 111, 165, 0.15);
            border-color: var(--accent-blue);
        }

        .counter-card:hover .counter-value {
            color: var(--accent-blue);
        }

        /* Loading animation */
        .counter-value.updating {
            animation: counterPulse 0.5s ease-in-out;
        }

        @keyframes counterPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
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
                <div style="display: flex; align-items: center; gap: 12px; width: 100%;">
                    <div class="page-title" style="font-size: 24px; font-weight: 700; color: var(--accent-navy); text-shadow: 1px 1px 2px rgba(13, 59, 102, 0.08); border-left: 4px solid var(--accent-blue); padding-left: 12px; margin-bottom: 0; white-space: nowrap;">
                        LPC Production Counters
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px; padding: 8px 14px; background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); border-radius: 8px; border: 2px solid #e9ecef; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <span style="font-size: 12px; font-weight: 600; color: #555;">Real-Time Monitor:</span>
                        <button id="toggle-realtime" onclick="window.lpcCountersDashboard.toggleRealTime()"
                                style="padding: 8px 16px; font-size: 12px; font-weight: 600; border: none; border-radius: 6px; cursor: pointer; transition: all 0.3s; background: linear-gradient(135deg, #27ae60 0%, #229954 100%); color: white; box-shadow: 0 2px 6px rgba(39, 174, 96, 0.3);">
                            <span id="toggle-status">ON</span>
                        </button>
                    </div>

                    <button class="btn-history" onclick="window.lpcHistory.open()" style="margin-left: auto;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                        </svg>
                        History
                    </button>
                </div>
            </div>

            <div class="counters-grid">
                <div class="counter-card">
                    <div class="counter-header">
                        <h3 class="lpc-title">LPC 1</h3>
                        <div class="part-badge">
                            <span>🏭</span>
                            <span>TR Line</span>
                        </div>
                    </div>
                    <div class="counter-display">
                        <div class="counter-value" id="value-tr-1">0</div>
                    </div>
                    <div class="counter-label">Production Count</div>
                    <div class="status-indicator">
                        <div class="status-dot active"></div>
                        <div class="status-text active">Active</div>
                    </div>
                </div>

                <div class="counter-card">
                    <div class="counter-header">
                        <h3 class="lpc-title">LPC 2</h3>
                        <div class="part-badge">
                            <span>🏭</span>
                            <span>TR Line</span>
                        </div>
                    </div>
                    <div class="counter-display">
                        <div class="counter-value" id="value-tr-2">0</div>
                    </div>
                    <div class="counter-label">Production Count</div>
                    <div class="status-indicator">
                        <div class="status-dot active"></div>
                        <div class="status-text active">Active</div>
                    </div>
                </div>

                <div class="counter-card">
                    <div class="counter-header">
                        <h3 class="lpc-title">LPC 3</h3>
                        <div class="part-badge">
                            <span>🏭</span>
                            <span>TR Line</span>
                        </div>
                    </div>
                    <div class="counter-display">
                        <div class="counter-value" id="value-tr-3">0</div>
                    </div>
                    <div class="counter-label">Production Count</div>
                    <div class="status-indicator">
                        <div class="status-dot active"></div>
                        <div class="status-text active">Active</div>
                    </div>
                </div>

                <div class="counter-card">
                    <div class="counter-header">
                        <h3 class="lpc-title">LPC 4</h3>
                        <div class="part-badge">
                            <span>🏭</span>
                            <span>TR Line</span>
                        </div>
                    </div>
                    <div class="counter-display">
                        <div class="counter-value" id="value-tr-4">0</div>
                    </div>
                    <div class="counter-label">Production Count</div>
                    <div class="status-indicator">
                        <div class="status-dot active"></div>
                        <div class="status-text active">Active</div>
                    </div>
                </div>

                <div class="counter-card">
                    <div class="counter-header">
                        <h3 class="lpc-title">LPC 6</h3>
                        <div class="part-badge">
                            <span>🏭</span>
                            <span>TR Line</span>
                        </div>
                    </div>
                    <div class="counter-display">
                        <div class="counter-value" id="value-tr-6">0</div>
                    </div>
                    <div class="counter-label">Production Count</div>
                    <div class="status-indicator">
                        <div class="status-dot active"></div>
                        <div class="status-text active">Active</div>
                    </div>
                </div>

                <div class="counter-card">
                    <div class="counter-header">
                        <h3 class="lpc-title">LPC 9</h3>
                        <div class="part-badge">
                            <span>🏭</span>
                            <span>3SZ/KR Line</span>
                        </div>
                    </div>
                    <div class="counter-display">
                        <div class="counter-value" id="value-3sz-kr">0</div>
                    </div>
                    <div class="counter-label">Production Count</div>
                    <div class="status-indicator">
                        <div class="status-dot active"></div>
                        <div class="status-text active">Active</div>
                    </div>
                </div>

                <div class="counter-card">
                    <div class="counter-header">
                        <h3 class="lpc-title">LPC 12</h3>
                        <div class="part-badge">
                            <span>🏭</span>
                            <span>NR Line</span>
                        </div>
                    </div>
                    <div class="counter-display">
                        <div class="counter-value" id="value-nr-12">0</div>
                    </div>
                    <div class="counter-label">Production Count</div>
                    <div class="status-indicator">
                        <div class="status-dot active"></div>
                        <div class="status-text active">Active</div>
                    </div>
                </div>

                <div class="counter-card">
                    <div class="counter-header">
                        <h3 class="lpc-title">LPC 13</h3>
                        <div class="part-badge">
                            <span>🏭</span>
                            <span>NR Line</span>
                        </div>
                    </div>
                    <div class="counter-display">
                        <div class="counter-value" id="value-nr-13">0</div>
                    </div>
                    <div class="counter-label">Production Count</div>
                    <div class="status-indicator">
                        <div class="status-dot active"></div>
                        <div class="status-text active">Active</div>
                    </div>
                </div>

                <div class="counter-card">
                    <div class="counter-header">
                        <h3 class="lpc-title">LPC 14</h3>
                        <div class="part-badge">
                            <span>🏭</span>
                            <span>NR Line</span>
                        </div>
                    </div>
                    <div class="counter-display">
                        <div class="counter-value" id="value-nr-14">0</div>
                    </div>
                    <div class="counter-label">Production Count</div>
                    <div class="status-indicator">
                        <div class="status-dot active"></div>
                        <div class="status-text active">Active</div>
                    </div>
                </div>

                <div class="counter-card">
                    <div class="counter-header">
                        <h3 class="lpc-title">LPC 11</h3>
                        <div class="part-badge">
                            <span>🏭</span>
                            <span>WA Line</span>
                        </div>
                    </div>
                    <div class="counter-display">
                        <div class="counter-value" id="value-wa">0</div>
                    </div>
                    <div class="counter-label">Production Count</div>
                    <div class="status-indicator">
                        <div class="status-dot active"></div>
                        <div class="status-text active">Active</div>
                    </div>
                </div>
            </div>

            <div class="last-updated" id="last-updated">Last updated: --:--:--</div>
        </div>
    </div>

    <!-- History Modal -->
    <div class="history-overlay" id="history-overlay" onclick="if(event.target===this) window.lpcHistory.close()">
        <div class="history-modal">

            <div class="history-modal-header">
                <div>
                    <h2>📋 LPC Counter History</h2>
                    <p>Production records per shift — excludes live row</p>
                </div>
                <button class="btn-close-modal" onclick="window.lpcHistory.close()">✕</button>
            </div>

            <div class="history-filters">
                <div class="filter-group">
                    <label>Line</label>
                    <select id="hist-line">
                        <option value="tr">TR Line</option>
                        <option value="wa">WA Line</option>
                        <option value="szkr">3SZ / KR Line</option>
                        <option value="nr">NR Line</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Shift</label>
                    <select id="hist-shift">
                        <option value="all">All Shifts</option>
                        <option value="morning">Morning Shift</option>
                        <option value="night">Night Shift</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Month</label>
                    <select id="hist-month">
                        <option value="">All Months</option>
                        <option value="1">January</option>
                        <option value="2">February</option>
                        <option value="3">March</option>
                        <option value="4">April</option>
                        <option value="5">May</option>
                        <option value="6">June</option>
                        <option value="7">July</option>
                        <option value="8">August</option>
                        <option value="9">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Year</label>
                    <select id="hist-year">
                        <option value="">All Years</option>
                        <option value="2025">2025</option>
                        <option value="2026" selected>2026</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Specific Date</label>
                    <input type="date" id="hist-date">
                </div>
                <button class="btn-apply" onclick="window.lpcHistory.load()">Apply</button>
                <button class="btn-reset" onclick="window.lpcHistory.reset()">Reset</button>
            </div>

            <div class="history-body">
                <div class="history-meta">
                    <span id="hist-result-info">—</span>
                </div>
                <div id="hist-table-area">
                    <div class="history-empty">
                        <p>Select filters and click Apply to load history.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="{{ asset('js/main.js') }}"></script>
    <script src="{{ asset('js/lpc-counters.js') }}"></script>
</body>
</html>