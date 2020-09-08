(function($) {
  $(document).ready(function() {
    $('#signupform-send_email').removeAttr('disabled');
    $('#signupform-send_email').click(function() {
      if($(this).is(":checked")) {
        $('#send_email_fields').show();
      } else {
        $('#send_email_fields').hide();
      }
    });

    $('#password-toggle').click(function () {
      if( $('#signupform-password').attr('type') === 'password' ) {
        $('#password-toggle').text('Hide');
        $('#signupform-password').attr('type', 'text');
      } else {
        $('#password-toggle').text('Show');
        $('#signupform-password').attr('type', 'password');
      }
    });

    $('[\data-toggle=\"tooltip\"]').tooltip();

    $('#captcha-image > img').on('click', function () {
      $(this).attr('src', '/site/captcha?v=' + Math.random())
    });
  });
})(jQuery)
