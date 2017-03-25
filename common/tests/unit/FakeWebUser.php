<?php
namespace common\tests\unit;

use yii;
use yii\base\Component;

/**
 * FakeUser model
 */
class FakeWebUser extends Component
{
  public $identityClass;
  public $enableAutoLogin;
  public $identityCookie;

  public function login($identity, $duration = 0) {
    return true;
  }
}