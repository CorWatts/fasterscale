<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
/**
 * @var yii\web\View $this
 */

$this->title = "Checkin Report";
$this->registerJsFile('/js/checkin/report.js', ['depends' => [\site\assets\AppAsset::className()]]);

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
    "color" => "AA5939",
    "highlight" => "D4886A"
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
    "color" => "AA5939",
    "highlight" => "D4886A"
  ]
];


$pie_data = [];
foreach($answer_pie as $key => $category) {
  $json = [
    "value" => (int)$category['count'],
    "color" => $pie_colors[$key]["color"],
    "highlight" => $pie_colors[$key]["highlight"],
    "label" => $category['name']
  ];
  $pie_data[] = $json;
}
?>
<h1>Checkin Report</h1>


<div class='row'>
    <div class='col-md-4'>
        <h2>Answers by Category</h2>
        <canvas id='category-pie-chart'></canvas>
    </div>
    <div class='col-md-8'>
        <h2>Top Answers</h2>
        <table class='table table-striped'>
            <tr>
                <th>#</th>
                <th>Category</th>
                <th>Option</th>
                <th>Count</th>
            </tr>
<?php foreach($top_options as $key => $row) {
$num = $key + 1;
print "<tr>".
  "<td>".$num."</td>".
  "<td>{$row['category']}</td>".
  "<td>{$row['name']}</td>".
  "<td>{$row['count']}</td>".
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
$this->registerJson($pie_data, "chart_pie_data");
$this->registerJson(array_keys($scores), 'chart_scores_keys_json');
$this->registerJson(array_values($scores), 'chart_scores_values_json');
