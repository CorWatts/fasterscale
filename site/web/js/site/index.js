(function($, Chart) {
  var ctx = document.getElementById("example-scores-line-chart").getContext("2d");

  var gradient = ctx.createLinearGradient(0, 0, 0, 200);
  gradient.addColorStop(0, "rgba(151,187,205,0.7)");   
  gradient.addColorStop(1, "rgba(151,187,205,0)");

  var example_line_chart_data = {
    "labels": ['2016-01-01', '2016-01-02', '2016-01-03', '2016-01-04', '2016-01-05'],
    "datasets": [{
      "strokeColor": "rgba(151,187,205,1)",
      "pointColor": "rgba(151,187,205,1)",
      "pointStrokeColor": "#fff",
      "pointHighlightFill": "#fff",
      "pointHighlightStroke": "rgba(151,187,205,1)",
      "data": [65, 20, 80, 81, 56]
    }]
  };

  var lineChartData = {
    labels : ["January","February","March","April","May"],
    datasets : [{
      label: "Example Scores Chart",
      fillColor : gradient,
      strokeColor : "rgba(151,187,205,1)",
      pointColor : "rgba(151,187,205,1)",
      pointStrokeColor : "#fff",
      pointHighlightFill : "#fff",
      pointHighlightStroke : "rgba(151,187,205,1)",
      data : [15, 20, 64, 43, 25]
    }]
  };

  new Chart(ctx).Line(lineChartData, {responsive: true, scaleBeginAtZero: true});
})(jQuery, Chart)
