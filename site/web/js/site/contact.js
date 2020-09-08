(function($) {
  $(document).ready(function() {
    $('#captcha-image > img').on('click', function () {
      $(this).attr('src', '/site/captcha?v=' + Math.random())
    });
  });
})(jQuery)