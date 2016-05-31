<?php
/**
 * @var yii\web\View $this
 */
$this->title = 'The Faster Scale App | Welcome';
$this->registerMetaTag([
  'name' => 'description',
  'content' => 'A totally free, online version of the Faster Scale. Increase your self-awareness and practice emotional mindfulness by signing up or logging in today.'
]);
$this->registerJsFile('/js/site/index.js', ['depends' => [\site\assets\AppAsset::className()]]);
?>
<div class="site-index">
	<div class="jumbotron">
		<h1>The Faster Scale App</h1>
		<p class="lead">Learn to be aware of your emotional state to see temptation coming before it hits.</p>
	</div>

	<div class="body-content">
		<div class="row">

			<div class="col-md-4">
				<div class="well">
					<div class="caption">
				  <h3>Useful Analytics & Alerts</h3>
					<canvas id='example-scores-line-chart'></canvas>
						<p>Everyone loves analytics. View beautiful charts and graphs to gain additional insights to your emotional tendencies. Your daily check-in answers are stored so you can go back to any point in history and view what you were experiencing at that time. You can also choose to send a report to your friends if you score above a certain threshold, which opens up avenues for conversation.</p>
					</div>
				</div>
			</div>

			<div class="col-md-4">
				<div class="thumbnail" style="border: none;">
					<div class="caption">
				    <h3>What is this?</h3>
            <p>Contrary to what many addicts might believe, relapse is not an event that randomly appears. Rather, there are many biological, psychological, and social changes that precede it.</p>
            <p>By using Michael Dye's popular and proven relapse prevention tool "The Faster Scale", a person can train themselves to notice these "warning signs" before relapse occurs and take evasive action. Log the emotions you are experiencing in order to see how vulnerable to temptation you are in the moment. Do a check-in daily to see the progress of your emotions over time.</p>
					</div>
				</div>
			</div>

			<div class="col-md-4">
				<div class="well">
				  <div class="caption">
				    <h3>Danger Score</h3>
    		    <div class='alert alert-warning'>Definite warning signs. You aren't doing well. Take some time out, write out what you're feeling, and discuss it with someone.</div>
				  	<p>A Danger Score is calculated for each check-in, allowing you to get a simple summary of how close to relapse you are. As your score goes up, you become more and more likely to relapse. Don't let temptation blindside you into relapse. See it coming before it arrives, and take preventative action.</p>
				  </div>
				</div>
			</div>

		</div>
	</div>
</div>
