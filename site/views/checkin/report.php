<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
//use miloschuman\highcharts\Highcharts;
/**
 * @var yii\web\View $this
 */
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
        var myPieChart = new Chart(ctx).Pie('.$pie_chart.', pie_chart_data);
');
?>

<?php
/*
echo Highcharts::widget([
   'options' => [
      'chart' => ['type' => 'area'],
      'title' => ['text' => 'Fruit Consumption'],
      'xAxis' => [
        'categories' => ['2014-01-01', '2014-01-02', '2014-01-03', '2014-01-04', '2014-01-05', '2014-01-06', '2014-01-07', '2014-01-08', '2014-01-09']
      ],
      'yAxis' => [
         'title' => ['text' => 'Fruit eaten'],
         'labels' => ['formatter' => new JSExpression('function() { return this.value; }')]
      ],
      'series' => [
         ['name' => 'Jane', 'data' => [89, 99, 45, 76,21, 83, 33, 36, 55]],
      ]
   ]
]);
 */
?>
