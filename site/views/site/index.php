<?php
/**
 * @var yii\web\View $this
 */
$this->title = 'The Faster Scale App';
$this->registerJsFile('/js/site/index.js', ['depends' => [\site\assets\AppAsset::className()]]);
?>
<div class="site-index">
	<div class="jumbotron">
		<h1>Got emotional mindfulness?</h1>
		<p class="lead">Learn to be aware of your emotional state to see temptation coming before it hits.</p>
	</div>

	<div class="body-content">
		<div class="row">

			<div class="col-md-4">
				<div class="well">
					<div class="caption">
				  <h3>Useful Analytics & Alerts</h3>
					<canvas id='example-scores-line-chart'></canvas>
						<p>Everyone loves analytics. View beautiful charts and graphs to gain additional insights to your emotional tendencies. Your daily checkin answers are stored so you can go back to any point in history and view what you were experiencing at that time. You can also choose to send a report to your friends if you score above a certain threshold, which opens up avenues for conversation.</p>
					</div>
				</div>
			</div>

			<div class="col-md-4">
				<div class="thumbnail" style="border: none;">
					<div class="caption">
				<h3>The Faster Scale</h3>
				<p>Use Michael Dye's popular and proven relapse prevention tool: The Faster Scale. Log the emotions you are experiencing in order to see how vulnerable to temptation you are in the moment. Check in daily to see the progress of your emotions over time.</p>
					</div>
				</div>
			</div>

			<div class="col-md-4">
				<div class="well">
				  <div class="caption">
				    <h3>Danger Score</h3>
    		    <div class='alert alert-warning'>Definite warning signs. You aren't doing well. Take some time out, write out what you're feeling, and discuss it with someone.</div>
				  	<p>A Danger Score is calculated for each checkin, allowing you to get a simple summary of how close to relapse you are. As your score goes up, you become more and more likely to relapse. Don't let temptation blindside you into relapse. See it coming before it arrives, and take preventative action.</p>
				  </div>
				</div>
			</div>

		</div>
	</div>
</div>
