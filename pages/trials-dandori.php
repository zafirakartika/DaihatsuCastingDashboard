<?php
// Set current page for sidebar active state
$current_page = 'trials-dandori';
$base_url = '../';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../assets/images/daihatsu-logo.png">
    <title>Trials & Dandori - Casting SMART Factory</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/trials-dandori.css">
</head>
<body>
    <!-- Top Header -->
    <div class="top-header">
        <div class="logo-section">
            <div class="hamburger-menu" onclick="toggleSidebar()">
                <div class="hamburger-line"></div>
                <div class="hamburger-line"></div>
                <div class="hamburger-line"></div>
            </div>
            <img src="../assets/images/daihatsu-logo.png" alt="Daihatsu Logo" class="company-logo">
        </div>
        <div class="header-center">
            <div class="monitoring-title">
                <span class="monitoring-text">Trials & Dandori Monitoring</span>
                <div class="monitoring-subtitle">Production Set-up & Quality Assurance</div>
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
            <!-- KPI Cards -->
            <div class="kpi-grid">
                <div class="kpi-card kpi-primary">
                    <div class="kpi-icon">⏱️</div>
                    <div class="kpi-content">
                        <div class="kpi-label">Efisiensi Waktu Set-up</div>
                        <div class="kpi-value" id="kpi-efficiency">--</div>
                        <div class="kpi-trend" id="kpi-efficiency-trend"></div>
                    </div>
                </div>
                <div class="kpi-card kpi-success">
                    <div class="kpi-icon">✓</div>
                    <div class="kpi-content">
                        <div class="kpi-label">First Time Quality</div>
                        <div class="kpi-value" id="kpi-quality">--</div>
                        <div class="kpi-trend" id="kpi-quality-trend"></div>
                    </div>
                </div>
                <div class="kpi-card kpi-warning">
                    <div class="kpi-icon">📊</div>
                    <div class="kpi-content">
                        <div class="kpi-label">Total Unit Uji Coba</div>
                        <div class="kpi-value" id="kpi-units">--</div>
                        <div class="kpi-trend" id="kpi-units-trend"></div>
                    </div>
                </div>
                <div class="kpi-card kpi-info">
                    <div class="kpi-icon">🔧</div>
                    <div class="kpi-content">
                        <div class="kpi-label">Mesin Ketersediaan</div>
                        <div class="kpi-value" id="kpi-oee">--</div>
                        <div class="kpi-trend" id="kpi-oee-trend"></div>
                    </div>
                </div>
            </div>

            <!-- Main Dashboard Sections -->
            <div class="dashboard-sections">
                <!-- Left Column -->
                <div class="dashboard-left">
                    <!-- Time Efficiency Dashboard -->
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h3 class="card-title">Dashboard Waktu Set-up</h3>
                            <div class="card-controls">
                                <select id="line-filter" class="filter-select">
                                    <option value="all">Semua Lini</option>
                                    <option value="WA">WA Line</option>
                                    <option value="TR">TR Line</option>
                                    <option value="KR">KR Line</option>
                                    <option value="NR">NR Line</option>
                                    <option value="3SZ">3SZ Line</option>
                                </select>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Gantt Chart / Waterfall Chart -->
                            <div class="chart-container">
                                <canvas id="setupTimeChart"></canvas>
                            </div>
                            <!-- Downtime Trend -->
                            <div class="chart-container" style="margin-top: 4px;">
                                <h4 class="chart-subtitle">Tren Durasi Downtime</h4>
                                <canvas id="downtimeChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Quality Process Dashboard -->
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h3 class="card-title">Dashboard Kualitas Uji Coba</h3>
                        </div>
                        <div class="card-body">
                            <!-- Defect Rate Control Chart -->
                            <div class="chart-container">
                                <h4 class="chart-subtitle">Tingkat Cacat (Control Chart)</h4>
                                <canvas id="defectRateChart"></canvas>
                            </div>
                            <!-- Defect Type Distribution -->
                            <div class="chart-container" style="margin-top: 4px;">
                                <h4 class="chart-subtitle">Jenis Cacat yang Ditemukan</h4>
                                <canvas id="defectTypeChart"></canvas>
                            </div>
                            <!-- Trial Units Bar Chart -->
                            <div class="chart-container" style="margin-top: 4px;">
                                <h4 class="chart-subtitle">Jumlah Unit Uji Coba</h4>
                                <canvas id="trialUnitsChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="dashboard-right">
                    <!-- Machine Availability (OEE) -->
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h3 class="card-title">Ketersediaan Mesin (OEE)</h3>
                        </div>
                        <div class="card-body">
                            <div class="oee-metrics">
                                <div class="oee-metric">
                                    <div class="oee-label">Availability</div>
                                    <div class="oee-value" id="oee-availability">--</div>
                                    <div class="oee-bar">
                                        <div class="oee-bar-fill" id="oee-availability-bar" style="width: 0%"></div>
                                    </div>
                                </div>
                                <div class="oee-metric">
                                    <div class="oee-label">Performance</div>
                                    <div class="oee-value" id="oee-performance">--</div>
                                    <div class="oee-bar">
                                        <div class="oee-bar-fill" id="oee-performance-bar" style="width: 0%"></div>
                                    </div>
                                </div>
                                <div class="oee-metric">
                                    <div class="oee-label">Quality</div>
                                    <div class="oee-value" id="oee-quality">--</div>
                                    <div class="oee-bar">
                                        <div class="oee-bar-fill" id="oee-quality-bar" style="width: 0%"></div>
                                    </div>
                                </div>
                                <div class="oee-total">
                                    <div class="oee-label">Total OEE</div>
                                    <div class="oee-value-large" id="oee-total">--</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- QA Status Indicator -->
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h3 class="card-title">Status Persetujuan Kualitas (QA)</h3>
                        </div>
                        <div class="card-body">
                            <div class="qa-status-grid">
                                <div class="qa-status-item">
                                    <div class="status-indicator status-approved" id="qa-status-indicator"></div>
                                    <div class="status-label">Current Status</div>
                                    <div class="status-value" id="qa-status-text">Menunggu Review</div>
                                </div>
                                <div class="qa-stats">
                                    <div class="qa-stat">
                                        <div class="qa-stat-label">Approved</div>
                                        <div class="qa-stat-value qa-approved" id="qa-approved">0</div>
                                    </div>
                                    <div class="qa-stat">
                                        <div class="qa-stat-label">Rejected</div>
                                        <div class="qa-stat-value qa-rejected" id="qa-rejected">0</div>
                                    </div>
                                    <div class="qa-stat">
                                        <div class="qa-stat-label">Pending</div>
                                        <div class="qa-stat-value qa-pending" id="qa-pending">0</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Historical Reports -->
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h3 class="card-title">Analisis & Pelaporan</h3>
                        </div>
                        <div class="card-body">
                            <div class="report-filters">
                                <label class="filter-label">Periode:</label>
                                <select id="report-period" class="filter-select">
                                    <option value="today">Hari Ini</option>
                                    <option value="week">Minggu Ini</option>
                                    <option value="month" selected>Bulan Ini</option>
                                    <option value="quarter">Quarter Ini</option>
                                    <option value="year">Tahun Ini</option>
                                </select>
                            </div>
                            <div class="report-summary">
                                <div class="summary-item">
                                    <div class="summary-label">Total Trials</div>
                                    <div class="summary-value" id="report-total-trials">--</div>
                                </div>
                                <div class="summary-item">
                                    <div class="summary-label">Avg Setup Time</div>
                                    <div class="summary-value" id="report-avg-setup">--</div>
                                </div>
                                <div class="summary-item">
                                    <div class="summary-label">Success Rate</div>
                                    <div class="summary-value" id="report-success-rate">--</div>
                                </div>
                            </div>
                            <div class="report-actions">
                                <button class="btn btn-primary" onclick="generateReport()">
                                    <span class="btn-icon">📊</span> Generate Report
                                </button>
                                <button class="btn btn-secondary" onclick="exportData()">
                                    <span class="btn-icon">📥</span> Export Data
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Trials Table -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3 class="card-title">Recent Trials & Dandori Records</h3>
                    <div class="card-controls">
                        <input type="text" id="table-search" class="search-input" placeholder="Search...">
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Timestamp</th>
                                    <th>Machine/Line</th>
                                    <th>Product Order</th>
                                    <th>Setup Duration</th>
                                    <th>Trial Units</th>
                                    <th>Defect Rate</th>
                                    <th>QA Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="trials-table-body">
                                <tr>
                                    <td colspan="8" class="loading-cell">
                                        <div class="loading-spinner"></div>
                                        <div>Loading data...</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="../js/main.js"></script>
    <script src="../js/trials-dandori.js"></script>
</body>
</html>
