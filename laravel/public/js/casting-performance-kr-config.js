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
            apiUrl: '/api/casting-data',
            refreshInterval: 10000
        });
    } else {
        console.warn('[casting-performance-kr-config] CastingPerformance core not loaded');
    }
});
