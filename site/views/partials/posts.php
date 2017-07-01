<?php
use \yii\helpers\Html;

foreach($posts as $file) {
  $yaml    = $file['yaml'];
  $content = $file['content'];

  $date = Html::encode(date('F j, Y', strtotime($file['date']['full'])));
  print "<h4>".Html::encode($yaml['title'])."</h4>";
  print "<em>Written by ".Html::encode($yaml['author'])." on $date</em>";
  print $content;
}
