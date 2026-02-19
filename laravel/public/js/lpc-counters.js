class LPCCountersDashboard {
    constructor() {
        this.updateInterval = 10000; // 10 seconds
        this.init();
    }

    init() {
        this.updateCounters();
        this.startAutoUpdate();
    }

    startAutoUpdate() {
        setInterval(() => this.updateCounters(), this.updateInterval);
    }

    toggleRealTime() {
        // Simple toggle logic for demo
        const btn = document.getElementById('toggle-status');
        if(btn.innerText === 'ON') {
            btn.innerText = 'OFF';
            btn.parentElement.style.background = '#e74c3c';
        } else {
            btn.innerText = 'ON';
            btn.parentElement.style.background = '#27ae60';
            this.updateCounters();
        }
    }

    async updateCounters() {
        const btn = document.getElementById('toggle-status');
        if(btn && btn.innerText === 'OFF') return;

        try {
            // FIXED: Point to Laravel API Route
            const response = await fetch('/api/counters');
            const result = await response.json();

            if (result.success) {
                this.renderData(result.data);
                this.updateTime(result.data.timestamp);
            }
        } catch (error) {
            console.error('Error fetching counters:', error);
        }
    }

    renderData(data) {
        // Helper to safely get value or 0
        const getVal = (obj, key) => (obj && obj[key]) ? obj[key] : 0;

        // TR Line
        if (data.tr_counter) {
            this.setVal('value-tr-1', getVal(data.tr_counter, 'LPC1'));
            this.setVal('value-tr-2', getVal(data.tr_counter, 'LPC2'));
            this.setVal('value-tr-3', getVal(data.tr_counter, 'LPC3'));
            this.setVal('value-tr-4', getVal(data.tr_counter, 'LPC4'));
            this.setVal('value-tr-6', getVal(data.tr_counter, 'LPC6'));
        }

        // 3SZ/KR
        if (data.sz_kr_counter) {
            this.setVal('value-3sz-kr', getVal(data.sz_kr_counter, 'LPC9'));
        }

        // NR Line
        if (data.nr_counter) {
            this.setVal('value-nr-12', getVal(data.nr_counter, 'LPC12'));
            this.setVal('value-nr-13', getVal(data.nr_counter, 'LPC13'));
            this.setVal('value-nr-14', getVal(data.nr_counter, 'LPC14'));
        }

        // WA Line
        if (data.wa_counter) {
            this.setVal('value-wa', getVal(data.wa_counter, 'LPC11'));
        }
    }

    setVal(id, value) {
        const el = document.getElementById(id);
        if (el && el.innerText != value) {
            el.innerText = value;
            el.classList.add('updated-flash');
            setTimeout(() => el.classList.remove('updated-flash'), 1000);
        }
    }

    updateTime(time) {
        const el = document.getElementById('last-updated');
        if (!el) return;
        const d = new Date(time);
        const pad = n => String(n).padStart(2, '0');
        const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        const formatted = `${pad(d.getDate())}-${months[d.getMonth()]}-${d.getFullYear()} ${pad(d.getHours())}:${pad(d.getMinutes())}:${pad(d.getSeconds())}`;
        el.innerText = 'Last updated: ' + formatted;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    window.lpcCountersDashboard = new LPCCountersDashboard();
    window.lpcHistory = new LPCHistory();
});

class LPCHistory {

    // Column definitions per line
    static COLUMNS = {
        tr:   [{ key: 'LPC1', label: 'LPC 1' }, { key: 'LPC2', label: 'LPC 2' }, { key: 'LPC3', label: 'LPC 3' }, { key: 'LPC4', label: 'LPC 4' }],
        wa:   [{ key: 'LPC11', label: 'LPC 11' }],
        szkr: [{ key: 'LPC9', label: 'LPC 9' }],
        nr:   [{ key: 'LPC12', label: 'LPC 12' }, { key: 'LPC13', label: 'LPC 13' }, { key: 'LPC14', label: 'LPC 14' }],
    };

    open() {
        document.getElementById('history-overlay').classList.add('open');
        this.load();
    }

    close() {
        document.getElementById('history-overlay').classList.remove('open');
    }

    reset() {
        document.getElementById('hist-line').value  = 'tr';
        document.getElementById('hist-shift').value = 'all';
        document.getElementById('hist-month').value = '';
        document.getElementById('hist-year').value  = '2026';
        document.getElementById('hist-date').value  = '';
        document.getElementById('hist-result-info').textContent = '—';
        document.getElementById('hist-table-area').innerHTML =
            '<div class="history-empty"><p>Select filters and click Apply to load history.</p></div>';
    }

    shiftLabel(row) {
        const h = parseInt(row.hour);
        if (h === 20) return '<span class="shift-badge shift-morning">Morning</span>';
        if (h === 7)  return '<span class="shift-badge shift-night">Night</span>';
        return '<span class="shift-badge shift-unknown">—</span>';
    }

    formatDatetime(dt) {
        if (!dt) return '—';
        const d = new Date(dt);
        const pad = n => String(n).padStart(2, '0');
        const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        return `${pad(d.getDate())}-${months[d.getMonth()]}-${d.getFullYear()} ${pad(d.getHours())}:${pad(d.getMinutes())}:${pad(d.getSeconds())}`;
    }

    async load() {
        const line  = document.getElementById('hist-line').value;
        const shift = document.getElementById('hist-shift').value;
        const month = document.getElementById('hist-month').value;
        const year  = document.getElementById('hist-year').value;
        const date  = document.getElementById('hist-date').value;

        const area = document.getElementById('hist-table-area');
        const info = document.getElementById('hist-result-info');

        area.innerHTML = '<div class="history-loading">⏳ Loading data...</div>';
        info.textContent = '—';

        const params = new URLSearchParams({ line, shift });
        if (date)  { params.set('date', date); }
        else       { if (month) params.set('month', month); if (year) params.set('year', year); }

        try {
            const res  = await fetch('/api/counters/history?' + params.toString());
            const json = await res.json();

            if (!json.success) throw new Error(json.message || 'API error');

            const rows = json.data;
            info.innerHTML = `Showing <strong>${rows.length}</strong> record${rows.length !== 1 ? 's' : ''}`;

            if (rows.length === 0) {
                area.innerHTML = `
                    <div class="history-empty">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <p>No records found for the selected filters.</p>
                    </div>`;
                return;
            }

            const cols = LPCHistory.COLUMNS[line] || [];

            // Build table
            let thead = `<tr>
                <th>#</th>
                <th>Date &amp; Time</th>
                <th>Shift</th>
                ${cols.map(c => `<th>${c.label}</th>`).join('')}
                <th>Total</th>
            </tr>`;

            let tbody = rows.map((row, i) => `
                <tr>
                    <td>${i + 1}</td>
                    <td>${this.formatDatetime(row.datetime)}</td>
                    <td>${this.shiftLabel(row)}</td>
                    ${cols.map(c => `<td class="num">${row[c.key] ?? 0}</td>`).join('')}
                    <td class="total-col">${row.total ?? 0}</td>
                </tr>`).join('');

            area.innerHTML = `
                <div class="history-table-wrap">
                    <table class="history-table">
                        <thead>${thead}</thead>
                        <tbody>${tbody}</tbody>
                    </table>
                </div>`;

        } catch (err) {
            area.innerHTML = `<div class="history-empty"><p>⚠️ Error loading data: ${err.message}</p></div>`;
            info.textContent = 'Error';
        }
    }
}