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
        if (el) el.innerText = 'Last updated: ' + time;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    window.lpcCountersDashboard = new LPCCountersDashboard();
});