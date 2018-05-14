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
  });
})(jQuery)
