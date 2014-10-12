<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
//use miloschuman\highcharts\Highcharts;
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
        <canvas id='category_pie_chart' width="250" height="250"></canvas>
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
<?php
$this->registerJs('
        var pie_chart_data = {
            //Boolean - Whether we should show a stroke on each segment
            segmentShowStroke : true,

            //String - The colour of each segment stroke
            segmentStrokeColor : "#fff",

            //Number - The width of each segment stroke
            segmentStrokeWidth : 2,

            //Number - Amount of animation steps
            animationSteps : 100,

            //String - Animation easing effect
            animationEasing : "easeOutBounce",

            //Boolean - Whether we animate the rotation of the Doughnut
            animateRotate : true,

            //Boolean - Whether we animate scaling the Doughnut from the centre
            animateScale : true,

            legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>"

        };
        var ctx = document.getElementById("category_pie_chart").getContext("2d");
        var myPieChart = new Chart(ctx).Pie('.json_encode($pie_data).', pie_chart_data);
');
?>
