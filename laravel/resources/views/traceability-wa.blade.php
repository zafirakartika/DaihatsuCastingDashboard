<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/daihatsu-logo.png') }}">
    <title>Traceability WA - Casting SMART Factory</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/traceability-enhanced.css') }}">
</head>
<body>
    <script src="{{ asset('js/traceability-shared.js') }}"></script>

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
                <span class="monitoring-text">Traceability System</span>
                <div class="monitoring-subtitle">ALPC WA - Casting SMART Factory</div>
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

        <div class="trace-content">
            <div class="traceability-container">
                <div class="control-panel">
                    <div class="filter-row">
                        <div class="filter-group">
                            <label class="filter-label">Date Range</label>
                            <input type="date" id="filter-date-start" class="filter-input">
                        </div>
                        <div class="filter-group">
                            <label class="filter-label">To</label>
                            <input type="date" id="filter-date-end" class="filter-input">
                        </div>
                        <div class="filter-group">
                            <label class="filter-label">Shift</label>
                            <select id="filter-shift" class="filter-input">
                                <option value="all">All Shifts</option>
                                <option value="morning">Morning (07:15 - 16:00)</option>
                                <option value="night">Night (19:00 - 06:00)</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label class="filter-label">Cavity</label>
                            <select id="filter-cavity" class="filter-input">
                                <option value="all">All Cavities</option>
                                <option value="L">Left (L)</option>
                                <option value="R">Right (R)</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label class="filter-label">Limit</label>
                            <select id="filter-limit" class="filter-input">
                                <option value="50">50 Records</option>
                                <option value="100">100 Records</option>
                                <option value="200">200 Records</option>
                                <option value="500">500 Records</option>
                                <option value="1000">1000 Records</option>
                                <option value="5000">5000 Records</option>
                                <option value="all" selected>All Records</option>
                            </select>
                        </div>
                        <button class="btn btn-primary" onclick="loadTraceabilityData()">
                            <span class="btn-icon"></span> Apply Filter
                        </button>
                        <button class="btn btn-secondary" onclick="resetFilters()">
                            <span class="btn-icon">↻</span> Reset
                        </button>
                    </div>

                    <div class="control-row">
                        <div class="search-box">
                            <input type="text" id="table-search" class="search-input"
                                   placeholder="Search by Shot Number, Part ID, or Timestamp...">
                            <span class="search-icon"></span>
                        </div>
                        <div class="export-buttons">
                            <button class="btn btn-export" onclick="exportToCSV()">
                                <span class="btn-icon"></span> Export CSV
                            </button>
                            <button class="btn btn-export" onclick="exportToExcel()">
                                <span class="btn-icon"></span> Export Excel
                            </button>
                            <button class="btn btn-export" onclick="printReport()">
                                <span class="btn-icon">🖨</span> Print
                            </button>
                        </div>
                    </div>
                </div>

                <div class="table-section">
                    <div class="table-header">
                        <h3 class="table-title">Production Traceability Records</h3>
                        <div class="table-info">
                            Showing <span id="record-count">0</span> records | Last updated: <span id="last-update">--:--:--</span>
                        </div>
                    </div>

                    <div class="table-container">
                        <table class="traceability-table" id="main-table">
                            <thead>
                                <tr>
                                    <th style="width: 60px; text-align: center;"><div class="th-content"><span>Detail</span></div></th>
                                    <th class="sortable" data-column="no_shot"><div class="th-content"><span>Shot #</span><span class="sort-indicator">↕</span></div></th>
                                    <th class="sortable" data-column="id_part"><div class="th-content"><span>Part ID</span><span class="sort-indicator">↕</span></div></th>
                                    <th class="sortable" data-column="lpc"><div class="th-content"><span>LPC</span><span class="sort-indicator">↕</span></div></th>
                                    <th class="sortable" data-column="year"><div class="th-content"><span>Year</span><span class="sort-indicator">↕</span></div></th>
                                    <th class="sortable" data-column="month"><div class="th-content"><span>Month</span><span class="sort-indicator">↕</span></div></th>
                                    <th class="sortable" data-column="date"><div class="th-content"><span>Date</span><span class="sort-indicator">↕</span></div></th>
                                    <th class="sortable" data-column="shift"><div class="th-content"><span>Shift</span><span class="sort-indicator">↕</span></div></th>
                                    <th class="sortable" data-column="cavity"><div class="th-content"><span>Cavity</span><span class="sort-indicator">↕</span></div></th>
                                    <th class="sortable" data-column="timestamp"><div class="th-content"><span>Timestamp</span><span class="sort-indicator">↕</span></div></th>
                                </tr>
                            </thead>
                            <tbody id="traceability-tbody">
                                <tr>
                                    <td colspan="10" class="loading-cell">
                                        <div class="loading-spinner"></div>
                                        <div>Loading traceability data...</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="pagination-container">
                        <div class="pagination-info">
                            Page <span id="current-page">1</span> of <span id="total-pages">1</span>
                        </div>
                        <div class="pagination-controls">
                            <button class="page-btn" onclick="changePage('first')" id="btn-first">⟨⟨</button>
                            <button class="page-btn" onclick="changePage('prev')" id="btn-prev">⟨</button>
                            <button class="page-btn" onclick="changePage('next')" id="btn-next">⟩</button>
                            <button class="page-btn" onclick="changePage('last')" id="btn-last">⟩⟩</button>
                        </div>
                        <div class="pagination-size">
                            <label>Rows per page:</label>
                            <select id="page-size" onchange="changePageSize()">
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="500">500</option>
                                <option value="all" selected>All</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="analytics-section">
                    <h3 class="section-title">Production Analytics</h3>
                    <div class="analytics-grid">
                        <div class="chart-card">
                            <h4 class="chart-card-title">LPC Distribution (Top 5)</h4>
                            <canvas id="lpcDistChart"></canvas>
                        </div>
                        <div class="chart-card">
                            <h4 class="chart-card-title">Production Trend (Last 50 Parts)</h4>
                            <canvas id="productionTrendChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="detailModal" class="modal">
        <div class="modal-content modal-large">
            <div class="modal-header">
                <h2 id="modal-title">154 ST-09 MAIN LINE POS 1 (WA LINE)</h2>
                <span class="modal-close" onclick="closeDetailModal()">&times;</span>
            </div>
            <div class="modal-body">
                <div class="modal-image-section">
                    <div class="part-image-container">
                        <img src="{{ asset('assets/images/part-diagram.png') }}" alt="Part Diagram" class="part-diagram" id="part-diagram">
                        <div class="image-placeholder" id="image-placeholder">
                            <svg width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="#ccc" stroke-width="1">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                <polyline points="21 15 16 10 5 21"></polyline>
                            </svg>
                            <p>Part Image</p>
                        </div>
                    </div>
                </div>
                <div class="modal-details-section" style="padding: 12px 0;">
                    <div class="detail-row" style="display: grid; grid-template-columns: repeat(8, 1fr); gap: 8px; align-items: start;">
                        <div class="detail-col" style="min-width: 0;">
                            <label style="font-size: 10px; font-weight: 600; color: #666; display: block; margin-bottom: 3px; text-transform: uppercase;">Part ID</label>
                            <span id="detail-id-part" style="font-size: 12px; font-weight: 600; color: #667eea; display: block; overflow: hidden; text-overflow: ellipsis;"></span>
                        </div>
                        <div class="detail-col" style="min-width: 0;">
                            <label style="font-size: 10px; font-weight: 600; color: #666; display: block; margin-bottom: 3px; text-transform: uppercase;">Shot</label>
                            <span id="detail-shot" style="font-size: 12px; font-weight: 600; display: block;"></span>
                        </div>
                        <div class="detail-col" style="min-width: 0;">
                            <label style="font-size: 10px; font-weight: 600; color: #666; display: block; margin-bottom: 3px; text-transform: uppercase;">LPC</label>
                            <span id="detail-lpc" style="font-size: 12px; font-weight: 600; display: block;"></span>
                        </div>
                        <div class="detail-col" style="min-width: 0;">
                            <label style="font-size: 10px; font-weight: 600; color: #666; display: block; margin-bottom: 3px; text-transform: uppercase;">Shift</label>
                            <span id="detail-shift" style="font-size: 12px; font-weight: 600; display: block;"></span>
                        </div>
                        <div class="detail-col" style="min-width: 0;">
                            <label style="font-size: 10px; font-weight: 600; color: #666; display: block; margin-bottom: 3px; text-transform: uppercase;">Type</label>
                            <span id="detail-type" style="font-size: 12px; font-weight: 600; display: block;"></span>
                        </div>
                        <div class="detail-col" style="min-width: 0;">
                            <label style="font-size: 10px; font-weight: 600; color: #666; display: block; margin-bottom: 3px; text-transform: uppercase;">MC</label>
                            <span id="detail-mc" style="font-size: 12px; font-weight: 600; display: block;"></span>
                        </div>
                        <div class="detail-col" style="min-width: 0;">
                            <label style="font-size: 10px; font-weight: 600; color: #666; display: block; margin-bottom: 3px; text-transform: uppercase;">Create</label>
                            <span id="detail-create-timestamp" style="font-size: 11px; display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"></span>
                        </div>
                        <div class="detail-col" style="min-width: 0;">
                            <label style="font-size: 10px; font-weight: 600; color: #666; display: block; margin-bottom: 3px; text-transform: uppercase;">Judge</label>
                            <span id="detail-judge" class="judge-badge" style="display: inline-block; font-size: 11px; padding: 2px 8px;">OK</span>
                        </div>
                    </div>
                </div>
                <div class="modal-table-section">
                    <table class="process-table">
                        <thead>
                            <tr><th>PROCESS NAME</th><th>JUDGE</th></tr>
                        </thead>
                        <tbody id="process-table-body">
                            <tr><td>FINISHING 1</td><td><span class="judge-badge judge-ok">OK</span></td></tr>
                            <tr><td>T6</td><td><span class="judge-badge judge-ok">OK</span></td></tr>
                            <tr><td>FINISHING 2</td><td><span class="judge-badge judge-ok">OK</span></td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeDetailModal()">Close</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="{{ asset('js/traceability-wa.js') }}"></script>
</body>
</html>
