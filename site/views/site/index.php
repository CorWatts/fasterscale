<?php
/**
 * @var yii\web\View $this
 */
$this->title = 'The Faster Scale App';
?>
<div class="site-index">
	<div class="jumbotron">
		<h1>Got emotional mindfulness?</h1>
		<p class="lead">Learn to be aware of your emotional state to see temptation coming before it hits.</p>
	</div>

	<div class="body-content">
		<div class="row">

			<div class="col-md-4">
				<div class="thumbnail">
				  <h3>Useful Analytics & Alerts</h3>
					<canvas id='example-scores-line-chart'></canvas>
					<div class="caption">
						<p>Everyone loves analytics. View beautiful charts and graphs to gain additional insights to your emotional tendencies. Your daily checkin answers are stored so you can go back to any point in history and view what you were experiencing at that time. You can also choose to send a report to your friends if you score above a certain threshold, which opens up avenues for conversation.</p>
					</div>
				</div>
			</div>

			<div class="col-md-4">
				<h2>The Faster Scale</h2>
				<p>Use Michael Dye's popular and proven relapse prevention tool: The Faster Scale. Log the emotions you are experiencing in order to see how vulnerable to temptation you are in the moment. Check in daily to see the progress of your emotions over time.</p>
			</div>

			<div class="col-md-4">
				<div class="thumbnail">
				  <h3>Danger Score</h3>
				  <div class="caption">
    		    <div class='alert alert-warning'>Definite warning signs. You aren't doing well. Take some time out, write out what you're feeling, and discuss it with someone.</div>
				  	<p>A Danger Score is calculated for each checkin, allowing you to get a simple summary of how close to relapse you are. As your score goes up, you become more and more likely to relapse. Don't let temptation blindside you into relapse. See it coming before it arrives, and take preventative action.</p>
				  </div>
				</div>
			</div>

		</div>
	</div>
</div>
<?php
$data = json_encode([
		"labels" => ['2016-01-01', '2016-01-02', '2016-01-03', '2016-01-04', '2016-01-05'],
		"datasets" => [
				[
						//"fillColor" => "rgba(72,108,136,0.4)",
            "strokeColor" => "rgba(151,187,205,1)",
            "pointColor" => "rgba(151,187,205,1)",
            "pointStrokeColor" => "#fff",
            "pointHighlightFill" => "#fff",
            "pointHighlightStroke" => "rgba(151,187,205,1)",
						"data" => [65, 20, 80, 81, 56]
				]
		]
]);

$this->registerJs('
var ctx = document.getElementById("example-scores-line-chart").getContext("2d");
    
    var gradient = ctx.createLinearGradient(0, 0, 0, 200);
    gradient.addColorStop(0, "rgba(151,187,205,0.7)");   
    gradient.addColorStop(1, "rgba(151,187,205,0)");
    
    var randomScalingFactor = function(){ return Math.round(Math.random()*100)};
    var lineChartData = {
        labels : ["January","February","March","April","May"],
        datasets : [
            {
                label: "Example Scores Chart",
                fillColor : gradient,
                strokeColor : "rgba(151,187,205,1)",
                pointColor : "rgba(151,187,205,1)",
                pointStrokeColor : "#fff",
                pointHighlightFill : "#fff",
                pointHighlightStroke : "rgba(151,187,205,1)",
                data : [15, 20, 64, 43, 25]
            }
        ]

    }
    
    new Chart(ctx).Line(lineChartData, {responsive: true, scaleBeginAtZero: true})');
?>
