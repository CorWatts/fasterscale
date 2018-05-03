(function($, Chart) {
  $(document).ready(function() {
    Chart.defaults.global.responsive = true;
    Chart.defaults.global.scaleBeginAtZero = true;
    Chart.defaults.global.maintainAspectRatio = false;

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
  });
})(jQuery, Chart)
