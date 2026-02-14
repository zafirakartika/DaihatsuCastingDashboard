/**
 * Finishing Performance NR Module
 * Real-time monitoring for Finishing Machine 1 - NR Line
 */

(function() {
    'use strict';

    const CONFIG = {
        REFRESH_INTERVAL: 5000,
        TARGET_CYCLE_TIME: 45,
        TARGET_TEMP_MIN: 180,
        TARGET_TEMP_MAX: 200,
        LINE: 'NR'
    };

    let charts = {};
    let refreshTimer = null;
    let partCounter = 0;

    function generateReading() {
        return {
            ovenTemp: 185 + Math.random() * 20,
            coolingTemp: 28 + Math.random() * 6,
            pressure: 2.8 + Math.random() * 0.6,
            speed: 1.3 + Math.random() * 0.2,
            humidity: 48 + Math.random() * 10,
            vibration: 2 + Math.random() * 2
        };
    }

    function updateKPIs() {
        const r = generateReading();
        const el = id => document.getElementById(id);
        if (el('parts-processed')) el('parts-processed').textContent = 150 + Math.floor(Math.random() * 50);
        if (el('cycle-time')) el('cycle-time').textContent = (CONFIG.TARGET_CYCLE_TIME + Math.floor(Math.random() * 10 - 5)).toString();
        if (el('fpy')) el('fpy').textContent = (96 + Math.random() * 3).toFixed(1);
        if (el('uptime')) el('uptime').textContent = (92 + Math.random() * 6).toFixed(1);
        if (el('oven-temp')) el('oven-temp').textContent = r.ovenTemp.toFixed(1);
        if (el('defect-rate')) el('defect-rate').textContent = (1 + Math.random() * 2).toFixed(2);
        if (el('lastUpdate')) el('lastUpdate').textContent = new Date().toLocaleTimeString();
        if (el('param-oven-temp')) el('param-oven-temp').textContent = r.ovenTemp.toFixed(1);
        if (el('param-cooling-temp')) el('param-cooling-temp').textContent = r.coolingTemp.toFixed(1);
        if (el('param-pressure')) el('param-pressure').textContent = r.pressure.toFixed(2);
        if (el('param-speed')) el('param-speed').textContent = r.speed.toFixed(2);
        if (el('param-humidity')) el('param-humidity').textContent = r.humidity.toFixed(1);
        if (el('param-vibration')) el('param-vibration').textContent = r.vibration.toFixed(2);
    }

    function initCharts() {
        const chartDefaults = { responsive: true, maintainAspectRatio: false, animation: { duration: 300 } };

        const tempCtx = document.getElementById('temperatureTrendChart');
        if (tempCtx && typeof Chart !== 'undefined') {
            const labels = Array.from({length: 20}, (_, i) => `${i + 1}m ago`).reverse();
            charts.temp = new Chart(tempCtx, {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                        label: 'Oven Temp (°C)',
                        data: labels.map(() => 185 + Math.random() * 20),
                        borderColor: '#e74c3c', tension: 0.4, fill: false
                    }]
                },
                options: { ...chartDefaults, scales: { y: { title: { display: true, text: '°C' } } } }
            });
        }

        const defectCtx = document.getElementById('defectDistributionChart');
        if (defectCtx && typeof Chart !== 'undefined') {
            charts.defect = new Chart(defectCtx, {
                type: 'doughnut',
                data: {
                    labels: ['None', 'Scratch', 'Coating', 'Contamination'],
                    datasets: [{ data: [85, 7, 5, 3], backgroundColor: ['#27ae60', '#e74c3c', '#f39c12', '#3498db'] }]
                },
                options: { ...chartDefaults, plugins: { legend: { position: 'right' } } }
            });
        }
    }

    function addPartsLogRow() {
        const tbody = document.getElementById('partsLogBody');
        if (!tbody) return;
        partCounter++;
        const defects = ['none', 'none', 'none', 'scratch', 'coating'];
        const defect = defects[Math.floor(Math.random() * defects.length)];
        const row = tbody.insertRow(0);
        row.innerHTML = `
            <td>${new Date().toLocaleTimeString()}</td>
            <td>BATCH-${String(partCounter).padStart(4, '0')}</td>
            <td>${(CONFIG.TARGET_CYCLE_TIME + Math.floor(Math.random()*10-5))}s</td>
            <td>${(0.12 + Math.random()*0.06).toFixed(3)} mm</td>
            <td>${(85 + Math.random()*15).toFixed(1)}%</td>
            <td><span class="status-badge ${defect === 'none' ? 'status-good' : 'status-warning'}">${defect === 'none' ? 'OK' : 'NG'}</span></td>
            <td><span class="defect-badge ${defect}">${defect}</span></td>
        `;
        while (tbody.rows.length > 20) tbody.deleteRow(tbody.rows.length - 1);
    }

    document.addEventListener('DOMContentLoaded', function() {
        initCharts();
        updateKPIs();
        addPartsLogRow();
        refreshTimer = setInterval(function() {
            updateKPIs();
            if (Math.random() > 0.5) addPartsLogRow();
        }, CONFIG.REFRESH_INTERVAL);
    });
})();
