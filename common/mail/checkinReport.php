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
  <link rel="stylesheet" type="text/css" href="css/app.css" />
  <link href="https://fonts.googleapis.com/css?family=Lato:300" rel="stylesheet" />
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width" />
  <title>Faster Scale App Report</title>
 </head>
 <body style="-moz-box-sizing:border-box;-ms-text-size-adjust:100%;-webkit-box-sizing:border-box;-webkit-text-size-adjust:100%;Margin:0;box-sizing:border-box;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0;min-width:100%;padding:0;text-align:left;width:100%!important">
  <style>@media only screen{html{min-height:100%;background:#CCC}}@media only screen and (max-width:596px){.small-float-center{margin:0 auto!important;float:none!important;text-align:center!important}.small-text-center{text-align:center!important}.small-text-left{text-align:left!important}.small-text-right{text-align:right!important}}@media only screen and (max-width:596px){.hide-for-large{display:block!important;width:auto!important;overflow:visible!important;max-height:none!important;font-size:inherit!important;line-height:inherit!important}}@media only screen and (max-width:596px){table.body table.container .hide-for-large,table.body table.container .row.hide-for-large{display:table!important;width:100%!important}}@media only screen and (max-width:596px){table.body table.container .callout-inner.hide-for-large{display:table-cell!important;width:100%!important}}@media only screen and (max-width:596px){table.body table.container .show-for-large{display:none!important;width:0;mso-hide:all;overflow:hidden}}@media only screen and (max-width:596px){table.body img{width:auto;height:auto}table.body center{min-width:0!important}table.body .container{width:95%!important}table.body .column,table.body .columns{height:auto!important;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;box-sizing:border-box;padding-left:16px!important;padding-right:16px!important}table.body .column .column,table.body .column .columns,table.body .columns .column,table.body .columns .columns{padding-left:0!important;padding-right:0!important}table.body .collapse .column,table.body .collapse .columns{padding-left:0!important;padding-right:0!important}td.small-1,th.small-1{display:inline-block!important;width:8.33333%!important}td.small-2,th.small-2{display:inline-block!important;width:16.66667%!important}td.small-3,th.small-3{display:inline-block!important;width:25%!important}td.small-4,th.small-4{display:inline-block!important;width:33.33333%!important}td.small-5,th.small-5{display:inline-block!important;width:41.66667%!important}td.small-6,th.small-6{display:inline-block!important;width:50%!important}td.small-7,th.small-7{display:inline-block!important;width:58.33333%!important}td.small-8,th.small-8{display:inline-block!important;width:66.66667%!important}td.small-9,th.small-9{display:inline-block!important;width:75%!important}td.small-10,th.small-10{display:inline-block!important;width:83.33333%!important}td.small-11,th.small-11{display:inline-block!important;width:91.66667%!important}td.small-12,th.small-12{display:inline-block!important;width:100%!important}.column td.small-12,.column th.small-12,.columns td.small-12,.columns th.small-12{display:block!important;width:100%!important}table.body td.small-offset-1,table.body th.small-offset-1{margin-left:8.33333%!important;Margin-left:8.33333%!important}table.body td.small-offset-2,table.body th.small-offset-2{margin-left:16.66667%!important;Margin-left:16.66667%!important}table.body td.small-offset-3,table.body th.small-offset-3{margin-left:25%!important;Margin-left:25%!important}table.body td.small-offset-4,table.body th.small-offset-4{margin-left:33.33333%!important;Margin-left:33.33333%!important}table.body td.small-offset-5,table.body th.small-offset-5{margin-left:41.66667%!important;Margin-left:41.66667%!important}table.body td.small-offset-6,table.body th.small-offset-6{margin-left:50%!important;Margin-left:50%!important}table.body td.small-offset-7,table.body th.small-offset-7{margin-left:58.33333%!important;Margin-left:58.33333%!important}table.body td.small-offset-8,table.body th.small-offset-8{margin-left:66.66667%!important;Margin-left:66.66667%!important}table.body td.small-offset-9,table.body th.small-offset-9{margin-left:75%!important;Margin-left:75%!important}table.body td.small-offset-10,table.body th.small-offset-10{margin-left:83.33333%!important;Margin-left:83.33333%!important}table.body td.small-offset-11,table.body th.small-offset-11{margin-left:91.66667%!important;Margin-left:91.66667%!important}table.body table.columns td.expander,table.body table.columns th.expander{display:none!important}table.body .right-text-pad,table.body .text-pad-right{padding-left:10px!important}table.body .left-text-pad,table.body .text-pad-left{padding-right:10px!important}table.menu{width:100%!important}table.menu td,table.menu th{width:auto!important;display:inline-block!important}table.menu.small-vertical td,table.menu.small-vertical th,table.menu.vertical td,table.menu.vertical th{display:block!important}table.menu[align=center]{width:auto!important}table.button.small-expand,table.button.small-expanded{width:100%!important}table.button.small-expand table,table.button.small-expanded table{width:100%}table.button.small-expand table a,table.button.small-expanded table a{text-align:center!important;width:100%!important;padding-left:0!important;padding-right:0!important}table.button.small-expand center,table.button.small-expanded center{min-width:0}}</style>
  <span class="preheader" style="color:#CCC;display:none!important;font-size:1px;line-height:1px;max-height:0;max-width:0;mso-hide:all!important;opacity:0;overflow:hidden;visibility:hidden"></span>
  <table class="body" style="Margin:0;background:#CCC;border-collapse:collapse;border-spacing:0;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;height:100%;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;width:100%">
   <tbody>
    <tr style="padding:0;text-align:left;vertical-align:top">
     <td class="center" align="center" valign="top" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">
      <center data-parsed="" style="min-width:580;width:100%">
       <table class="spacer float-center" style="Margin:0 auto;border-collapse:collapse;border-spacing:0;float:none;margin:0 auto;padding:0;text-align:center;vertical-align:top;width:100%">
        <tbody>
         <tr style="padding:0;text-align:left;vertical-align:top">
          <td height="16px" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:16px;margin:0;mso-line-height-rule:exactly;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">&nbsp;</td>
         </tr>
        </tbody>
       </table>
       <table align="center" class="container header float-center" style="Margin:0 auto;background:#fefefe;border-collapse:collapse;border-spacing:0;float:none;margin:0 auto;padding:0;text-align:center;vertical-align:top;width:580">
        <tbody>
         <tr style="padding:0;text-align:left;vertical-align:top">
          <td style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">
           <table class="row" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:left;vertical-align:top;width:100%">
            <tbody>
             <tr style="padding:0;text-align:left;vertical-align:top">
              <th class="small-12 large-12 columns first last" valign="middle" style="Margin:0 auto;background-color:#1FC28F;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:0;padding-left:16px;padding-right:16px;text-align:left;width:564px">
               <table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
                <tbody>
                 <tr style="padding:0;text-align:left;vertical-align:top">
                  <th style="Margin:0;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left"><h1 class="text-center" style="Margin:0;Margin-bottom:10px;color:#fefefe;font-family:Lato,Helvetica,Arial,sans-serif;font-size:34px;font-weight:400;line-height:1.3;margin:0;margin-bottom:0;padding:16px;text-align:center;text-transform:uppercase;word-wrap:normal">Faster Scale App Report</h1></th>
                  <th class="expander" style="Margin:0;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0;padding:0!important;text-align:left;visibility:hidden;width:0"></th>
                 </tr>
                </tbody>
               </table></th>
             </tr>
            </tbody>
           </table>
           <table class="row" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:left;vertical-align:top;width:100%">
            <tbody>
             <tr style="padding:0;text-align:left;vertical-align:top">
              <th class="small-12 large-12 columns first last" style="Margin:0 auto;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:0;padding-left:16px;padding-right:16px;text-align:left;width:564px">
               <table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
                <tbody>
                 <tr style="padding:0;text-align:left;vertical-align:top">
                  <th style="Margin:0;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
                    
                    <center><img width="400" src="<?=$imgRef?>"></img></center>
                    
                    <p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0 32px 4px 32px;text-align:left">Hello there! <?=$username?> has set you as one of their report recipients. These reports are sent out when <?=$username?> scores above <strong><?= Html::encode($user->email_threshold) ?></strong> in their check-in. This means that they might be struggling emotionally, and you should contact them to see how they are. Their report results are below. You can reply to this email, and it will go directly to your friend.</p>
                  </th>
                  <th class="expander" style="Margin:0;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0;padding:0!important;text-align:left;visibility:hidden;width:0"></th>
                 </tr>
                </tbody>
               </table></th>
             </tr>
            </tbody>
           </table></td>
         </tr>
        </tbody>
       </table>
       <table class="spacer float-center" style="Margin:0 auto;border-collapse:collapse;border-spacing:0;float:none;margin:0 auto;padding:0;text-align:center;vertical-align:top;width:100%">
        <tbody>
         <tr style="padding:0;text-align:left;vertical-align:top">
          <td height="16px" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:16px;margin:0;mso-line-height-rule:exactly;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">&nbsp;</td>
         </tr>
        </tbody>
       </table>
       <table align="center" class="container questions float-center" style="Margin:0 auto;background:#fefefe;border-collapse:collapse;border-spacing:0;float:none;margin:0 auto;padding:0;text-align:center;vertical-align:top;width:580">
        <tbody>
         <tr style="padding:0;text-align:left;vertical-align:top">
          <td style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">
           <table class="row" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:left;vertical-align:top;width:100%">
            <tbody>
             <tr style="padding:0;text-align:left;vertical-align:top">
              <th class="small-12 large-12 columns first last" valign="middle" style="Margin:0 auto;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:0;padding-left:16px;padding-right:16px;text-align:left;width:564px">
               <table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
                <tbody>
                 <tr style="padding:0;text-align:left;vertical-align:top">
                  <th style="Margin:0;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left"><h2 style="Margin:0;Margin-bottom:10px;color:inherit;font-family:Lato,Helvetica,Arial,sans-serif;font-size:30px;font-weight:400;line-height:1.3;margin:0;margin-bottom:0;padding:24px 16px 8px 16px;text-align:left;word-wrap:normal"><?=$username?>'s Responses:</h2></th>
                  <th class="expander" style="Margin:0;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0;padding:0!important;text-align:left;visibility:hidden;width:0"></th>
                 </tr>
                </tbody>
               </table></th>
             </tr>
            </tbody>
           </table>
           <table class="row" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:left;vertical-align:top;width:100%">
            <tbody>
             <tr style="padding:0;text-align:left;vertical-align:top">
              <th class="small-12 large-12 columns first last" style="Margin:0 auto;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:0;padding-left:16px;padding-right:16px;text-align:left;width:564px">
               <table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
                <tbody>
                 <tr style="padding:0;text-align:left;vertical-align:top">
                  <th style="Margin:0;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">

<?php
if($questions) {
  foreach($questions as $behavior_id => $behavior_questions) {
?>

<h4 style="Margin:0;Margin-bottom:10px;color:#1FC28F;font-family:Lato,Helvetica,Arial,sans-serif;font-size:24px;font-weight:400;line-height:1.3;margin:0;margin-bottom:5px;padding:0 32px;text-align:left;word-wrap:normal"> <?= $behavior_questions['question']['title'] ?> </h4>
<?php
    foreach($behavior_questions['answers'] as $key => $question) {  ?>
<p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0 32px 4px 32px;text-align:left">
<?=$question['title']?></strong> <?=$question['answer']?>
<?php
    }
  } 
  // don't close if yet
?>
                  </th>
                  <th class="expander" style="Margin:0;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0;padding:0!important;text-align:left;visibility:hidden;width:0"></th>
                 </tr>
                </tbody>
               </table></th>
             </tr>
            </tbody>
           </table></td>
         </tr>
        </tbody>
       </table>
       <table class="spacer float-center" style="Margin:0 auto;border-collapse:collapse;border-spacing:0;float:none;margin:0 auto;padding:0;text-align:center;vertical-align:top;width:100%">
        <tbody>
         <tr style="padding:0;text-align:left;vertical-align:top">
          <td height="16px" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:16px;margin:0;mso-line-height-rule:exactly;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">&nbsp;</td>
         </tr>
        </tbody>
       </table>
       <table align="center" class="container behaviors float-center" style="Margin:0 auto;background:#fefefe;border-collapse:collapse;border-spacing:0;float:none;margin:0 auto;padding:0;text-align:center;vertical-align:top;width:580">
        <tbody>
         <tr style="padding:0;text-align:left;vertical-align:top">
          <td style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">
           <table class="row" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:left;vertical-align:top;width:100%">
            <tbody>
             <tr style="padding:0;text-align:left;vertical-align:top">
              <th class="small-12 large-12 columns first last" valign="middle" style="Margin:0 auto;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:0;padding-left:16px;padding-right:16px;text-align:left;width:564px">
               <table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
                <tbody>
                 <tr style="padding:0;text-align:left;vertical-align:top">
                  <th style="Margin:0;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left"><h2 style="Margin:0;Margin-bottom:10px;color:inherit;font-family:Lato,Helvetica,Arial,sans-serif;font-size:30px;font-weight:400;line-height:1.3;margin:0;margin-bottom:0;padding:24px 16px 8px 16px;text-align:left;word-wrap:normal">Selected Emotions:</h2></th>
                  <th class="expander" style="Margin:0;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0;padding:0!important;text-align:left;visibility:hidden;width:0"></th>
                 </tr>
                </tbody>
               </table></th>
             </tr>
            </tbody>
           </table>
           <table class="row" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:left;vertical-align:top;width:100%">
            <tbody>
             <tr style="padding:0;text-align:left;vertical-align:top">
              <th class="small-12 large-12 columns first last" style="Margin:0 auto;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:0;padding-left:16px;padding-right:16px;text-align:left;width:564px">
               <table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
                <tbody>
                 <tr style="padding:0;text-align:left;vertical-align:top">
                  <th style="Margin:0;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">

<?php foreach($user_options as $user_option) { ?>
                    <p style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0 32px 4px 32px;text-align:left">
                      <strong style="color:#1FC28F"><?= $user_option['category_name'] ?></strong>
<?php foreach($user_option['options'] as $option) { ?>
                                    <br><?= $option['name'] ?>
<?php
    }
  }
}
?>
</p>

                  <th class="expander" style="Margin:0;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0;padding:0!important;text-align:left;visibility:hidden;width:0"></th>
                 </tr>
                </tbody>
               </table></th>
             </tr>
            </tbody>
           </table></td>
         </tr>
        </tbody>
       </table>
       <table class="spacer float-center" style="Margin:0 auto;border-collapse:collapse;border-spacing:0;float:none;margin:0 auto;padding:0;text-align:center;vertical-align:top;width:100%">
        <tbody>
         <tr style="padding:0;text-align:left;vertical-align:top">
          <td height="8px" style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:8px;font-weight:400;hyphens:auto;line-height:8px;margin:0;mso-line-height-rule:exactly;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">&nbsp;</td>
         </tr>
        </tbody>
       </table>
       <table align="center" class="container footer float-center" style="Margin:0 auto;background:#fefefe;background-color:#CCC;border-collapse:collapse;border-spacing:0;float:none;margin:0 auto;padding:0;text-align:center;vertical-align:top;width:580">
        <tbody>
         <tr style="padding:0;text-align:left;vertical-align:top">
          <td style="-moz-hyphens:auto;-webkit-hyphens:auto;Margin:0;border-collapse:collapse!important;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;hyphens:auto;line-height:1.3;margin:0;padding:0;text-align:left;vertical-align:top;word-wrap:break-word">
           <table class="row" style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:left;vertical-align:top;width:100%">
            <tbody>
             <tr style="padding:0;text-align:left;vertical-align:top">
              <th class="small-12 large-12 columns first last" valign="middle" style="Margin:0 auto;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0 auto;padding:0;padding-bottom:0;padding-left:16px;padding-right:16px;text-align:left;width:564px">
               <table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%">
                <tbody>
                 <tr style="padding:0;text-align:left;vertical-align:top">
                  <th style="Margin:0;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
                   <center data-parsed="" style="min-width:532px;width:100%">
                   <p class="text-center float-center" align="center" style="Margin:0;Margin-bottom:10px;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0;margin-bottom:10px;padding:0 32px 4px 32px;text-align:center"><a href="<?=Url::to(['site/privacy'])?>" style="Margin:0;color:#2199e8;font-family:Lato,Helvetica,Arial,sans-serif;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left;text-decoration:none">Privacy</a> | <a href="<?=Url::to(['site/terms'])?>" style="Margin:0;color:#2199e8;font-family:Lato,Helvetica,Arial,sans-serif;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left;text-decoration:none">Terms</a></p>
                   </center></th>
                  <th class="expander" style="Margin:0;color:#0a0a0a;font-family:Lato,Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0;padding:0!important;text-align:left;visibility:hidden;width:0"></th>
                 </tr>
                </tbody>
               </table></th>
             </tr>
            </tbody>
           </table></td>
         </tr>
        </tbody>
       </table>
      </center></td>
    </tr>
   </tbody>
  </table>
  <!-- prevent Gmail on iOS font size manipulation -->
  <div style="display:none;white-space:nowrap;font:15px courier;line-height:0">
   &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
  </div>
 </body>
</html>