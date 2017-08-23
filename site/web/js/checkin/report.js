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
        },
        responsive: true,
        maintainAspectRatio: false
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
      var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec']
      return new Chart(bar_ctx, {
        type: 'bar',
        data: {labels: labels, datasets: datasets},
        options: {
          tooltips: {
            mode: 'index',
            intersect: false,
            callbacks: {
              title: function(tooltipItem, data) {
                // format tooltip title
                var d = new Date(tooltipItem[0].xLabel)
                return months[d.getUTCMonth()]+" "+d.getUTCDate()+", "+d.getUTCFullYear()
              },
              label: function(tooltipItem, data) {
                // format tooltip label
                return "Score: " + tooltipItem.yLabel;
              }
            }
          },
          responsive: true,
          maintainAspectRatio: false,
          legend: {
            labels: {
              filter: function(legendItem, chartData) {
                // if no data for this dataset, do not show the legend item.
                return chartData.datasets[legendItem.datasetIndex].data.filter(Number.isInteger).length
              }
            }
          },
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
