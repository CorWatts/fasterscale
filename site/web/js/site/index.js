(function(Chart) {
  var ctx = document.getElementById("example-scores-line-chart").getContext("2d");

  var gradient = ctx.createLinearGradient(0, 0, 0, 200);
  gradient.addColorStop(0, "rgba(75,192,192,0.9)");   
  gradient.addColorStop(1, "rgba(75,192,192,0.1)");

  Chart.defaults.global.legendCallback = function(chart) { return ""; };

  var lineChartData = {
    labels : ["January","February","March","April","May"],
    datasets : [{
      label: "Score",
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
      data : [15, 20, 64, 43, 25]
    }]
  };

  var lineChart = new Chart(ctx, {
    type: 'line',
    data: lineChartData,
    options: {
      legend: {
        display: false
      }
    }
  });
})(Chart)
