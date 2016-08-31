<?php
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\UserOption;

$username = Html::encode($user->username);
$date = str_replace('-', '', $date);
$imgRef = $message->embedContent($chartContent, [
  'fileName'       => "$username-scores-$date.png",
  'contentType'    => 'image/png',
  'setDisposition' => 'inline'
]);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
   <head>
      <link rel="stylesheet" type="text/css" href="css/app.css">
      <link href="https://fonts.googleapis.com/css?family=Lato:300" rel="stylesheet">
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
      <meta name="viewport" content="width=device-width">
      <title>Faster Scale App Report</title>
   </head>
   <body style="-moz-box-sizing:border-box;-ms-text-size-adjust:100%;-webkit-box-sizing:border-box;-webkit-text-size-adjust:100%;Margin:0;box-sizing:border-box;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0;min-width:100%;padding:0;text-align:left;width:100%!important">
      <span class="preheader" style="color:#CCCCCB;display:none!important;font-size:1px;line-height:1px;max-height:0;max-width:0;mso-hide:all!important;opacity:0;overflow:hidden;visibility:hidden"></span>
      <table class="body" style="Margin:0;background:#CCCCCB;border-collapse:collapse;border-spacing:0;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;height:100%;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;width:100%">
         <tr style="padding:0;text-align:left;vertical-align:top">
            <td class="center" align="center" valign="top" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">
               <center data-parsed="" style="min-width:580;width:100%">
                  <table class="spacer float-center" style="Margin:0 auto;border-collapse:collapse;border-spacing:0;float:none;margin:0 auto;padding:0;text-align:center;vertical-align:top;width:100%">
                     <tbody>
                        <tr style="padding:0;text-align:left;vertical-align:top">
                           <td height="16px" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:16px;margin:0;mso-line-height-rule:exactly;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">&#xA0;</td>
                        </tr>
                     </tbody>
                  </table>
                  <table align="center" class="container header float-center" style="Margin:0 auto;background:#FFF;border-collapse:collapse;border-spacing:0;float:none;margin:0 auto;padding:0;text-align:center;vertical-align:top;width:580">
                     <tbody>
                        <tr style="padding:0;text-align:left;vertical-align:top">
                           <td style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">
                              <table class="row" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:left;vertical-align:top;width:100%">
                                 <tbody>
                                    <tr style="padding:0;text-align:left;vertical-align:top">
                                       <th class="small-12 large-12 columns first last" valign="middle" style="Margin:0 auto;background-color:#1FC28F;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:0;padding-left:16px;padding-right:16px;text-align:left;width:564px">
                                          <table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
                                             <tr style="padding:0;text-align:left;vertical-align:top">
                                                <th style="Margin:0;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
                                                   <h1 class="text-center" style="Margin:0;Margin-bottom:10px;color:#FFF;font-family:Lato,Helvetica,Arial,sans-serif;font-size:34px;font-weight:400;line-height:1.3;margin:0;margin-bottom:0;padding:0;text-align:center;text-transform:uppercase;word-wrap:normal">Faster Scale App Report</h1>
                                                </th>
                                                <th class="expander" style="Margin:0;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0;padding:0!important;text-align:left;visibility:hidden;width:0"></th>
                                             </tr>
                                          </table>
                                       </th>
                                    </tr>
                                 </tbody>
                              </table>
                              <table class="spacer" style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
                                 <tbody>
                                    <tr style="padding:0;text-align:left;vertical-align:top">
                                       <td height="8px" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:8px;font-weight:400;hyphens:auto;line-height:8px;margin:0;mso-line-height-rule:exactly;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">&#xA0;</td>
                                    </tr>
                                 </tbody>
                              </table>
                              <table class="row" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:left;vertical-align:top;width:100%">
                                 <tbody>
                                    <tr style="padding:0;text-align:left;vertical-align:top">
                                       <th class="small-12 large-12 columns first last" style="Margin:0 auto;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:0;padding-left:16px;padding-right:16px;text-align:left;width:564px">
                                          <table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
                                             <tr style="padding:0;text-align:left;vertical-align:top">
                                                <th style="Margin:0;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
                                                   <img width="400" src="<?=$imgRef?>" style="-ms-interpolation-mode:bicubic;clear:both;display:block;max-width:100%;outline:0;text-decoration:none;width:auto">
                                                   <table class="spacer" style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
                                                      <tbody>
                                                         <tr style="padding:0;text-align:left;vertical-align:top">
                                                            <td height="8px" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:8px;font-weight:400;hyphens:auto;line-height:8px;margin:0;mso-line-height-rule:exactly;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">&#xA0;</td>
                                                         </tr>
                                                      </tbody>
                                                   </table>
                                                   <p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0 8px 4px 8px;text-align:left">Hello there! <?=$username?> has set you as one of their report recipients. These reports are sent out when <?=$username?> scores above <strong><?= Html::encode($user->email_threshold) ?></strong> in their check-in. This means that they might be struggling emotionally, and you should contact them to see how they are. Their report results are below. You can reply to this email, and it will go directly to your friend.</p>
                                                </th>
                                                <th class="expander" style="Margin:0;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0;padding:0!important;text-align:left;visibility:hidden;width:0"></th>
                                             </tr>
                                          </table>
                                       </th>
                                    </tr>
                                 </tbody>
                              </table>
                           </td>
                        </tr>
                     </tbody>
                  </table>
                  <table class="spacer float-center" style="Margin:0 auto;border-collapse:collapse;border-spacing:0;float:none;margin:0 auto;padding:0;text-align:center;vertical-align:top;width:100%">
                     <tbody>
                        <tr style="padding:0;text-align:left;vertical-align:top">
                           <td height="16px" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:16px;margin:0;mso-line-height-rule:exactly;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">&#xA0;</td>
                        </tr>
                     </tbody>
                  </table>
                  <table align="center" class="container questions float-center" style="Margin:0 auto;background:#FFF;border-collapse:collapse;border-spacing:0;float:none;margin:0 auto;padding:0;text-align:center;vertical-align:top;width:580">
                     <tbody>
                        <tr style="padding:0;text-align:left;vertical-align:top">
                           <td style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">
                              <table class="row" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:left;vertical-align:top;width:100%">
                                 <tbody>
                                    <tr style="padding:0;text-align:left;vertical-align:top">
                                       <th class="small-12 large-12 columns first last" valign="middle" style="Margin:0 auto;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:0;padding-left:16px;padding-right:16px;text-align:left;width:564px">
                                          <table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
                                             <tr style="padding:0;text-align:left;vertical-align:top">
                                                <th style="Margin:0;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
                                                   <h2 style="Margin:0;Margin-bottom:10px;color:inherit;font-family:Lato,Helvetica,Arial,sans-serif;font-size:30px;font-weight:400;line-height:1.3;margin:0;margin-bottom:0;padding:24px 8px 8px 8px;text-align:left;word-wrap:normal"><?=$username?>'s Responses:</h2>
                                                </th>
                                                <th class="expander" style="Margin:0;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0;padding:0!important;text-align:left;visibility:hidden;width:0"></th>
                                             </tr>
                                          </table>
                                       </th>
                                    </tr>
                                 </tbody>
                              </table>
                              <table class="row" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:left;vertical-align:top;width:100%">
                                 <tbody>
                                    <tr style="padding:0;text-align:left;vertical-align:top">
                                       <th class="small-12 large-12 columns first last" style="Margin:0 auto;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:0;padding-left:16px;padding-right:16px;text-align:left;width:564px">
                                          <table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
                                             <tr style="padding:0;text-align:left;vertical-align:top">
                                                <th style="Margin:0;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">

<?php
if($questions) {
  foreach($questions as $behavior_id => $behavior_questions) {
?>

                                                   <h4 style="Margin:0;Margin-bottom:10px;color:#37b98f;font-family:Lato,Helvetica,Arial,sans-serif;font-size:24px;font-weight:400;line-height:1.3;margin:0;margin-bottom:5px;padding:0 8px;text-align:left;word-wrap:normal"> <?= $behavior_questions['question']['title'] ?> </h4>

<?php
    foreach($behavior_questions['answers'] as $key => $question) {  ?>
      <p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0 8px 4px 8px;text-align:left"><strong><?=$question['title']?></strong> <?=$question['answer']?></p>
<?php
    }
  }
  // don't close if yet
?>

                                                </th>
                                                <th class="expander" style="Margin:0;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0;padding:0!important;text-align:left;visibility:hidden;width:0"></th>
                                             </tr>
                                          </table>
                                       </th>
                                    </tr>
                                 </tbody>
                              </table>
                           </td>
                        </tr>
                     </tbody>
                  </table>
                  <table class="spacer float-center" style="Margin:0 auto;border-collapse:collapse;border-spacing:0;float:none;margin:0 auto;padding:0;text-align:center;vertical-align:top;width:100%">
                     <tbody>
                        <tr style="padding:0;text-align:left;vertical-align:top">
                           <td height="16px" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:16px;margin:0;mso-line-height-rule:exactly;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">&#xA0;</td>
                        </tr>
                     </tbody>
                  </table>
                  <table align="center" class="container behaviors float-center" style="Margin:0 auto;background:#FFF;border-collapse:collapse;border-spacing:0;float:none;margin:0 auto;margin-bottom:8px;padding:0;text-align:center;vertical-align:top;width:580">
                     <tbody>
                        <tr style="padding:0;text-align:left;vertical-align:top">
                           <td style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">
                              <table class="row" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:left;vertical-align:top;width:100%">
                                 <tbody>
                                    <tr style="padding:0;text-align:left;vertical-align:top">
                                       <th class="small-12 large-12 columns first last" valign="middle" style="Margin:0 auto;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:0;padding-left:16px;padding-right:16px;text-align:left;width:564px">
                                          <table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
                                             <tr style="padding:0;text-align:left;vertical-align:top">
                                                <th style="Margin:0;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
                                                   <h2 style="Margin:0;Margin-bottom:10px;color:inherit;font-family:Lato,Helvetica,Arial,sans-serif;font-size:30px;font-weight:400;line-height:1.3;margin:0;margin-bottom:0;padding:24px 8px 8px 8px;text-align:left;word-wrap:normal">Selected Behaviors:</h2>
                                                </th>
                                                <th class="expander" style="Margin:0;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0;padding:0!important;text-align:left;visibility:hidden;width:0"></th>
                                             </tr>
                                          </table>
                                       </th>
                                    </tr>
                                 </tbody>
                              </table>
                              <table class="row" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:left;vertical-align:top;width:100%">
                                 <tbody>
                                    <tr style="padding:0;text-align:left;vertical-align:top">
                                       <th class="small-12 large-12 columns first last" style="Margin:0 auto;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:0;padding-left:16px;padding-right:16px;text-align:left;width:564px">
                                          <table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
                                             <tr style="padding:0;text-align:left;vertical-align:top">
                                                <th style="Margin:0;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">

<?php foreach($user_options as $user_option) { ?>
        <p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0 8px 4px 8px;text-align:left">
          <strong style="color:#37b98f"><?= $user_option['category_name'] ?></strong>
<?php foreach($user_option['options'] as $option) { ?>
      <br><?= $option['name'] ?>
<?php
    }
  }
}
?>
</p>

                                                </th>
                                                <th class="expander" style="Margin:0;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0;padding:0!important;text-align:left;visibility:hidden;width:0"></th>
                                             </tr>
                                          </table>
                                       </th>
                                    </tr>
                                 </tbody>
                              </table>
                           </td>
                        </tr>
                     </tbody>
                  </table>
                  <table class="spacer float-center" style="Margin:0 auto;border-collapse:collapse;border-spacing:0;float:none;margin:0 auto;padding:0;text-align:center;vertical-align:top;width:100%">
                     <tbody>
                        <tr style="padding:0;text-align:left;vertical-align:top">
                           <td height="8px" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:8px;font-weight:400;hyphens:auto;line-height:8px;margin:0;mso-line-height-rule:exactly;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">&#xA0;</td>
                        </tr>
                     </tbody>
                  </table>
                  <table align="center" class="container footer float-center" style="Margin:0 auto;background:#FFF;background-color:#CCCCCB;border-collapse:collapse;border-spacing:0;float:none;margin:0 auto;padding:0;text-align:center;vertical-align:top;width:580">
                     <tbody>
                        <tr style="padding:0;text-align:left;vertical-align:top">
                           <td style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">
                              <table class="row" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:left;vertical-align:top;width:100%">
                                 <tbody>
                                    <tr style="padding:0;text-align:left;vertical-align:top">
                                       <th class="small-12 large-12 columns first last" valign="middle" style="Margin:0 auto;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:0;padding-left:16px;padding-right:16px;text-align:left;width:564px">
                                          <table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
                                             <tr style="padding:0;text-align:left;vertical-align:top">
                                                <th style="Margin:0;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
                                                   <center data-parsed="" style="min-width:532px;width:100%">
                                                   <p class="text-center float-center" align="center" style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0 8px 4px 8px;text-align:center">
                                                     <a href="<?=Url::to(['site/privacy'], true)?>" style="Margin:0;color:#2199e8;font-family:Lato,Helvetica,Arial,sans-serif;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left;text-decoration:none">Privacy</a> |
                                                     <a href="<?=Url::to(['site/terms'], true)?>" style="Margin:0;color:#2199e8;font-family:Lato,Helvetica,Arial,sans-serif;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left;text-decoration:none">Terms</a> |
                                                     <a href="%unsubscribe_url%" style="Margin:0;color:#2199e8;font-family:Lato,Helvetica,Arial,sans-serif;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left;text-decoration:none">Unsubscribe</a>
</p>
                                                   </center>
                                                </th>
                                                <th class="expander" style="Margin:0;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0;padding:0!important;text-align:left;visibility:hidden;width:0"></th>
                                             </tr>
                                          </table>
                                       </th>
                                    </tr>
                                 </tbody>
                              </table>
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </center>
            </td>
         </tr>
      </table>
      <!-- prevent Gmail on iOS font size manipulation -->
      <div style="display:none;white-space:nowrap;font:15px courier;line-height:0">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</div>
   </body>
</html>