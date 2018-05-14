<?php
/**
 * @var yii\web\View $this
 */

$this->title = "The Faster Scale App | Report";
$this->registerJsFile('/js/checkin/report.js', ['depends' => [\site\assets\AppAsset::class]]);

?>
<h1>Report</h1>

<div class='row'>
    <div class='col-md-4'>
        <h3>Behaviors by Category</h3>
        <canvas id='category-pie-chart'></canvas>
    </div>
    <div class='col-md-8'>
        <h3>Most Frequently Selected Behaviors</h3>
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
        <div>
          <h3 style="display: inline;">Your Check-in History</h3>
          <div style="display: inline; float: right;" class="btn-group date-period-switcher" data-toggle="buttons">
            <label class="btn btn-default active" data-period="30">
              <input type="radio" name="options" checked> 30 Days
            </label>
            <label class="btn btn-default" data-period="90">
              <input type="radio" name="options"> 90 Days
            </label>
            <label class="btn btn-default" data-period="180">
              <input type="radio" name="options"> 180 Days
            </label>
          </div>
        </div>
        <canvas id='scores-line-chart'></canvas>
    </div>
</div>
<?php
$this->registerJson($pie_data, "pie_data");
$this->registerJson($bar_dates, 'bar_dates_json');
$this->registerJson($bar_datasets, 'bar_datasets_json');
