<?php

namespace common\interfaces;

interface UserInterface extends \yii\db\ActiveRecordInterface {
  public function findByEmail($email);
  public function findByChangeEmailToken($email);

  public function generateChangeEmailToken();
  public function removeChangeEmailToken();

  public function generateVerifyEmailToken();
  public function confirmVerifyEmailToken();
  public function removeVerifyEmailToken();

}
