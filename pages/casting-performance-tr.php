<?php
// Set current page for sidebar active state
$current_page = 'casting-performance-tr';
$base_url = '../';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../assets/images/daihatsu-logo.png">
    <title>Casting Performance - ALPC TR</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/casting-performance.css">
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
                <span class="monitoring-text">Casting Performance</span>
                <div class="monitoring-subtitle">ALPC TR - Real-Time Monitoring</div>
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
                    <div class="page-title" style="font-size: 32px; font-weight: 700; color: var(--accent-navy); text-shadow: 2px 2px 4px rgba(13, 59, 102, 0.1); border-left: 5px solid var(--accent-blue); padding-left: 15px; margin-bottom: 0;">
                        Casting Performance - ALPC TR
                    </div>
                </div>
                <div class="filter-controls" style="display: flex; gap: 10px; align-items: center; margin-top: 12px; flex-wrap: wrap;">
                    <!-- Current Shift Display -->
                    <div style="display: flex; align-items: center; gap: 8px; padding: 8px 14px; background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); border-radius: 8px; border: 2px solid #e9ecef; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <span style="font-size: 12px; font-weight: 600; color: #555;">Current Shift:</span>
                        <span id="current-shift-display" style="padding: 6px 14px; font-size: 12px; font-weight: 700; border-radius: 6px; background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); color: white; box-shadow: 0 2px 6px rgba(52, 152, 219, 0.3);">
                            Morning
                        </span>
                    </div>

                    <!-- Real-time Monitoring Toggle (for OEE) -->
                    <div style="display: flex; align-items: center; gap: 8px; padding: 8px 14px; background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); border-radius: 8px; border: 2px solid #e9ecef; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <span style="font-size: 12px; font-weight: 600; color: #555;">Real-Time Monitor:</span>
                        <button id="toggle-realtime" onclick="toggleRealTimeMonitoring()"
                                style="padding: 8px 16px; font-size: 12px; font-weight: 600; border: none; border-radius: 6px; cursor: pointer; transition: all 0.3s; background: linear-gradient(135deg, #27ae60 0%, #229954 100%); color: white; box-shadow: 0 2px 6px rgba(39, 174, 96, 0.3);">
                            <span id="toggle-status">ON</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="filter-section" style="margin-bottom: 12px; gap: 8px; display: flex; flex-wrap: wrap; align-items: center;">
                <div class="filter-label" style="font-size: 12px; font-weight: 600;">Date:</div>
                <input type="date" id="filter-date" class="filter-input" style="padding: 6px; font-size: 12px;">

                <div class="filter-label" style="font-size: 12px; font-weight: 600; margin-left: 12px;">Shift:</div>
                <select id="filter-shift" class="filter-input" style="padding: 6px; font-size: 12px;">
                    <option value="auto">Auto (Current Shift)</option>
                    <option value="morning">Morning (07:15 - 16:00)</option>
                    <option value="night">Night (19:00 - 06:00)</option>
                </select>

                <button class="filter-btn active" onclick="CastingPerformanceTR.loadAllData()" style="padding: 6px 16px; font-size: 12px; margin-left: 8px;">
                    Apply Filter
                </button>
                <button class="filter-btn" onclick="resetFilters()" style="padding: 6px 12px; font-size: 12px; background: var(--gray-light); color: var(--text-dark);">
                    Reset
                </button>
            </div>

            <!-- OEE Metrics -->
            <div class="metrics-grid" style="margin-bottom: 12px;">
                <!-- Production Count -->
                <div class="metric-card">
                    <div class="metric-label" style="font-size: 11px;">Production Count</div>
                    <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 6px;">
                        <div style="font-size: 10px; color: #666;">
                            <span style="font-weight: 600;">Target:</span> <span id="oee-count-target">101</span>
                        </div>
                        <div style="font-size: 10px; color: #666;">
                            <span style="font-weight: 600;">Actual:</span> <span id="oee-count-actual">0</span>
                        </div>
                    </div>
                    <div class="metric-value" style="font-size: 28px; font-weight: 700; margin-bottom: 4px;">
                        <span id="status-count-percent">0</span><span style="font-size: 16px;">%</span>
                    </div>
                    <span class="status-badge" id="status-count" style="font-size: 10px; padding: 3px 8px;">Achievement</span>
                </div>

                <!-- Availability -->
                <div class="metric-card">
                    <div class="metric-label" style="font-size: 11px;">Availability</div>
                    <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 6px;">
                        <div style="font-size: 10px; color: #666;">
                            <span style="font-weight: 600;">Target:</span> 85%
                        </div>
                        <div style="font-size: 10px; color: #666;">
                            <span style="font-weight: 600;">Actual:</span> <span id="oee-availability">0</span>%
                        </div>
                    </div>
                    <div class="metric-value" style="font-size: 28px; font-weight: 700; margin-bottom: 4px;">
                        <span id="status-availability-percent">0</span><span style="font-size: 16px;">%</span>
                    </div>
                    <span class="status-badge" id="status-availability" style="font-size: 10px; padding: 3px 8px;">Achievement</span>
                </div>

                <!-- Performance -->
                <div class="metric-card">
                    <div class="metric-label" style="font-size: 11px;">Performance</div>
                    <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 6px;">
                        <div style="font-size: 10px; color: #666;">
                            <span style="font-weight: 600;">Target:</span> 95%
                        </div>
                        <div style="font-size: 10px; color: #666;">
                            <span style="font-weight: 600;">Actual:</span> <span id="oee-performance">0</span>%
                        </div>
                    </div>
                    <div class="metric-value" style="font-size: 28px; font-weight: 700; margin-bottom: 4px;">
                        <span id="status-performance-percent">0</span><span style="font-size: 16px;">%</span>
                    </div>
                    <span class="status-badge" id="status-performance" style="font-size: 10px; padding: 3px 8px;">Achievement</span>
                </div>

                <!-- Quality -->
                <div class="metric-card">
                    <div class="metric-label" style="font-size: 11px;">Quality</div>
                    <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 6px;">
                        <div style="font-size: 10px; color: #666;">
                            <span style="font-weight: 600;">Target:</span> 99%
                        </div>
                        <div style="font-size: 10px; color: #666;">
                            <span style="font-weight: 600;">Actual:</span> <span id="oee-quality">0</span>%
                        </div>
                    </div>
                    <div class="metric-value" style="font-size: 28px; font-weight: 700; margin-bottom: 4px;">
                        <span id="status-quality-percent">0</span><span style="font-size: 16px;">%</span>
                    </div>
                    <span class="status-badge" id="status-quality" style="font-size: 10px; padding: 3px 8px;">Achievement</span>
                </div>

                <!-- Overall OEE -->
                <div class="metric-card">
                    <div class="metric-label" style="font-size: 11px;">Overall OEE</div>
                    <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 6px;">
                        <div style="font-size: 10px; color: #666;">
                            <span style="font-weight: 600;">Target:</span> 80%
                        </div>
                        <div style="font-size: 10px; color: #666;">
                            <span style="font-weight: 600;">Actual:</span> <span id="oee-overall">0</span>%
                        </div>
                    </div>
                    <div class="metric-value" style="font-size: 28px; font-weight: 700; margin-bottom: 4px;">
                        <span id="status-oee-percent">0</span><span style="font-size: 16px;">%</span>
                    </div>
                    <span class="status-badge" id="status-oee" style="font-size: 10px; padding: 3px 8px;">Achievement</span>
                </div>
            </div>

            <!-- Temperature Metrics -->
            <div style="margin-bottom: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <h3 style="font-size: 14px; font-weight: 600; margin: 0;">Latest Temperature Readings</h3>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <label style="font-size: 11px; color: #666;">Show:</label>
                        <select id="temp-metric-filter" onchange="CastingPerformanceTR.filterTemperatureMetrics(this.value)"
                                style="padding: 4px 8px; font-size: 11px; border: 1px solid #ddd; border-radius: 4px;">
                            <option value="all">All Sensors</option>
                            <option value="gate">Gate Only</option>
                            <option value="main">Main Only</option>
                            <option value="cooling">Cooling Only</option>
                        </select>
                    </div>
                </div>
                <div class="metrics-grid" id="temperature-metrics-grid" style="grid-template-columns: repeat(4, 1fr);">
                    <div class="metric-card" data-metric-type="gate">
                        <div class="metric-label" style="font-size: 11px;">L Gate Front</div>
                        <div class="metric-value" style="font-size: 24px;">
                            <span id="metric-l-gate-front">--</span>
                            <span class="metric-unit" style="font-size: 14px;">°C</span>
                        </div>
                        <span class="status-badge status-normal" id="status-l-gate-front" style="font-size: 10px; padding: 3px 8px;">Loading...</span>
                    </div>

                    <div class="metric-card" data-metric-type="gate">
                        <div class="metric-label" style="font-size: 11px;">L Gate Rear</div>
                        <div class="metric-value" style="font-size: 24px;">
                            <span id="metric-l-gate-rear">--</span>
                            <span class="metric-unit" style="font-size: 14px;">°C</span>
                        </div>
                        <span class="status-badge status-normal" id="status-l-gate-rear" style="font-size: 10px; padding: 3px 8px;">Loading...</span>
                    </div>

                    <div class="metric-card" data-metric-type="chamber">
                        <div class="metric-label" style="font-size: 11px;">L Chamber 1</div>
                        <div class="metric-value" style="font-size: 24px;">
                            <span id="metric-l-chamber-1">--</span>
                            <span class="metric-unit" style="font-size: 14px;">°C</span>
                        </div>
                        <span class="status-badge status-normal" id="status-l-chamber-1" style="font-size: 10px; padding: 3px 8px;">Loading...</span>
                    </div>

                    <div class="metric-card" data-metric-type="chamber">
                        <div class="metric-label" style="font-size: 11px;">L Chamber 2</div>
                        <div class="metric-value" style="font-size: 24px;">
                            <span id="metric-l-chamber-2">--</span>
                            <span class="metric-unit" style="font-size: 14px;">°C</span>
                        </div>
                        <span class="status-badge status-normal" id="status-l-chamber-2" style="font-size: 10px; padding: 3px 8px;">Loading...</span>
                    </div>

                    <div class="metric-card" data-metric-type="gate">
                        <div class="metric-label" style="font-size: 11px;">R Gate Front</div>
                        <div class="metric-value" style="font-size: 24px;">
                            <span id="metric-r-gate-front">--</span>
                            <span class="metric-unit" style="font-size: 14px;">°C</span>
                        </div>
                        <span class="status-badge status-normal" id="status-r-gate-front" style="font-size: 10px; padding: 3px 8px;">Loading...</span>
                    </div>

                    <div class="metric-card" data-metric-type="gate">
                        <div class="metric-label" style="font-size: 11px;">R Gate Rear</div>
                        <div class="metric-value" style="font-size: 24px;">
                            <span id="metric-r-gate-rear">--</span>
                            <span class="metric-unit" style="font-size: 14px;">°C</span>
                        </div>
                        <span class="status-badge status-normal" id="status-r-gate-rear" style="font-size: 10px; padding: 3px 8px;">Loading...</span>
                    </div>

                    <div class="metric-card" data-metric-type="chamber">
                        <div class="metric-label" style="font-size: 11px;">R Chamber 1</div>
                        <div class="metric-value" style="font-size: 24px;">
                            <span id="metric-r-chamber-1">--</span>
                            <span class="metric-unit" style="font-size: 14px;">°C</span>
                        </div>
                        <span class="status-badge status-normal" id="status-r-chamber-1" style="font-size: 10px; padding: 3px 8px;">Loading...</span>
                    </div>

                    <div class="metric-card" data-metric-type="chamber">
                        <div class="metric-label" style="font-size: 11px;">R Chamber 2</div>
                        <div class="metric-value" style="font-size: 24px;">
                            <span id="metric-r-chamber-2">--</span>
                            <span class="metric-unit" style="font-size: 14px;">°C</span>
                        </div>
                        <span class="status-badge status-normal" id="status-r-chamber-2" style="font-size: 10px; padding: 3px 8px;">Loading...</span>
                    </div>
                </div>
            </div>

            <!-- Temperature Trend Chart -->
            <div class="chart-wrapper" style="margin-bottom: 10px; padding: 12px;">
                <div style="margin-bottom: 12px;">
                    <div class="chart-title" style="margin: 0 0 10px 0; font-size: 15px;">Temperature Trend</div>
                    <div style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
                        <label style="font-size: 11px; color: #666; font-weight: 600;">Filter:</label>
                        <label style="display: flex; align-items: center; gap: 5px; font-size: 11px; cursor: pointer;">
                            <input type="radio" name="temp-trend-filter" value="all" checked onchange="CastingPerformanceTR.filterTrendChart(this.value)"
                                   style="cursor: pointer;">
                            <span>All Sensors</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 5px; font-size: 11px; cursor: pointer;">
                            <input type="radio" name="temp-trend-filter" value="gate" onchange="CastingPerformanceTR.filterTrendChart(this.value)"
                                   style="cursor: pointer;">
                            <span>Gate Sensors</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 5px; font-size: 11px; cursor: pointer;">
                            <input type="radio" name="temp-trend-filter" value="chamber" onchange="CastingPerformanceTR.filterTrendChart(this.value)"
                                   style="cursor: pointer;">
                            <span>Chamber Sensors</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 5px; font-size: 11px; cursor: pointer;">
                            <input type="radio" name="temp-trend-filter" value="right" onchange="CastingPerformanceTR.filterTrendChart(this.value)"
                                   style="cursor: pointer;">
                            <span>Right Side</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 5px; font-size: 11px; cursor: pointer;">
                            <input type="radio" name="temp-trend-filter" value="left" onchange="CastingPerformanceTR.filterTrendChart(this.value)"
                                   style="cursor: pointer;">
                            <span>Left Side</span>
                        </label>
                    </div>
                </div>
                <div style="position: relative; height: 600px; width: 100%; overflow: hidden;">
                    <canvas id="tempTrendChart" style="display: block;"></canvas>
                </div>
            </div>

            <!-- Comparison Charts -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 10px;">
                <div class="chart-wrapper" style="margin-bottom: 0; padding: 12px;">
                    <div class="chart-title" style="margin-bottom: 10px; font-size: 15px;">Capability Process</div>
                    <div style="position: relative; height: 280px; width: 100%; overflow: hidden;">
                        <canvas id="leftRightChart" style="display: block;"></canvas>
                    </div>
                </div>

                <div class="chart-wrapper" style="margin-bottom: 0; padding: 12px;">
                    <div class="chart-title" style="margin-bottom: 10px; font-size: 15px;">Shot</div>
                    <div style="position: relative; height: 280px; width: 100%; overflow: hidden;">
                        <canvas id="distributionChart" style="display: block;"></canvas>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="chart-wrapper" style="padding: 16px; margin-bottom: 8px; background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); border: 2px solid #e9ecef;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                    <div class="chart-title" style="font-size: 16px; margin: 0; font-weight: 700; color: #2c3e50;">
                        Production Records (Last 50 Shots)
                    </div>
                    <div style="display: flex; gap: 8px;">
                        <button onclick="exportTableData()" style="padding: 6px 12px; font-size: 11px; background: linear-gradient(135deg, #27ae60 0%, #229954 100%); color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; box-shadow: 0 2px 6px rgba(39, 174, 96, 0.3); transition: all 0.3s;">
                            Export CSV
                        </button>
                    </div>
                </div>

                <!-- Search and Controls -->
                <div style="margin-bottom: 12px; display: flex; gap: 8px; flex-wrap: wrap; align-items: center;">
                    <div style="position: relative; flex: 1; min-width: 200px;">
                        <input type="text" id="table-search" placeholder="Search by Shot #, Temperature, or Timestamp..."
                               style="width: 100%; padding: 8px 12px; font-size: 12px; border: 2px solid #e9ecef; border-radius: 8px; transition: all 0.3s;">
                    </div>
                    <select id="sort-column" style="padding: 8px 12px; font-size: 12px; border: 2px solid #e9ecef; border-radius: 8px; background: white; cursor: pointer; transition: all 0.3s;">
                        <option value="timestamp-desc">Newest First</option>
                        <option value="timestamp-asc">Oldest First</option>
                        <option value="l-gate-front-desc">L Gate Front ↓</option>
                        <option value="l-gate-front-asc">L Gate Front ↑</option>
                        <option value="r-gate-front-desc">R Gate Front ↓</option>
                        <option value="r-gate-front-asc">R Gate Front ↑</option>
                    </select>
                    <button id="clear-search" style="padding: 8px 16px; font-size: 12px; background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; box-shadow: 0 2px 6px rgba(231, 76, 60, 0.3); transition: all 0.3s;">
                        Clear
                    </button>
                </div>

                <div style="overflow-x: auto; max-height: 400px; overflow-y: auto; border: 1px solid #e9ecef; border-radius: 8px; box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);">
                    <table style="width: 100%; border-collapse: collapse; font-size: 11px; background: white;">
                        <thead>
                            <tr style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); position: sticky; top: 0; z-index: 5;">
                                <th style="padding: 10px 8px; text-align: center; font-size: 11px; font-weight: 700; color: white; border-right: 1px solid rgba(255,255,255,0.2);">Shot #</th>
                                <th style="padding: 10px 8px; text-align: left; font-size: 11px; font-weight: 700; color: white; border-right: 1px solid rgba(255,255,255,0.2);">Timestamp</th>
                                <th style="padding: 10px 8px; text-align: center; font-size: 11px; font-weight: 700; color: white; border-right: 1px solid rgba(255,255,255,0.2);">L Gate F (°C)</th>
                                <th style="padding: 10px 8px; text-align: center; font-size: 11px; font-weight: 700; color: white; border-right: 1px solid rgba(255,255,255,0.2);">L Gate R (°C)</th>
                                <th style="padding: 10px 8px; text-align: center; font-size: 11px; font-weight: 700; color: white; border-right: 1px solid rgba(255,255,255,0.2);">L Ch 1 (°C)</th>
                                <th style="padding: 10px 8px; text-align: center; font-size: 11px; font-weight: 700; color: white; border-right: 1px solid rgba(255,255,255,0.2);">L Ch 2 (°C)</th>
                                <th style="padding: 10px 8px; text-align: center; font-size: 11px; font-weight: 700; color: white; border-right: 1px solid rgba(255,255,255,0.2);">R Gate F (°C)</th>
                                <th style="padding: 10px 8px; text-align: center; font-size: 11px; font-weight: 700; color: white; border-right: 1px solid rgba(255,255,255,0.2);">R Gate R (°C)</th>
                                <th style="padding: 10px 8px; text-align: center; font-size: 11px; font-weight: 700; color: white; border-right: 1px solid rgba(255,255,255,0.2);">R Ch 1 (°C)</th>
                                <th style="padding: 10px 8px; text-align: center; font-size: 11px; font-weight: 700; color: white;">R Ch 2 (°C)</th>
                            </tr>
                        </thead>
                        <tbody id="data-table-body">
                            <tr>
                                <td colspan="10" style="text-align: center; padding: 30px; color: #666;">
                                    <div class="loading-spinner"></div>
                                    <div style="margin-top: 12px; font-weight: 600;">Loading production data...</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Table Footer Info -->
                <div style="margin-top: 12px; padding: 10px; background: #f8f9fa; border-radius: 6px; display: flex; justify-content: space-between; align-items: center; font-size: 11px; color: #666;">
                    <div>
                        <strong>Quality Status:</strong>
                        <span style="margin-left: 8px; padding: 3px 8px; background: #d4edda; color: #155724; border-radius: 4px; font-weight: 600;">✓ Pass</span>
                        <span style="margin-left: 4px; padding: 3px 8px; background: #fff3cd; color: #856404; border-radius: 4px; font-weight: 600;">⚠ Warning</span>
                        <span style="margin-left: 4px; padding: 3px 8px; background: #f8d7da; color: #721c24; border-radius: 4px; font-weight: 600;">✕ Fail</span>
                    </div>
                    <div>
                        <strong>Showing:</strong> <span id="table-record-count">0</span> records
                    </div>
                </div>
            </div>

            <div class="refresh-info" style="font-size: 12px; padding: 5px 0; color: var(--text-light);">
                Last updated: <span id="last-update">--:--:--</span> | Auto-refresh: <span id="refresh-status">60s</span>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@3.0.1/dist/chartjs-plugin-annotation.min.js"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="../js/main.js?v=<?php echo time(); ?>"></script>
    <script src="../js/casting-performance-core.js?v=<?php echo time(); ?>"></script>
    <script src="../js/casting-performance-tr-config.js?v=<?php echo time(); ?>"></script>
    <script>
        // Fungsi toggle submenu
        function toggleSubmenu(id) {
            const submenu = document.getElementById('submenu-' + id);
            const expandIcon = document.getElementById('expand-' + id);
            
            if (submenu.classList.contains('expanded')) {
                submenu.classList.remove('expanded');
                if (expandIcon) expandIcon.classList.remove('expanded');
            } else {
                submenu.classList.add('expanded');
                if (expandIcon) expandIcon.classList.add('expanded');
            }
        }
        
        // Update waktu dan tanggal
        function updateTime() {
            const now = new Date();
            
            // Nama hari dalam bahasa Indonesia
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const dayName = days[now.getDay()];
            
            // Nama bulan dalam bahasa Inggris (sesuai referensi gambar)
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            const monthName = months[now.getMonth()];
            
            // Format tanggal
            const day = String(now.getDate()).padStart(2, '0');
            const year = now.getFullYear();
            
            // Format waktu
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            
            // Update date dan time display
            const dateDisplay = document.getElementById('current-date');
            const timeDisplay = document.getElementById('current-time');
            
            if (dateDisplay) {
                dateDisplay.textContent = `${dayName}, ${day}-${monthName}-${year}`;
            }
            
            if (timeDisplay) {
                timeDisplay.textContent = `${hours}:${minutes}:${seconds}`;
            }
        }
        
        updateTime();
        setInterval(updateTime, 1000);

        // Reset filters function
        function resetFilters() {
            // Set to today's date and auto shift
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('filter-date').value = today;
            document.getElementById('filter-shift').value = 'auto';

            // Reset temperature metrics filter
            document.getElementById('temp-metric-filter').value = 'all';

            // Reset temperature trend radio buttons
            const allRadio = document.querySelector('input[name="temp-trend-filter"][value="all"]');
            if (allRadio) allRadio.checked = true;

            CastingPerformanceTR.filterTemperatureMetrics('all');
            CastingPerformanceTR.filterTrendChart('all');
            CastingPerformanceTR.loadAllData();
        }

        // On page load, set to current shift
        window.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('filter-date').value = today;
            document.getElementById('filter-shift').value = 'auto';

            console.log('Page loaded - showing current shift data');
        });

        // Export table data to CSV
        function exportTableData() {
            const table = document.getElementById('data-table-body');
            const rows = table.querySelectorAll('tr');

            if (rows.length === 0 || rows[0].cells.length === 1) {
                alert('No data available to export');
                return;
            }

            let csv = 'Shot #,Timestamp,L Gate Front (°C),L Gate Rear (°C),L Chamber 1 (°C),L Chamber 2 (°C),R Gate Front (°C),R Gate Rear (°C),R Chamber 1 (°C),R Chamber 2 (°C)\n';

            rows.forEach(row => {
                const cells = row.cells;
                if (cells.length > 1) {
                    const rowData = [];
                    for (let i = 0; i < cells.length; i++) {
                        rowData.push('"' + cells[i].textContent.trim() + '"');
                    }
                    csv += rowData.join(',') + '\n';
                }
            });

            const blob = new Blob([csv], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            link.download = 'casting-tr-data-' + new Date().toISOString().split('T')[0] + '.csv';
            link.click();
            window.URL.revokeObjectURL(url);
        }

        // Real-time monitoring toggle (controls simulation)
        let isRealTimeEnabled = true;

        function toggleRealTimeMonitoring() {
            isRealTimeEnabled = !isRealTimeEnabled;

            const toggleBtn = document.getElementById('toggle-realtime');
            const statusText = document.getElementById('toggle-status');
            const refreshStatus = document.getElementById('refresh-status');

            if (isRealTimeEnabled) {
                // Turn ON - Green
                toggleBtn.style.background = '#27ae60';
                statusText.textContent = 'ON';
                refreshStatus.textContent = '3s';

                // Start simulation
                CastingPerformanceTR.startSimulation(3);
                console.log('✅ Real-time simulation STARTED');
            } else {
                // Turn OFF - Red
                toggleBtn.style.background = '#e74c3c';
                statusText.textContent = 'OFF';
                refreshStatus.textContent = 'Paused';

                // Stop simulation
                CastingPerformanceTR.stopSimulation();
                console.log('⛔ Real-time simulation STOPPED');
            }
        }
    </script>
</body>
</html>
