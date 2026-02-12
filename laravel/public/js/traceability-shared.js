/**
 * Shared Traceability Utilities
 * Common functions used by both WA and TR traceability modules
 * Version: 1.0.0
 */

/**
 * Parse Part ID into segments
 * Format: AB22C01A01
 * - AB: LPC (positions 0-1)
 * - 22: Year (positions 2-3)
 * - C: Month (position 4) - C=March, D=April, etc.
 * - 01: Date (positions 5-6)
 * - A: Shift (position 7) - A=Morning, B=Night
 * - 01: Shot number (positions 8-9)
 */
function parsePartId(partId) {
    if (!partId || partId.length < 10) {
        return {
            lpc: '',
            year: '',
            month: '',
            date: '',
            shift: '',
            shot: '',
            cavity: ''
        };
    }

    // Parse month letter to month name
    const monthChar = partId.substring(4, 5);
    const monthMap = {
        'A': 'Jan', 'B': 'Feb', 'C': 'Mar', 'D': 'Apr',
        'E': 'May', 'F': 'Jun', 'G': 'Jul', 'H': 'Aug',
        'I': 'Sep', 'J': 'Oct', 'K': 'Nov', 'L': 'Dec'
    };

    // Parse shift letter to shift name
    const shiftChar = partId.substring(7, 8);
    const shiftName = shiftChar === 'A' ? 'Morning' : shiftChar === 'B' ? 'Night' : shiftChar;

    return {
        lpc: partId.substring(0, 2),
        year: partId.substring(2, 4),
        month: monthMap[monthChar] || monthChar,
        date: partId.substring(5, 7),
        shift: shiftName,
        shot: partId.substring(8, 10),
        cavity: '' // No cavity in this format
    };
}

/**
 * Debounce utility function for search input
 */
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

/**
 * Update pagination controls
 */
function updatePaginationControls(currentPage, totalPages) {
    const currentPageElement = document.getElementById('current-page');
    if (currentPageElement) currentPageElement.textContent = currentPage;

    const totalPagesElement = document.getElementById('total-pages');
    if (totalPagesElement) totalPagesElement.textContent = totalPages;

    const btnFirst = document.getElementById('btn-first');
    if (btnFirst) btnFirst.disabled = currentPage === 1;

    const btnPrev = document.getElementById('btn-prev');
    if (btnPrev) btnPrev.disabled = currentPage === 1;

    const btnNext = document.getElementById('btn-next');
    if (btnNext) btnNext.disabled = currentPage === totalPages || totalPages === 0;

    const btnLast = document.getElementById('btn-last');
    if (btnLast) btnLast.disabled = currentPage === totalPages || totalPages === 0;
}

/**
 * Update record count display
 */
function updateRecordCountDisplay(currentPage, pageSize, filteredDataLength) {
    const start = (currentPage - 1) * pageSize + 1;
    const end = Math.min(currentPage * pageSize, filteredDataLength);
    const total = filteredDataLength;

    const recordCountElement = document.getElementById('record-count');
    if (recordCountElement) {
        if (total === 0) {
            recordCountElement.textContent = '0';
        } else {
            recordCountElement.textContent = `${start}-${end} of ${total}`;
        }
    }
}

/**
 * Update last update timestamp
 */
function updateLastUpdateTimeDisplay() {
    const now = new Date();
    const timeString = now.toLocaleTimeString();
    const lastUpdateElement = document.getElementById('last-update');
    if (lastUpdateElement) {
        lastUpdateElement.textContent = timeString;
    }
}
