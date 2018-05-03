(function($, Chart) {
  $(document).ready(function() {
    var past_checkin_dates = JSON.parse(document.getElementById('past-checkin-dates').innerHTML)
      .map(function(date) {
      return new Date(date)
    })


    var pie_data = JSON.parse(document.getElementById('pie-chart-data').innerHTML)
    if(pie_data.labels.length) {
      Chart.defaults.global.responsive = true
      Chart.defaults.global.scaleBeginAtZero = true
      var pie_ctx = document.getElementById('category-pie-chart').getContext('2d')
      var pieChart = new Chart(pie_ctx, {
        type: 'pie',
        data: pie_data,
        options: {
          legend: {
            display: false
          }
        }
      })
    }

    $( '#datepicker' ).click(function() {
      $(this).pickadate({

        // The title label to use for the month nav buttons
        labelMonthNext: 'Next month',
        labelMonthPrev: 'Previous month',

        // The title label to use for the dropdown selectors
        labelMonthSelect: 'Select a month',
        labelYearSelect: 'Select a year',

        // Months and weekdays
        monthsFull: [ 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' ],
        monthsShort: [ 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec' ],
        weekdaysFull: [ 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday' ],
        weekdaysShort: [ 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat' ],

        // Today and clear
        today: 'Today',
        clear: 'Clear',
        close: 'Close',

        // Close on a user action
        closeOnSelect: true,
        closeOnClear: true,

        // The format to show on the `input` element
        format: 'yyyy-mm-dd',
        formatSubmit: 'yyyy-mm-dd',
        hiddenName: true,

        onSet: function(obj) {
          // we only want to redirect them if they've selected a date
          if(obj.hasOwnProperty('select')) {
            location.href = "/checkin/view/"+this.get()
          }
        },

        // only show specific dates
        disable: [
          true // disable all except the following
        ].concat(past_checkin_dates, [new Date()])

      })
    })
  })
})(jQuery, Chart)
