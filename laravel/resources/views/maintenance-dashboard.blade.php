<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/daihatsu-logo.png') }}">
    <title>Maintenance Dashboard - SMART Factory</title>
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
                <span class="monitoring-text">Maintenance Dashboard</span>
                <div class="monitoring-subtitle">Energy Consumption &amp; Resource Monitoring</div>
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
                    <div class="page-title">Maintenance Dashboard</div>
                </div>
                <div class="page-controls">
                    <button onclick="window.location.href='{{ route('home') }}'"
                            style="display: flex; align-items: center; gap: 8px; padding: 8px 16px; background: linear-gradient(135deg, #ec4899, #f43f5e); color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.3s; box-shadow: 0 2px 8px rgba(236,72,153,0.2);">
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
                <!-- Energy KPI Cards -->
                <div class="metric-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
                    <div class="metric-card metric-good" id="metric-total-energy">
                        <div class="metric-header"><span class="metric-icon">⚡</span><span class="metric-title">Total Energy Today</span></div>
                        <div class="metric-body"><div class="metric-value-wrapper"><span class="metric-value">4,258</span><span class="metric-unit"> kWh</span></div><div class="metric-trend trend-down"><svg class="trend-icon" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline><polyline points="17 18 23 18 23 12"></polyline></svg><span class="trend-text">-8%</span></div></div>
                    </div>
                    <div class="metric-card metric-good" id="metric-electricity">
                        <div class="metric-header"><span class="metric-icon">💡</span><span class="metric-title">Electricity</span></div>
                        <div class="metric-body"><div class="metric-value-wrapper"><span class="metric-value">3,542</span><span class="metric-unit"> kWh</span></div><div class="metric-trend trend-down"><svg class="trend-icon" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline><polyline points="17 18 23 18 23 12"></polyline></svg><span class="trend-text">-5%</span></div></div>
                    </div>
                    <div class="metric-card metric-normal" id="metric-gas">
                        <div class="metric-header"><span class="metric-icon">🔥</span><span class="metric-title">Gas Consumption</span></div>
                        <div class="metric-body"><div class="metric-value-wrapper"><span class="metric-value">847</span><span class="metric-unit"> m³</span></div><div class="metric-trend trend-neutral"><svg class="trend-icon" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line></svg><span class="trend-text">±0%</span></div></div>
                    </div>
                    <div class="metric-card metric-warning" id="metric-water">
                        <div class="metric-header"><span class="metric-icon">💧</span><span class="metric-title">Water Usage</span></div>
                        <div class="metric-body"><div class="metric-value-wrapper"><span class="metric-value">1,245</span><span class="metric-unit"> L</span></div><div class="metric-trend trend-up"><svg class="trend-icon" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg><span class="trend-text">+3%</span></div></div>
                    </div>
                    <div class="metric-card metric-normal" id="metric-compressed-air">
                        <div class="metric-header"><span class="metric-icon">💨</span><span class="metric-title">Compressed Air</span></div>
                        <div class="metric-body"><div class="metric-value-wrapper"><span class="metric-value">2,156</span><span class="metric-unit"> m³</span></div><div class="metric-trend trend-neutral"><svg class="trend-icon" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line></svg><span class="trend-text">±1%</span></div></div>
                    </div>
                    <div class="metric-card metric-good" id="metric-cost-savings">
                        <div class="metric-header"><span class="metric-icon">💰</span><span class="metric-title">Cost Savings</span></div>
                        <div class="metric-body"><div class="metric-value-wrapper"><span class="metric-value">12.4</span><span class="metric-unit">%</span></div><div class="metric-trend trend-up"><svg class="trend-icon" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg><span class="trend-text">+2.4%</span></div></div>
                    </div>
                </div>

                <div class="section-header" style="margin-top: 20px;"><h2 class="section-title">Consumption Trends Analysis</h2></div>

                <div class="comparison-grid" style="gap: 16px; margin-bottom: 20px;">
                    <div class="chart-container-wrapper">
                        <div class="chart-header"><div class="chart-title-section"><h3 class="chart-title">Daily Consumption</h3><p class="chart-subtitle">Last 7 Days</p></div><div class="chart-legend" id="energyTrendChart-legend"></div></div>
                        <div class="chart-canvas-area" style="position: relative; height: 280px; width: 100%;"><canvas id="energyTrendChart"></canvas></div>
                    </div>
                    <div class="chart-container-wrapper">
                        <div class="chart-header">
                            <div class="chart-title-section"><h3 class="chart-title">Hourly Pattern</h3><p class="chart-subtitle">Today (24 hours)</p></div>
                            <div class="chart-filter-buttons" id="hourlyPatternChart-filters">
                                <button class="chart-filter-btn active" data-filter="All" onclick="filterChart('hourlyPatternChart', 'All')">All</button>
                                <button class="chart-filter-btn" data-filter="Electricity" onclick="filterChart('hourlyPatternChart', 'Electricity')">Electricity</button>
                                <button class="chart-filter-btn" data-filter="Gas" onclick="filterChart('hourlyPatternChart', 'Gas')">Gas</button>
                                <button class="chart-filter-btn" data-filter="Water" onclick="filterChart('hourlyPatternChart', 'Water')">Water</button>
                            </div>
                        </div>
                        <div class="chart-canvas-area" style="position: relative; height: 280px; width: 100%;"><canvas id="hourlyPatternChart"></canvas></div>
                    </div>
                </div>

                <div class="section-header" style="margin-top: 20px;"><h2 class="section-title">Energy Distribution &amp; Cost Analysis</h2></div>

                <div class="comparison-grid" style="gap: 16px; margin-bottom: 20px;">
                    <div class="chart-container-wrapper">
                        <div class="chart-header"><div class="chart-title-section"><h3 class="chart-title">Energy Distribution</h3><p class="chart-subtitle">Breakdown by Resource Type</p></div></div>
                        <div class="chart-canvas-area" style="position: relative; height: 250px; width: 100%;"><canvas id="energyDistributionChart"></canvas></div>
                    </div>
                    <div class="chart-container-wrapper">
                        <div class="chart-header"><div class="chart-title-section"><h3 class="chart-title">Cost Analysis</h3><p class="chart-subtitle">Monthly Comparison (IDR)</p></div></div>
                        <div class="chart-canvas-area" style="position: relative; height: 250px; width: 100%;"><canvas id="costAnalysisChart"></canvas></div>
                    </div>
                </div>

                <div class="section-header" style="margin-top: 20px;"><h2 class="section-title">Real-time Resource Consumption</h2></div>

                <div class="data-table-wrapper">
                    <table class="data-table" id="resourceConsumptionTable">
                        <thead>
                            <tr><th>Time</th><th>Electricity (kWh)</th><th>Gas (m³)</th><th>Water (L)</th><th>Compressed Air (m³)</th><th>Status</th></tr>
                        </thead>
                        <tbody id="resourceTableBody">
                            <tr><td colspan="6" style="text-align: center; padding: 40px;"><div style="color: #999; font-size: 14px;">Loading data...</div></td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/main.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="{{ asset('js/maintenance-dashboard.js') }}"></script>
</body>
</html>
