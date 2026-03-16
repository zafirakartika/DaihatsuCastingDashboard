{{-- Temperature Metrics Grid (11 sensors) --}}
<div id="temperature-metrics-grid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin-bottom: 12px;">
    {{-- R Side: gate --}}
    <div class="metric-card" data-metric-type="gate" style="border-left: 3px solid #E74C3C;">
        <div class="metric-label" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">R Lower Gate1 Temp</div>
        <div class="metric-value" id="metric-r-gate1-temp" style="font-size: 26px; font-weight: 700;">--</div>
        <div class="metric-unit" style="font-size: 11px;">°C</div>
        <span class="status-badge status-normal" id="status-r-gate1-temp" style="font-size: 10px; padding: 3px 8px; margin-top: 4px; display: inline-block;">--</span>
    </div>
    <div class="metric-card" data-metric-type="gate" style="border-left: 3px solid #EC7063;">
        <div class="metric-label" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">R Lower Gate2 Temp</div>
        <div class="metric-value" id="metric-r-gate2-temp" style="font-size: 26px; font-weight: 700;">--</div>
        <div class="metric-unit" style="font-size: 11px;">°C</div>
        <span class="status-badge status-normal" id="status-r-gate2-temp" style="font-size: 10px; padding: 3px 8px; margin-top: 4px; display: inline-block;">--</span>
    </div>
    {{-- R Side: main --}}
    <div class="metric-card" data-metric-type="main" style="border-left: 3px solid #E67E22;">
        <div class="metric-label" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">R Lower Main1 Temp</div>
        <div class="metric-value" id="metric-r-main1-temp" style="font-size: 26px; font-weight: 700;">--</div>
        <div class="metric-unit" style="font-size: 11px;">°C</div>
        <span class="status-badge status-normal" id="status-r-main1-temp" style="font-size: 10px; padding: 3px 8px; margin-top: 4px; display: inline-block;">--</span>
    </div>
    <div class="metric-card" data-metric-type="main" style="border-left: 3px solid #F39C12;">
        <div class="metric-label" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">R Lower Main2 Temp</div>
        <div class="metric-value" id="metric-r-main2-temp" style="font-size: 26px; font-weight: 700;">--</div>
        <div class="metric-unit" style="font-size: 11px;">°C</div>
        <span class="status-badge status-normal" id="status-r-main2-temp" style="font-size: 10px; padding: 3px 8px; margin-top: 4px; display: inline-block;">--</span>
    </div>
    {{-- L Side: upper main --}}
    <div class="metric-card" data-metric-type="main" style="border-left: 3px solid #1ABC9C;">
        <div class="metric-label" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">L Upper Main Temp</div>
        <div class="metric-value" id="metric-l-upper-main-temp" style="font-size: 26px; font-weight: 700;">--</div>
        <div class="metric-unit" style="font-size: 11px;">°C</div>
        <span class="status-badge status-normal" id="status-l-upper-main-temp" style="font-size: 10px; padding: 3px 8px; margin-top: 4px; display: inline-block;">--</span>
    </div>
    {{-- L Side: gate --}}
    <div class="metric-card" data-metric-type="gate" style="border-left: 3px solid #3498DB;">
        <div class="metric-label" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">L Lower Gate1 Temp</div>
        <div class="metric-value" id="metric-l-gate1-temp" style="font-size: 26px; font-weight: 700;">--</div>
        <div class="metric-unit" style="font-size: 11px;">°C</div>
        <span class="status-badge status-normal" id="status-l-gate1-temp" style="font-size: 10px; padding: 3px 8px; margin-top: 4px; display: inline-block;">--</span>
    </div>
    <div class="metric-card" data-metric-type="gate" style="border-left: 3px solid #5DADE2;">
        <div class="metric-label" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">L Lower Gate2 Temp</div>
        <div class="metric-value" id="metric-l-gate2-temp" style="font-size: 26px; font-weight: 700;">--</div>
        <div class="metric-unit" style="font-size: 11px;">°C</div>
        <span class="status-badge status-normal" id="status-l-gate2-temp" style="font-size: 10px; padding: 3px 8px; margin-top: 4px; display: inline-block;">--</span>
    </div>
    {{-- L Side: lower main --}}
    <div class="metric-card" data-metric-type="main" style="border-left: 3px solid #2980B9;">
        <div class="metric-label" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">L Lower Main1 Temp</div>
        <div class="metric-value" id="metric-l-main1-temp" style="font-size: 26px; font-weight: 700;">--</div>
        <div class="metric-unit" style="font-size: 11px;">°C</div>
        <span class="status-badge status-normal" id="status-l-main1-temp" style="font-size: 10px; padding: 3px 8px; margin-top: 4px; display: inline-block;">--</span>
    </div>
    <div class="metric-card" data-metric-type="main" style="border-left: 3px solid #85C1E2;">
        <div class="metric-label" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">L Lower Main2 Temp</div>
        <div class="metric-value" id="metric-l-main2-temp" style="font-size: 26px; font-weight: 700;">--</div>
        <div class="metric-unit" style="font-size: 11px;">°C</div>
        <span class="status-badge status-normal" id="status-l-main2-temp" style="font-size: 10px; padding: 3px 8px; margin-top: 4px; display: inline-block;">--</span>
    </div>
    {{-- Room temps --}}
    <div class="metric-card" data-metric-type="room" style="border-left: 3px solid #9B59B6;">
        <div class="metric-label" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">Pressure Room Temp</div>
        <div class="metric-value" id="metric-pressure-room-temp" style="font-size: 26px; font-weight: 700;">--</div>
        <div class="metric-unit" style="font-size: 11px;">°C</div>
        <span class="status-badge status-normal" id="status-pressure-room-temp" style="font-size: 10px; padding: 3px 8px; margin-top: 4px; display: inline-block;">--</span>
    </div>
    <div class="metric-card" data-metric-type="room" style="border-left: 3px solid #8E44AD;">
        <div class="metric-label" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">Holding Room Temp</div>
        <div class="metric-value" id="metric-holding-room-temp" style="font-size: 26px; font-weight: 700;">--</div>
        <div class="metric-unit" style="font-size: 11px;">°C</div>
        <span class="status-badge status-normal" id="status-holding-room-temp" style="font-size: 10px; padding: 3px 8px; margin-top: 4px; display: inline-block;">--</span>
    </div>
</div>

{{-- Temperature Trend Chart (R & L Mold Die Temps) --}}
<div style="display: grid; grid-template-columns: 1fr; gap: 10px; margin-bottom: 10px;">
    <div class="chart-wrapper" style="padding: 12px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; flex-wrap: wrap; gap: 6px;">
            <div class="chart-title" style="font-size: 15px;">Temperature Trend (Mold Die Temp)</div>
            <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
                <label style="font-size: 11px; cursor: pointer; user-select: none;">
                    <input type="checkbox" checked onchange="CastingPerformanceTR.toggleTrendChartGroup('gate', this.checked)"> Gate
                </label>
                <label style="font-size: 11px; cursor: pointer; user-select: none;">
                    <input type="checkbox" checked onchange="CastingPerformanceTR.toggleTrendChartGroup('main', this.checked)"> Main
                </label>
                <label style="font-size: 11px; cursor: pointer; user-select: none;">
                    <input type="checkbox" checked onchange="CastingPerformanceTR.toggleTrendChartGroup('left', this.checked)"> Left
                </label>
                <label style="font-size: 11px; cursor: pointer; user-select: none;">
                    <input type="checkbox" checked onchange="CastingPerformanceTR.toggleTrendChartGroup('right', this.checked)"> Right
                </label>
            </div>
        </div>
        <div style="position: relative; height: 250px; width: 100%; overflow: hidden;">
            <canvas id="tempTrendChart"></canvas>
        </div>
    </div>
</div>

{{-- Pressure & Holding Room Temperature Trend Chart --}}
<div style="display: grid; grid-template-columns: 1fr; gap: 10px; margin-bottom: 10px;">
    <div class="chart-wrapper" style="padding: 12px;">
        <div class="chart-title" style="margin-bottom: 10px; font-size: 15px;">Pressure &amp; Holding Room Temperature Trend</div>
        <div style="position: relative; height: 250px; width: 100%; overflow: hidden;">
            <canvas id="pressureRoomChart"></canvas>
        </div>
    </div>
</div>

{{-- Flow Metrics Grid (8 sensors) --}}
<div style="margin-bottom: 12px;">
    <div style="font-size: 13px; font-weight: 700; color: var(--accent-navy); margin-bottom: 8px; padding-left: 4px; border-left: 3px solid #27AE60;">
        Cooling Flow Sensors
    </div>
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px;">
        <div class="metric-card" style="border-left: 3px solid #27AE60;">
            <div class="metric-label" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">R Upper SP Flow</div>
            <div class="metric-value" id="metric-r-upper-sp-flow" style="font-size: 26px; font-weight: 700;">--</div>
            <div class="metric-unit" style="font-size: 11px;">L/min</div>
        </div>
        <div class="metric-card" style="border-left: 3px solid #2ECC71;">
            <div class="metric-label" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">R Upper Flow</div>
            <div class="metric-value" id="metric-r-upper-flow" style="font-size: 26px; font-weight: 700;">--</div>
            <div class="metric-unit" style="font-size: 11px;">L/min</div>
        </div>
        <div class="metric-card" style="border-left: 3px solid #16A085;">
            <div class="metric-label" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">L Upper SP Flow</div>
            <div class="metric-value" id="metric-l-upper-sp-flow" style="font-size: 26px; font-weight: 700;">--</div>
            <div class="metric-unit" style="font-size: 11px;">L/min</div>
        </div>
        <div class="metric-card" style="border-left: 3px solid #1ABC9C;">
            <div class="metric-label" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">L Upper Flow</div>
            <div class="metric-value" id="metric-l-upper-flow" style="font-size: 26px; font-weight: 700;">--</div>
            <div class="metric-unit" style="font-size: 11px;">L/min</div>
        </div>
        <div class="metric-card" style="border-left: 3px solid #117A65;">
            <div class="metric-label" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">R Lower Cool Air1 Flow</div>
            <div class="metric-value" id="metric-r-cool-air1-flow" style="font-size: 26px; font-weight: 700;">--</div>
            <div class="metric-unit" style="font-size: 11px;">L/min</div>
        </div>
        <div class="metric-card" style="border-left: 3px solid #148F77;">
            <div class="metric-label" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">L Lower Cool Air1 Flow</div>
            <div class="metric-value" id="metric-l-cool-air1-flow" style="font-size: 26px; font-weight: 700;">--</div>
            <div class="metric-unit" style="font-size: 11px;">L/min</div>
        </div>
        <div class="metric-card" style="border-left: 3px solid #0E6655;">
            <div class="metric-label" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">R Lower Cool Air2 Flow</div>
            <div class="metric-value" id="metric-r-cool-air2-flow" style="font-size: 26px; font-weight: 700;">--</div>
            <div class="metric-unit" style="font-size: 11px;">L/min</div>
        </div>
        <div class="metric-card" style="border-left: 3px solid #17A589;">
            <div class="metric-label" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">L Lower Cool Air2 Flow</div>
            <div class="metric-value" id="metric-l-cool-air2-flow" style="font-size: 26px; font-weight: 700;">--</div>
            <div class="metric-unit" style="font-size: 11px;">L/min</div>
        </div>
    </div>
</div>

{{-- Flow Charts (3 charts: SP Flow, Upper Flow, Air Flow) --}}
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 10px;">
    <div class="chart-wrapper" style="padding: 12px;">
        <div class="chart-title" style="margin-bottom: 10px; font-size: 15px;">Upper SP Flow Trend</div>
        <div style="position: relative; height: 220px; width: 100%; overflow: hidden;">
            <canvas id="spFlowChart"></canvas>
        </div>
    </div>
    <div class="chart-wrapper" style="padding: 12px;">
        <div class="chart-title" style="margin-bottom: 10px; font-size: 15px;">Upper Flow Trend</div>
        <div style="position: relative; height: 220px; width: 100%; overflow: hidden;">
            <canvas id="upperFlowChart"></canvas>
        </div>
    </div>
</div>
<div style="display: grid; grid-template-columns: 1fr; gap: 10px; margin-bottom: 10px;">
    <div class="chart-wrapper" style="padding: 12px;">
        <div class="chart-title" style="margin-bottom: 10px; font-size: 15px;">Lower Cool Air Flow Trend</div>
        <div style="position: relative; height: 220px; width: 100%; overflow: hidden;">
            <canvas id="airFlowChart"></canvas>
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
                <option value="r_lower_gate1_temp_1-desc">R Gate1 Temp ↓</option>
                <option value="r_lower_gate1_temp_1-asc">R Gate1 Temp ↑</option>
                <option value="l_lower_gate1_temp_1-desc">L Gate1 Temp ↓</option>
                <option value="l_lower_gate1_temp_1-asc">L Gate1 Temp ↑</option>
            </select>
            <button id="clear-search" class="filter-btn" style="padding: 5px 10px; font-size: 12px;">Clear</button>
        </div>
    </div>
    <div style="overflow-x: auto; overflow-y: auto; max-height: 550px;">
        <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
            <thead>
                <tr style="background: var(--accent-navy); color: white; position: sticky; top: 0; z-index: 1;">
                    <th style="padding: 8px; text-align: left;  border: 1px solid #ddd; white-space: nowrap;">Timestamp</th>
                    <th style="padding: 8px; text-align: right; border: 1px solid #ddd; white-space: nowrap;">R Gate1 Temp</th>
                    <th style="padding: 8px; text-align: right; border: 1px solid #ddd; white-space: nowrap;">R Gate2 Temp</th>
                    <th style="padding: 8px; text-align: right; border: 1px solid #ddd; white-space: nowrap;">R Main1 Temp</th>
                    <th style="padding: 8px; text-align: right; border: 1px solid #ddd; white-space: nowrap;">R Main2 Temp</th>
                    <th style="padding: 8px; text-align: right; border: 1px solid #ddd; white-space: nowrap;">L Up Main</th>
                    <th style="padding: 8px; text-align: right; border: 1px solid #ddd; white-space: nowrap;">L Gate1 Temp</th>
                    <th style="padding: 8px; text-align: right; border: 1px solid #ddd; white-space: nowrap;">L Gate2 Temp</th>
                    <th style="padding: 8px; text-align: right; border: 1px solid #ddd; white-space: nowrap;">L Main1 Temp</th>
                    <th style="padding: 8px; text-align: right; border: 1px solid #ddd; white-space: nowrap;">L Main2 Temp</th>
                    <th style="padding: 8px; text-align: right; border: 1px solid #ddd; white-space: nowrap;">Pres Room</th>
                    <th style="padding: 8px; text-align: right; border: 1px solid #ddd; white-space: nowrap;">Hold Room</th>
                    <th style="padding: 8px; text-align: right; border: 1px solid #ddd; white-space: nowrap;">R Up SP Flow</th>
                    <th style="padding: 8px; text-align: right; border: 1px solid #ddd; white-space: nowrap;">R Up Flow</th>
                    <th style="padding: 8px; text-align: right; border: 1px solid #ddd; white-space: nowrap;">L Up SP Flow</th>
                    <th style="padding: 8px; text-align: right; border: 1px solid #ddd; white-space: nowrap;">L Up Flow</th>
                    <th style="padding: 8px; text-align: right; border: 1px solid #ddd; white-space: nowrap;">R Cool Air1</th>
                    <th style="padding: 8px; text-align: right; border: 1px solid #ddd; white-space: nowrap;">L Cool Air1</th>
                    <th style="padding: 8px; text-align: right; border: 1px solid #ddd; white-space: nowrap;">R Cool Air2</th>
                    <th style="padding: 8px; text-align: right; border: 1px solid #ddd; white-space: nowrap;">L Cool Air2</th>
                </tr>
            </thead>
            <tbody id="data-table-body">
                <tr>
                    <td colspan="20" style="padding: 20px; text-align: center; color: #999;">Loading data...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
