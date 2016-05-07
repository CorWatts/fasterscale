<?php
use yii\helpers\Html;
use yii\jui\DatePicker;

/**
 * @var yii\web\View $this
 */
$this->title = 'About';
$this->registerJsFile('/js/site/about.js', ['depends' => [\site\assets\AppAsset::className()]]);
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>Hopefully you've heard of Michael Dye's emotional check-in tool called the Faster Scale. We have found the Faster Scale helpful in our personal lives as we've tried to become more emotionally mindful. This aims to be an electronic version of the paper questionaire. It stores a history of your check-ins so you can visually see your emotional state change over time. This app has been a personal project for the last several years -- it is very much a work in progress!</p>

    <h2>Free as in Beer AND Speech</h2>
    <p><strong>This all free.</strong> We don't change anything to use this. The code is <a href="https://github.com/CorWatts/fasterscale">freely available</a>, and if you like you can download it and run this app yourself! Your data is available to you at any time as a CSV export, available on your profile page.</p>

    <h2>The Code</h2>
    <p>If you're interested in peeking beneath the hood, or maybe even helping me with some code contributions, you can find the repo at <a href='https://github.com/CorWatts/fasterscale'>Github</a>.</p>

    <h2>Latest Commits</h2>
    <table id='commits' class="table table-striped">
        <tr>
            <th>When</th>
            <th>Link</th>
            <th>Committer</th>
            <th>Description</th>
        </tr>
    </table>
    <div id="spinner"><img src="/img/spinner.gif" id="spinner-gif" /></div>
</div>
