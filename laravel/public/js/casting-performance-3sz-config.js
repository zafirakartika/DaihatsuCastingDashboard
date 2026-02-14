/**
 * Casting Performance 3SZ Configuration
 * Line-specific configuration for ALPC 3SZ casting performance
 */

// Wait for casting-performance-core.js to be loaded
document.addEventListener('DOMContentLoaded', function() {
    if (typeof CastingPerformance !== 'undefined') {
        CastingPerformance.init({
            line: '3SZ',
            lpc: 4,
            apiUrl: 'http://127.0.0.1:8000/api/casting-data',
            refreshInterval: 10000
        });
    } else {
        console.warn('[casting-performance-3sz-config] CastingPerformance core not loaded');
    }
});
