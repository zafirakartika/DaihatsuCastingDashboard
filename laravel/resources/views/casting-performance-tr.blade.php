<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/daihatsu-logo.png') }}">
    <title>Casting Performance - ALPC TR</title>
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
                <div class="monitoring-subtitle">ALPC TR - Real-Time Monitoring</div>
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
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                    <div class="page-title" style="font-size: 32px; font-weight: 700; color: var(--accent-navy); text-shadow: 2px 2px 4px rgba(13, 59, 102, 0.1); border-left: 5px solid var(--accent-blue); padding-left: 15px; margin-bottom: 0;">
                        Casting Performance - ALPC TR
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

            {{-- Filter Bar: LPC + Date + Shift all on one line --}}
            <div class="filter-section" style="margin-bottom: 8px; gap: 8px; display: flex; flex-wrap: wrap; align-items: center;">
                {{-- LPC group --}}
                <span style="font-size: 12px; font-weight: 700; color: #2980b9;">LPC:</span>
                <select id="lpc-select" style="padding: 6px 10px; font-size: 12px; font-weight: 600; border: 1px solid #bcd5e8; border-radius: 6px; background: #fff; color: #2c3e50; cursor: pointer;">
                    <option value="1">LPC 1</option>
                    <option value="2" selected>LPC 2</option>
                    <option value="3">LPC 3</option>
                    <option value="4">LPC 4</option>
                    <option value="6">LPC 6</option>
                </select>
                <button onclick="applyLpc()" style="padding: 6px 12px; font-size: 12px; font-weight: 600; border: none; border-radius: 6px; cursor: pointer; background: linear-gradient(135deg, #2980b9 0%, #1a6fa0 100%); color: #fff; box-shadow: 0 2px 6px rgba(41,128,185,0.3);">
                    Apply LPC
                </button>
                <span id="active-lpc-badge" style="padding: 4px 12px; font-size: 12px; font-weight: 700; border-radius: 20px; background: linear-gradient(135deg, #27ae60 0%, #229954 100%); color: #fff;">
                    Active: LPC 2
                </span>
                {{-- Divider --}}
                <div style="width: 1px; height: 24px; background: #ddd; margin: 0 4px;"></div>
                {{-- Date + Shift group --}}
                <span class="filter-label" style="font-size: 12px; font-weight: 600;">Date:</span>
                <input type="date" id="filter-date" class="filter-input" style="padding: 6px; font-size: 12px;">
                <span class="filter-label" style="font-size: 12px; font-weight: 600;">Shift:</span>
                <select id="filter-shift" class="filter-input" style="padding: 6px; font-size: 12px;">
                    <option value="auto">Auto (Current Shift)</option>
                    <option value="morning">Morning (07:15 - 16:00)</option>
                    <option value="night">Night (19:00 - 06:00)</option>
                </select>
                <button class="filter-btn active" onclick="CastingPerformanceTR.loadAllData()" style="padding: 6px 14px; font-size: 12px;">Apply Filter</button>
                <button class="filter-btn" onclick="resetFilters()" style="padding: 6px 10px; font-size: 12px; background: var(--gray-light); color: var(--text-dark);">Reset</button>
            </div>

            @include('includes.casting-tr-metrics') 
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
    <script src="{{ asset('js/casting-performance-tr-config.js') }}?v={{ time() }}"></script>
    
    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            if(sidebar) sidebar.classList.toggle('active');
        }

        // Apply selected LPC
        function applyLpc() {
            const lpcSelect = document.getElementById('lpc-select');
            const badge = document.getElementById('active-lpc-badge');
            if (!lpcSelect) return;
            const lpc = parseInt(lpcSelect.value, 10);
            if (typeof CastingPerformanceTR !== 'undefined') {
                CastingPerformanceTR.setLpc(lpc);
                CastingPerformanceTR.loadAllData();
            }
            if (badge) badge.textContent = 'Active: LPC ' + lpc;
        }

        function resetFilters() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('filter-date').value = today;
            document.getElementById('filter-shift').value = 'auto';
            document.getElementById('temp-metric-filter').value = 'all';

            const allRadio = document.querySelector('input[name="temp-trend-filter"][value="all"]');
            if (allRadio) allRadio.checked = true;

            if(typeof CastingPerformanceTR !== 'undefined') {
                CastingPerformanceTR.filterTemperatureMetrics('all');
                CastingPerformanceTR.filterTrendChart('all');
                CastingPerformanceTR.loadAllData();
            }
        }

        window.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            const dateInput = document.getElementById('filter-date');
            if(dateInput) dateInput.value = today;

            const shiftInput = document.getElementById('filter-shift');
            if(shiftInput) shiftInput.value = 'auto';
        });

        // Real-time monitoring toggle
        let isRealTimeEnabled = true;

        function toggleRealTimeMonitoring() {
            isRealTimeEnabled = !isRealTimeEnabled;
            const toggleBtn = document.getElementById('toggle-realtime');
            const statusText = document.getElementById('toggle-status');
            const refreshStatus = document.getElementById('refresh-status');

            if (isRealTimeEnabled) {
                toggleBtn.style.background = '#27ae60';
                statusText.textContent = 'ON';
                if(refreshStatus) refreshStatus.textContent = '3s';
                if(typeof CastingPerformanceTR !== 'undefined') CastingPerformanceTR.startSimulation(3);
            } else {
                toggleBtn.style.background = '#e74c3c';
                statusText.textContent = 'OFF';
                if(refreshStatus) refreshStatus.textContent = 'Paused';
                if(typeof CastingPerformanceTR !== 'undefined') CastingPerformanceTR.stopSimulation();
            }
        }
    </script>
</body>
</html>