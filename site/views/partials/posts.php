<?php

use yii\helpers\Html;

foreach ($posts as $file) {
    $yaml       = $file['yaml'];
    $content    = $file['content'];
    $short_name = Html::encode($file['date']['name']);

    $date = Html::encode(date('F j, Y', strtotime($file['date']['full']))); ?>
<hr />
<div class="post">
  <div class="post-heading">
    <h3><?=Html::encode($yaml['title'])?></h3>
    <p><em>Written by <?=Html::encode($yaml['author'])?> on <?=$date?></em></p>
  </div>
  <div class="post-body"><?=$content?></div>
</div>
    <?php
}
