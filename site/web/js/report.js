(function($, Chart) {
  $(document).ready(function() {

    Chart.defaults.global.responsive = true;
    Chart.defaults.global.scaleBeginAtZero = true;

    var pie_chart_options = {};
    var pie_chart_ctx = document.getElementById('category-pie-chart').getContext('2d');

    new Chart(pie_chart_ctx).Pie($.parseJSON($('#chart_pie_data').html()), pie_chart_options);


    var line_chart_ctx = document.getElementById('scores-line-chart').getContext('2d');

    var gradient = line_chart_ctx.createLinearGradient(0, 0, 0, 500);
    gradient.addColorStop(0, 'rgba(151,187,205,0.7)');   
    gradient.addColorStop(1, 'rgba(151,187,205,0)');

    var line_chart_data = {
      'labels' : $.parseJSON($("#chart_scores_keys_json").html()),
      'datasets' : [
      {
        fillColor: gradient,
        strokeColor : "rgba(151,187,205,1)",
        pointColor : "rgba(151,187,205,1)",
        pointStrokeColor : "#fff",
        pointHighlightFill : "#fff",
        pointHighlightStroke : "rgba(151,187,205,1)",
        data: $.parseJSON($("#chart_scores_values_json").html())
      }
      ]
    };

    var scores_line_chart = new Chart(line_chart_ctx).Line(line_chart_data, {});
  })
})(jQuery, Chart)
