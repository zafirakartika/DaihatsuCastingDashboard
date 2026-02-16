/**
 * Traceability 3SZ Module
 * Handles Part ID traceability for 3SZ parts
 * Delegates to shared utilities from traceability-shared.js
 */

const CONFIG = {
    API_URL: '/api/traceability?action=recent&line=3sz'
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
