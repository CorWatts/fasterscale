(function($, moment) {
$.ajax({
type: 'GET',
  url: 'https://api.github.com/repos/CorWatts/emotionalcheckin/commits?per_page=10',
  dataType: 'json',
  success: function(data) {
    $('#spinner').remove();
    $.each(data, function(key, commit) {
      $('#commits').append("<tr>"+
        "<td class='text-nowrap'>"+moment(commit.commit.committer.date).fromNow()+"</td>"+
        "<td><a href='"+commit.html_url+"'>Commit</a></td>"+
        "<td><a href='"+commit.author.html_url+"'>"+commit.author.login+"</a></td>"+
        "<td>"+escape(commit.commit.message)+"</td>"+
        "</tr>");
        });
    }
});
})(jQuery, moment)

// Stole this HTML escaping code from the UnderscoreJS source
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

var escape = createEscaper(escapeMap);
