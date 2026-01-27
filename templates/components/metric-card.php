<?php
/**
 * Reusable Metric Card Component
 *
 * Usage Example:
 * $metric_config = [
 *     'icon' => '📊',
 *     'title' => 'Total Production',
 *     'value' => '2,847',
 *     'unit' => ' parts',
 *     'trend' => 'up',          // 'up', 'down', 'neutral', null
 *     'trend_value' => '+12%',  // Optional trend percentage
 *     'status' => 'good'        // 'good', 'warning', 'critical', 'normal'
 * ];
 * include 'templates/components/metric-card.php';
 */

$icon = $metric_config['icon'] ?? '📊';
$title = $metric_config['title'] ?? 'Metric';
$value = $metric_config['value'] ?? '0';
$unit = $metric_config['unit'] ?? '';
$trend = $metric_config['trend'] ?? null;
$trend_value = $metric_config['trend_value'] ?? '';
$status = $metric_config['status'] ?? 'normal';
$element_id = $metric_config['element_id'] ?? uniqid('metric-');
?>

<div class="metric-card metric-<?php echo $status; ?>" id="<?php echo $element_id; ?>">
    <div class="metric-header">
        <span class="metric-icon"><?php echo $icon; ?></span>
        <span class="metric-title"><?php echo $title; ?></span>
    </div>

    <div class="metric-body">
        <div class="metric-value-wrapper">
            <span class="metric-value"><?php echo $value; ?></span>
            <?php if ($unit): ?>
            <span class="metric-unit"><?php echo $unit; ?></span>
            <?php endif; ?>
        </div>

        <?php if ($trend): ?>
        <div class="metric-trend trend-<?php echo $trend; ?>">
            <?php
            if ($trend === 'up') {
                echo '<svg class="trend-icon" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>';
            } elseif ($trend === 'down') {
                echo '<svg class="trend-icon" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline><polyline points="17 18 23 18 23 12"></polyline></svg>';
            } else {
                echo '<svg class="trend-icon" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line></svg>';
            }
            ?>
            <span class="trend-text"><?php echo $trend_value; ?></span>
        </div>
        <?php endif; ?>
    </div>
</div>
