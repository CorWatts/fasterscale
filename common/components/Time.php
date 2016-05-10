<?php
namespace common\components;

use yii;
use \DateTime;
use \DateTimeZone;

class Time {
  public static function convertLocalToUTC($local, $inc_time = true) {
    $fmt = $inc_time ? "Y-m-d H:i:s" : "Y-m-d";

    $timestamp = new DateTime($local, new DateTimeZone(Yii::$app->user->identity->timezone));
    $timestamp->setTimeZone(new DateTimeZone("UTC"));
    return $timestamp->format($fmt);
  }

  public static function convertUTCToLocal($utc, $inc_time = true) {
    $fmt = $inc_time ? "Y-m-d H:i:s" : "Y-m-d";

    $timestamp = new DateTime($utc, new DateTimeZone("UTC"));
    $timestamp->setTimeZone(new DateTimeZone(Yii::$app->user->identity->timezone));
    return $timestamp->format($fmt);
  }

  public static function getLocalTime($timezone = null) {
    if($timezone === null)
      $timezone = Yii::$app->user->identity->timezone;

    $timestamp = new DateTime("now", new DateTimeZone($timezone));
    return $timestamp->format("Y-m-d H:i:s");
  }

  public static function getLocalDate($timezone = null) {
    if($timezone === null)
      $timezone = Yii::$app->user->identity->timezone;

    $timestamp = new DateTime("now", new DateTimeZone($timezone));
    return $timestamp->format("Y-m-d");
  }

  public static function alterLocalDate($date, $modifier) {
    $new_date = new DateTime("$date $modifier", new DateTimeZone(Yii::$app->user->identity->timezone));
    return $new_date->format("Y-m-d");
  }

  public static function getUTCBookends($local) {
    $local = trim($local);
    if(strpos($local, " ")) {
      return false;
    }

    $start = $local . " 00:00:00";
    $end   = $local . "23:59:59";

    $front = self::convertLocalToUTC($start);
    $back  = self::convertLocalToUTC($end);

    return [$front, $back];
  }
}
