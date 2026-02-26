<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/daihatsu-logo.png') }}">
    <title>Finishing 1 Performance - ALPC NR</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
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
                <span class="monitoring-text">Finishing 1 Performance</span>
                <div class="monitoring-subtitle">ALPC NR - Real-Time Monitoring</div>
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
                    Finishing 1 Performance - ALPC NR
                </div>
                <div class="filter-controls" style="display: flex; gap: 10px; align-items: center;">
                    <div style="display: flex; align-items: center; gap: 8px; padding: 4px 12px; background: white; border-radius: 6px; border: 1px solid #ddd;">
                        <span style="font-size: 12px; font-weight: 600; color: #555;">Current Shift:</span>
                        <span id="current-shift-display" style="padding: 4px 12px; font-size: 12px; font-weight: 700; border-radius: 4px; background: #3498db; color: white;">
                            Morning
                        </span>
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
            </div>
        </div>
    </div>
    <script src="{{ asset('js/main.js') }}"></script>
</body>
</html>
