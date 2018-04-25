<?php
/**
 * @var yii\web\View $this
 */

$this->title = "The Faster Scale App | Report";
$this->registerJsFile('/js/checkin/report.js', ['depends' => [\site\assets\AppAsset::class]]);

$values     = array_map('intval', array_column($answer_pie, "count"));
$labels     = array_column($answer_pie, "name");
$colors     = array_column($answer_pie, "color");
$highlights = array_column($answer_pie, "highlight");

$pie_data = [
  "labels"   => $labels,
  "datasets" => [[
      "data"                 => $values,
      "backgroundColor"      => $colors,
      "hoverBackgroundColor" => $highlights
  ]]
];
?>
<h1>Check-in Report</h1>

<div class='row'>
    <div class='col-md-4'>
        <h2>Behaviors by Category</h2>
        <canvas id='category-pie-chart'></canvas>
    </div>
    <div class='col-md-8'>
        <h2>Most Frequently Selected Behaviors</h2>
        <table class='table table-striped'>
            <tr>
                <th>Count</th>
                <th>Behavior</th>
                <th>Category</th>
            </tr>
<?php foreach($top_behaviors as $key => $row) {
$num = $key + 1;
print "<tr>".
  "<td>{$row['count']}</td>".
  "<td>{$row['behavior']['name']}</td>".
  "<td>{$row['behavior']['category']['name']}</td>".
  "</tr>";
}
?>
        </table>
    </div>
</div>
<div class='row'>
    <div class='col-md-12'>
        <h2>Last Month's Scores</h2>
        <canvas id='scores-line-chart'></canvas>
    </div>
</div>
<?php
$line_keys = array_map(function($date) {
  $timestamp = new \DateTime($date);
  return $timestamp->format('Y-m-d');
}, array_keys($scores));

$this->registerJson($pie_data, "pie_data");
$this->registerJson($line_keys, 'chart_scores_keys_json');
$this->registerJson(array_values($scores), 'chart_scores_values_json');
