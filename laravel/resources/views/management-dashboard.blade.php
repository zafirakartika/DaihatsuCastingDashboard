<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/daihatsu-logo.png') }}">
    <title>Management Dashboard - SMART Factory</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard-components.css') }}">
    <style>
        .main-content { padding: 20px !important; }
        .content-header { margin-bottom: 16px !important; padding-bottom: 12px !important; }
        .page-title { font-size: 24px !important; }
        .chart-container-wrapper { padding: 16px !important; margin-bottom: 16px !important; }
        .section-header { margin: 16px 0 12px 0 !important; }
        .section-title { font-size: 18px !important; }
        .data-table-wrapper { padding: 16px !important; }
        .chart-title { font-size: 16px !important; }
        .chart-subtitle { font-size: 12px !important; }
    </style>
</head>
<body>
    <div class="top-header">
        <div class="logo-section">
            <img src="{{ asset('assets/images/daihatsu-logo.png') }}" alt="Daihatsu Logo" class="company-logo">
        </div>
        <div class="header-center">
            <div class="monitoring-title">
                <span class="monitoring-text">Management Dashboard</span>
                <div class="monitoring-subtitle">Production Overview &amp; Key Performance Indicators</div>
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
        <div class="main-content" style="margin-left: 0; width: 100%;">
            <div class="content-header">
                <div class="page-title-wrapper">
                    <div class="page-title">Management Dashboard</div>
                </div>
                <div class="page-controls">
                    <button onclick="window.location.href='{{ route('home') }}'"
                            style="display: flex; align-items: center; gap: 8px; padding: 8px 16px; background: linear-gradient(135deg, #4a6fa5, #0d3b66); color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.3s; box-shadow: 0 2px 8px rgba(74,111,165,0.2);">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M19 12H5M12 19l-7-7 7-7"/>
                        </svg>
                        Back
                    </button>
                    <div class="filter-group">
                        <select id="dateRange" class="filter-select">
                            <option value="today">Today</option>
                            <option value="week" selected>Last 7 Days</option>
                            <option value="month">Last 30 Days</option>
                            <option value="custom">Custom Range</option>
                        </select>
                    </div>
                    <button class="export-btn" onclick="exportDashboard()">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="7 10 12 15 17 10"></polyline>
                            <line x1="12" y1="15" x2="12" y2="3"></line>
                        </svg>
                        Export
                    </button>
                </div>
            </div>

            <div class="dashboard-content-area">
                <!-- KPI Metrics Grid -->
                <div class="metric-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
                    <div class="metric-card metric-good" id="metric-total-production">
                        <div class="metric-header"><span class="metric-icon">📦</span><span class="metric-title">Today Production</span></div>
                        <div class="metric-body"><div class="metric-value-wrapper"><span class="metric-value">2,847</span><span class="metric-unit"> parts</span></div><div class="metric-trend trend-up"><svg class="trend-icon" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg><span class="trend-text">+12%</span></div></div>
                    </div>
                    <div class="metric-card metric-good" id="metric-oee">
                        <div class="metric-header"><span class="metric-icon">⚡</span><span class="metric-title">Overall OEE</span></div>
                        <div class="metric-body"><div class="metric-value-wrapper"><span class="metric-value">89.4</span><span class="metric-unit">%</span></div><div class="metric-trend trend-up"><svg class="trend-icon" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg><span class="trend-text">+2.4%</span></div></div>
                    </div>
                    <div class="metric-card metric-good" id="metric-quality">
                        <div class="metric-header"><span class="metric-icon">✓</span><span class="metric-title">Quality Rate</span></div>
                        <div class="metric-body"><div class="metric-value-wrapper"><span class="metric-value">96.3</span><span class="metric-unit">%</span></div><div class="metric-trend trend-neutral"><svg class="trend-icon" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line></svg><span class="trend-text">0%</span></div></div>
                    </div>
                    <div class="metric-card metric-normal" id="metric-active-lines">
                        <div class="metric-header"><span class="metric-icon">🏭</span><span class="metric-title">Active Lines</span></div>
                        <div class="metric-body"><div class="metric-value-wrapper"><span class="metric-value">11</span><span class="metric-unit">/11</span></div></div>
                    </div>
                    <div class="metric-card metric-normal" id="metric-avg-temp">
                        <div class="metric-header"><span class="metric-icon">🌡️</span><span class="metric-title">Avg Temperature</span></div>
                        <div class="metric-body"><div class="metric-value-wrapper"><span class="metric-value">506</span><span class="metric-unit">°C</span></div><div class="metric-trend trend-neutral"><svg class="trend-icon" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line></svg><span class="trend-text">±1°C</span></div></div>
                    </div>
                    <div class="metric-card metric-normal" id="metric-total-records">
                        <div class="metric-header"><span class="metric-icon">📊</span><span class="metric-title">Total Records</span></div>
                        <div class="metric-body"><div class="metric-value-wrapper"><span class="metric-value">0</span><span class="metric-unit"> shots</span></div><div class="metric-trend trend-up"><svg class="trend-icon" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg><span class="trend-text">Loading...</span></div></div>
                    </div>
                </div>

                <!-- Production Trend Chart -->
                <div class="chart-container-wrapper">
                    <div class="chart-header">
                        <div class="chart-title-section">
                            <h3 class="chart-title">Production Trends</h3>
                            <p class="chart-subtitle">WA vs TR - Last 7 Days</p>
                        </div>
                        <div class="chart-legend" id="productionTrendChart-legend"></div>
                    </div>
                    <div class="chart-canvas-area" style="position: relative; height: 300px; width: 100%;">
                        <canvas id="productionTrendChart"></canvas>
                    </div>
                </div>

                <div class="section-header" style="margin-top: 20px;">
                    <h2 class="section-title">Part Performance Comparison</h2>
                </div>

                <div class="comparison-grid" style="gap: 16px; margin-bottom: 20px;">
                    <div class="chart-container-wrapper">
                        <div class="chart-header"><div class="chart-title-section"><h3 class="chart-title">WA Performance Distribution</h3><p class="chart-subtitle">Quality Status Breakdown</p></div></div>
                        <div class="chart-canvas-area" style="position: relative; height: 250px; width: 100%;"><canvas id="waPerformanceChart"></canvas></div>
                    </div>
                    <div class="chart-container-wrapper">
                        <div class="chart-header"><div class="chart-title-section"><h3 class="chart-title">TR Performance Distribution</h3><p class="chart-subtitle">Quality Status Breakdown</p></div></div>
                        <div class="chart-canvas-area" style="position: relative; height: 250px; width: 100%;"><canvas id="trPerformanceChart"></canvas></div>
                    </div>
                </div>

                <!-- Temperature Comparison Chart -->
                <div class="chart-container-wrapper">
                    <div class="chart-header">
                        <div class="chart-title-section">
                            <h3 class="chart-title">Temperature Trends Comparison</h3>
                            <p class="chart-subtitle">Average temperatures across all sensors</p>
                        </div>
                        <div class="chart-filter-buttons" id="temperatureComparisonChart-filters">
                            <button class="chart-filter-btn active" data-filter="All" onclick="filterChart('temperatureComparisonChart', 'All')">All</button>
                            <button class="chart-filter-btn" data-filter="WA" onclick="filterChart('temperatureComparisonChart', 'WA')">WA</button>
                            <button class="chart-filter-btn" data-filter="TR" onclick="filterChart('temperatureComparisonChart', 'TR')">TR</button>
                        </div>
                    </div>
                    <div class="chart-canvas-area" style="position: relative; height: 280px; width: 100%;"><canvas id="temperatureComparisonChart"></canvas></div>
                    <div class="chart-legend" id="temperatureComparisonChart-legend"></div>
                </div>

                <div class="section-header" style="margin-top: 20px;">
                    <h2 class="section-title">Recent Production Records</h2>
                </div>

                <div class="data-table-wrapper">
                    <table class="data-table" id="productionRecordsTable">
                        <thead>
                            <tr>
                                <th>Shot No</th><th>Part</th><th>Timestamp</th><th>Avg Temp (°C)</th><th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="productionTableBody">
                            <tr><td colspan="5" style="text-align: center; padding: 40px;"><div style="color: #999; font-size: 14px;">Loading data...</div></td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/main.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="{{ asset('js/management-dashboard.js') }}"></script>
</body>
</html>
