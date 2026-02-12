/**
 * Performance Monitor
 * Real-time performance monitoring and optimization for the dashboard
 */

class PerformanceMonitor {
    constructor() {
        this.metrics = {
            fps: [],
            memory: [],
            loadTimes: [],
            renderTimes: [],
            longTasks: []
        };

        this.isMonitoring = false;
        this.observers = [];
        this.intervals = [];
    }

    startMonitoring() {
        if (this.isMonitoring) return;

        this.isMonitoring = true;
        console.log('🚀 Performance monitoring started');

        this.monitorFPS();
        this.monitorMemory();
        this.setupPerformanceObserver();

        // Generate performance report every 30 seconds
        const reportInterval = setInterval(() => {
            this.logPerformanceReport();
        }, 30000);

        this.intervals.push(reportInterval);
    }

    stopMonitoring() {
        this.isMonitoring = false;

        this.intervals.forEach(interval => clearInterval(interval));
        this.observers.forEach(observer => observer.disconnect());

        this.intervals = [];
        this.observers = [];

        console.log('⏹️ Performance monitoring stopped');
    }

    monitorFPS() {
        let frameCount = 0;
        let lastTime = performance.now();

        const measureFPS = () => {
            frameCount++;
            const currentTime = performance.now();

            if (currentTime - lastTime >= 1000) {
                const fps = Math.round((frameCount * 1000) / (currentTime - lastTime));

                this.metrics.fps.push(fps);

                // Keep only last 60 readings (1 minute of data)
                if (this.metrics.fps.length > 60) {
                    this.metrics.fps.shift();
                }

                // Warn if FPS drops below 30
                if (fps < 30) {
                    console.warn(`⚠️ Low FPS detected: ${fps}`);
                }

                frameCount = 0;
                lastTime = currentTime;
            }

            if (this.isMonitoring) {
                requestAnimationFrame(measureFPS);
            }
        };

        requestAnimationFrame(measureFPS);
    }

    monitorMemory() {
        if (!performance.memory) {
            console.log('ℹ️ Memory monitoring not available in this browser');
            return;
        }

        const memoryInterval = setInterval(() => {
            const memInfo = performance.memory;
            const usedMB = (memInfo.usedJSHeapSize / 1048576).toFixed(2);
            const totalMB = (memInfo.totalJSHeapSize / 1048576).toFixed(2);
            const usagePercent = ((memInfo.usedJSHeapSize / memInfo.totalJSHeapSize) * 100).toFixed(1);

            this.metrics.memory.push({
                used: memInfo.usedJSHeapSize,
                total: memInfo.totalJSHeapSize,
                timestamp: Date.now()
            });

            // Keep only last 100 readings
            if (this.metrics.memory.length > 100) {
                this.metrics.memory.shift();
            }

            // Warn if memory usage exceeds 80%
            if (usagePercent > 80) {
                console.warn(`⚠️ High memory usage: ${usagePercent}% (${usedMB}MB / ${totalMB}MB)`);
            }
        }, 5000);

        this.intervals.push(memoryInterval);
    }

    setupPerformanceObserver() {
        try {
            // Monitor long tasks (>50ms)
            const longTaskObserver = new PerformanceObserver((list) => {
                for (const entry of list.getEntries()) {
                    if (entry.duration > 50) {
                        this.metrics.longTasks.push({
                            duration: entry.duration,
                            timestamp: entry.startTime
                        });

                        console.warn(`🐌 Long task detected: ${entry.duration.toFixed(2)}ms`);
                    }
                }
            });

            longTaskObserver.observe({ entryTypes: ['longtask'] });
            this.observers.push(longTaskObserver);

            // Monitor layout shifts
            const layoutShiftObserver = new PerformanceObserver((list) => {
                for (const entry of list.getEntries()) {
                    if (entry.value > 0.1) {
                        console.warn(`📐 Layout shift detected: ${entry.value.toFixed(4)}`);
                    }
                }
            });

            layoutShiftObserver.observe({ entryTypes: ['layout-shift'] });
            this.observers.push(layoutShiftObserver);

            // Monitor paint timing
            const paintObserver = new PerformanceObserver((list) => {
                for (const entry of list.getEntries()) {
                    this.metrics.renderTimes.push(entry.startTime);
                    if (this.metrics.renderTimes.length > 50) {
                        this.metrics.renderTimes.shift();
                    }
                }
            });

            paintObserver.observe({ entryTypes: ['paint'] });
            this.observers.push(paintObserver);

        } catch (error) {
            console.log('ℹ️ Performance Observer not fully supported:', error.message);
        }
    }

    getMetricsSummary() {
        const summary = {
            averageFPS: this.calculateAverage(this.metrics.fps),
            minFPS: Math.min(...this.metrics.fps),
            maxFPS: Math.max(...this.metrics.fps),
            memoryUsage: this.calculateAverageMemory(),
            longTasksCount: this.metrics.longTasks.length,
            averageRenderTime: this.calculateAverage(this.metrics.renderTimes)
        };

        return summary;
    }

    calculateAverage(array) {
        if (array.length === 0) return 0;
        return array.reduce((sum, value) => sum + value, 0) / array.length;
    }

    calculateAverageMemory() {
        if (this.metrics.memory.length === 0) return null;

        const latest = this.metrics.memory[this.metrics.memory.length - 1];
        return {
            usedMB: (latest.used / 1048576).toFixed(2),
            totalMB: (latest.total / 1048576).toFixed(2),
            usagePercent: ((latest.used / latest.total) * 100).toFixed(1)
        };
    }

    logPerformanceReport() {
        const summary = this.getMetricsSummary();

        console.group('📊 Performance Report');
        console.log(`🎯 FPS: ${summary.averageFPS.toFixed(1)} (min: ${summary.minFPS}, max: ${summary.maxFPS})`);
        console.log(`🧠 Memory: ${summary.memoryUsage ? `${summary.memoryUsage.usedMB}MB / ${summary.memoryUsage.totalMB}MB (${summary.memoryUsage.usagePercent}%)` : 'N/A'}`);
        console.log(`🐌 Long Tasks (>50ms): ${summary.longTasksCount}`);
        console.log(`🎨 Render Time: ${summary.averageRenderTime.toFixed(2)}ms`);
        console.groupEnd();

        // Performance recommendations
        this.provideRecommendations(summary);
    }

    provideRecommendations(summary) {
        const recommendations = [];

        if (summary.averageFPS < 30) {
            recommendations.push('⚡ Enable hardware acceleration in CSS');
            recommendations.push('📉 Reduce chart data points or use sampling');
        }

        if (summary.memoryUsage && summary.memoryUsage.usagePercent > 80) {
            recommendations.push('🗑️ Clear unused data and implement memory cleanup');
            recommendations.push('♻️ Use object pooling for frequently created objects');
        }

        if (summary.longTasksCount > 10) {
            recommendations.push('⚡ Break up long-running tasks with setTimeout/requestAnimationFrame');
            recommendations.push('📦 Use Web Workers for heavy calculations');
        }

        if (recommendations.length > 0) {
            console.group('💡 Performance Recommendations');
            recommendations.forEach(rec => console.log(rec));
            console.groupEnd();
        }
    }

    // API for external monitoring
    markStart(label) {
        performance.mark(`${label}-start`);
    }

    markEnd(label) {
        performance.mark(`${label}-end`);
        performance.measure(label, `${label}-start`, `${label}-end`);

        const measure = performance.getEntriesByName(label)[0];
        console.log(`⏱️ ${label}: ${measure.duration.toFixed(2)}ms`);
    }

    // Memory cleanup helper
    cleanup() {
        // Clear performance marks and measures
        performance.clearMarks();
        performance.clearMeasures();

        // Clear metrics
        Object.keys(this.metrics).forEach(key => {
            this.metrics[key] = [];
        });

        console.log('🧹 Performance monitor cleaned up');
    }
}

// Create global instance
const performanceMonitor = new PerformanceMonitor();

// Auto-start monitoring in development
if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
    // Delay start to avoid interference with initial page load
    setTimeout(() => {
        performanceMonitor.startMonitoring();
    }, 2000);
}

// Export for use in other modules
window.PerformanceMonitor = PerformanceMonitor;
window.performanceMonitor = performanceMonitor;
