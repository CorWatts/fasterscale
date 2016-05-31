(function($, Chart) {
  $(document).ready(function() {
    Chart.defaults.global.responsive = true;
    Chart.defaults.global.scaleBeginAtZero = true;

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

    var line_ctx = document.getElementById('scores-line-chart').getContext('2d');

    var gradient = line_ctx.createLinearGradient(0, 0, 0, 200);
    gradient.addColorStop(0, "rgba(75,192,192,0.9)");   
    gradient.addColorStop(1, "rgba(75,192,192,0.1)");

    var line_data = {
      labels : $.parseJSON($("#chart_scores_keys_json").html()),
      datasets : [{
        backgroundColor : gradient,
        lineTension: 0.3,
        borderColor: "rgba(75,192,192,1)",
        borderWidth: 2,
        borderCapStyle: 'butt',
        borderDash: [],
        borderDashOffset: 0.0,
        borderJoinStyle: 'miter',
        pointBorderColor: "rgba(75,192,192,1)",
        pointBackgroundColor: "#fff",
        pointBorderWidth: 1,
        pointHoverRadius: 5,
        pointHoverBackgroundColor: "rgba(75,192,192,1)",
        pointHoverBorderColor: "rgba(220,220,220,1)",
        pointHoverBorderWidth: 2,
        pointRadius: 1,
        pointHitRadius: 10,
        data: $.parseJSON($("#chart_scores_values_json").html())
      }]
    };


    var scoresChart = new Chart(line_ctx, {
      type: 'line',
      data: line_data,
      options: {
        legend: {
          display: false
        },
        tooltips: { 
          callbacks: {
            title: function(tooltipItem, data) {
              // format tooltip title
              return moment(tooltipItem[0].xLabel).format('MMM DD, YYYY');
            },
            label: function(tooltipItem, data) {
              // format tooltip label
              return "Score: " + tooltipItem.yLabel;
            }
          }
        },
        scales: {
          xAxes: [{
            type: "time",
            time: {
              unit: "day" // only show 'day' units on the scale
            }
          }]
        }
      }
    });
  });
})(jQuery, Chart)
