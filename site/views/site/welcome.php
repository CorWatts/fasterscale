<?php
use yii\helpers\Html;
use yii\jui\DatePicker;

/**
 * @var yii\web\View $this
 */
$this->title = 'The Faster Scale App | Welcome';
?>
<div class="site-welcome">
  <h1>Welcome to the Faster Scale App</h1>
  <p>Thanks for signing up!</p>

  <h2>Next Steps</h2>

  <h3>Check-Ins</h3>
  <p>You will soon be doing a brief check-in to log and process your emotions. If you have email reports enabled and you score above your set threshold during a check-in, your selected friends will be emailed a summary of your result. Hopefully this encourages discussion and verbal processing of your thought patterns, behavioral patterns, and feelings.</p>

  <h3>Reports</h3>
  <p>After your check-in, you'll be redirected to your previous check-in log. Here you can view the results of the check-in you just completed, as well as any you've completed in the past. View previous check-ins by clicking the arrow buttons. Additionally, a navigable calendar appears when you click on the date itself.</p>

  <h3>Analytics</h3>
  <p>You can view helpful stats and analytics, such as your "Danger Score" history, by clicking on the "Statistics" link at the top of this page.</p>

  <h3>Account Settings</h3>
  <p>If you need to change any of your account settings, simply go to your settings page by clicking on your email at the very top of this page. You can change email report thesholds, report partners, your password, and other settings there.</p>

  <h3>Finally, do your first Check-in</h3>
    <p>Your next step should be to do a check-in. Use this button to do so:</p>
    <a href="/checkin" class="btn btn-primary" role="button">Check-in Here</a>
</div>
