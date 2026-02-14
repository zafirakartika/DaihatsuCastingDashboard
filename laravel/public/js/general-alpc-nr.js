/**
 * General ALPC NR Dashboard
 * Aggregated metrics and analytics for NR part casting performance
 */

const GeneralALPCNR = (function() {
    'use strict';

    const CONFIG = {
        API_URL: 'http://127.0.0.1:8000/api/casting-data',
        SIMULATION_ENABLED: false,
        REAL_TIME_ENABLED: true,
        REAL_TIME_INTERVAL: 10000,
        THRESHOLDS: { min: 490, max: 520 },
        SENSORS: ["r_lower_gate1", "r_lower_main1", "l_lower_gate1", "l_lower_main1", "cooling_water"],
        DEFAULT_LPC: '9'
    };

    let charts = {};
    let refreshTimer = null;

    function loadData() {
        const date = document.getElementById('filter-date') ? document.getElementById('filter-date').value : '';
        const shift = document.getElementById('filter-shift') ? document.getElementById('filter-shift').value : 'auto';
        const lpc = document.getElementById('filter-lpc') ? document.getElementById('filter-lpc').value : 'all';
        console.log('[GeneralALPCNR] Loading data:', { date, shift, lpc });
        // TODO: Implement API call for NR line data
    }

    function startSimulation(interval) {
        stopSimulation();
        refreshTimer = setInterval(loadData, (interval || 10) * 1000);
    }

    function stopSimulation() {
        if (refreshTimer) { clearInterval(refreshTimer); refreshTimer = null; }
    }

    document.addEventListener('DOMContentLoaded', function() {
        loadData();
        startSimulation(10);
    });

    return { loadData, startSimulation, stopSimulation };
})();
