/**
 * Casting Performance NR Configuration
 * Line-specific configuration for ALPC NR casting performance
 */

// Wait for casting-performance-core.js to be loaded
document.addEventListener('DOMContentLoaded', function() {
    if (typeof CastingPerformance !== 'undefined') {
        CastingPerformance.init({
            line: 'NR',
            lpc: 9,
            apiUrl: 'http://127.0.0.1:8000/api/casting-data',
            refreshInterval: 10000
        });
    } else {
        console.warn('[casting-performance-nr-config] CastingPerformance core not loaded');
    }
});
