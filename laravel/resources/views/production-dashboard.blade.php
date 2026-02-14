<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/daihatsu-logo.png') }}">
    <title>Production Dashboard - SMART Factory</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/casting-performance.css') }}">
    <style>
        .production-overview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .overview-card {
            background: white;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            border: 1px solid #e8e8e8;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .overview-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--accent-blue), var(--accent-navy));
            opacity: 0;
            transition: opacity 0.3s;
        }

        .overview-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(74, 111, 165, 0.12);
            border-color: var(--accent-blue);
        }

        .overview-card:hover::before {
            opacity: 1;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            padding-bottom: 10px;
            border-bottom: 1px solid #f0f0f0;
        }

        .card-title {
            font-size: 13px;
            font-weight: 600;
            color: #666;
            display: flex;
            align-items: center;
            gap: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .card-icon {
            width: 24px;
            height: 24px;
            background: linear-gradient(135deg, var(--accent-blue), var(--accent-navy));
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
        }

        .card-value {
            font-size: 32px;
            font-weight: 700;
            color: var(--accent-navy);
            line-height: 1;
            margin-bottom: 4px;
        }

        .card-value.good {
            color: #27ae60;
        }

        .card-value.warning {
            color: #f39c12;
        }

        .card-value.critical {
            color: #e74c3c;
        }

        .card-label {
            font-size: 11px;
            color: #999;
            font-weight: 500;
        }

        .lpc-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 16px;
            margin-top: 20px;
        }

        .lpc-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border: 2px solid #e8e8e8;
            border-radius: 12px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .lpc-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .lpc-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(74, 111, 165, 0.15);
            border-color: var(--accent-blue);
        }

        .lpc-card:hover::before {
            opacity: 1;
        }

        .lpc-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .lpc-name {
            font-size: 16px;
            font-weight: 700;
            color: var(--accent-navy);
        }

        .lpc-status {
            font-size: 10px;
            padding: 4px 10px;
            border-radius: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .lpc-status.normal {
            background: #d4edda;
            color: #155724;
        }

        .lpc-status.warning {
            background: #fff3cd;
            color: #856404;
        }

        .lpc-metrics {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .lpc-metric {
            text-align: center;
            padding: 10px;
            background: rgba(74, 111, 165, 0.05);
            border-radius: 8px;
        }

        .lpc-metric-label {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-bottom: 4px;
            font-weight: 600;
        }

        .lpc-metric-value {
            font-size: 20px;
            font-weight: 700;
            color: var(--accent-navy);
        }

        .lpc-metric-value.good {
            color: #27ae60;
        }

        .lpc-metric-value.warning {
            color: #f39c12;
        }

        .section-title {
            font-size: 22px;
            font-weight: 700;
            color: var(--accent-navy);
            margin: 30px 0 20px 0;
            padding-left: 12px;
            border-left: 4px solid var(--accent-blue);
        }

        .line-divider {
            background: linear-gradient(135deg, var(--accent-blue), var(--accent-navy));
            color: white;
            padding: 16px 24px;
            border-radius: 12px;
            margin: 30px 0 20px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 12px rgba(74, 111, 165, 0.2);
        }

        .line-name {
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .line-stats {
            display: flex;
            gap: 20px;
            font-size: 14px;
        }

        .line-stat {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        .line-stat-label {
            font-size: 11px;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .line-stat-value {
            font-size: 18px;
            font-weight: 700;
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
            <img src="{{ asset('assets/images/daihatsu-logo.png') }}" alt="Daihatsu Logo" class="company-logo">
        </div>
        <div class="header-center">
            <div class="monitoring-title">
                <span class="monitoring-text">Production Dashboard</span>
                <div class="monitoring-subtitle">Overall Production Monitoring - All Lines & LPCs</div>
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

    <!-- Dashboard Container -->
    <div class="dashboard-container">
        @include('includes.sidebar')

        <!-- Main Content -->
        <div class="main-content">
            <div class="content-header" style="margin-bottom: 24px;">
                <div class="page-title" style="font-size: 28px; font-weight: 700; color: var(--accent-navy); text-shadow: 1px 1px 2px rgba(13, 59, 102, 0.08); border-left: 4px solid var(--accent-blue); padding-left: 12px;">
                    Production Overview
                </div>
            </div>

            <!-- Overall Metrics -->
            <div class="production-overview-grid">
                <div class="overview-card">
                    <div class="card-header">
                        <div class="card-title">
                            <div class="card-icon">📊</div>
                            <span>Overall OEE</span>
                        </div>
                    </div>
                    <div class="card-value good">89.4%</div>
                    <div class="card-label">Overall Equipment Effectiveness</div>
                </div>

                <div class="overview-card">
                    <div class="card-header">
                        <div class="card-title">
                            <div class="card-icon">⚡</div>
                            <span>Performance</span>
                        </div>
                    </div>
                    <div class="card-value good">94.1%</div>
                    <div class="card-label">Production Performance Rate</div>
                </div>

                <div class="overview-card">
                    <div class="card-header">
                        <div class="card-title">
                            <div class="card-icon">✓</div>
                            <span>Quality</span>
                        </div>
                    </div>
                    <div class="card-value good">96.3%</div>
                    <div class="card-label">Quality Rate</div>
                </div>

                <div class="overview-card">
                    <div class="card-header">
                        <div class="card-title">
                            <div class="card-icon">🔧</div>
                            <span>Availability</span>
                        </div>
                    </div>
                    <div class="card-value warning">90.8%</div>
                    <div class="card-label">Machine Availability</div>
                </div>

                <div class="overview-card">
                    <div class="card-header">
                        <div class="card-title">
                            <div class="card-icon">🏭</div>
                            <span>Active LPCs</span>
                        </div>
                    </div>
                    <div class="card-value">11</div>
                    <div class="card-label">Total Production Lines</div>
                </div>

                <div class="overview-card">
                    <div class="card-header">
                        <div class="card-title">
                            <div class="card-icon">📦</div>
                            <span>Parts Today</span>
                        </div>
                    </div>
                    <div class="card-value">15,847</div>
                    <div class="card-label">Total Parts Produced</div>
                </div>
            </div>

            <!-- Quick Access Info -->
            <div style="background: linear-gradient(135deg, var(--accent-blue), var(--accent-navy)); color: white; padding: 20px 24px; border-radius: 12px; margin-top: 24px; box-shadow: 0 4px 12px rgba(74, 111, 165, 0.2);">
                <div style="font-size: 16px; font-weight: 600; margin-bottom: 8px;">📊 Detailed LPC Overview</div>
                <div style="font-size: 13px; opacity: 0.95;">For detailed metrics of individual LPCs, please navigate to <strong>ALPC > Overview</strong> from the sidebar menu.</div>
            </div>

        </div>
    </div>

    <script src="{{ asset('js/main.js') }}"></script>
</body>
</html>