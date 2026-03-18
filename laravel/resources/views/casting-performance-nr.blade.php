<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/adm-logo.png') }}">
    <title>Casting Performance - ALPC NR</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/casting-performance.css') }}">
    <style>
        .btn-casting-hist{display:inline-flex;align-items:center;gap:7px;padding:8px 18px;font-size:12px;font-weight:700;border:none;border-radius:8px;cursor:pointer;transition:all .3s;background:linear-gradient(135deg,#0ea5e9 0%,#2563eb 60%,#1e3a8a 100%);color:#fff;box-shadow:0 4px 14px rgba(14,165,233,.4);margin-left:auto}
        .btn-casting-hist:hover{transform:translateY(-2px);box-shadow:0 6px 20px rgba(14,165,233,.6)}
        .btn-casting-hist svg{width:15px;height:15px}
        .c-hist-overlay{display:none;position:fixed;inset:0;background:rgba(13,59,102,.55);backdrop-filter:blur(3px);z-index:9999;align-items:center;justify-content:center;padding:20px}
        .c-hist-overlay.open{display:flex}
        .c-hist-modal{background:#f0f2f5;border-radius:16px;width:100%;max-width:1150px;max-height:90vh;display:flex;flex-direction:column;box-shadow:0 20px 60px rgba(13,59,102,.3);overflow:hidden}
        .c-hist-header{background:linear-gradient(135deg,#3498db 0%,#1a4fa0 100%);color:#fff;padding:18px 24px;display:flex;align-items:center;justify-content:space-between;flex-shrink:0}
        .c-hist-header h2{margin:0;font-size:16px;font-weight:700}.c-hist-header p{margin:3px 0 0;font-size:11px;opacity:.8}
        .c-hist-close{background:rgba(255,255,255,.15);border:none;color:#fff;width:32px;height:32px;border-radius:8px;font-size:16px;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:background .2s;flex-shrink:0}
        .c-hist-close:hover{background:rgba(255,255,255,.28)}
        .c-hist-filters{background:#fff;padding:14px 24px;display:flex;flex-wrap:wrap;gap:12px;align-items:flex-end;border-bottom:1px solid #e8e8e8;flex-shrink:0}
        .c-hist-fg{display:flex;flex-direction:column;gap:4px}
        .c-hist-fg label{font-size:10px;font-weight:700;color:#666;text-transform:uppercase;letter-spacing:.5px}
        .c-hist-fg select,.c-hist-fg input[type=date]{padding:6px 10px;border:1.5px solid #e0e0e0;border-radius:7px;font-size:12px;color:#1a3a6a;background:#fafafa;cursor:pointer;min-width:120px}
        .c-hist-btn-apply{padding:7px 18px;background:linear-gradient(135deg,#3498db 0%,#1a4fa0 100%);color:#fff;border:none;border-radius:7px;font-size:12px;font-weight:700;cursor:pointer;align-self:flex-end}
        .c-hist-btn-reset{padding:7px 12px;background:#f0f2f5;color:#666;border:1.5px solid #e0e0e0;border-radius:7px;font-size:12px;font-weight:600;cursor:pointer;align-self:flex-end}
        .c-hist-btn-csv{padding:7px 16px;background:linear-gradient(135deg,#1a7f4b 0%,#145c36 100%);color:#fff;border:none;border-radius:7px;font-size:12px;font-weight:700;cursor:pointer;align-self:flex-end;display:flex;align-items:center;gap:5px}
        .c-hist-btn-csv:disabled{opacity:.45;cursor:not-allowed}
        .c-hist-body{flex:1;overflow-y:auto;padding:16px 24px}
        .c-hist-meta{font-size:12px;color:#666;margin-bottom:10px}
        .c-hist-table-wrap{border-radius:10px;overflow:hidden;border:1px solid #e0e0e0;box-shadow:0 2px 8px rgba(0,0,0,.05)}
        .c-hist-table{width:100%;border-collapse:collapse;font-size:12px}
        .c-hist-table thead tr{background:linear-gradient(135deg,#3498db 0%,#1a4fa0 100%);color:#fff}
        .c-hist-table th{padding:10px 12px;text-align:left;font-weight:600;white-space:nowrap}
        .c-hist-table tbody tr{background:#fff}.c-hist-table tbody tr:nth-child(even){background:#f8f9fa}
        .c-hist-table tbody tr:hover{background:#eef5fb}
        .c-hist-table td{padding:8px 12px;border-bottom:1px solid #f0f0f0;white-space:nowrap;color:#2c3e50}
        .c-hist-empty{text-align:center;padding:50px 20px;color:#aaa;font-size:13px}
        .c-hist-loading{text-align:center;padding:50px 20px;color:#3498db;font-size:13px}
    </style>
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
                <div class="monitoring-subtitle">ALPC NR - Real-Time Monitoring</div>
            </div>
        </div>
        <div class="header-right">
            <div class="header-logos">
                <img src="{{ asset('assets/images/adm-logo.png') }}" alt="ADM" class="company-logo">
                <img src="{{ asset('assets/images/icare.png') }}" alt="I CARE" class="company-logo">
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
                        Casting Performance - ALPC NR
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
                    <button class="btn-casting-hist" onclick="castingHistory.open()">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        History
                    </button>
                </div>
            </div>
            <div class="filter-section" style="margin-bottom: 8px; gap: 8px; display: flex; flex-wrap: wrap; align-items: center;">
                {{-- NR LPC dropdown (LPCs 12, 13, 14) --}}
                <span style="font-size: 12px; font-weight: 700; color: #2980b9;">LPC:</span>
                <select id="lpc-select" style="padding: 6px 10px; font-size: 12px; font-weight: 600; border: 1px solid #bcd5e8; border-radius: 6px; background: #fff; color: #2c3e50; cursor: pointer;">
                    <option value="12">LPC 12</option>
                    <option value="13">LPC 13</option>
                    <option value="14">LPC 14</option>
                </select>
                <button onclick="applyLpc()" style="padding: 6px 12px; font-size: 12px; font-weight: 600; border: none; border-radius: 6px; cursor: pointer; background: linear-gradient(135deg, #2980b9 0%, #1a6fa0 100%); color: #fff; box-shadow: 0 2px 6px rgba(41,128,185,0.3);">
                    Apply LPC
                </button>
                <span id="active-lpc-badge" style="padding: 4px 12px; font-size: 12px; font-weight: 700; border-radius: 20px; background: linear-gradient(135deg, #27ae60 0%, #229954 100%); color: #fff;">
                    Active: LPC 12
                </span>
                <div style="width: 1px; height: 24px; background: #ddd; margin: 0 4px;"></div>
                <div class="filter-label" style="font-size: 12px; font-weight: 600;">Date:</div>
                <input type="date" id="filter-date" class="filter-input" style="padding: 6px; font-size: 12px;">
                <div class="filter-label" style="font-size: 12px; font-weight: 600;">Shift:</div>
                <select id="filter-shift" class="filter-input" style="padding: 6px; font-size: 12px;">
                    <option value="auto">Auto (Current Shift)</option>
                    <option value="morning">Morning (07:15 - 16:00)</option>
                    <option value="night">Night (19:00 - 06:00)</option>
                </select>
                <button class="filter-btn active" onclick="CastingPerformance.loadAllData()" style="padding: 6px 14px; font-size: 12px;">Apply Filter</button>
                <button class="filter-btn" onclick="resetFilters()" style="padding: 6px 10px; font-size: 12px; background: var(--gray-light); color: var(--text-dark);">Reset</button>
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
    <script src="{{ asset('js/casting-performance-nr-config.js') }}"></script>
    <script>
        function applyLpc() {
            const lpcSelect = document.getElementById('lpc-select');
            const badge = document.getElementById('active-lpc-badge');
            if (!lpcSelect) return;
            const lpc = parseInt(lpcSelect.value, 10);
            if (badge) badge.textContent = 'Active: LPC ' + lpc;
            if (typeof CastingPerformance !== 'undefined') CastingPerformance.loadAllData();
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

    <!-- Casting History Modal - NR -->
    <div class="c-hist-overlay" id="c-hist-overlay" onclick="if(event.target===this)castingHistory.close()">
        <div class="c-hist-modal">
            <div class="c-hist-header">
                <div>
                    <h2>📋 Casting Temperature History — ALPC NR</h2>
                    <p>Temperature records from database | Select LPC and date to view data</p>
                </div>
                <button class="c-hist-close" onclick="castingHistory.close()">✕</button>
            </div>
            <div class="c-hist-filters">
                <div class="c-hist-fg">
                    <label>LPC</label>
                    <select id="hist-lpc">
                        <option value="12">LPC 12</option>
                        <option value="13">LPC 13</option>
                        <option value="14">LPC 14</option>
                    </select>
                </div>
                <div class="c-hist-fg">
                    <label>Date</label>
                    <input type="date" id="hist-date">
                </div>
                <div class="c-hist-fg">
                    <label>Shift</label>
                    <select id="hist-shift">
                        <option value="all">All Shifts</option>
                        <option value="morning">Morning (07:15–16:00)</option>
                        <option value="night">Night (19:00–06:00)</option>
                    </select>
                </div>
                <button class="c-hist-btn-apply" onclick="castingHistory.load()">Apply</button>
                <button class="c-hist-btn-reset" onclick="castingHistory.reset()">Reset</button>
                <button class="c-hist-btn-csv" id="hist-csv-btn" onclick="castingHistory.download()" disabled>
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Download CSV
                </button>
            </div>
            <div class="c-hist-body">
                <div class="c-hist-meta" id="hist-meta">—</div>
                <div id="hist-table-area"><div class="c-hist-empty">Select filters and click Apply to load history.</div></div>
            </div>
        </div>
    </div>

    <script>
    const castingHistory = {
        apiUrl: '/api/casting-data',
        dateField: 'datetime_stamp',
        currentData: [],
        columns: [
            {key:'r_lower_gate1',label:'R Lower Gate 1'},{key:'r_lower_main1',label:'R Lower Main 1'},
            {key:'l_lower_gate1',label:'L Lower Gate 1'},{key:'l_lower_main1',label:'L Lower Main 1'},
            {key:'cooling_water',label:'Cooling Water'}
        ],
        open() {
            const badge = document.getElementById('active-lpc-badge');
            const lpcText = badge ? badge.textContent.replace('Active: LPC','').trim() : '12';
            const histLpc = document.getElementById('hist-lpc');
            if (histLpc) histLpc.value = lpcText;
            const activeDate = document.getElementById('filter-date')?.value;
            const histDate = document.getElementById('hist-date');
            if (activeDate && histDate) histDate.value = activeDate;
            document.getElementById('c-hist-overlay').classList.add('open');
        },
        close() { document.getElementById('c-hist-overlay').classList.remove('open'); },
        async load() {
            const area = document.getElementById('hist-table-area');
            const meta = document.getElementById('hist-meta');
            area.innerHTML = '<div class="c-hist-loading">Loading...</div>';
            const lpc = document.getElementById('hist-lpc')?.value || 12;
            const date = document.getElementById('hist-date')?.value || new Date().toISOString().split('T')[0];
            const shift = document.getElementById('hist-shift')?.value || 'all';
            let st = '00:00:00', et = '23:59:59';
            if (shift === 'morning') { st = '07:15:00'; et = '16:00:00'; }
            else if (shift === 'night') { st = '19:00:00'; et = '06:00:00'; }
            try {
                const params = new URLSearchParams({action:'trend',lpc,date,start_time:st,end_time:et,limit:500});
                const res = await fetch(`${this.apiUrl}?${params}`);
                const json = await res.json();
                if (json.status === 'success' && json.data?.length) {
                    this.currentData = json.data;
                    meta.textContent = `${json.data.length} records — LPC ${lpc} | ${date} | ${shift === 'all' ? 'All Shifts' : shift}`;
                    area.innerHTML = this._renderTable(json.data);
                    document.getElementById('hist-csv-btn').disabled = false;
                } else {
                    this.currentData = [];
                    meta.textContent = '—';
                    area.innerHTML = '<div class="c-hist-empty">No data found for the selected filters.</div>';
                    document.getElementById('hist-csv-btn').disabled = true;
                }
            } catch(e) {
                this.currentData = [];
                area.innerHTML = '<div class="c-hist-empty">Failed to load data. Check API connection.</div>';
            }
        },
        _renderTable(data) {
            const cols = this.columns;
            const th = ['Datetime',...cols.map(c=>c.label)].map(h=>`<th>${h}</th>`).join('');
            const rows = data.map(r => {
                const dt = (r[this.dateField]||'—').toString().replace('T',' ').substring(0,19);
                return `<tr><td>${dt}</td>${cols.map(c=>`<td>${r[c.key]!=null?parseFloat(r[c.key]).toFixed(1):'—'}</td>`).join('')}</tr>`;
            }).join('');
            return `<div class="c-hist-table-wrap"><table class="c-hist-table"><thead><tr>${th}</tr></thead><tbody>${rows}</tbody></table></div>`;
        },
        reset() {
            document.getElementById('hist-date').value = new Date().toISOString().split('T')[0];
            document.getElementById('hist-shift').value = 'all';
            document.getElementById('hist-table-area').innerHTML = '<div class="c-hist-empty">Select filters and click Apply to load history.</div>';
            document.getElementById('hist-meta').textContent = '—';
            document.getElementById('hist-csv-btn').disabled = true;
            this.currentData = [];
        },
        download() {
            if (!this.currentData.length) return;
            const lpc = document.getElementById('hist-lpc')?.value || '';
            const date = document.getElementById('hist-date')?.value || '';
            const headers = ['Datetime',...this.columns.map(c=>c.label)];
            const rows = this.currentData.map(r => {
                const dt = (r[this.dateField]||'').toString().replace('T',' ').substring(0,19);
                return [dt,...this.columns.map(c=>r[c.key]??'')];
            });
            const csv = [headers,...rows].map(r=>r.join(',')).join('\n');
            const a = document.createElement('a');
            a.href = URL.createObjectURL(new Blob([csv],{type:'text/csv'}));
            a.download = `casting-nr-lpc${lpc}-${date}.csv`;
            a.click();
        }
    };
    </script>
</body>
</html>
