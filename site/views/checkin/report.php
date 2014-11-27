<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
/**
 * @var yii\web\View $this
 */

$this->title = "Checkin Report";

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
                <th>Option Name</th>
                <th>Count</th>
            </tr>
        <?php foreach($top_options as $key => $row) {
            $num = $key + 1;
            print "<tr>".
                "<td>".$num."</td>".
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
$this->registerJs('
    Chart.defaults.global.responsive = true;

    var pie_chart_options = {};
    
    var pie_chart_ctx = document.getElementById("category-pie-chart").getContext("2d");
    var myPieChart = new Chart(pie_chart_ctx).Pie('.json_encode($pie_data).', pie_chart_options);
');



$data = json_encode([
    "labels" => array_keys($scores),
    "datasets" => [
        [
            "fillColor" => "rgba(72,108,136,0.4)",
            "strokeColor" => "rgba(41,79,109,1)",
            "pointColor" => "rgba(41,79,109,1)",
            "pointStrokeColor" => "#fff",
            "pointHighlightFill" => "rgba(112,141,164,1)",
            "pointHighlightStroke" => "#fff",
            "data" => array_values($scores)
        ]
    ]
]);
$this->registerJs("
    var line_chart_options = {};

    var line_chart_ctx = document.getElementById('scores-line-chart').getContext('2d');
    var scores_line_chart = new Chart(line_chart_ctx).Line($data, {});");
?>
