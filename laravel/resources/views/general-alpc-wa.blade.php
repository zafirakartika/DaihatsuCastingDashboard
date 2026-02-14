<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/daihatsu-logo.png') }}">
    <title>General ALPC WA Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/casting-performance.css') }}">
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
                <span class="monitoring-text">General Dashboard</span>
                <div class="monitoring-subtitle">ALPC WA - LPC 11 - Overview & Analytics</div>
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
            <div class="content-header" style="margin-bottom: 12px;">
                <div class="page-title" style="font-size: 32px; font-weight: 700; color: var(--accent-navy); text-shadow: 2px 2px 4px rgba(13, 59, 102, 0.1); border-left: 5px solid var(--accent-blue); padding-left: 15px; margin-bottom: 8px;">
                    General Dashboard - ALPC WA (LPC 11)
                </div>
                <div class="filter-controls" style="display: flex; gap: 10px; align-items: center;">
                    <div style="display: flex; align-items: center; gap: 8px; padding: 4px 12px; background: white; border-radius: 6px; border: 1px solid #ddd;">
                        <span style="font-size: 12px; font-weight: 600; color: #555;">Current Shift:</span>
                        <span id="current-shift-display" style="padding: 4px 12px; font-size: 12px; font-weight: 700; border-radius: 4px; background: #3498db; color: white;">
                            Morning
                        </span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px; padding: 4px 12px; background: white; border-radius: 6px; border: 1px solid #ddd;">
                        <span style="font-size: 12px; font-weight: 600; color: #555;">Real-Time Monitor:</span>
                        <button id="toggle-realtime" onclick="toggleRealTimeMonitoring()"
                                style="padding: 8px 16px; font-size: 12px; font-weight: 600; border: none; border-radius: 4px; cursor: pointer; transition: all 0.3s; background: #27ae60; color: white;">
                            <span id="toggle-status">ON</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="filter-section" style="margin-bottom: 12px; gap: 8px; display: flex; flex-wrap: wrap; align-items: center;">
                <div style="display: flex; align-items: center; gap: 8px; padding: 4px 12px; background: linear-gradient(135deg, var(--accent-blue), var(--accent-navy)); border-radius: 6px; border: 2px solid var(--accent-blue);">
                    <span style="font-size: 12px; font-weight: 700; color: white;">LPC 11</span>
                </div>
                <div class="filter-label" style="font-size: 12px; font-weight: 600; margin-left: 8px;">Date:</div>
                <input type="date" id="filter-date" class="filter-input" style="padding: 6px; font-size: 12px;">
                <div class="filter-label" style="font-size: 12px; font-weight: 600; margin-left: 12px;">Shift:</div>
                <select id="filter-shift" class="filter-input" style="padding: 6px; font-size: 12px;">
                    <option value="auto">Auto (Current Shift)</option>
                    <option value="morning">Morning (07:15 - 16:00)</option>
                    <option value="night">Night (19:00 - 06:00)</option>
                </select>
                <button class="filter-btn active" onclick="GeneralALPCWA.loadData()" style="padding: 6px 16px; font-size: 12px; margin-left: 8px;">
                    Apply Filter
                </button>
                <button class="filter-btn" onclick="resetFilters()" style="padding: 6px 12px; font-size: 12px; background: var(--gray-light); color: var(--text-dark);">
                    Reset
                </button>
                <button class="filter-btn" onclick="window.location.href='{{ route('alpc-overview') }}'" style="padding: 6px 12px; font-size: 12px; background: var(--accent-navy); color: white;">
                    ← Back to Overview
                </button>
            </div>

            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin-bottom: 12px;">
                <div class="metric-card">
                    <div class="metric-label" style="font-size: 11px;">Total Shots</div>
                    <div class="metric-value" id="total-shots" style="font-size: 28px; font-weight: 700;">--</div>
                    <div class="metric-change" style="font-size: 10px; color: #27ae60;">▲ This Shift</div>
                </div>
                <div class="metric-card">
                    <div class="metric-label" style="font-size: 11px;">Good Shots</div>
                    <div class="metric-value" id="good-shots" style="font-size: 28px; font-weight: 700; color: #27ae60;">--</div>
                    <div class="metric-change" id="good-rate" style="font-size: 10px; color: #27ae60;">-- %</div>
                </div>
                <div class="metric-card">
                    <div class="metric-label" style="font-size: 11px;">Rejected Shots</div>
                    <div class="metric-value" id="rejected-shots" style="font-size: 28px; font-weight: 700; color: #e74c3c;">--</div>
                    <div class="metric-change" id="reject-rate" style="font-size: 10px; color: #e74c3c;">-- %</div>
                </div>
                <div class="metric-card">
                    <div class="metric-label" style="font-size: 11px;">Avg. Production Rate</div>
                    <div class="metric-value" id="production-rate" style="font-size: 28px; font-weight: 700;">--</div>
                    <div class="metric-change" style="font-size: 10px; color: #555;">shots/hour</div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-bottom: 12px;">
                <div class="metric-card">
                    <div class="metric-label" style="font-size: 11px;">Left Side Avg</div>
                    <div class="metric-value" id="left-avg" style="font-size: 24px;">--</div>
                    <div class="metric-unit" style="font-size: 12px;">°C</div>
                    <span class="status-badge status-normal" id="status-left" style="font-size: 10px; padding: 3px 8px;">--</span>
                </div>
                <div class="metric-card">
                    <div class="metric-label" style="font-size: 11px;">Right Side Avg</div>
                    <div class="metric-value" id="right-avg" style="font-size: 24px;">--</div>
                    <div class="metric-unit" style="font-size: 12px;">°C</div>
                    <span class="status-badge status-normal" id="status-right" style="font-size: 10px; padding: 3px 8px;">--</span>
                </div>
                <div class="metric-card">
                    <div class="metric-label" style="font-size: 11px;">Cooling Water Avg</div>
                    <div class="metric-value" id="cooling-avg" style="font-size: 24px;">--</div>
                    <div class="metric-unit" style="font-size: 12px;">°C</div>
                    <span class="status-badge status-normal" id="status-cooling" style="font-size: 10px; padding: 3px 8px;">--</span>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-bottom: 12px;">
                <div class="metric-card">
                    <div class="metric-label" style="font-size: 11px;">Temperature Range</div>
                    <div class="metric-value" id="temp-range" style="font-size: 24px;">--</div>
                    <div class="metric-unit" style="font-size: 12px;">°C spread</div>
                </div>
                <div class="metric-card">
                    <div class="metric-label" style="font-size: 11px;">Std Deviation</div>
                    <div class="metric-value" id="std-dev" style="font-size: 24px;">--</div>
                    <div class="metric-unit" style="font-size: 12px;">°C</div>
                </div>
                <div class="metric-card">
                    <div class="metric-label" style="font-size: 11px;">L-R Temperature Diff</div>
                    <div class="metric-value" id="lr-diff" style="font-size: 24px;">--</div>
                    <div class="metric-unit" style="font-size: 12px;">°C</div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 10px; margin-bottom: 10px;">
                <div class="chart-wrapper" style="padding: 12px;">
                    <div class="chart-title" style="margin-bottom: 10px; font-size: 15px;">Production Trend (Hourly)</div>
                    <div style="position: relative; height: 200px; width: 100%; overflow: hidden;">
                        <canvas id="productionTrendChart"></canvas>
                    </div>
                </div>
                <div class="chart-wrapper" style="padding: 12px;">
                    <div class="chart-title" style="margin-bottom: 10px; font-size: 15px;">Quality Status</div>
                    <div style="position: relative; height: 200px; width: 100%; overflow: hidden;">
                        <canvas id="qualityPieChart"></canvas>
                    </div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 10px;">
                <div class="chart-wrapper" style="padding: 12px;">
                    <div class="chart-title" style="margin-bottom: 10px; font-size: 15px;">Zone Temperature Comparison</div>
                    <div style="position: relative; height: 180px; width: 100%; overflow: hidden;">
                        <canvas id="zoneComparisonChart"></canvas>
                    </div>
                </div>
                <div class="chart-wrapper" style="padding: 12px;">
                    <div class="chart-title" style="margin-bottom: 10px; font-size: 15px;">All Sensors Average Trend</div>
                    <div style="position: relative; height: 180px; width: 100%; overflow: hidden;">
                        <canvas id="avgTrendChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="chart-wrapper" style="padding: 12px; margin-bottom: 8px;">
                <div class="chart-title" style="margin-bottom: 10px; font-size: 15px;">Sensor Statistics Summary</div>
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
                        <thead>
                            <tr style="background: var(--accent-navy); color: white;">
                                <th style="padding: 8px; text-align: left; border: 1px solid #ddd;">Sensor</th>
                                <th style="padding: 8px; text-align: right; border: 1px solid #ddd;">Current</th>
                                <th style="padding: 8px; text-align: right; border: 1px solid #ddd;">Average</th>
                                <th style="padding: 8px; text-align: right; border: 1px solid #ddd;">Min</th>
                                <th style="padding: 8px; text-align: right; border: 1px solid #ddd;">Max</th>
                                <th style="padding: 8px; text-align: right; border: 1px solid #ddd;">Std Dev</th>
                                <th style="padding: 8px; text-align: center; border: 1px solid #ddd;">Status</th>
                            </tr>
                        </thead>
                        <tbody id="stats-table-body">
                            <tr>
                                <td colspan="7" style="padding: 20px; text-align: center; color: #999;">Loading data...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="refresh-info" style="font-size: 12px; padding: 5px 0; color: var(--text-light);">
                Last updated: <span id="last-update">--:--:--</span> | Auto-refresh: <span id="refresh-status">60s</span>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="{{ asset('js/general-alpc-wa.js') }}"></script>
    <script>
        function resetFilters() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('filter-date').value = today;
            document.getElementById('filter-shift').value = 'auto';
            GeneralALPCWA.loadData();
        }

        window.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('filter-date').value = today;
            document.getElementById('filter-shift').value = 'auto';
        });

        let isRealTimeEnabled = true;

        function toggleRealTimeMonitoring() {
            isRealTimeEnabled = !isRealTimeEnabled;
            const toggleBtn = document.getElementById('toggle-realtime');
            const statusText = document.getElementById('toggle-status');
            const refreshStatus = document.getElementById('refresh-status');

            if (isRealTimeEnabled) {
                toggleBtn.style.background = '#27ae60';
                statusText.textContent = 'ON';
                refreshStatus.textContent = '3s';
                GeneralALPCWA.startSimulation(3);
            } else {
                toggleBtn.style.background = '#e74c3c';
                statusText.textContent = 'OFF';
                refreshStatus.textContent = 'Paused';
                GeneralALPCWA.stopSimulation();
            }
        }
    </script>
</body>
</html>
