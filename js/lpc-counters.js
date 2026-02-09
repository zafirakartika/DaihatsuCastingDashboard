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
        // Update TR counters (LPC 1, 2, 3, 4, 6) from tr_counter table
        if (data.tr_counter) {
            this.updateCounter('tr-1', data.tr_counter.LPC1 || 0);
            this.updateCounter('tr-2', data.tr_counter.LPC2 || 0);
            this.updateCounter('tr-3', data.tr_counter.LPC3 || 0);
            this.updateCounter('tr-4', data.tr_counter.LPC4 || 0);
            this.updateCounter('tr-6', data.tr_counter.LPC6 || 0);
        }

        // Update 3SZ/KR counter (LPC 9) from sz_kr_counter table
        if (data.sz_kr_counter) {
            // Matches ID 'value-3sz-kr' in the HTML
            this.updateCounter('3sz-kr', data.sz_kr_counter.LPC9 || 0);
        }

        // Update NR counters (LPC 12, 13, 14) from nr_counter table
        if (data.nr_counter) {
            this.updateCounter('nr-12', data.nr_counter.LPC12 || 0);
            this.updateCounter('nr-13', data.nr_counter.LPC13 || 0);
            this.updateCounter('nr-14', data.nr_counter.LPC14 || 0);
        }

        // Update WA counter (LPC 11) from wa_counter table
        if (data.wa_counter) {
            // Matches ID 'value-wa' in the HTML
            this.updateCounter('wa', data.wa_counter.LPC11 || 0);
        }
    }

    updateCounter(counterId, value) {
        const element = document.getElementById(`value-${counterId}`);
        if (element) {
            const newValue = parseInt(value) || 0;
            element.innerHTML = newValue;
        }
    }

    animateCounter(element, from, to) {
        const duration = 500; // 500ms animation
        const start = Date.now();
        const difference = to - from;

        const animate = () => {
            const elapsed = Date.now() - start;
            const progress = Math.min(elapsed / duration, 1);
            const current = Math.round(from + (difference * progress));

            element.textContent = current;

            if (progress < 1) {
                requestAnimationFrame(animate);
            }
        };

        animate();
    }

    showUpdatingStatus() {
        // Update all status indicators to updating
        const statusDots = document.querySelectorAll('.status-dot');
        const statusTexts = document.querySelectorAll('.status-text');

        statusDots.forEach(dot => {
            dot.className = 'status-dot updating';
        });

        statusTexts.forEach(text => {
            text.className = 'status-text updating';
            text.textContent = 'Updating...';
        });
    }

    showSuccessStatus() {
        // Update all status indicators to active
        const statusDots = document.querySelectorAll('.status-dot');
        const statusTexts = document.querySelectorAll('.status-text');

        statusDots.forEach(dot => {
            dot.className = 'status-dot active';
        });

        statusTexts.forEach(text => {
            text.className = 'status-text active';
            text.textContent = 'Active';
        });
    }

    showErrorStatus() {
        // Update all status indicators to error
        const statusDots = document.querySelectorAll('.status-dot');
        const statusTexts = document.querySelectorAll('.status-text');

        statusDots.forEach(dot => {
            dot.className = 'status-dot error';
        });

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

// Initialize the dashboard when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.lpcCountersDashboard = new LPCCountersDashboard();
});

// Cleanup on page unload
window.addEventListener('beforeunload', function() {
    if (window.lpcCountersDashboard) {
        window.lpcCountersDashboard.stopAutoUpdate();
    }
});