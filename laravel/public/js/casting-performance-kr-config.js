/**
 * Casting Performance KR Configuration
 * Line-specific configuration for ALPC KR casting performance
 */

// Wait for casting-performance-core.js to be loaded
document.addEventListener('DOMContentLoaded', function() {
    if (typeof CastingPerformance !== 'undefined') {
        CastingPerformance.init({
            line: 'KR',
            lpc: 7,
            apiUrl: 'http://127.0.0.1:8000/api/casting-data',
            refreshInterval: 10000
        });
    } else {
        console.warn('[casting-performance-kr-config] CastingPerformance core not loaded');
    }
});
