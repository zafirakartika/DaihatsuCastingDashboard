<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/daihatsu-logo.png') }}">
    <title>Casting Performance - ALPC WA</title>
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
                <span class="monitoring-text">Casting Performance</span>
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
        {{-- Include the sidebar using Blade directive --}}
        @include('includes.sidebar')

        <div class="main-content">
            <div class="content-header" style="margin-bottom: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                    <div class="page-title" style="font-size: 32px; font-weight: 700; color: var(--accent-navy); text-shadow: 2px 2px 4px rgba(13, 59, 102, 0.1); border-left: 5px solid var(--accent-blue); padding-left: 15px; margin-bottom: 0;">
                        Casting Performance - ALPC WA
                    </div>
                </div>
                <div class="filter-controls" style="display: flex; gap: 10px; align-items: center; margin-top: 12px; flex-wrap: wrap;">
                    <div style="display: flex; align-items: center; gap: 8px; padding: 8px 14px; background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); border-radius: 8px; border: 2px solid #e9ecef; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <span style="font-size: 12px; font-weight: 600; color: #555;">Current Shift:</span>
                        <span id="current-shift-display" style="padding: 6px 14px; font-size: 12px; font-weight: 700; border-radius: 6px; background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); color: white; box-shadow: 0 2px 6px rgba(52, 152, 219, 0.3);">
                            Morning
                        </span>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px; padding: 8px 14px; background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); border-radius: 8px; border: 2px solid #e9ecef; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <span style="font-size: 12px; font-weight: 600; color: #555;">Real-Time Monitor:</span>
                        <button id="toggle-realtime" onclick="toggleRealTimeMonitoring()"
                                style="padding: 8px 16px; font-size: 12px; font-weight: 600; border: none; border-radius: 6px; cursor: pointer; transition: all 0.3s; background: linear-gradient(135deg, #27ae60 0%, #229954 100%); color: white; box-shadow: 0 2px 6px rgba(39, 174, 96, 0.3);">
                            <span id="toggle-status">ON</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="filter-section" style="margin-bottom: 12px; gap: 8px; display: flex; flex-wrap: wrap; align-items: center;">
                <div class="filter-label" style="font-size: 12px; font-weight: 600;">Date:</div>
                <input type="date" id="filter-date" class="filter-input" style="padding: 6px; font-size: 12px;">

                <div class="filter-label" style="font-size: 12px; font-weight: 600; margin-left: 12px;">Shift:</div>
                <select id="filter-shift" class="filter-input" style="padding: 6px; font-size: 12px;">
                    <option value="auto">Auto (Current Shift)</option>
                    <option value="morning">Morning (07:15 - 16:00)</option>
                    <option value="night">Night (19:00 - 06:00)</option>
                </select>

                <button class="filter-btn active" onclick="CastingPerformance.loadAllData()" style="padding: 6px 16px; font-size: 12px; margin-left: 8px;">
                    Apply Filter
                </button>
                <button class="filter-btn" onclick="resetFilters()" style="padding: 6px 12px; font-size: 12px; background: var(--gray-light); color: var(--text-dark);">
                    Reset
                </button>
            </div>

            <div class="metrics-grid" style="margin-bottom: 12px;">
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

            <div style="margin-bottom: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <h3 style="font-size: 14px; font-weight: 600; margin: 0;">Latest Temperature Readings</h3>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <label style="font-size: 11px; color: #666;">Show:</label>
                        <select id="temp-metric-filter" onchange="CastingPerformance.filterTemperatureMetrics(this.value)"
                                style="padding: 4px 8px; font-size: 11px; border: 1px solid #ddd; border-radius: 4px;">
                            <option value="all">All Sensors</option>
                            <option value="gate">Gate Only</option>
                            <option value="main">Main Only</option>
                            <option value="cooling">Cooling Only</option>
                        </select>
                    </div>
                </div>
                <div class="metrics-grid" id="temperature-metrics-grid">
                    <div class="metric-card" data-metric-type="gate">
                        <div class="metric-label" style="font-size: 11px;">R Lower Gate 1</div>
                        <div class="metric-value" style="font-size: 24px;">
                            <span id="metric-r-gate">--</span>
                            <span class="metric-unit" style="font-size: 14px;">°C</span>
                        </div>
                        <span class="status-badge status-normal" id="status-r-gate" style="font-size: 10px; padding: 3px 8px;">Loading...</span>
                    </div>

                    <div class="metric-card" data-metric-type="main">
                        <div class="metric-label" style="font-size: 11px;">R Lower Main 1</div>
                        <div class="metric-value" style="font-size: 24px;">
                            <span id="metric-r-main">--</span>
                            <span class="metric-unit" style="font-size: 14px;">°C</span>
                        </div>
                        <span class="status-badge status-normal" id="status-r-main" style="font-size: 10px; padding: 3px 8px;">Loading...</span>
                    </div>

                    <div class="metric-card" data-metric-type="gate">
                        <div class="metric-label" style="font-size: 11px;">L Lower Gate 1</div>
                        <div class="metric-value" style="font-size: 24px;">
                            <span id="metric-l-gate">--</span>
                            <span class="metric-unit" style="font-size: 14px;">°C</span>
                        </div>
                        <span class="status-badge status-normal" id="status-l-gate" style="font-size: 10px; padding: 3px 8px;">Loading...</span>
                    </div>

                    <div class="metric-card" data-metric-type="main">
                        <div class="metric-label" style="font-size: 11px;">L Lower Main 1</div>
                        <div class="metric-value" style="font-size: 24px;">
                            <span id="metric-l-main">--</span>
                            <span class="metric-unit" style="font-size: 14px;">°C</span>
                        </div>
                        <span class="status-badge status-normal" id="status-l-main" style="font-size: 10px; padding: 3px 8px;">Loading...</span>
                    </div>

                    <div class="metric-card" data-metric-type="cooling">
                        <div class="metric-label" style="font-size: 11px;">Cooling Water</div>
                        <div class="metric-value" style="font-size: 24px;">
                            <span id="metric-cooling">--</span>
                            <span class="metric-unit" style="font-size: 14px;">°C</span>
                        </div>
                        <span class="status-badge status-normal" id="status-cooling" style="font-size: 10px; padding: 3px 8px;">Loading...</span>
                    </div>
                </div>
            </div>

            <div class="chart-wrapper" style="margin-bottom: 10px; padding: 20px;">
                <div style="margin-bottom: 15px;">
                    <div class="chart-title" style="margin: 0 0 10px 0; font-size: 15px;">Temperature Trend</div>
                    <div style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
                        <label style="font-size: 11px; color: #666; font-weight: 600;">Filter:</label>
                        <label style="display: flex; align-items: center; gap: 5px; font-size: 11px; cursor: pointer;">
                            <input type="radio" name="temp-trend-filter" value="all" checked onchange="CastingPerformance.filterTrendChart(this.value)"
                                   style="cursor: pointer;">
                            <span>All Sensors</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 5px; font-size: 11px; cursor: pointer;">
                            <input type="radio" name="temp-trend-filter" value="gate" onchange="CastingPerformance.filterTrendChart(this.value)"
                                   style="cursor: pointer;">
                            <span>Gate Sensors</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 5px; font-size: 11px; cursor: pointer;">
                            <input type="radio" name="temp-trend-filter" value="main" onchange="CastingPerformance.filterTrendChart(this.value)"
                                   style="cursor: pointer;">
                            <span>Main Sensors</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 5px; font-size: 11px; cursor: pointer;">
                            <input type="radio" name="temp-trend-filter" value="cooling" onchange="CastingPerformance.filterTrendChart(this.value)"
                                   style="cursor: pointer;">
                            <span>Cooling Water</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 5px; font-size: 11px; cursor: pointer;">
                            <input type="radio" name="temp-trend-filter" value="right" onchange="CastingPerformance.filterTrendChart(this.value)"
                                   style="cursor: pointer;">
                            <span>Right Side</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 5px; font-size: 11px; cursor: pointer;">
                            <input type="radio" name="temp-trend-filter" value="left" onchange="CastingPerformance.filterTrendChart(this.value)"
                                   style="cursor: pointer;">
                            <span>Left Side</span>
                        </label>
                    </div>
                </div>
                <div style="position: relative; height: 350px; width: 100%;">
                    <canvas id="tempTrendChart" style="display: block; width: 100% !important; height: 100% !important;"></canvas>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 10px;">
                <div class="chart-wrapper" style="margin-bottom: 0; padding: 12px;">
                    <div class="chart-title" style="margin-bottom: 10px; font-size: 15px;">Capability Process</div>
                    <div style="position: relative; height: 250px; width: 100%; overflow: hidden;">
                        <canvas id="leftRightChart" style="display: block;"></canvas>
                    </div>
                </div>

                <div class="chart-wrapper" style="margin-bottom: 0; padding: 12px;">
                    <div class="chart-title" style="margin-bottom: 10px; font-size: 15px;">Last Shot</div>
                    <div style="position: relative; height: 250px; width: 100%; overflow: hidden;">
                        <canvas id="distributionChart" style="display: block;"></canvas>
                    </div>
                </div>
            </div>

            <div class="chart-wrapper" style="padding: 12px; margin-bottom: 8px;">
                <div class="chart-title" style="font-size: 15px; margin-bottom: 10px;">Recent Data (Last 50 Records)</div>
                
                <div style="margin-bottom: 10px; display: flex; gap: 8px; flex-wrap: wrap; align-items: center;">
                    <input type="text" id="table-search" placeholder="Search ID Part or Timestamp..." 
                           style="flex: 1; min-width: 200px; padding: 6px 10px; font-size: 12px; border: 1px solid var(--gray-border); border-radius: 4px;">
                    <select id="sort-column" style="padding: 6px 10px; font-size: 12px; border: 1px solid var(--gray-border); border-radius: 4px;">
                        <option value="timestamp-desc">Sort: Newest First</option>
                        <option value="timestamp-asc">Sort: Oldest First</option>
                        <option value="id-part-desc">Sort: ID Part (Z to A)</option>
                        <option value="id-part-asc">Sort: ID Part (A to Z)</option>
                        <option value="r-gate-desc">Sort: R Gate (High to Low)</option>
                        <option value="r-gate-asc">Sort: R Gate (Low to High)</option>
                        <option value="r-main-desc">Sort: R Main (High to Low)</option>
                        <option value="r-main-asc">Sort: R Main (Low to High)</option>
                    </select>
                    <button id="clear-search" style="padding: 6px 12px; font-size: 12px; background: var(--baby-blue); color: white; border: none; border-radius: 4px; cursor: pointer;">Clear</button>
                </div>
                
                <div style="overflow-x: auto; max-height: 350px; overflow-y: auto;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
                        <thead>
                            <tr style="background: var(--gray-light); position: sticky; top: 0;">
                                <th style="padding: 6px; text-align: left; font-size: 10px; font-weight: 600;">ID Part</th>
                                <th style="padding: 6px; text-align: left; font-size: 10px; font-weight: 600;">Timestamp</th>
                                <th style="padding: 6px; text-align: right; font-size: 10px; font-weight: 600;">R Gate</th>
                                <th style="padding: 6px; text-align: right; font-size: 10px; font-weight: 600;">R Main</th>
                                <th style="padding: 6px; text-align: right; font-size: 10px; font-weight: 600;">L Gate</th>
                                <th style="padding: 6px; text-align: right; font-size: 10px; font-weight: 600;">L Main</th>
                                <th style="padding: 6px; text-align: right; font-size: 10px; font-weight: 600;">Cooling</th>
                            </tr>
                        </thead>
                        <tbody id="data-table-body">
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 15px; color: var(--text-light);">
                                    <div class="loading-spinner"></div>
                                    Loading data...
                                </td>
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
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@3.0.1/dist/chartjs-plugin-annotation.min.js"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="{{ asset('js/main.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/casting-performance-core.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/casting-performance-wa-config.js') }}?v={{ time() }}"></script>
    <script>
        // Fungsi toggle submenu
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            if(sidebar) {
                sidebar.classList.toggle('active');
            }
        }

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
            
            // Nama bulan dalam bahasa Inggris
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
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('filter-date').value = today;
            document.getElementById('filter-shift').value = 'auto';

            document.getElementById('temp-metric-filter').value = 'all';

            const allRadio = document.querySelector('input[name="temp-trend-filter"][value="all"]');
            if (allRadio) allRadio.checked = true;

            if (typeof CastingPerformance !== 'undefined') {
                CastingPerformance.filterTemperatureMetrics('all');
                CastingPerformance.filterTrendChart('all');
                CastingPerformance.loadAllData();
            }
        }

        window.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            const filterDate = document.getElementById('filter-date');
            if(filterDate) filterDate.value = today;
            
            const filterShift = document.getElementById('filter-shift');
            if(filterShift) filterShift.value = 'auto';

            console.log('Page loaded - CastingPerformance module will auto-initialize');
        });

        // Real-time monitoring toggle
        let isRealTimeEnabled = true;

        function toggleRealTimeMonitoring() {
            isRealTimeEnabled = !isRealTimeEnabled;

            const toggleBtn = document.getElementById('toggle-realtime');
            const statusText = document.getElementById('toggle-status');
            const refreshStatus = document.getElementById('refresh-status');

            if (isRealTimeEnabled) {
                toggleBtn.style.background = 'linear-gradient(135deg, #27ae60 0%, #229954 100%)';
                statusText.textContent = 'ON';
                if(refreshStatus) refreshStatus.textContent = '2s';

                if (typeof CastingPerformance !== 'undefined') {
                    CastingPerformance.loadAllData();
                }
            } else {
                toggleBtn.style.background = 'linear-gradient(135deg, #e74c3c 0%, #c0392b 100%)';
                statusText.textContent = 'OFF';
                if(refreshStatus) refreshStatus.textContent = 'Paused';

                if (typeof CastingPerformance !== 'undefined') {
                    CastingPerformance.stopSimulation();
                }
            }
        }
    </script>
</body>
</html>