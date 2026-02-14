<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/daihatsu-logo.png') }}">
    <title>Quality Dashboard - SMART Factory</title>
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
                <span class="monitoring-text">Quality Dashboard</span>
                <div class="monitoring-subtitle">Rejection Tracking &amp; Quality Control</div>
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
                    <div class="page-title">Quality Dashboard</div>
                </div>
                <div class="page-controls">
                    <button onclick="window.location.href='{{ route('home') }}'"
                            style="display: flex; align-items: center; gap: 8px; padding: 8px 16px; background: linear-gradient(135deg, #10b981, #14b8a6); color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.3s; box-shadow: 0 2px 8px rgba(16,185,129,0.2);">
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
                <!-- Quality KPI Cards -->
                <div class="metric-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
                    <div class="metric-card metric-normal" id="metric-total-production">
                        <div class="metric-header"><span class="metric-icon"></span><span class="metric-title">Total Production</span></div>
                        <div class="metric-body"><div class="metric-value-wrapper"><span class="metric-value">2,847</span><span class="metric-unit"> parts</span></div><div class="metric-trend trend-up"><svg class="trend-icon" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg><span class="trend-text">+5%</span></div></div>
                    </div>
                    <div class="metric-card metric-good" id="metric-total-rejections">
                        <div class="metric-header"><span class="metric-icon"></span><span class="metric-title">Total Rejections</span></div>
                        <div class="metric-body"><div class="metric-value-wrapper"><span class="metric-value">47</span><span class="metric-unit"> parts</span></div><div class="metric-trend trend-down"><svg class="trend-icon" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline><polyline points="17 18 23 18 23 12"></polyline></svg><span class="trend-text">-8%</span></div></div>
                    </div>
                    <div class="metric-card metric-good" id="metric-rejection-rate">
                        <div class="metric-header"><span class="metric-icon"></span><span class="metric-title">Rejection Rate</span></div>
                        <div class="metric-body"><div class="metric-value-wrapper"><span class="metric-value">1.65</span><span class="metric-unit">%</span></div><div class="metric-trend trend-down"><svg class="trend-icon" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline><polyline points="17 18 23 18 23 12"></polyline></svg><span class="trend-text">-0.3%</span></div></div>
                    </div>
                    <div class="metric-card metric-good" id="metric-external-rejection">
                        <div class="metric-header"><span class="metric-icon"></span><span class="metric-title">External Rejection</span></div>
                        <div class="metric-body"><div class="metric-value-wrapper"><span class="metric-value">12</span><span class="metric-unit"> parts</span></div><div class="metric-trend trend-down"><svg class="trend-icon" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline><polyline points="17 18 23 18 23 12"></polyline></svg><span class="trend-text">-2</span></div></div>
                    </div>
                    <div class="metric-card metric-normal" id="metric-internal-rejection">
                        <div class="metric-header"><span class="metric-icon"></span><span class="metric-title">Internal Rejection</span></div>
                        <div class="metric-body"><div class="metric-value-wrapper"><span class="metric-value">35</span><span class="metric-unit"> parts</span></div><div class="metric-trend trend-neutral"><svg class="trend-icon" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line></svg><span class="trend-text">±0</span></div></div>
                    </div>
                    <div class="metric-card metric-good" id="metric-fpy">
                        <div class="metric-header"><span class="metric-icon"></span><span class="metric-title">First Pass Yield</span></div>
                        <div class="metric-body"><div class="metric-value-wrapper"><span class="metric-value">98.35</span><span class="metric-unit">%</span></div><div class="metric-trend trend-up"><svg class="trend-icon" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg><span class="trend-text">+0.3%</span></div></div>
                    </div>
                </div>

                <div class="section-header" style="margin-top: 20px;"><h2 class="section-title">Rejection Trends Analysis</h2></div>

                <div class="comparison-grid" style="gap: 16px; margin-bottom: 20px;">
                    <div class="chart-container-wrapper">
                        <div class="chart-header"><div class="chart-title-section"><h3 class="chart-title">Daily Rejection Trend</h3><p class="chart-subtitle">Last 7 Days</p></div><div class="chart-legend" id="rejectionTrendChart-legend"></div></div>
                        <div class="chart-canvas-area" style="position: relative; height: 280px; width: 100%;"><canvas id="rejectionTrendChart"></canvas></div>
                    </div>
                    <div class="chart-container-wrapper">
                        <div class="chart-header">
                            <div class="chart-title-section"><h3 class="chart-title">Hourly Pattern</h3><p class="chart-subtitle">Today (24 hours)</p></div>
                            <div class="chart-filter-buttons" id="hourlyRejectionChart-filters">
                                <button class="chart-filter-btn active" data-filter="All" onclick="filterChart('hourlyRejectionChart', 'All')">All</button>
                                <button class="chart-filter-btn" data-filter="External" onclick="filterChart('hourlyRejectionChart', 'External')">External</button>
                                <button class="chart-filter-btn" data-filter="Internal" onclick="filterChart('hourlyRejectionChart', 'Internal')">Internal</button>
                            </div>
                        </div>
                        <div class="chart-canvas-area" style="position: relative; height: 280px; width: 100%;"><canvas id="hourlyRejectionChart"></canvas></div>
                    </div>
                </div>

                <div class="section-header" style="margin-top: 20px;"><h2 class="section-title">Rejection Type &amp; Part Analysis</h2></div>

                <div class="comparison-grid" style="gap: 16px; margin-bottom: 20px;">
                    <div class="chart-container-wrapper">
                        <div class="chart-header"><div class="chart-title-section"><h3 class="chart-title">Rejection by Type</h3><p class="chart-subtitle">Breakdown by Category</p></div></div>
                        <div class="chart-canvas-area" style="position: relative; height: 250px; width: 100%;"><canvas id="rejectionTypeChart"></canvas></div>
                    </div>
                    <div class="chart-container-wrapper">
                        <div class="chart-header"><div class="chart-title-section"><h3 class="chart-title">Rejection by Part</h3><p class="chart-subtitle">WA vs TR Comparison</p></div></div>
                        <div class="chart-canvas-area" style="position: relative; height: 250px; width: 100%;"><canvas id="rejectionPartChart"></canvas></div>
                    </div>
                </div>

                <div class="section-header" style="margin-top: 20px;"><h2 class="section-title">Recent Rejection Records</h2></div>

                <div class="data-table-wrapper">
                    <table class="data-table" id="rejectionRecordsTable">
                        <thead>
                            <tr><th>Time</th><th>Part Type</th><th>ID Part</th><th>Rejection Type</th><th>Category</th><th>Remarks</th></tr>
                        </thead>
                        <tbody id="rejectionTableBody">
                            <tr><td colspan="6" style="text-align: center; padding: 40px;"><div style="color: #999; font-size: 14px;">Loading data...</div></td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/main.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="{{ asset('js/quality-dashboard.js') }}"></script>
</body>
</html>
