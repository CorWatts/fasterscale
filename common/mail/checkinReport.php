<?php
use yii\helpers\Html;
use common\models\UserOption;

/**
* @var yii\web\View $this
* @var common\models\User $user
*/

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html><head>
		<title>The Faster Scale App -- Email Report</title>
	</head>
	<style type="text/css">

		/* /\/\/\/\/\/\/\/\/ RESET STYLES /\/\/\/\/\/\/\/\/ */
		body {
		margin:0;
		padding:0;
		}
		img {
		border:0 none;
		height:auto;
		line-height:100%;
		outline:none;
		text-decoration:none;
		}
		a img {
		border:0 none;
		}
		.imageFix {
		display:block;
		}
		table, td {
		border-collapse:collapse;
		}


		/* /\/\/\/\/\/\/\/\/ CLIENT SPECIFIC STYLES /\/\/\/\/\/\/\/\/ */
		.ExternalClass {
		width:100%;
		}
		.ExternalClass,
		.ExternalClass p,
		.ExternalClass span,
		.ExternalClass font,
		.ExternalClass td,
		.ExternalClass div {
		line-height: 100%;
		}
		#outlook a {
		padding:0;
		}
		table {
		mso-table-lspace:0pt;
		mso-table-rspace:0pt;
		}
		img {
		-ms-interpolation-mode:bicubic;
		}
		border-style: {
		-ms-text-size-adjust:100%;
		}


		/* /\/\/\/\/\/\/\/\/ GLOBAL STYLES /\/\/\/\/\/\/\/\/ */
		img[id="full"] {
		width:100% !important;
		max-width:600px !important;
		}
		@media only screen and (max-width: 480px) {
		table[id="wrapper"] {
		width:100% !important;
		max-width: 480px !important;
		margin-left:0px !important;
		margin-right:0px !important;
		}
		table[id="header"] {
		border-top-left-radius: 0px !important;
		border-top-right-radius: 0px !important;
		}
		table[id="content"] {
		width:100% !important;
		max-width: 480px !important;
		margin-left:0px !important;
		margin-right:0px !important;
		border-bottom-left-radius: 0px !important;
		border-bottom-right-radius: 0px !important;
		}
		img[id="logo"] {
		padding-left: 12% !important;
		width: 150px !important;
		height: 33px !important;
		}
		img[id="full"] {
		max-width: 480px !important;
		width: 100% !important;
		height: auto !important;
		}
		td[id="footer"] {
		padding-left: 7% !important;
		padding-right: 7% !important;
		}
		td[id="footer-social"] {
		padding-left: 7% !important;
		padding-right: 7% !important;
		}
		h1 {
		font-size:21px !important;
		}
		h2 {
		font-size:17px !important;
		}
		h3 {
		font-size:17px !important;
		}
		h4 {
		font-size:14px !important;
		}
		p {
		font-size:14px !important;
		}
		.contact-info p {
		font-size:12px !important;
		}
		}
	</style>
	<head>
		<body ><table style="background:#edeff0; " border="0" cellspacing="0" cellpadding="0" width="100%" align="center" ><tbody ><tr>
						<td colspan="3">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="3"></td>
					</tr>
					<tr >
						<td width="94%" align="center" valign="top" class="mktEditable" id="id01" ><table style="background-color: #edeff0; font-family: Helvetica, sans-serif; margin: 0px; font-size: 14px; color: #8d9aa5; height: 100% !important; width: 100% !important; background-position: initial  initial; background-repeat: initial initial;" border="0" cellspacing="0" cellpadding="0" width="100%" height="100%">
								<tbody>
									<tr>
										<td style="vertical-align: top; border-collapse: collapse;" align="center"></td>
									</tr>
								</tbody>
							</table>
							<table style="background-color: #edeff0; font-family: Helvetica, sans-serif; margin: 0px; font-size: 14px; color: #8d9aa5; height: 100% !important; width: 100% !important; background-position: initial  initial; background-repeat: initial initial;" border="0" cellspacing="0" cellpadding="0" width="100%" height="100%">
								<tbody>
									<tr>
										<td style="vertical-align: top; padding-bottom: 15px; border-collapse: collapse; max-width: 600px;" align="center">

											<!-- // BEGIN CONTAINER --> 
											<table id="wrapper" style="width: 90%; max-width: 600px; margin-right: 5%; margin-left: 5%; display: block;" border="0" cellspacing="0" cellpadding="0">
												<tbody>
													<tr>
														<td style="vertical-align: top; border-collapse: collapse;" align="center"><!-- // BEGIN PREHEADER --> 
															<table style="width: 100%;" border="0" cellspacing="0" cellpadding="0">
																<tbody>
																	<tr>
																		<!--<td width="421" align="left" style="width: 180px; vertical-align: top; padding-top: 0px; padding-left:25px; padding-bottom: 21px; border-collapse: collapse;"><p><a style="margin: 0; padding: 0;" href="http://ilikecodeine.com"><br>
																				<img style="width: 100%; max-width: 180px; height: auto; border: 0px; line-height: 100%; outline: none; text-decoration: none; text-align: center; margin: 0px;" src="http://ilikecodeine.com/images/logo.png" alt="The Faster Scale App Logo"></a></p>

																		</td>-->
																		<td width="190" align="right" style="width: 150px; vertical-align: top; padding-top: 14px; padding-bottom: 21px; border-collapse: collapse;"><p>&nbsp;</p></tr>
																	</tbody>
																</table>
																<!-- END PREHEADER \\ --></td>
														</tr>
														<tr>
															<td style="vertical-align: top; border-collapse: collapse;" align="center"><!-- // BEGIN HEADER --> 
																<table id="header" style="background-color: #fff; border-top-left-radius: 5px; border-top-right-radius: 5px;" border="0" cellspacing="0" cellpadding="0">
																	<tbody>
																		<tr>
																			<td style="vertical-align: top; border-collapse: collapse; padding-top: 3%; padding-right: 7%; padding-left: 7%;" align="center">
																				<table border="0" cellspacing="0" cellpadding="0">
																					<tbody>
																						<tr>
																							<td style="vertical-align: top; font-size: 18px; font-weight: bold; line-height: 100%; padding-bottom: 25px; text-align: left; border-collapse: collapse;">
																								<h1 style="font-family: Helvetica,sans-serif; color: #384047; display: block; font-size: 24px; font-weight: bold; line-height: 130%; letter-spacing: normal; margin-right: 0; margin-top: 15px; margin-bottom: 15px; margin-left: 0; text-align: left;">The Faster Scale App -- Email Report for <?= Html::encode($user->username) ?></h1>
																																																<p style=\"font-family: Helvetica,sans-serif; line-height: 160%; margin-top: 15px; margin-bottom: 15px; text-decoration: none;\">Hello there! <?= Html::encode($user->username) ?> has set you as one of their report recipients. These reports are sent out when <?= Html::encode($user->username) ?> scores above <?= Html::encode($user->email_threshold) ?> in their checkin. This means that they might be struggling emotionally, and you should contact them to see how they are. Their report results are below. You can reply to this email, and it will go directly to your friend.</p>
																						</td>
																					</tr>
																				</tbody>
																			</table>
																		</td>
																	</tr>
																</tbody>
															</table>
															<!-- END HEADER \\ --></td>
													</tr>
													<tr>
														<td style="vertical-align: top;" align="center"><!-- // BEGIN BODY --> 
															<table id="content" style="width: 100%; max-width: 600px; background-color: #fff;" border="0" cellspacing="0" cellpadding="0" width="100%">
																<!-- FULL WIDTH FEATURE --> <!-- CONTENT START HERE --> 
																<tbody>
																	<!-- TILE#1 --> 
																	<tr>
																		<td style="vertical-align: top; border-collapse: collapse;" align="center">
																			<table style="margin: 0px;" border="0" cellspacing="0" cellpadding="0" width="100%">
																				<tbody>
																					<tr>
																						<td style="vertical-align: top; color: #fff; font-size: 14px; text-align: center; border-collapse: collapse; margin: 0; padding: 0; background: #fff;">
																						<?= UserOption::generateScoresGraph() ?>
																						</td>
																					</tr>
																				</tbody>
																			</table>
																		</td>
																	</tr>
																	<tr>
																		<td style="vertical-align: top; border-collapse: collapse; padding-right: 7%; padding-left: 7%; padding-bottom: 3%;" align="center">
																			<table border="0" cellspacing="0" cellpadding="0" width="100%">
																				<tbody>
																					<tr>
																						<td style="vertical-align: top; color: #8d9aa5; font-size: 15px; line-height: 150%; text-align: left; border-collapse: collapse; padding-top: 20px;">
																							<h3 style="font-family: Helvetica,sans-serif; color: #384047; display: block; font-size: 18px; font-weight: bold; line-height: 130%; letter-spacing: normal; margin-right: 0; margin-left: 0; margin-top: 10px; margin-bottom: 5px; text-align: left;">Questions:
																							</h3>
																							<?php 
																								if($questions)  {
																									foreach($questions as $option_id => $option_questions) {
																										print "<h4 style=\"font-family: Helvetica,sans-serif; color: #384047; display: block; font-size: 14px; font-weight: bold; line-height: 130%; letter-spacing: normal; margin-right: 0; margin-left: 0; margin-top: 10px; margin-bottom: 5px; text-align: left;\">{$option_questions['question']['title']}</h4>";
																										foreach($option_questions['answers'] as $question) { 
																											print "<p style=\"font-family: Helvetica,sans-serif; line-height: 160%; margin-top: 15px; margin-bottom: 15px; text-decoration: none;\"><b>{$question['title']}</b></p>";
																											if(!empty($question['answer']))
																												print "<p style=\"font-family: Helvetica,sans-serif; line-height: 160%; margin-top: 15px; margin-bottom: 15px; text-decoration: none;\">{$question['answer']}</p>";
																										}
																									}
																								} 
																							?>
																						</td>
																					</tr>
																				</tbody>
																			</table>
																			<!-- TILE#1 END--></td>
																	</tr>
																	<tr>
																		<!-- TILE#2 -->
																	</tr>
																	<tr>
																		<td style="vertical-align: top; border-collapse: collapse; padding-right: 7%; padding-left: 7%; padding-bottom: 3%;" align="center">
																			<table border="0" cellspacing="0" cellpadding="0" width="100%">
																				<tbody>
																					<tr>
																						<td style="vertical-align: top; color: #8d9aa5; font-size: 15px; line-height: 150%; text-align: left; border-collapse: collapse; padding-top: 20px;">
																							<table border="0" cellspacing="0" cellpadding="0" width="100%">
																								<tbody>
																									<tr>
																										<td style="vertical-align: top; color: #8d9aa5; font-size: 15px; line-height: 150%; text-align: left; border-collapse: collapse; padding-top: 20px;">
																											<h3 style="font-family: Helvetica,sans-serif; color: #384047; display: block; font-size: 18px; font-weight: bold; line-height: 130%; letter-spacing: normal; margin-right: 0; margin-left: 0; margin-top: 10px; margin-bottom: 5px; text-align: left;">Selected Emotions:</h3>
																											<?php foreach($user_options as $user_option) {
																											print "<p style=\"font-family: Helvetica,sans-serif; line-height: 160%; margin-top: 15px; margin-bottom: 15px;\"><b>{$user_option['category_name']}</b></p>";
																											foreach($user_option['options'] as $option) {
																											print "<p style=\"font-family: Helvetica,sans-serif; line-height: 160%; margin-top: 15px; margin-bottom: 15px;\">{$option['name']}</p>";
																											}
																											} ?>
																										</td>
																									</tr>
																								</tbody>
																							</table>
																							<!-- TILE#2 END--></td>

																					</tr>
																					<tr>

																						<tr>
																							<!-- TILE FOOTER -->
																						</tr>
																						<tr>
																							<td style="vertical-align: top; border-collapse: collapse;" align="center">
																								<table style="margin: 0px;" border="0" cellspacing="0" cellpadding="0" width="100%">
																									<tbody>
																										<tr>
																										</tr>
																									</tbody>
																								</table>
																							</td>
																						</tr>
																						<tr>
																							<td style="vertical-align: top; border-collapse: collapse; padding-right: 7%; padding-left: 7%; padding-bottom: 3%;" align="center">
																								<table border="0" cellspacing="0" cellpadding="0">
																									<tbody>
																										<tr>
																											<td style="vertical-align: top; color: #8d9aa5; font-size: 15px; line-height: 150%; text-align: left; border-collapse: collapse; padding-top: 20px;"><table border="0" cellspacing="0" cellpadding="0" width="100%">
																													<tbody>
																														<tr>
																															<td colspan="2" align="left" valign="top"><p style="font-family: Arial,Helvetica,sans-serif; font-size: 13px; font-weight: normal; color: #000000; line-height: 18px; clear: both;"><a style="color: #14a4d7; text-decoration: 
																																	none;" href="http://www.ilikecodeine.com/unsubscribe/">Unsubscribe</a> | <a style="color: #14a4d7; text-decoration: none;" href="http://www.ilikecodeine.com/privacy/">
																																	Privacy Policy</a> | <a style="color: #14a4d7; text-decoration: 
																																	none;" href="http://www.ilikecodeine.com/terms/">Terms of Services</a> | &copy; <a style="color: #14a4d7; text-decoration: none;" href="http://corwatts.com">Corey Watts</a> <?= date('Y') ?></p>
																															</tr>
																														</tbody>
																													</table>
																												</td>
																											</tr>
																										</tbody>
																									</table>
																									<!-- TILE FOOTER END--></td>
																							</tr>
																						</table>


																					</td>
																				</tr>
																			</table>
																			<!-- END BODY \\ --></td>
																	</tr>
																	<tr>
																		<td style="vertical-align: top; border-collapse: collapse;" align="center"><!-- // BEGIN FOOTER --> 
																			<table id="footer" style="width: 100%; max-width: 600px;" border="0" cellspacing="0" cellpadding="0">
																				<tbody>
																					<tr></tr>
																					<tr></tr>
																				</table>


																				<!-- END FOOTER \\ --></td>
																		</tr>
																	</tbody>
																</table>
																<!-- END CONTAINER \\ --></td>
														</tr>
														<tr>
															<td colspan="3">&nbsp;</td>
														</tr>
													</tbody>
												</table>
											</body>
										</html>