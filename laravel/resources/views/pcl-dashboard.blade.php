<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/daihatsu-logo.png') }}">
    <title>PCL Dashboard - SMART Factory</title>
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
                <span class="monitoring-text">PCL Dashboard</span>
                <div class="monitoring-subtitle">Planning, Control &amp; Logistics Management</div>
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
                    <div class="page-title">PCL Dashboard</div>
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
                <!-- Inventory KPI Cards -->
                <div class="metric-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
                    <div class="metric-card metric-normal" id="metric-total-stock">
                        <div class="metric-header"><span class="metric-icon"></span><span class="metric-title">Total Stock</span></div>
                        <div class="metric-body"><div class="metric-value-wrapper"><span class="metric-value">8,542</span><span class="metric-unit"> parts</span></div><div class="metric-trend trend-up"><svg class="trend-icon" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg><span class="trend-text">+12%</span></div></div>
                    </div>
                    <div class="metric-card metric-normal" id="metric-wa-stock">
                        <div class="metric-header"><span class="metric-icon"></span><span class="metric-title">WA Stock</span></div>
                        <div class="metric-body"><div class="metric-value-wrapper"><span class="metric-value">4,820</span><span class="metric-unit"> parts</span></div><div class="metric-trend trend-up"><svg class="trend-icon" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg><span class="trend-text">+8%</span></div></div>
                    </div>
                    <div class="metric-card metric-normal" id="metric-tr-stock">
                        <div class="metric-header"><span class="metric-icon"></span><span class="metric-title">TR Stock</span></div>
                        <div class="metric-body"><div class="metric-value-wrapper"><span class="metric-value">3,722</span><span class="metric-unit"> parts</span></div><div class="metric-trend trend-up"><svg class="trend-icon" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg><span class="trend-text">+15%</span></div></div>
                    </div>
                    <div class="metric-card metric-warning" id="metric-low-stock">
                        <div class="metric-header"><span class="metric-icon"></span><span class="metric-title">Low Stock Alert</span></div>
                        <div class="metric-body"><div class="metric-value-wrapper"><span class="metric-value">3</span><span class="metric-unit"> items</span></div><div class="metric-trend trend-down"><svg class="trend-icon" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline><polyline points="17 18 23 18 23 12"></polyline></svg><span class="trend-text">-2</span></div></div>
                    </div>
                    <div class="metric-card metric-good" id="metric-shipments">
                        <div class="metric-header"><span class="metric-icon"></span><span class="metric-title">Shipments Today</span></div>
                        <div class="metric-body"><div class="metric-value-wrapper"><span class="metric-value">24</span><span class="metric-unit"> batches</span></div><div class="metric-trend trend-up"><svg class="trend-icon" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg><span class="trend-text">+6</span></div></div>
                    </div>
                    <div class="metric-card metric-good" id="metric-turnover">
                        <div class="metric-header"><span class="metric-icon"></span><span class="metric-title">Turnover Rate</span></div>
                        <div class="metric-body"><div class="metric-value-wrapper"><span class="metric-value">2.4</span><span class="metric-unit"> days</span></div><div class="metric-trend trend-down"><svg class="trend-icon" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline><polyline points="17 18 23 18 23 12"></polyline></svg><span class="trend-text">-0.3</span></div></div>
                    </div>
                </div>

                <div class="section-header" style="margin-top: 20px;"><h2 class="section-title">Stock &amp; Shipment Trends</h2></div>

                <div class="comparison-grid" style="gap: 16px; margin-bottom: 20px;">
                    <div class="chart-container-wrapper">
                        <div class="chart-header"><div class="chart-title-section"><h3 class="chart-title">Stock Level Trend</h3><p class="chart-subtitle">Last 7 Days</p></div><div class="chart-legend" id="stockTrendChart-legend"></div></div>
                        <div class="chart-canvas-area" style="position: relative; height: 280px; width: 100%;"><canvas id="stockTrendChart"></canvas></div>
                    </div>
                    <div class="chart-container-wrapper">
                        <div class="chart-header">
                            <div class="chart-title-section"><h3 class="chart-title">Daily Shipments</h3><p class="chart-subtitle">Last 7 Days</p></div>
                            <div class="chart-filter-buttons" id="shipmentChart-filters">
                                <button class="chart-filter-btn active" data-filter="All" onclick="filterChart('shipmentChart', 'All')">All</button>
                                <button class="chart-filter-btn" data-filter="WA" onclick="filterChart('shipmentChart', 'WA')">WA</button>
                                <button class="chart-filter-btn" data-filter="TR" onclick="filterChart('shipmentChart', 'TR')">TR</button>
                            </div>
                        </div>
                        <div class="chart-canvas-area" style="position: relative; height: 280px; width: 100%;"><canvas id="shipmentChart"></canvas></div>
                    </div>
                </div>

                <div class="section-header" style="margin-top: 20px;"><h2 class="section-title">Inventory Analysis &amp; Planning</h2></div>

                <div class="comparison-grid" style="gap: 16px; margin-bottom: 20px;">
                    <div class="chart-container-wrapper">
                        <div class="chart-header"><div class="chart-title-section"><h3 class="chart-title">Stock Distribution</h3><p class="chart-subtitle">By Part Type</p></div></div>
                        <div class="chart-canvas-area" style="position: relative; height: 250px; width: 100%;"><canvas id="stockDistributionChart"></canvas></div>
                    </div>
                    <div class="chart-container-wrapper">
                        <div class="chart-header"><div class="chart-title-section"><h3 class="chart-title">Warehouse Utilization</h3><p class="chart-subtitle">By Location</p></div></div>
                        <div class="chart-canvas-area" style="position: relative; height: 250px; width: 100%;"><canvas id="warehouseChart"></canvas></div>
                    </div>
                </div>

                <div class="section-header" style="margin-top: 20px;"><h2 class="section-title">Recent Stock Movements</h2></div>

                <div class="data-table-wrapper">
                    <table class="data-table" id="stockMovementTable">
                        <thead>
                            <tr><th>Time</th><th>Part Type</th><th>Batch ID</th><th>Movement Type</th><th>Quantity</th><th>Location</th><th>Status</th></tr>
                        </thead>
                        <tbody id="stockTableBody">
                            <tr><td colspan="7" style="text-align: center; padding: 40px;"><div style="color: #999; font-size: 14px;">Loading data...</div></td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/main.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="{{ asset('js/pcl-dashboard.js') }}"></script>
</body>
</html>
