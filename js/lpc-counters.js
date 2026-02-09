// LPC Counters Dashboard JavaScript
// Handles real-time counter updates every 10 seconds

class LPCCountersDashboard {
    constructor() {
        this.updateInterval = 10000; // 10 seconds
        this.intervalId = null;
        this.isUpdating = false;
        this.isRealTimeEnabled = true;
        this.lastUpdate = null;
        this.init();
    }

    init() {
        this.startAutoUpdate();
        this.updateCounters(); // Initial load
    }

    startAutoUpdate() {
        this.intervalId = setInterval(() => {
            if (this.isRealTimeEnabled) {
                this.updateCounters();
            }
        }, this.updateInterval);
    }

    stopAutoUpdate() {
        if (this.intervalId) {
            clearInterval(this.intervalId);
            this.intervalId = null;
        }
    }

    toggleRealTime() {
        this.isRealTimeEnabled = !this.isRealTimeEnabled;

        const toggleBtn = document.getElementById('toggle-realtime');
        const statusText = document.getElementById('toggle-status');

        if (this.isRealTimeEnabled) {
            // Turn ON - Green
            toggleBtn.style.background = 'linear-gradient(135deg, #27ae60 0%, #229954 100%)';
            statusText.textContent = 'ON';

            // Start updating immediately
            this.updateCounters();
            console.log('✅ Real-time monitoring ENABLED');
        } else {
            // Turn OFF - Red
            toggleBtn.style.background = 'linear-gradient(135deg, #e74c3c 0%, #c0392b 100%)';
            statusText.textContent = 'OFF';
            console.log('⛔ Real-time monitoring STOPPED');
        }
    }

    async updateCounters() {
        if (this.isUpdating) return;

        this.isUpdating = true;
        this.showUpdatingStatus();

        try {
            const response = await fetch('../api/data-connector.php?endpoint=counters');
            const result = await response.json();

            if (result.success) {
                // Debugging: Warn if any specific table failed in the backend
                if (result.data._debug && Object.keys(result.data._debug).length > 0) {
                    console.warn('Backend warnings:', result.data._debug);
                }

                console.log('✅ Counter data updated:', result.data);
                this.updateCounterDisplay(result.data);
                this.showSuccessStatus();
                this.updateLastUpdated();
            } else {
                console.error('Failed to fetch counter data:', result.error);
                this.showErrorStatus();
            }
        } catch (error) {
            console.error('Error fetching counter data:', error);
            this.showErrorStatus();
        } finally {
            this.isUpdating = false;
        }
    }

    updateCounterDisplay(data) {
        // Safe access helper - handles undefined/null values gently
        const getVal = (obj, key) => (obj && obj[key] !== undefined && obj[key] !== null) ? obj[key] : 0;

        // NOTE: Keys are now guaranteed to be UPPERCASE by the PHP script (LPC1, LPC2...)

        // Update TR counters (LPC 1, 2, 3, 4, 6)
        if (data.tr_counter) {
            this.updateCounter('tr-1', getVal(data.tr_counter, 'LPC1'));
            this.updateCounter('tr-2', getVal(data.tr_counter, 'LPC2'));
            this.updateCounter('tr-3', getVal(data.tr_counter, 'LPC3'));
            this.updateCounter('tr-4', getVal(data.tr_counter, 'LPC4'));
            this.updateCounter('tr-6', getVal(data.tr_counter, 'LPC6'));
        }

        // Update 3SZ/KR counter (LPC 9)
        if (data.sz_kr_counter) {
            this.updateCounter('3sz-kr', getVal(data.sz_kr_counter, 'LPC9'));
        }

        // Update NR counters (LPC 12, 13, 14)
        if (data.nr_counter) {
            this.updateCounter('nr-12', getVal(data.nr_counter, 'LPC12'));
            this.updateCounter('nr-13', getVal(data.nr_counter, 'LPC13'));
            this.updateCounter('nr-14', getVal(data.nr_counter, 'LPC14'));
        }

        // Update WA counter (LPC 11)
        if (data.wa_counter) {
            this.updateCounter('wa', getVal(data.wa_counter, 'LPC11'));
        }
    }

    updateCounter(counterId, value) {
        const element = document.getElementById(`value-${counterId}`);
        if (element) {
            // Parse integer to ensure no "undefined" text appears
            const numericValue = parseInt(value);
            const displayValue = isNaN(numericValue) ? 0 : numericValue;

            if (element.innerText != displayValue) {
                element.innerText = displayValue;
                // Add flash animation class
                element.classList.remove('updated-flash');
                void element.offsetWidth; // Trigger reflow
                element.classList.add('updated-flash');
            }
        }
    }

    showUpdatingStatus() {
        const statusDots = document.querySelectorAll('.status-dot');
        const statusTexts = document.querySelectorAll('.status-text');

        statusDots.forEach(dot => dot.className = 'status-dot updating');
        statusTexts.forEach(text => {
            text.className = 'status-text updating';
            text.textContent = 'Updating...';
        });
    }

    showSuccessStatus() {
        const statusDots = document.querySelectorAll('.status-dot');
        const statusTexts = document.querySelectorAll('.status-text');

        statusDots.forEach(dot => dot.className = 'status-dot active');
        statusTexts.forEach(text => {
            text.className = 'status-text active';
            text.textContent = 'Active';
        });
    }

    showErrorStatus() {
        const statusDots = document.querySelectorAll('.status-dot');
        const statusTexts = document.querySelectorAll('.status-text');

        statusDots.forEach(dot => dot.className = 'status-dot error');
        statusTexts.forEach(text => {
            text.className = 'status-text error';
            text.textContent = 'Error';
        });
    }

    updateLastUpdated() {
        this.lastUpdate = new Date();
        const element = document.getElementById('last-updated');
        if (element) {
            element.textContent = `Last updated: ${this.lastUpdate.toLocaleTimeString()}`;
        }
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    window.lpcCountersDashboard = new LPCCountersDashboard();
});

// Cleanup
window.addEventListener('beforeunload', function() {
    if (window.lpcCountersDashboard) {
        window.lpcCountersDashboard.stopAutoUpdate();
    }
});