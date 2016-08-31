<?php

namespace common\components;

class Message extends \yii\swiftmailer\Message {
  public function attachContent($content, array $options = [])
  {
    $attachment = \Swift_Image::newInstance($content);
    if (!empty($options['fileName'])) {
      $attachment->setFilename($options['fileName']);
    }
    if (!empty($options['contentType'])) {
      $attachment->setContentType($options['contentType']);
    }
    if (!empty($options['setDisposition'])) {
      $attachment->setDisposition($options['setDisposition']);
    }
    $this->getSwiftMessage()->attach($attachment);

    return $this;
  }
}