(function($) {
  /**
   * Ripped from Stack Overflow
   * Thanks to Caio Tarifa via https://stackoverflow.com/a/37802747
   * MIT Licensed
   */
  var TimeAgo = (function() {
    var self = {};

    // Public Methods
    self.locales = {
      prefix: '',
      sufix:  'ago',

      seconds: 'less than a minute',
      minute:  'about a minute',
      minutes: '%d minutes',
      hour:    'about an hour',
      hours:   'about %d hours',
      day:     'a day',
      days:    '%d days',
      month:   'about a month',
      months:  '%d months',
      year:    'about a year',
      years:   '%d years'
    };

    self.inWords = function(timeAgo) {
      var seconds = Math.floor((new Date() - parseInt(timeAgo)) / 1000),
        separator = this.locales.separator || ' ',
        words = this.locales.prefix + separator,
        interval = 0,
        intervals = {
          year:   seconds / 31536000,
          month:  seconds / 2592000,
          day:    seconds / 86400,
          hour:   seconds / 3600,
          minute: seconds / 60
        };

      var distance = this.locales.seconds;

      for (var key in intervals) {
        interval = Math.floor(intervals[key]);

        if (interval > 1) {
          distance = this.locales[key + 's'];
          break;
        } else if (interval === 1) {
          distance = this.locales[key];
          break;
        }
      }

      distance = distance.replace(/%d/i, interval);
      words += distance + separator + this.locales.sufix;

      return words.trim();
    };

    return self;
  }());
  /* end Caio Tarifa's code */

  /**
   * Taken from the UnderscoreJS source
   * MIT Licensed
   */
  var escapeMap = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#x27;',
    '`': '&#x60;'
  };

  var createEscaper = function(map) {
    var escaper = function(match) {
      return map[match];
    };

    var source = '(?:' + Object.keys(escapeMap).join('|') + ')';
    var testRegexp = RegExp(source);
    var replaceRegexp = RegExp(source, 'g');
    return function(string) {
      string = string == null ? '' : '' + string;
      return testRegexp.test(string) ? string.replace(replaceRegexp, escaper) : string;
    };
  };
  /* End UnderscoreJS code */

  var escape = createEscaper(escapeMap);

  $.ajax({
    type: 'GET',
    url: 'https://api.github.com/repos/CorWatts/emotionalcheckin/commits?per_page=10',
    dataType: 'json',
    success: function(data) {
      $('#spinner').remove();
      $.each(data, function(key, data) {
        $('#commits').append("<tr>"+
          "<td class='text-nowrap'>"+TimeAgo.inWords(new Date(data.commit.committer.date).getTime())+"</td>"+
          "<td><a href='"+data.html_url+"'>Commit</a></td>"+
          "<td><a href='"+data.author.html_url+"'>"+data.author.login+"</a></td>"+
          "<td>"+escape(data.commit.message)+"</td>"+
          "</tr>");
      });
    }
  });
})(jQuery)
