{{-- OEE Metrics Section --}}
<div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 10px; margin-bottom: 12px;">
    <div class="metric-card">
        <div class="metric-label" style="font-size: 11px;">Production Count</div>
        <div class="metric-value" id="oee-count-actual" style="font-size: 28px; font-weight: 700;">--</div>
        <div class="metric-unit" style="font-size: 11px;">/ 101 target</div>
        <div style="display: flex; align-items: center; gap: 4px; margin-top: 4px;">
            <span class="status-badge" id="status-count-actual">--</span>
            <span style="font-size: 10px; color: #999;"><span id="status-count-actual-percent">--</span>%</span>
        </div>
    </div>
    <div class="metric-card">
        <div class="metric-label" style="font-size: 11px;">Availability</div>
        <div class="metric-value" id="oee-availability" style="font-size: 28px; font-weight: 700;">--</div>
        <div class="metric-unit" style="font-size: 11px;">% (target: 85%)</div>
        <div style="display: flex; align-items: center; gap: 4px; margin-top: 4px;">
            <span class="status-badge" id="status-availability">--</span>
            <span style="font-size: 10px; color: #999;"><span id="status-availability-percent">--</span>%</span>
        </div>
    </div>
    <div class="metric-card">
        <div class="metric-label" style="font-size: 11px;">Performance</div>
        <div class="metric-value" id="oee-performance" style="font-size: 28px; font-weight: 700;">--</div>
        <div class="metric-unit" style="font-size: 11px;">% (target: 95%)</div>
        <div style="display: flex; align-items: center; gap: 4px; margin-top: 4px;">
            <span class="status-badge" id="status-performance">--</span>
            <span style="font-size: 10px; color: #999;"><span id="status-performance-percent">--</span>%</span>
        </div>
    </div>
    <div class="metric-card">
        <div class="metric-label" style="font-size: 11px;">Quality</div>
        <div class="metric-value" id="oee-quality" style="font-size: 28px; font-weight: 700;">--</div>
        <div class="metric-unit" style="font-size: 11px;">% (target: 99%)</div>
        <div style="display: flex; align-items: center; gap: 4px; margin-top: 4px;">
            <span class="status-badge" id="status-quality">--</span>
            <span style="font-size: 10px; color: #999;"><span id="status-quality-percent">--</span>%</span>
        </div>
    </div>
    <div class="metric-card" style="border-left-color: #9b59b6;">
        <div class="metric-label" style="font-size: 11px;">Overall OEE</div>
        <div class="metric-value" id="oee-overall" style="font-size: 28px; font-weight: 700; color: #9b59b6;">--</div>
        <div class="metric-unit" style="font-size: 11px;">% (target: 80%)</div>
        <div style="display: flex; align-items: center; gap: 4px; margin-top: 4px;">
            <span class="status-badge" id="status-overall">--</span>
            <span style="font-size: 10px; color: #999;"><span id="status-overall-percent">--</span>%</span>
        </div>
    </div>
</div>

{{-- Temperature Metrics Filter Controls --}}
<div style="display: flex; gap: 8px; align-items: center; margin-bottom: 10px; flex-wrap: wrap;">
    <span style="font-size: 12px; font-weight: 600; color: #555;">Filter Metrics:</span>
    <select id="temp-metric-filter" class="filter-input" style="padding: 5px 10px; font-size: 12px;"
            onchange="CastingPerformance.filterTemperatureMetrics(this.value)">
        <option value="all">All Sensors</option>
        <option value="gate">Gate Sensors</option>
        <option value="chamber">Chamber Sensors</option>
    </select>
    <span style="font-size: 12px; font-weight: 600; color: #555; margin-left: 12px;">Chart View:</span>
    <label style="font-size: 12px; cursor: pointer;"><input type="radio" name="temp-trend-filter" value="all" checked onchange="CastingPerformance.filterTrendChart(this.value)"> All</label>
    <label style="font-size: 12px; cursor: pointer;"><input type="radio" name="temp-trend-filter" value="gate" onchange="CastingPerformance.filterTrendChart(this.value)"> Gate</label>
    <label style="font-size: 12px; cursor: pointer;"><input type="radio" name="temp-trend-filter" value="chamber" onchange="CastingPerformance.filterTrendChart(this.value)"> Chamber</label>
    <label style="font-size: 12px; cursor: pointer;"><input type="radio" name="temp-trend-filter" value="left" onchange="CastingPerformance.filterTrendChart(this.value)"> Left</label>
    <label style="font-size: 12px; cursor: pointer;"><input type="radio" name="temp-trend-filter" value="right" onchange="CastingPerformance.filterTrendChart(this.value)"> Right</label>
</div>

{{-- Temperature Metrics Grid (8 sensors) --}}
<div id="temperature-metrics-grid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin-bottom: 12px;">
    <div class="metric-card" data-metric-type="gate" style="border-left: 3px solid #3498DB;">
        <div class="metric-label" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">L Gate Front</div>
        <div class="metric-value" id="metric-l-gate-front" style="font-size: 26px; font-weight: 700;">--</div>
        <div class="metric-unit" style="font-size: 11px;">°C</div>
        <span class="status-badge status-normal" id="status-l-gate-front" style="font-size: 10px; padding: 3px 8px; margin-top: 4px; display: inline-block;">--</span>
    </div>
    <div class="metric-card" data-metric-type="gate" style="border-left: 3px solid #5DADE2;">
        <div class="metric-label" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">L Gate Rear</div>
        <div class="metric-value" id="metric-l-gate-rear" style="font-size: 26px; font-weight: 700;">--</div>
        <div class="metric-unit" style="font-size: 11px;">°C</div>
        <span class="status-badge status-normal" id="status-l-gate-rear" style="font-size: 10px; padding: 3px 8px; margin-top: 4px; display: inline-block;">--</span>
    </div>
    <div class="metric-card" data-metric-type="chamber" style="border-left: 3px solid #85C1E2;">
        <div class="metric-label" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">L Chamber 1</div>
        <div class="metric-value" id="metric-l-chamber-1" style="font-size: 26px; font-weight: 700;">--</div>
        <div class="metric-unit" style="font-size: 11px;">°C</div>
        <span class="status-badge status-normal" id="status-l-chamber-1" style="font-size: 10px; padding: 3px 8px; margin-top: 4px; display: inline-block;">--</span>
    </div>
    <div class="metric-card" data-metric-type="chamber" style="border-left: 3px solid #AED6F1;">
        <div class="metric-label" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">L Chamber 2</div>
        <div class="metric-value" id="metric-l-chamber-2" style="font-size: 26px; font-weight: 700;">--</div>
        <div class="metric-unit" style="font-size: 11px;">°C</div>
        <span class="status-badge status-normal" id="status-l-chamber-2" style="font-size: 10px; padding: 3px 8px; margin-top: 4px; display: inline-block;">--</span>
    </div>
    <div class="metric-card" data-metric-type="gate" style="border-left: 3px solid #E74C3C;">
        <div class="metric-label" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">R Gate Front</div>
        <div class="metric-value" id="metric-r-gate-front" style="font-size: 26px; font-weight: 700;">--</div>
        <div class="metric-unit" style="font-size: 11px;">°C</div>
        <span class="status-badge status-normal" id="status-r-gate-front" style="font-size: 10px; padding: 3px 8px; margin-top: 4px; display: inline-block;">--</span>
    </div>
    <div class="metric-card" data-metric-type="gate" style="border-left: 3px solid #EC7063;">
        <div class="metric-label" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">R Gate Rear</div>
        <div class="metric-value" id="metric-r-gate-rear" style="font-size: 26px; font-weight: 700;">--</div>
        <div class="metric-unit" style="font-size: 11px;">°C</div>
        <span class="status-badge status-normal" id="status-r-gate-rear" style="font-size: 10px; padding: 3px 8px; margin-top: 4px; display: inline-block;">--</span>
    </div>
    <div class="metric-card" data-metric-type="chamber" style="border-left: 3px solid #F1948A;">
        <div class="metric-label" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">R Chamber 1</div>
        <div class="metric-value" id="metric-r-chamber-1" style="font-size: 26px; font-weight: 700;">--</div>
        <div class="metric-unit" style="font-size: 11px;">°C</div>
        <span class="status-badge status-normal" id="status-r-chamber-1" style="font-size: 10px; padding: 3px 8px; margin-top: 4px; display: inline-block;">--</span>
    </div>
    <div class="metric-card" data-metric-type="chamber" style="border-left: 3px solid #F5B7B1;">
        <div class="metric-label" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">R Chamber 2</div>
        <div class="metric-value" id="metric-r-chamber-2" style="font-size: 26px; font-weight: 700;">--</div>
        <div class="metric-unit" style="font-size: 11px;">°C</div>
        <span class="status-badge status-normal" id="status-r-chamber-2" style="font-size: 10px; padding: 3px 8px; margin-top: 4px; display: inline-block;">--</span>
    </div>
</div>

{{-- Temperature Trend Chart --}}
<div style="display: grid; grid-template-columns: 1fr; gap: 10px; margin-bottom: 10px;">
    <div class="chart-wrapper" style="padding: 12px;">
        <div class="chart-title" style="margin-bottom: 10px; font-size: 15px;">Temperature Trend (Mold Die Temp)</div>
        <div style="position: relative; height: 250px; width: 100%; overflow: hidden;">
            <canvas id="tempTrendChart"></canvas>
        </div>
    </div>
</div>

{{-- Comparison & Distribution Charts --}}
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 10px;">
    <div class="chart-wrapper" style="padding: 12px;">
        <div class="chart-title" style="margin-bottom: 10px; font-size: 15px;">Left vs Right Comparison</div>
        <div style="position: relative; height: 200px; width: 100%; overflow: hidden;">
            <canvas id="leftRightChart"></canvas>
        </div>
    </div>
    <div class="chart-wrapper" style="padding: 12px;">
        <div class="chart-title" style="margin-bottom: 10px; font-size: 15px;">Temperature Distribution</div>
        <div style="position: relative; height: 200px; width: 100%; overflow: hidden;">
            <canvas id="distributionChart"></canvas>
        </div>
    </div>
</div>

{{-- Data Table --}}
<div class="chart-wrapper" style="padding: 12px; margin-bottom: 8px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; flex-wrap: wrap; gap: 8px;">
        <div class="chart-title" style="font-size: 15px;">Recent Temperature Data</div>
        <div style="display: flex; gap: 8px; align-items: center;">
            <input type="text" id="table-search" placeholder="Search..."
                   style="padding: 5px 10px; font-size: 12px; border: 1px solid var(--gray-border); border-radius: 4px; width: 160px;">
            <select id="sort-column" style="padding: 5px 10px; font-size: 12px; border: 1px solid var(--gray-border); border-radius: 4px;">
                <option value="timestamp-desc">Newest First</option>
                <option value="timestamp-asc">Oldest First</option>
                <option value="l_gate_front-desc">L Gate Front ↓</option>
                <option value="l_gate_front-asc">L Gate Front ↑</option>
                <option value="r_gate_front-desc">R Gate Front ↓</option>
                <option value="r_gate_front-asc">R Gate Front ↑</option>
            </select>
            <button id="clear-search" class="filter-btn" style="padding: 5px 10px; font-size: 12px;">Clear</button>
        </div>
    </div>
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
            <thead>
                <tr style="background: var(--accent-navy); color: white;">
                    <th style="padding: 8px; text-align: left; border: 1px solid #ddd;">Timestamp</th>
                    <th style="padding: 8px; text-align: right; border: 1px solid #ddd;">L Gate Front</th>
                    <th style="padding: 8px; text-align: right; border: 1px solid #ddd;">L Gate Rear</th>
                    <th style="padding: 8px; text-align: right; border: 1px solid #ddd;">L Chamber 1</th>
                    <th style="padding: 8px; text-align: right; border: 1px solid #ddd;">L Chamber 2</th>
                    <th style="padding: 8px; text-align: right; border: 1px solid #ddd;">R Gate Front</th>
                    <th style="padding: 8px; text-align: right; border: 1px solid #ddd;">R Gate Rear</th>
                    <th style="padding: 8px; text-align: right; border: 1px solid #ddd;">R Chamber 1</th>
                    <th style="padding: 8px; text-align: right; border: 1px solid #ddd;">R Chamber 2</th>
                </tr>
            </thead>
            <tbody id="data-table-body">
                <tr>
                    <td colspan="9" style="padding: 20px; text-align: center; color: #999;">Loading data...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
