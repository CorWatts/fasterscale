<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
/**
 * @var yii\web\View $this
 */

$this->title = "The Faster Scale App | Report";
$this->registerJsFile('/js/checkin/report.js', ['depends' => [\site\assets\AppAsset::class]]);

$pie_colors = [
  [
    "color" => "#277553",
    "highlight" => "#499272"
  ],
  [
    "color" => "#29506D",
    "highlight" => "#496D89"
  ],
  [
    "color" => "#AA5939",
    "highlight" => "#D4886A"
  ],
  [
    "color" => "#AA7939",
    "highlight" => "#D4A76A"
  ],
  [
    "color" => "#277553",
    "highlight" => "#499272"
  ],
  [
    "color" => "#29506D",
    "highlight" => "#496D89"
  ],
  [
    "color" => "#AA5939",
    "highlight" => "#D4886A"
  ]
];

$tmp_pie = [];
foreach($answer_pie as $key => $category) {
  $json = [
    "value" => (int)$category['count'],
    "color" => $pie_colors[$key]["color"],
    "highlight" => $pie_colors[$key]["highlight"],
    "label" => $category['name']
  ];
  $tmp_pie[] = $json;
}

$labels     = array_column($tmp_pie, "label");
$values     = array_column($tmp_pie, "value");
$highlights = array_column($tmp_pie, "highlight");
$colors     = array_column($tmp_pie, "color");

$pie_data = [
  "labels" => $labels,
  "datasets" => [
    ["data" => $values, "backgroundColor" => $colors, "hoverBackgroundColor" => $highlights]
  ]
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
