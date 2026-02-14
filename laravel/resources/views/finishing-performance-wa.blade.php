<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/daihatsu-logo.png') }}">
    <title>Finishing 1 Performance - ALPC WA</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
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
                <span class="monitoring-text">Finishing 1 Performance</span>
                <div class="monitoring-subtitle">ALPC WA - Real-Time Monitoring</div>
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
<style>
    .performance-content { padding: 15px; }
    .status-bar { background: white; padding: 15px 20px; border-radius: 12px; margin-bottom: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); display: flex; justify-content: space-between; align-items: center; border-left: 4px solid #27ae60; }
    .status-bar.warning { border-left-color: #f39c12; }
    .status-bar.critical { border-left-color: #e74c3c; }
    .machine-status { display: flex; align-items: center; gap: 10px; }
    .status-indicator { width: 12px; height: 12px; border-radius: 50%; background: #27ae60; animation: pulse-status 2s ease-in-out infinite; }
    .status-indicator.warning { background: #f39c12; }
    .status-indicator.critical { background: #e74c3c; }
    @keyframes pulse-status { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }
    .machine-name { font-size: 16px; font-weight: 600; color: var(--text-dark); }
    .machine-state { font-size: 13px; color: var(--text-light); }
    .last-update-info { text-align: right; }
    .last-update-label { font-size: 11px; color: var(--text-light); text-transform: uppercase; }
    .last-update-time { font-size: 14px; font-weight: 600; color: var(--text-dark); }
    .kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px; margin-bottom: 15px; }
    .kpi-card { background: white; padding: 16px; border-radius: 10px; box-shadow: 0 2px 6px rgba(0,0,0,0.05); border-left: 3px solid #3498db; transition: transform 0.2s; }
    .kpi-card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    .kpi-card.success { border-left-color: #27ae60; }
    .kpi-card.warning { border-left-color: #f39c12; }
    .kpi-card.danger { border-left-color: #e74c3c; }
    .kpi-label { font-size: 11px; color: var(--text-light); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; font-weight: 600; }
    .kpi-value { font-size: 28px; font-weight: 700; color: var(--text-dark); line-height: 1; }
    .kpi-unit { font-size: 14px; color: var(--text-light); margin-left: 4px; }
    .kpi-trend { font-size: 11px; margin-top: 6px; display: flex; align-items: center; gap: 4px; }
    .kpi-trend.up { color: #27ae60; }
    .kpi-trend.down { color: #e74c3c; }
    .charts-row { display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-bottom: 15px; }
    .chart-container { background: white; padding: 18px; border-radius: 10px; box-shadow: 0 2px 6px rgba(0,0,0,0.05); }
    .chart-container.full-width { grid-column: 1 / -1; }
    .chart-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
    .chart-title { font-size: 15px; font-weight: 600; color: var(--text-dark); }
    .chart-filters { display: flex; gap: 8px; }
    .filter-btn { padding: 5px 12px; border: 1px solid var(--gray-border); background: white; border-radius: 6px; font-size: 11px; cursor: pointer; transition: all 0.2s; color: var(--text-dark); }
    .filter-btn:hover { border-color: var(--accent-blue); background: var(--primary-light); }
    .filter-btn.active { background: var(--accent-blue); color: white; border-color: var(--accent-blue); }
    .chart-canvas { position: relative; height: 280px; }
    .chart-canvas.tall { height: 350px; }
    .process-table { background: white; border-radius: 10px; padding: 18px; box-shadow: 0 2px 6px rgba(0,0,0,0.05); margin-bottom: 15px; }
    .table-header { font-size: 15px; font-weight: 600; color: var(--text-dark); margin-bottom: 15px; }
    table { width: 100%; border-collapse: collapse; }
    th { background: var(--gray-light); padding: 10px; text-align: left; font-size: 12px; font-weight: 600; color: var(--text-dark); text-transform: uppercase; letter-spacing: 0.5px; border: 1px solid var(--gray-border); }
    td { padding: 10px; font-size: 13px; border: 1px solid var(--gray-border); }
    tr:hover { background: var(--gray-light); }
    .param-name { font-weight: 600; color: var(--text-dark); }
    .param-current { font-weight: 700; font-size: 14px; }
    .param-target { color: var(--text-light); }
    .defect-badge { display: inline-block; padding: 3px 10px; border-radius: 12px; font-size: 10px; font-weight: 600; text-transform: uppercase; }
    .defect-badge.scratch { background: #ffe0e0; color: #c62828; }
    .defect-badge.coating { background: #fff3e0; color: #e65100; }
    .defect-badge.contamination { background: #fff9c4; color: #f57f17; }
    .defect-badge.none { background: #e8f5e9; color: #2e7d32; }
    @media (max-width: 1200px) { .charts-row { grid-template-columns: 1fr; } }
    @media (max-width: 768px) { .kpi-grid { grid-template-columns: repeat(2, 1fr); } .status-bar { flex-direction: column; gap: 10px; text-align: center; } .last-update-info { text-align: center; } }
</style>

<div class="performance-content">
    <div class="status-bar" id="statusBar">
        <div class="machine-status">
            <div class="status-indicator" id="statusIndicator"></div>
            <div>
                <div class="machine-name">Finishing Machine 1 - WA Line</div>
                <div class="machine-state" id="machineState">Running - Normal Operation</div>
            </div>
        </div>
        <div class="last-update-info">
            <div class="last-update-label">Last Update</div>
            <div class="last-update-time" id="lastUpdate">--:--:--</div>
        </div>
    </div>

    <div class="kpi-grid">
        <div class="kpi-card success">
            <div class="kpi-label">Parts Processed</div>
            <div class="kpi-value" id="parts-processed">0</div>
            <div class="kpi-trend up" id="parts-trend"><span>↑</span><span>+12 vs yesterday</span></div>
        </div>
        <div class="kpi-card">
            <div class="kpi-label">Cycle Time</div>
            <div class="kpi-value"><span id="cycle-time">0</span><span class="kpi-unit">sec</span></div>
            <div class="kpi-trend" id="cycle-trend"><span>Target: 45s</span></div>
        </div>
        <div class="kpi-card success">
            <div class="kpi-label">First Pass Yield</div>
            <div class="kpi-value"><span id="fpy">0</span><span class="kpi-unit">%</span></div>
            <div class="kpi-trend up" id="fpy-trend"><span>↑ 2.3%</span></div>
        </div>
        <div class="kpi-card success">
            <div class="kpi-label">Machine Uptime</div>
            <div class="kpi-value"><span id="uptime">0</span><span class="kpi-unit">%</span></div>
            <div class="kpi-trend" id="uptime-trend"><span>8h 42m running</span></div>
        </div>
        <div class="kpi-card">
            <div class="kpi-label">Oven Temperature</div>
            <div class="kpi-value"><span id="oven-temp">0</span><span class="kpi-unit">°C</span></div>
            <div class="kpi-trend" id="temp-trend"><span>Target: 180-200°C</span></div>
        </div>
        <div class="kpi-card warning">
            <div class="kpi-label">Defect Rate</div>
            <div class="kpi-value"><span id="defect-rate">0</span><span class="kpi-unit">%</span></div>
            <div class="kpi-trend down" id="defect-trend"><span>↓ 0.8%</span></div>
        </div>
    </div>

    <div class="chart-container full-width">
        <div class="chart-header">
            <div class="chart-title">Temperature Trend (Real-time)</div>
            <div class="chart-filters">
                <button class="filter-btn active" data-metric="oven">Oven</button>
                <button class="filter-btn" data-metric="cooling">Cooling</button>
                <button class="filter-btn" data-metric="ambient">Ambient</button>
                <button class="filter-btn" data-metric="all">All</button>
            </div>
        </div>
        <div class="chart-canvas tall"><canvas id="temperatureTrendChart"></canvas></div>
    </div>

    <div class="charts-row">
        <div class="chart-container">
            <div class="chart-header"><div class="chart-title">Pressure & Speed Monitoring</div></div>
            <div class="chart-canvas"><canvas id="pressureSpeedChart"></canvas></div>
        </div>
        <div class="chart-container">
            <div class="chart-header"><div class="chart-title">Cycle Time & Throughput</div></div>
            <div class="chart-canvas"><canvas id="cycleTimeChart"></canvas></div>
        </div>
    </div>

    <div class="charts-row">
        <div class="chart-container">
            <div class="chart-header"><div class="chart-title">Defect Distribution</div></div>
            <div class="chart-canvas"><canvas id="defectDistributionChart"></canvas></div>
        </div>
        <div class="chart-container">
            <div class="chart-header"><div class="chart-title">Hourly Performance Pattern</div></div>
            <div class="chart-canvas"><canvas id="hourlyPerformanceChart"></canvas></div>
        </div>
    </div>

    <div class="process-table">
        <div class="table-header">Current Process Parameters</div>
        <table>
            <thead>
                <tr>
                    <th>Parameter</th>
                    <th>Current Value</th>
                    <th>Target Range</th>
                    <th>Unit</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="processTableBody">
                <tr>
                    <td class="param-name">Oven Temperature</td>
                    <td class="param-current" id="param-oven-temp">--</td>
                    <td class="param-target">180 - 200</td>
                    <td>°C</td>
                    <td><span class="status-badge status-normal">Normal</span></td>
                </tr>
                <tr>
                    <td class="param-name">Cooling Temperature</td>
                    <td class="param-current" id="param-cooling-temp">--</td>
                    <td class="param-target">25 - 35</td>
                    <td>°C</td>
                    <td><span class="status-badge status-normal">Normal</span></td>
                </tr>
                <tr>
                    <td class="param-name">Coating Pressure</td>
                    <td class="param-current" id="param-pressure">--</td>
                    <td class="param-target">2.5 - 3.5</td>
                    <td>bar</td>
                    <td><span class="status-badge status-normal">Normal</span></td>
                </tr>
                <tr>
                    <td class="param-name">Conveyor Speed</td>
                    <td class="param-current" id="param-speed">--</td>
                    <td class="param-target">1.2 - 1.5</td>
                    <td>m/min</td>
                    <td><span class="status-badge status-normal">Normal</span></td>
                </tr>
                <tr>
                    <td class="param-name">Humidity Level</td>
                    <td class="param-current" id="param-humidity">--</td>
                    <td class="param-target">40 - 60</td>
                    <td>%</td>
                    <td><span class="status-badge status-normal">Normal</span></td>
                </tr>
                <tr>
                    <td class="param-name">Vibration Level</td>
                    <td class="param-current" id="param-vibration">--</td>
                    <td class="param-target">&lt; 5</td>
                    <td>mm/s</td>
                    <td><span class="status-badge status-normal">Normal</span></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="process-table">
        <div class="table-header">Recent Parts Processed</div>
        <table>
            <thead>
                <tr>
                    <th>Time</th>
                    <th>Batch ID</th>
                    <th>Cycle Time</th>
                    <th>Coating Thickness</th>
                    <th>Surface Quality</th>
                    <th>Status</th>
                    <th>Defect Type</th>
                </tr>
            </thead>
            <tbody id="partsLogBody"></tbody>
        </table>
    </div>
</div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="{{ asset('js/finishing-performance-wa.js') }}"></script>
</body>
</html>
