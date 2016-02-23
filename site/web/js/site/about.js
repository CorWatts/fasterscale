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
        "<td>"+commit.commit.message+"</td>"+
        "</tr>");
        });
    }
});
})(jQuery, moment)
