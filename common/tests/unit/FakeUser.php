<?php
namespace common\tests\unit;

use yii;
use yii\web\IdentityInterface;
use yii\base\Model;

/**
 * FakeUser model
 */
class FakeUser extends Model implements IdentityInterface
{
    public $timezone;

    public static function findIdentity($id)
    {
    }
    public static function findIdentityByAccessToken($token, $type = null)
    {
    }
    public function getId()
    {
    }
    public function getAuthKey()
    {
    }
    public function validateAuthKey($authKey)
    {
    }
}
