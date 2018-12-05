<?php

namespace common\tests\_support;
class MockRequest extends \yii\web\Request {

  public $csrfValidationReturn = true;


  public function setCsrfToken($token) {
    $this->_csrfToken = $token;
  }

  public function validateCsrfToken($clientSuppliedToken = null) {
    return $this->csrfValidationReturn;
  }
}