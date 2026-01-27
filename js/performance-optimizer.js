/**
 * Performance Optimization Module
 * Provides utilities for optimizing dashboard performance
 */

class PerformanceOptimizer {
    constructor() {
        this.debounceTimers = new Map();
        this.throttleTimers = new Map();
        this.rafCallbacks = new Set();
        this.observers = new Set();
        this.workers = new Set();
        this.cache = new Map();
        this.cacheExpiry = new Map();
    }

    // Debounce function calls
    debounce(key, func, delay = 300) {
        if (this.debounceTimers.has(key)) {
            clearTimeout(this.debounceTimers.get(key));
        }

        const timer = setTimeout(() => {
            func();
            this.debounceTimers.delete(key);
        }, delay);

        this.debounceTimers.set(key, timer);
    }

    // Throttle function calls
    throttle(key, func, limit = 100) {
        if (this.throttleTimers.has(key)) {
            return;
        }

        func();

        const timer = setTimeout(() => {
            this.throttleTimers.delete(key);
        }, limit);

        this.throttleTimers.set(key, timer);
    }

    // RequestAnimationFrame batching
    requestAnimationFrame(callback) {
        const rafCallback = () => {
            callback();
            this.rafCallbacks.delete(rafCallback);
        };

        this.rafCallbacks.add(rafCallback);
        requestAnimationFrame(rafCallback);
    }

    // Memory-efficient caching with TTL
    setCache(key, value, ttl = 300000) { // 5 minutes default
        this.cache.set(key, value);
        this.cacheExpiry.set(key, Date.now() + ttl);
    }

    getCache(key) {
        if (this.cacheExpiry.get(key) < Date.now()) {
            this.cache.delete(key);
            this.cacheExpiry.delete(key);
            return null;
        }
        return this.cache.get(key);
    }

    // Intersection Observer for lazy loading
    createIntersectionObserver(callback, options = {}) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    callback(entry.target);
                }
            });
        }, {
            root: null,
            rootMargin: '50px',
            threshold: 0.1,
            ...options
        });

        this.observers.add(observer);
        return observer;
    }

    // Web Worker for heavy calculations
    createWorker(scriptUrl) {
        const worker = new Worker(scriptUrl);
        this.workers.add(worker);
        return worker;
    }

    // Batch DOM updates
    batchDOMUpdates(updates) {
        const fragment = document.createDocumentFragment();

        updates.forEach(update => {
            const { selector, property, value, action = 'update' } = update;

            if (action === 'update') {
                const element = fragment.querySelector ? fragment.querySelector(selector) :
                              document.querySelector(selector);
                if (element && property in element) {
                    element[property] = value;
                }
            }
        });

        // Apply all updates at once
        this.requestAnimationFrame(() => {
            // Implementation would apply the fragment updates
        });
    }

    // Memory cleanup
    cleanup() {
        // Clear all timers
        this.debounceTimers.forEach(timer => clearTimeout(timer));
        this.throttleTimers.forEach(timer => clearTimeout(timer));
        this.debounceTimers.clear();
        this.throttleTimers.clear();

        // Cancel all RAF callbacks
        this.rafCallbacks.clear();

        // Disconnect observers
        this.observers.forEach(observer => observer.disconnect());
        this.observers.clear();

        // Terminate workers
        this.workers.forEach(worker => worker.terminate());
        this.workers.clear();

        // Clear cache
        this.cache.clear();
        this.cacheExpiry.clear();
    }

    // Performance monitoring
    startMonitoring() {
        this.performanceMarks = new Map();

        // Monitor memory usage
        if (performance.memory) {
            setInterval(() => {
                const memInfo = performance.memory;
                console.log(`Memory: ${(memInfo.usedJSHeapSize / 1048576).toFixed(2)}MB used of ${(memInfo.totalJSHeapSize / 1048576).toFixed(2)}MB`);
            }, 10000);
        }
    }

    mark(name) {
        this.performanceMarks.set(name, performance.now());
    }

    measure(name, startMark) {
        const end = performance.now();
        const start = this.performanceMarks.get(startMark);
        if (start) {
            console.log(`${name}: ${(end - start).toFixed(2)}ms`);
        }
    }
}

// Create global instance
const performanceOptimizer = new PerformanceOptimizer();

// Optimized Chart.js configuration
const optimizedChartConfig = {
    animation: {
        duration: 0, // Disable animations for better performance
        easing: 'linear'
    },
    responsive: true,
    maintainAspectRatio: false,
    interaction: {
        mode: 'nearest',
        intersect: false,
        throttle: 16 // ~60fps
    },
    plugins: {
        legend: {
            display: false // Reduce DOM complexity
        },
        tooltip: {
            enabled: false // Disable tooltips for performance
        }
    },
    elements: {
        point: {
            radius: 0, // Remove points for better performance
            hoverRadius: 3
        },
        line: {
            borderWidth: 2,
            tension: 0.1 // Reduce curve calculation
        }
    },
    scales: {
        x: {
            display: false, // Hide axes for cleaner look and better performance
            grid: {
                display: false
            }
        },
        y: {
            display: false,
            grid: {
                display: false
            }
        }
    }
};

// Optimized data processing functions
const optimizedDataProcessor = {
    // Use typed arrays for better memory efficiency
    createFloat32Array: (data) => new Float32Array(data),

    // Batch calculations
    batchCalculate: (data, operations) => {
        const results = {};
        const length = data.length;

        operations.forEach(op => {
            const result = new Float32Array(length);
            for (let i = 0; i < length; i++) {
                result[i] = op.func(data[i]);
            }
            results[op.name] = result;
        });

        return results;
    },

    // Efficient filtering
    filterData: (data, predicate) => {
        const filtered = [];
        for (let i = 0; i < data.length; i++) {
            if (predicate(data[i])) {
                filtered.push(data[i]);
            }
        }
        return filtered;
    },

    // Memory-efficient averaging
    fastAverage: (array) => {
        let sum = 0;
        for (let i = 0; i < array.length; i++) {
            sum += array[i];
        }
        return sum / array.length;
    },

    // Optimized standard deviation
    fastStdDev: (array, mean) => {
        let sumSquaredDiffs = 0;
        for (let i = 0; i < array.length; i++) {
            const diff = array[i] - mean;
            sumSquaredDiffs += diff * diff;
        }
        return Math.sqrt(sumSquaredDiffs / array.length);
    }
};

// Export for use in other modules
window.PerformanceOptimizer = PerformanceOptimizer;
window.performanceOptimizer = performanceOptimizer;
window.optimizedChartConfig = optimizedChartConfig;
window.optimizedDataProcessor = optimizedDataProcessor;
