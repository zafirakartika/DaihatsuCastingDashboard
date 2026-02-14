/**
 * Traceability KR Module
 * Handles Part ID traceability for KR parts
 * Delegates to shared utilities from traceability-shared.js
 */

const CONFIG = {
    API_URL: '/daihatsu-dashboard/laravel/public/api/traceability-data-consolidated.php?part=KR'
};

let allData = [];
let filteredData = [];
let currentPage = 1;
let pageSize = 9999999;
let currentSort = null;
let charts = { lpc: null, trend: null };

document.addEventListener('DOMContentLoaded', function() {
    initializePage();
    changePageSize();
    loadTraceabilityData();
    setupEventListeners();
});
