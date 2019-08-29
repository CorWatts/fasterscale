<?php
/**
 * @var yii\web\View $this
 */

$this->title = "The Faster Scale App | Report";
$this->registerJsFile('/js/checkin/report.js', ['depends' => [\site\assets\AppAsset::class]]);

?>
<h1>Report</h1>

<div class="checkin-report">
  <div class='row'>
      <div class='col-sm-4'>
          <h3>Behaviors by Category</h3>
          <div class='pie-chart-container'>
            <canvas id='category-pie-chart'></canvas>
          </div>
      </div>
      <div class='col-sm-8'>
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
    "<td>{$row['category']['name']}</td>".
    "</tr>";
  }
  ?>
          </table>
      </div>
  </div>
  <div class='row'>
      <div class='col-md-12'>
          <div>
            <span class='h3'>Your Check-in History</span>
            <div class="btn-group date-period-switcher" data-toggle="buttons">
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
          <div class='line-chart-container'>
            <canvas id='checkins-line-chart'></canvas>
          </div>
      </div>
  </div>
</div>
<?php
$this->registerJson($pie_data, "pie_data");
$this->registerJson($bar_dates, 'bar_dates_json');
$this->registerJson($bar_datasets, 'bar_datasets_json');
