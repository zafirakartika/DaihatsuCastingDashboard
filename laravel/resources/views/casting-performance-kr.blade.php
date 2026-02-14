<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/daihatsu-logo.png') }}">
    <title>Casting Performance - ALPC KR</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/casting-performance.css') }}">
</head>
<body>
    <div class="top-header">
        <div class="logo-section">
            <div class="hamburger-menu" onclick="toggleSidebar()">
                <div class="hamburger-line"></div><div class="hamburger-line"></div><div class="hamburger-line"></div>
            </div>
            <img src="{{ asset('assets/images/daihatsu-logo.png') }}" alt="Daihatsu Logo" class="company-logo">
        </div>
        <div class="header-center">
            <div class="monitoring-title">
                <span class="monitoring-text">Casting Performance</span>
                <div class="monitoring-subtitle">ALPC KR - Real-Time Monitoring</div>
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
                        Casting Performance - ALPC KR
                    </div>
                </div>
                <div class="filter-controls" style="display: flex; gap: 10px; align-items: center; margin-top: 12px; flex-wrap: wrap;">
                    <div style="display: flex; align-items: center; gap: 8px; padding: 8px 14px; background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); border-radius: 8px; border: 2px solid #e9ecef; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <span style="font-size: 12px; font-weight: 600; color: #555;">Current Shift:</span>
                        <span id="current-shift-display" style="padding: 6px 14px; font-size: 12px; font-weight: 700; border-radius: 6px; background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); color: white; box-shadow: 0 2px 6px rgba(52, 152, 219, 0.3);">Morning</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px; padding: 8px 14px; background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); border-radius: 8px; border: 2px solid #e9ecef; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <span style="font-size: 12px; font-weight: 600; color: #555;">Real-Time Monitor:</span>
                        <button id="toggle-realtime" onclick="toggleRealTimeMonitoring()" style="padding: 8px 16px; font-size: 12px; font-weight: 600; border: none; border-radius: 6px; cursor: pointer; transition: all 0.3s; background: linear-gradient(135deg, #27ae60 0%, #229954 100%); color: white; box-shadow: 0 2px 6px rgba(39, 174, 96, 0.3);">
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
                <button class="filter-btn active" onclick="CastingPerformance.loadAllData()" style="padding: 6px 16px; font-size: 12px; margin-left: 8px;">Apply Filter</button>
                <button class="filter-btn" onclick="resetFilters()" style="padding: 6px 12px; font-size: 12px; background: var(--gray-light); color: var(--text-dark);">Reset</button>
            </div>
            <div id="casting-metrics-container">
                <div style="text-align: center; padding: 40px; color: #999;">Loading casting performance data...</div>
            </div>
            <div class="refresh-info" style="font-size: 12px; padding: 5px 0; color: var(--text-light);">Last updated: <span id="last-update">--:--:--</span> | Auto-refresh: <span id="refresh-status">60s</span></div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@3.0.1/dist/chartjs-plugin-annotation.min.js"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="{{ asset('js/casting-performance-core.js') }}"></script>
    <script src="{{ asset('js/casting-performance-kr-config.js') }}"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            if(sidebar) sidebar.classList.toggle('active');
        }
        function resetFilters() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('filter-date').value = today;
            document.getElementById('filter-shift').value = 'auto';
            if (typeof CastingPerformance !== 'undefined') CastingPerformance.loadAllData();
        }
        window.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            const dateInput = document.getElementById('filter-date');
            if(dateInput) dateInput.value = today;
            const shiftInput = document.getElementById('filter-shift');
            if(shiftInput) shiftInput.value = 'auto';
        });
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
                if (typeof CastingPerformance !== 'undefined') CastingPerformance.loadAllData();
            } else {
                toggleBtn.style.background = 'linear-gradient(135deg, #e74c3c 0%, #c0392b 100%)';
                statusText.textContent = 'OFF';
                if(refreshStatus) refreshStatus.textContent = 'Paused';
                if (typeof CastingPerformance !== 'undefined') CastingPerformance.stopSimulation();
            }
        }
    </script>
</body>
</html>
