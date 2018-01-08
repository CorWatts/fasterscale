<?php

namespace site\tests\_support;
class MockUser implements \common\interfaces\UserInterface, \yii\web\IdentityInterface {
  public $timezone = 'America/Los_Angeles';
  public $password;

 public static function findIdentity($id) {}
 public static function findIdentityByAccessToken($token, $type = null) {}
 public function getId() {}
 public function getAuthKey() {}
 public function validateAuthKey($authKey) {}
 public function validatePassword($password) {}
 public function setPassword($password) {}

  public static function primaryKey() {}
  public function attributes() {}
  public function getAttribute($name) {}
  public function setAttribute($name, $value) {}
  public function hasAttribute($name) {}
  public function getPrimaryKey($asArray = false) {}
  public function getOldPrimaryKey($asArray = false) {}
  public static function isPrimaryKey($keys) {}
  public static function find() {}
  public static function findOne($condition) {}
  public static function findAll($condition) {}
  public static function updateAll($attributes, $condition = null) {}
  public static function deleteAll($condition = null) {}
  public function save($runValidation = true, $attributeNames = null) {}
  public function insert($runValidation = true, $attributes = null) {}
  public function update($runValidation = true, $attributeNames = null) {}
  public function delete() {}
  public function getIsNewRecord() {}
  public function equals($record) {}
  public function getRelation($name, $throwException = true) {}
  public function populateRelation($name, $records) {}
  public function link($name, $model, $extraColumns = []) {}
  public function unlink($name, $model, $delete = false) {}
  public static function getDb() {}
}