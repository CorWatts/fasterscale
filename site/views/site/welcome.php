<?php
use yii\helpers\Html;
use yii\jui\DatePicker;

/**
 * @var yii\web\View $this
 */
$this->title = 'Welcome';
?>
<div class="site-welcome">
    <h1><?= Html::encode($this->title) ?> to the Faster Scale App!</h1>
    <p>Thanks for signing up!</p>

  <h2>Next Steps</h2>
  <h3>Do a Check In</h3>
  <p>
    Your next step should be to do a check in. Click this button to do that:<br />
    <a href="/checkin" class="btn btn-primary" role="button">Do a Check In</a>
  </p>

  <p>If you have email reports enabled and you scored above your set threshold your selected friends will be emailed a summary of your check in results. This should encourage discussion and processing if you're feeling poorly.</p>

  <h3>Reports</h3>
  <p>After your check in, you'll be redirected to your past checkin log. Here you can view the results of the check in you just completed, as well as any check ins you've made in the past. Navigate into the past by clicking the arrow buttons. A navigable calendar appears when you click on the date itself.

  <h3>Analytics</h3>
  <p>You can also view interesting stats and analytics, such as your "Danger Score" history, by clicking on the "Statistics" link up top. 

  <h3>Account Settings</h3>
  <p>If you need to change any of your account settings, simply go to your settings page by clicking on your username at the very top. You can change email report thesholds, report partners, your apssword, and other goodies.</p>
</div>
