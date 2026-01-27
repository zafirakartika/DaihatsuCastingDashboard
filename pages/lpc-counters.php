<?php
$current_page = 'lpc-counters';
$base_url = '../';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../assets/images/daihatsu-logo.png">
    <title>LPC Counters - ALPC Monitoring</title>
    <link rel="stylesheet" href="../css/styles.css">
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
    <!-- Top Header -->
    <div class="top-header">
        <div class="logo-section">
            <!-- Hamburger Menu -->
            <div class="hamburger-menu" onclick="toggleSidebar()">
                <div class="hamburger-line"></div>
                <div class="hamburger-line"></div>
                <div class="hamburger-line"></div>
            </div>
            <img src="../assets/images/daihatsu-logo.png" alt="Daihatsu Logo" class="company-logo">
        </div>
        <div class="header-center">
            <div class="monitoring-title">
                <span class="monitoring-text">ALPC Counters</span>
                <div class="monitoring-subtitle">Real-time Production Monitoring</div>
            </div>
        </div>
        <div class="header-right">
            <div class="header-logos">
                <img src="../assets/images/icare.png" alt="I CARE" class="company-logo">
                <img src="../assets/images/adm-unity.png" alt="ADM Unity" class="company-logo">
            </div>
            <div class="datetime-display">
                <div class="date-text" id="current-date"></div>
                <div class="time-text" id="current-time"></div>
            </div>
        </div>
    </div>

    <!-- Dashboard Container -->
    <div class="dashboard-container">
        <?php include '../includes/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="main-content">
            <div class="content-header" style="margin-bottom: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                    <div class="page-title" style="font-size: 24px; font-weight: 700; color: var(--accent-navy); text-shadow: 1px 1px 2px rgba(13, 59, 102, 0.08); border-left: 4px solid var(--accent-blue); padding-left: 12px; margin-bottom: 0;">
                        LPC Production Counters
                    </div>

                    <!-- Real-time Monitoring Toggle -->
                    <div style="display: flex; align-items: center; gap: 8px; padding: 8px 14px; background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); border-radius: 8px; border: 2px solid #e9ecef; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <span style="font-size: 12px; font-weight: 600; color: #555;">Real-Time Monitor:</span>
                        <button id="toggle-realtime" onclick="window.lpcCountersDashboard.toggleRealTime()"
                                style="padding: 8px 16px; font-size: 12px; font-weight: 600; border: none; border-radius: 6px; cursor: pointer; transition: all 0.3s; background: linear-gradient(135deg, #27ae60 0%, #229954 100%); color: white; box-shadow: 0 2px 6px rgba(39, 174, 96, 0.3);">
                            <span id="toggle-status">ON</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Page Description -->
            <div class="page-description">
                <div class="description-title">Real-Time Counter Monitoring</div>
                <div class="description-text">
                    Monitor production counters from all Local Processing Centers (LPCs) across ALPC lines.
                    Data updates automatically every 10 seconds to provide real-time production insights.
                </div>
            </div>

            <!-- Counters Grid -->
            <div class="counters-grid">
                <!-- TR Line Counters -->
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

                <!-- 3SZ/KR Line Counter -->
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

                <!-- NR Line Counters -->
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

                <!-- WA Line Counter -->
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

            <!-- Last Updated -->
            <div class="last-updated" id="last-updated">Last updated: --:--:--</div>
        </div>
    </div>

    <script src="../js/main.js"></script>
    <script src="../js/lpc-counters.js"></script>
</body>
</html>
