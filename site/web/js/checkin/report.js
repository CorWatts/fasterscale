(function($, Chart) {
  $(document).ready(function() {

    /* General Chart Config */
    Chart.defaults.global.responsive = true;
    Chart.defaults.global.scaleBeginAtZero = true;

    /* Pie Chart */
    var pie_ctx = document.getElementById('category-pie-chart').getContext('2d');
    var pie_data = JSON.parse(document.getElementById('pie_data').innerHTML);
    var pieChart = new Chart(pie_ctx, {
      type: 'pie',
      data: pie_data,
      options: {
        legend: {
          display: false
        }
      }
    });

    /* Bar Chart */
    var bar_ctx = document.getElementById('checkins-line-chart').getContext('2d');
    var labels = $.parseJSON($("#bar_dates_json").html());
    var datasets = $.parseJSON($("#bar_datasets_json").html());
    var chart = buildChart(labels, datasets);

    /* Event Listeners */
    $('.date-period-switcher').on('click', 'label', function(e) {
      var period = this.getAttribute('data-period');
      console.log('clicked', this, period)

      $.ajax({
        url: '/checkin/history/'+period,
        dataType: 'json'
      })
        .then(function(response) {
          chart.data = response;
          chart.update();
        });
    });


    function buildChart(labels, datasets) {
      return new Chart(bar_ctx, {
        type: 'bar',
        data: {labels: labels, datasets: datasets},
        options: {
          tooltips: { mode: 'index', intersect: false },
          responsive: true,
          scales: {
            xAxes: [{ stacked: true, }],
            yAxes: [{
              stacked: true,
              scaleLabel: { display: true, labelString: '# of behaviors', }
            }]
          }
        }
      });
    }

  });
})(jQuery, Chart)
