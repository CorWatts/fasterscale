<?php
use yii\helpers\Html;
use common\models\UserOption;

/**
 * @var yii\web\View $this
 * @var common\models\User $user
 */

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title></title>
        <style></style>
    </head>
    <body>
        <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable">
            <tr>
                <td align="center" valign="top">
                    <table border="0" cellpadding="20" cellspacing="0" width="800" id="emailContainer">
                        <tr>
                            <td align="center" valign="top">
        						<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="header">
									<tr>
										<td>Hello there! <?= Html::encode($user->username) ?> has set you as one of their report recipients. These reports are sent out when <?= Html::encode($user->username) ?> scores above <?= Html::encode($user->email_threshold) ?> in their checkin. This means that they might be struggling emotionally, and you should contact them to see how they are. Their report results are below. You can reply to this email, and it will go directly to your friend.</td>
									</tr>

									<tr><td><h2>Score: <?= $score ?></h2><td></tr>
									<tr><td>
										<h2>Past Month's Scores:</h2>
										<img src="data:image/png;base64,<?= base64_encode(UserOption::generateScoresGraph()) ?>" /><br />
										<span>Note: Some email clients (GMail/Outlook) might not display this image.</span>
									</td><tr>
								</table>

								<h2>Questions:</h2>
        						<table border="0" cellpadding="0" cellspacing="1" height="100%" width="100%" id="itemTable">
								<?php 
								if($questions) {
									foreach($questions as $option_id => $option_questions) {
										print "<tr><td colspan='2'><h3 style='text-decoration: underline;'>{$option_questions['question']['title']}</h3></td></tr>";
										print "<tr>";
										foreach($option_questions['answers'] as $question) { 
											print "<th>{$question['title']}</th>";
										}
										print "</tr>";
										print "<tr>";
										foreach($option_questions['answers'] as $question) { 
											if(!empty($question['answer']))
												print "<td>{$question['answer']}</td>";
										}
										print "</tr>";
									}
								} ?>
								</table>

								<h2>Selected Emotions:</h2>
        						<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="itemTable">
								<?php foreach($user_options as $user_option) {
									print "<tr>";
									print "<td><h2>{$user_option['category_name']}</h2></td>";
									print "</tr>";
									foreach($user_option['options'] as $option) {
										print "<tr><td>{$option['name']}</td></tr>";
									}
								} ?>
								</table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>
