(function($) {
  $(document).ready(function() {
    $('#signupform-send_email').removeAttr('disabled');
    $('#signupform-send_email').click(function() {
      if($(this).is(":checked")) {
        $('#email_threshold_fields').show();
      } else {
        $('#email_threshold_fields').hide();
      }
    });
  });
})(jQuery)
