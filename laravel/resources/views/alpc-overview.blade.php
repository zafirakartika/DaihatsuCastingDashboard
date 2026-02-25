<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/daihatsu-logo.png') }}">
    <title>Dashboard Overview - ALPC Monitoring</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <style>
        .parts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(360px, 1fr));
            gap: 16px;
            margin: 16px 0;
            padding: 0;
            width: 100%;
        }

        @media (max-width: 1200px) {
            .parts-grid {
                grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
                gap: 14px;
            }
        }

        @media (max-width: 800px) {
            .parts-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }
        }

        .part-card {
            background: white;
            border-radius: 12px;
            padding: 18px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            cursor: pointer;
            transition: all 0.2s;
            border: 2px solid #e8e8e8;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            width: 100%;
            min-height: 280px;
        }

        .part-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, var(--accent-blue) 0%, var(--accent-navy) 100%);
            border-radius: 12px 12px 0 0;
            opacity: 0;
            transition: opacity 0.2s;
        }

        .part-card:hover::before {
            opacity: 1;
        }

        .part-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(74, 111, 165, 0.12);
            border-color: var(--accent-blue);
        }

        .part-header {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 14px;
            padding-bottom: 12px;
            border-bottom: 2px solid #e8e8e8;
            position: relative;
            z-index: 1;
        }

        .part-title-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .part-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--accent-navy);
            letter-spacing: -0.3px;
        }

        .part-type-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: linear-gradient(135deg, var(--accent-blue) 0%, var(--accent-navy) 100%);
            color: white;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            box-shadow: 0 2px 6px rgba(74, 111, 165, 0.2);
        }

        .expand-icon {
            font-size: 20px;
            color: var(--accent-blue);
            transition: all 0.2s;
            opacity: 0.7;
            flex-shrink: 0;
        }

        .part-card:hover .expand-icon {
            opacity: 1;
            transform: translateX(3px);
        }

        .part-metrics {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-bottom: 12px;
            position: relative;
            z-index: 1;
        }

        .metric-item {
            display: flex;
            flex-direction: column;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 8px;
            transition: all 0.2s;
            min-height: 62px;
            justify-content: center;
            border: 1px solid #e8e8e8;
        }

        .metric-item:hover {
            background: #e8f4f8;
            transform: translateY(-1px);
            box-shadow: 0 3px 8px rgba(74, 111, 165, 0.1);
            border-color: var(--accent-blue);
        }

        .metric-label {
            font-size: 10px;
            color: #666;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            font-weight: 600;
            white-space: nowrap;
        }

        .metric-value {
            font-size: 22px;
            font-weight: 700;
            color: var(--accent-navy);
            line-height: 1;
        }

        .metric-value.good {
            color: #27ae60;
        }

        .metric-value.warning {
            color: #f39c12;
        }

        .metric-value.critical {
            color: #e74c3c;
        }

        .lpc-dropdown {
            display: none;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #e0e0e0;
        }

        .lpc-dropdown.show {
            display: block;
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                max-height: 0;
            }
            to {
                opacity: 1;
                max-height: 500px;
            }
        }

        .lpc-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 14px;
            margin-top: 14px;
        }

        .lpc-item {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border: 2px solid #e8e8e8;
            border-radius: 12px;
            padding: 18px 14px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            min-height: 95px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .lpc-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, var(--accent-blue), #667eea);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .lpc-item:hover::before {
            opacity: 1;
        }

        .lpc-item:hover {
            border-color: var(--accent-blue);
            background: linear-gradient(135deg, #e8f4f8 0%, #d4e7f3 100%);
            transform: translateY(-4px) scale(1.03);
            box-shadow: 0 6px 16px rgba(52, 152, 219, 0.15);
        }

        .lpc-name {
            font-size: 16px;
            font-weight: 700;
            color: var(--accent-navy);
            margin-bottom: 10px;
            letter-spacing: -0.2px;
        }

        .lpc-status {
            font-size: 12px;
            padding: 6px 12px;
            border-radius: 14px;
            display: inline-block;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .lpc-status.normal {
            background: #d4edda;
            color: #155724;
        }

        .lpc-status.warning {
            background: #fff3cd;
            color: #856404;
        }

        .page-description {
            background: linear-gradient(135deg, var(--accent-blue) 0%, var(--accent-navy) 100%);
            color: white;
            padding: 28px;
            border-radius: 16px;
            margin-bottom: 28px;
            box-shadow: 0 6px 20px rgba(74, 111, 165, 0.25);
            border: 2px solid rgba(255, 255, 255, 0.1);
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
            font-size: 22px;
            font-weight: 800;
            margin-bottom: 12px;
            position: relative;
            z-index: 1;
        }

        .description-text {
            font-size: 14px;
            opacity: 0.95;
            line-height: 1.7;
            position: relative;
            z-index: 1;
        }

        /* Modal Popup Styles */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            z-index: 9998;
            animation: fadeIn 0.3s ease;
        }

        .modal-overlay.show {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .modal-container {
            background: white;
            border-radius: 20px;
            max-width: 900px;
            width: 90%;
            max-height: 85vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px) scale(0.9);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .modal-header {
            background: linear-gradient(135deg, var(--accent-blue) 0%, var(--accent-navy) 100%);
            color: white;
            padding: 28px 32px;
            border-radius: 20px 20px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            font-size: 28px;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .modal-close {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            font-size: 28px;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-close:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: rotate(90deg);
        }

        .modal-body {
            padding: 32px;
        }

        .lpc-modal-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        @media (max-width: 768px) {
            .lpc-modal-grid {
                grid-template-columns: 1fr;
            }
        }

        .lpc-modal-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border: 3px solid #e8e8e8;
            border-radius: 16px;
            padding: 24px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .lpc-modal-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--accent-blue), #667eea);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .lpc-modal-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 12px 24px rgba(74, 111, 165, 0.2);
            border-color: var(--accent-blue);
        }

        .lpc-modal-card:hover::before {
            opacity: 1;
        }

        .lpc-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .lpc-modal-name {
            font-size: 22px;
            font-weight: 800;
            color: var(--accent-navy);
        }

        .lpc-modal-status {
            font-size: 13px;
            padding: 8px 14px;
            border-radius: 16px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .lpc-modal-status.normal {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .lpc-modal-status.warning {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .lpc-modal-metrics {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .lpc-modal-metric {
            text-align: center;
            padding: 12px;
            background: rgba(74, 111, 165, 0.05);
            border-radius: 10px;
        }

        .lpc-modal-metric-label {
            font-size: 11px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
            font-weight: 600;
        }

        .lpc-modal-metric-value {
            font-size: 24px;
            font-weight: 800;
            color: var(--accent-navy);
        }

        .lpc-modal-metric-value.good {
            color: #27ae60;
        }

        .lpc-modal-metric-value.warning {
            color: #f39c12;
        }

        /* Enhanced Part Card Animations */
        .part-card {
            animation: cardFadeIn 0.5s ease backwards;
        }

        .part-card:nth-child(1) { animation-delay: 0.1s; }
        .part-card:nth-child(2) { animation-delay: 0.2s; }
        .part-card:nth-child(3) { animation-delay: 0.3s; }
        .part-card:nth-child(4) { animation-delay: 0.4s; }
        .part-card:nth-child(5) { animation-delay: 0.5s; }

        @keyframes cardFadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Quick Access Button */
        .quick-access-btn {
            background: linear-gradient(135deg, var(--accent-blue), var(--accent-navy));
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 16px;
            width: 100%;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .quick-access-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(74, 111, 165, 0.3);
        }

        .quick-access-btn:active {
            transform: translateY(0);
        }

        /* Info Badge */
        .info-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            background: rgba(74, 111, 165, 0.08);
            color: var(--accent-blue);
            padding: 6px 12px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 600;
            margin-top: auto;
            border: 1px solid rgba(74, 111, 165, 0.15);
            transition: all 0.2s;
            letter-spacing: 0.3px;
        }

        .part-card:hover .info-badge {
            background: rgba(74, 111, 165, 0.12);
            border-color: var(--accent-blue);
        }

        /* Quick Stats Summary */
        .quick-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 12px;
            margin-bottom: 16px;
            padding: 0;
        }

        .stat-box {
            background: #ffffff;
            border: 2px solid #e8e8e8;
            border-radius: 10px;
            padding: 12px;
            text-align: center;
            transition: all 0.2s;
        }

        .stat-box:hover {
            border-color: var(--accent-blue);
            transform: translateY(-2px);
            box-shadow: 0 3px 10px rgba(74, 111, 165, 0.1);
        }

        .stat-box-value {
            font-size: 28px;
            font-weight: 700;
            color: var(--accent-navy);
            line-height: 1;
            margin-bottom: 5px;
        }

        .stat-box-label {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            font-weight: 600;
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
                <span class="monitoring-text">ALPC Dashboard</span>
                <div class="monitoring-subtitle">Master Overview - All Lines & Parts</div>
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
            <div class="content-header" style="margin-bottom: 16px;">
                <div class="page-title" style="font-size: 28px; font-weight: 700; color: var(--accent-navy); text-shadow: 1px 1px 2px rgba(13, 59, 102, 0.08); border-left: 4px solid var(--accent-blue); padding-left: 12px;">
                    ALPC Dashboard Overview
                </div>
            </div>

            <div class="quick-stats">
                <div class="stat-box">
                    <div class="stat-box-value">5</div>
                    <div class="stat-box-label">Part Types</div>
                </div>
                <div class="stat-box">
                    <div class="stat-box-value">11</div>
                    <div class="stat-box-label">Total LPCs</div>
                </div>
                <div class="stat-box">
                    <div class="stat-box-value">2</div>
                    <div class="stat-box-label">Lines</div>
                </div>
                <div class="stat-box">
                    <div class="stat-box-value">89.4%</div>
                    <div class="stat-box-label">Overall OEE</div>
                </div>
            </div>

            <div class="parts-grid">
                <div class="part-card" id="card-tr" onclick="togglePartCard('tr')">
                    <div class="part-header">
                        <div class="part-title-row">
                            <div class="part-title">Overall (TR)</div>
                            <div class="expand-icon" id="icon-tr">▶</div>
                        </div>
                        <div class="part-type-badge">
                            <span>🏭</span>
                            <span>ALPC TR - Line 1</span>
                        </div>
                    </div>
                    <div class="part-metrics">
                        <div class="metric-item">
                            <div class="metric-label">Performance</div>
                            <div class="metric-value good">88.1%</div>
                        </div>
                        <div class="metric-item">
                            <div class="metric-label">OEE</div>
                            <div class="metric-value good">87.2%</div>
                        </div>
                        <div class="metric-item">
                            <div class="metric-label">Quality</div>
                            <div class="metric-value good">96.5%</div>
                        </div>
                        <div class="metric-item">
                            <div class="metric-label">Availability</div>
                            <div class="metric-value warning">88.0%</div>
                        </div>
                    </div>
                    <div class="info-badge">
                        <span>🔍</span>
                        <span>5 LPCs Available - Click to view</span>
                    </div>
                </div>

                <div class="part-card" id="card-3sz" onclick="togglePartCard('3sz')">
                    <div class="part-header">
                        <div class="part-title-row">
                            <div class="part-title">Overall (3SZ)</div>
                            <div class="expand-icon" id="icon-3sz">▶</div>
                        </div>
                        <div class="part-type-badge">
                            <span>🏭</span>
                            <span>ALPC 3SZ - Line 1</span>
                        </div>
                    </div>
                    <div class="part-metrics">
                        <div class="metric-item">
                            <div class="metric-label">Performance</div>
                            <div class="metric-value good">97.0%</div>
                        </div>
                        <div class="metric-item">
                            <div class="metric-label">OEE</div>
                            <div class="metric-value good">90.5%</div>
                        </div>
                        <div class="metric-item">
                            <div class="metric-label">Quality</div>
                            <div class="metric-value good">95.8%</div>
                        </div>
                        <div class="metric-item">
                            <div class="metric-label">Availability</div>
                            <div class="metric-value good">92.8%</div>
                        </div>
                    </div>
                    <div class="info-badge">
                        <span>🔍</span>
                        <span>1 LPC Available - Click to view</span>
                    </div>
                </div>

                <div class="part-card" id="card-kr" onclick="togglePartCard('kr')">
                    <div class="part-header">
                        <div class="part-title-row">
                            <div class="part-title">Overall (KR)</div>
                            <div class="expand-icon" id="icon-kr">▶</div>
                        </div>
                        <div class="part-type-badge">
                            <span>🏭</span>
                            <span>ALPC KR - Line 1</span>
                        </div>
                    </div>
                    <div class="part-metrics">
                        <div class="metric-item">
                            <div class="metric-label">Performance</div>
                            <div class="metric-value good">94.1%</div>
                        </div>
                        <div class="metric-item">
                            <div class="metric-label">OEE</div>
                            <div class="metric-value good">89.8%</div>
                        </div>
                        <div class="metric-item">
                            <div class="metric-label">Quality</div>
                            <div class="metric-value good">97.1%</div>
                        </div>
                        <div class="metric-item">
                            <div class="metric-label">Availability</div>
                            <div class="metric-value good">91.2%</div>
                        </div>
                    </div>
                    <div class="info-badge">
                        <span>🔍</span>
                        <span>1 LPC Available - Click to view</span>
                    </div>
                </div>

                <div class="part-card" id="card-nr" onclick="togglePartCard('nr')">
                    <div class="part-header">
                        <div class="part-title-row">
                            <div class="part-title">Overall (NR)</div>
                            <div class="expand-icon" id="icon-nr">▶</div>
                        </div>
                        <div class="part-type-badge">
                            <span>🏭</span>
                            <span>ALPC NR - Line 2</span>
                        </div>
                    </div>
                    <div class="part-metrics">
                        <div class="metric-item">
                            <div class="metric-label">Performance</div>
                            <div class="metric-value good">91.1%</div>
                        </div>
                        <div class="metric-item">
                            <div class="metric-label">OEE</div>
                            <div class="metric-value warning">85.3%</div>
                        </div>
                        <div class="metric-item">
                            <div class="metric-label">Quality</div>
                            <div class="metric-value good">94.2%</div>
                        </div>
                        <div class="metric-item">
                            <div class="metric-label">Availability</div>
                            <div class="metric-value warning">89.5%</div>
                        </div>
                    </div>
                    <div class="info-badge">
                        <span>🔍</span>
                        <span>3 LPCs Available - Click to view</span>
                    </div>
                </div>

                <div class="part-card" id="card-wa" onclick="togglePartCard('wa')">
                    <div class="part-header">
                        <div class="part-title-row">
                            <div class="part-title">Overall (WA)</div>
                            <div class="expand-icon" id="icon-wa">▶</div>
                        </div>
                        <div class="part-type-badge">
                            <span>🏭</span>
                            <span>ALPC WA - Line 2</span>
                        </div>
                    </div>
                    <div class="part-metrics">
                        <div class="metric-item">
                            <div class="metric-label">Performance</div>
                            <div class="metric-value good">100%</div>
                        </div>
                        <div class="metric-item">
                            <div class="metric-label">OEE</div>
                            <div class="metric-value good">92.1%</div>
                        </div>
                        <div class="metric-item">
                            <div class="metric-label">Quality</div>
                            <div class="metric-value good">98.2%</div>
                        </div>
                        <div class="metric-item">
                            <div class="metric-label">Availability</div>
                            <div class="metric-value good">94.5%</div>
                        </div>
                    </div>
                    <div class="info-badge">
                        <span>🔍</span>
                        <span>1 LPC Available - Click to view</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TR Modal (5 LPCs) -->
    <div class="modal-overlay" id="modal-tr" onclick="closeModal('tr')">
        <div class="modal-container" onclick="event.stopPropagation()">
            <div class="modal-header">
                <div>
                    <h2 style="margin: 0; font-size: 28px; font-weight: 800;">ALPC TR Line</h2>
                    <p style="margin: 8px 0 0 0; opacity: 0.9; font-size: 14px;">Select LPC to view detailed performance</p>
                </div>
                <button class="modal-close" onclick="closeModal('tr')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="lpc-modal-grid">
                    <!-- LPC 1 -->
                    <div class="lpc-modal-card" onclick="navigateToLPC('tr', '1')">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                            <div class="lpc-modal-name">LPC 1</div>
                            <div class="lpc-modal-status normal">Normal</div>
                        </div>
                        <div class="lpc-modal-metrics">
                            <div class="lpc-modal-metric"><div class="lpc-modal-metric-label">OEE</div><div class="lpc-modal-metric-value good">88.5%</div></div>
                            <div class="lpc-modal-metric"><div class="lpc-modal-metric-label">Quality</div><div class="lpc-modal-metric-value good">97.2%</div></div>
                            <div class="lpc-modal-metric"><div class="lpc-modal-metric-label">Availability</div><div class="lpc-modal-metric-value good">89.8%</div></div>
                            <div class="lpc-modal-metric"><div class="lpc-modal-metric-label">Performance</div><div class="lpc-modal-metric-value good">90.0%</div></div>
                        </div>
                        <div class="info-badge"><span>Click to view details</span></div>
                    </div>
                    <!-- LPC 2 -->
                    <div class="lpc-modal-card" onclick="navigateToLPC('tr', '2')">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                            <div class="lpc-modal-name">LPC 2</div>
                            <div class="lpc-modal-status normal">Normal</div>
                        </div>
                        <div class="lpc-modal-metrics">
                            <div class="lpc-modal-metric"><div class="lpc-modal-metric-label">OEE</div><div class="lpc-modal-metric-value good">90.1%</div></div>
                            <div class="lpc-modal-metric"><div class="lpc-modal-metric-label">Quality</div><div class="lpc-modal-metric-value good">96.8%</div></div>
                            <div class="lpc-modal-metric"><div class="lpc-modal-metric-label">Availability</div><div class="lpc-modal-metric-value good">91.2%</div></div>
                            <div class="lpc-modal-metric"><div class="lpc-modal-metric-label">Performance</div><div class="lpc-modal-metric-value good">95.0%</div></div>
                        </div>
                        <div class="info-badge"><span>Click to view details</span></div>
                    </div>
                    <!-- LPC 3 -->
                    <div class="lpc-modal-card" onclick="navigateToLPC('tr', '3')">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                            <div class="lpc-modal-name">LPC 3</div>
                            <div class="lpc-modal-status warning">Warning</div>
                        </div>
                        <div class="lpc-modal-metrics">
                            <div class="lpc-modal-metric"><div class="lpc-modal-metric-label">OEE</div><div class="lpc-modal-metric-value warning">82.3%</div></div>
                            <div class="lpc-modal-metric"><div class="lpc-modal-metric-label">Quality</div><div class="lpc-modal-metric-value good">95.1%</div></div>
                            <div class="lpc-modal-metric"><div class="lpc-modal-metric-label">Availability</div><div class="lpc-modal-metric-value warning">85.5%</div></div>
                            <div class="lpc-modal-metric"><div class="lpc-modal-metric-label">Performance</div><div class="lpc-modal-metric-value warning">80.0%</div></div>
                        </div>
                        <div class="info-badge"><span>Needs attention</span></div>
                    </div>
                    <!-- LPC 4 -->
                    <div class="lpc-modal-card" onclick="navigateToLPC('tr', '4')">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                            <div class="lpc-modal-name">LPC 4</div>
                            <div class="lpc-modal-status normal">Normal</div>
                        </div>
                        <div class="lpc-modal-metrics">
                            <div class="lpc-modal-metric"><div class="lpc-modal-metric-label">OEE</div><div class="lpc-modal-metric-value good">87.9%</div></div>
                            <div class="lpc-modal-metric"><div class="lpc-modal-metric-label">Quality</div><div class="lpc-modal-metric-value good">96.5%</div></div>
                            <div class="lpc-modal-metric"><div class="lpc-modal-metric-label">Availability</div><div class="lpc-modal-metric-value good">89.2%</div></div>
                            <div class="lpc-modal-metric"><div class="lpc-modal-metric-label">Performance</div><div class="lpc-modal-metric-value good">90.0%</div></div>
                        </div>
                        <div class="info-badge"><span>Click to view details</span></div>
                    </div>
                    <!-- LPC 6 -->
                    <div class="lpc-modal-card" onclick="navigateToLPC('tr', '5')">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                            <div class="lpc-modal-name">LPC 6</div>
                            <div class="lpc-modal-status normal">Normal</div>
                        </div>
                        <div class="lpc-modal-metrics">
                            <div class="lpc-modal-metric"><div class="lpc-modal-metric-label">OEE</div><div class="lpc-modal-metric-value good">86.8%</div></div>
                            <div class="lpc-modal-metric"><div class="lpc-modal-metric-label">Quality</div><div class="lpc-modal-metric-value good">96.9%</div></div>
                            <div class="lpc-modal-metric"><div class="lpc-modal-metric-label">Availability</div><div class="lpc-modal-metric-value good">88.3%</div></div>
                            <div class="lpc-modal-metric"><div class="lpc-modal-metric-label">Performance</div><div class="lpc-modal-metric-value warning">85.7%</div></div>
                        </div>
                        <div class="info-badge"><span>Click to view details</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- WA Modal (1 LPC) -->
    <div class="modal-overlay" id="modal-wa" onclick="closeModal('wa')">
        <div class="modal-container" onclick="event.stopPropagation()">
            <div class="modal-header">
                <div>
                    <h2 style="margin: 0; font-size: 28px; font-weight: 800;">ALPC WA Line</h2>
                    <p style="margin: 8px 0 0 0; opacity: 0.9; font-size: 14px;">Select LPC to view detailed performance</p>
                </div>
                <button class="modal-close" onclick="closeModal('wa')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="lpc-modal-grid">
                    <!-- LPC 11 -->
                    <div class="lpc-modal-card" onclick="navigateToLPC('wa', '1')">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                            <div class="lpc-modal-name">LPC 11</div>
                            <div class="lpc-modal-status normal">Normal</div>
                        </div>
                        <div class="lpc-modal-metrics">
                            <div class="lpc-modal-metric"><div class="lpc-modal-metric-label">OEE</div><div class="lpc-modal-metric-value good">92.1%</div></div>
                            <div class="lpc-modal-metric"><div class="lpc-modal-metric-label">Quality</div><div class="lpc-modal-metric-value good">98.2%</div></div>
                            <div class="lpc-modal-metric"><div class="lpc-modal-metric-label">Availability</div><div class="lpc-modal-metric-value good">94.5%</div></div>
                            <div class="lpc-modal-metric"><div class="lpc-modal-metric-label">Performance</div><div class="lpc-modal-metric-value good">100%</div></div>
                        </div>
                        <div class="info-badge"><span>Click to view details</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- KR Modal (1 LPC) -->
    <div class="modal-overlay" id="modal-kr" onclick="closeModal('kr')">
        <div class="modal-container" onclick="event.stopPropagation()">
            <div class="modal-header">
                <div>
                    <h2 style="margin: 0; font-size: 28px; font-weight: 800;">ALPC KR Line</h2>
                    <p style="margin: 8px 0 0 0; opacity: 0.9; font-size: 14px;">Select LPC to view detailed performance</p>
                </div>
                <button class="modal-close" onclick="closeModal('kr')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="lpc-modal-grid">
                    <!-- LPC 9 -->
                    <div class="lpc-modal-card" onclick="navigateToLPC('kr', '1')">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                            <div class="lpc-modal-name">LPC 9</div>
                            <div class="lpc-modal-status normal">Normal</div>
                        </div>
                        <div class="lpc-modal-metrics">
                            <div class="lpc-modal-metric"><div class="lpc-modal-metric-label">OEE</div><div class="lpc-modal-metric-value good">89.8%</div></div>
                            <div class="lpc-modal-metric"><div class="lpc-modal-metric-label">Quality</div><div class="lpc-modal-metric-value good">97.1%</div></div>
                            <div class="lpc-modal-metric"><div class="lpc-modal-metric-label">Availability</div><div class="lpc-modal-metric-value good">91.2%</div></div>
                            <div class="lpc-modal-metric"><div class="lpc-modal-metric-label">Performance</div><div class="lpc-modal-metric-value good">94.1%</div></div>
                        </div>
                        <div class="info-badge"><span>Click to view details</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3SZ Modal (1 LPC) -->
    <div class="modal-overlay" id="modal-3sz" onclick="closeModal('3sz')">
        <div class="modal-container" onclick="event.stopPropagation()">
            <div class="modal-header">
                <div>
                    <h2 style="margin: 0; font-size: 28px; font-weight: 800;">ALPC 3SZ Line</h2>
                    <p style="margin: 8px 0 0 0; opacity: 0.9; font-size: 14px;">Select LPC to view detailed performance</p>
                </div>
                <button class="modal-close" onclick="closeModal('3sz')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="lpc-modal-grid">
                    <!-- LPC 9 -->
                    <div class="lpc-modal-card" onclick="navigateToLPC('3sz', '1')">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                            <div class="lpc-modal-name">LPC 9</div>
                            <div class="lpc-modal-status normal">Normal</div>
                        </div>
                        <div class="lpc-modal-metrics">
                            <div class="lpc-modal-metric"><div class="lpc-modal-metric-label">OEE</div><div class="lpc-modal-metric-value good">90.5%</div></div>
                            <div class="lpc-modal-metric"><div class="lpc-modal-metric-label">Quality</div><div class="lpc-modal-metric-value good">95.8%</div></div>
                            <div class="lpc-modal-metric"><div class="lpc-modal-metric-label">Availability</div><div class="lpc-modal-metric-value good">92.8%</div></div>
                            <div class="lpc-modal-metric"><div class="lpc-modal-metric-label">Performance</div><div class="lpc-modal-metric-value good">97.0%</div></div>
                        </div>
                        <div class="info-badge"><span>Click to view details</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- NR Modal (3 LPCs) -->
    <div class="modal-overlay" id="modal-nr" onclick="closeModal('nr')">
        <div class="modal-container" onclick="event.stopPropagation()">
            <div class="modal-header">
                <div>
                    <h2 style="margin: 0; font-size: 28px; font-weight: 800;">ALPC NR Line</h2>
                    <p style="margin: 8px 0 0 0; opacity: 0.9; font-size: 14px;">Select LPC to view detailed performance</p>
                </div>
                <button class="modal-close" onclick="closeModal('nr')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="lpc-modal-grid">
                    <!-- LPC 12 -->
                    <div class="lpc-modal-card" onclick="navigateToLPC('nr', '1')">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                            <div class="lpc-modal-name">LPC 12</div>
                            <div class="lpc-modal-status normal">Normal</div>
                        </div>
                        <div class="lpc-modal-metrics">
                            <div class="lpc-modal-metric"><div class="lpc-modal-metric-label">OEE</div><div class="lpc-modal-metric-value good">87.2%</div></div>
                            <div class="lpc-modal-metric"><div class="lpc-modal-metric-label">Quality</div><div class="lpc-modal-metric-value good">95.8%</div></div>
                            <div class="lpc-modal-metric"><div class="lpc-modal-metric-label">Availability</div><div class="lpc-modal-metric-value good">90.1%</div></div>
                            <div class="lpc-modal-metric"><div class="lpc-modal-metric-label">Performance</div><div class="lpc-modal-metric-value good">91.2%</div></div>
                        </div>
                        <div class="info-badge"><span>Click to view details</span></div>
                    </div>
                    <!-- LPC 13 -->
                    <div class="lpc-modal-card" onclick="navigateToLPC('nr', '2')">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                            <div class="lpc-modal-name">LPC 13</div>
                            <div class="lpc-modal-status warning">Warning</div>
                        </div>
                        <div class="lpc-modal-metrics">
                            <div class="lpc-modal-metric"><div class="lpc-modal-metric-label">OEE</div><div class="lpc-modal-metric-value warning">81.8%</div></div>
                            <div class="lpc-modal-metric"><div class="lpc-modal-metric-label">Quality</div><div class="lpc-modal-metric-value good">92.5%</div></div>
                            <div class="lpc-modal-metric"><div class="lpc-modal-metric-label">Availability</div><div class="lpc-modal-metric-value warning">87.2%</div></div>
                            <div class="lpc-modal-metric"><div class="lpc-modal-metric-label">Performance</div><div class="lpc-modal-metric-value warning">85.3%</div></div>
                        </div>
                        <div class="info-badge"><span>Needs attention</span></div>
                    </div>
                    <!-- LPC 14 -->
                    <div class="lpc-modal-card" onclick="navigateToLPC('nr', '3')">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                            <div class="lpc-modal-name">LPC 14</div>
                            <div class="lpc-modal-status normal">Normal</div>
                        </div>
                        <div class="lpc-modal-metrics">
                            <div class="lpc-modal-metric"><div class="lpc-modal-metric-label">OEE</div><div class="lpc-modal-metric-value good">86.9%</div></div>
                            <div class="lpc-modal-metric"><div class="lpc-modal-metric-label">Quality</div><div class="lpc-modal-metric-value good">94.5%</div></div>
                            <div class="lpc-modal-metric"><div class="lpc-modal-metric-label">Availability</div><div class="lpc-modal-metric-value good">91.2%</div></div>
                            <div class="lpc-modal-metric"><div class="lpc-modal-metric-label">Performance</div><div class="lpc-modal-metric-value good">90.0%</div></div>
                        </div>
                        <div class="info-badge"><span>Click to view details</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/main.js') }}"></script>
    <script>
        // Mapping route names for JS
        const pageRoutes = {
            'tr': "{{ route('general-alpc-tr') }}",
            'wa': "{{ route('general-alpc-wa') }}",
            '3sz': "{{ route('general-alpc-3sz') }}",
            'kr': "{{ route('general-alpc-kr') }}",
            'nr': "{{ route('general-alpc-nr') }}"
        };

        // Open modal popup
        function openModal(partType) {
            const modal = document.getElementById('modal-' + partType);
            if (modal) {
                modal.classList.add('show');
                document.body.style.overflow = 'hidden'; 
            }
        }

        // Close modal popup
        function closeModal(partType) {
            const modal = document.getElementById('modal-' + partType);
            if (modal) {
                modal.classList.remove('show');
                document.body.style.overflow = ''; 
            }
        }

        // Toggle part card expansion 
        function togglePartCard(partType) {
            openModal(partType);
        }

        // Navigate to specific LPC page using Laravel Routes
        function navigateToLPC(partType, lpcNumber) {
            if (pageRoutes[partType]) {
                window.location.href = `${pageRoutes[partType]}?lpc=${lpcNumber}`;
            } else {
                console.error("Route not found for type: " + partType);
            }
        }

        // Close modal with ESC key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const openModals = document.querySelectorAll('.modal-overlay.show');
                openModals.forEach(modal => {
                    modal.classList.remove('show');
                });
                document.body.style.overflow = '';
            }
        });
    </script>
</body>
</html>