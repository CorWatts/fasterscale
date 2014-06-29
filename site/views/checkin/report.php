<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use miloschuman\highcharts\Highcharts;
/**
 * @var yii\web\View $this
 */
?>
<h1>Checkin Report</h1>
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
        
<?php
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
?>
