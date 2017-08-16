<?php
namespace common\components;

use yii;
use \DateTime;
use \DateTimeZone;

class Time extends \yii\base\Object implements \common\interfaces\TimeInterface {

  public $timezone;

  public function __construct(String $timezone, $config = []) {
    $this->timezone = $timezone;
    parent::__construct($config);
  }

  public function convertLocalToUTC($local, $inc_time = true) {
    $fmt = $inc_time ? "Y-m-d H:i:s" : "Y-m-d";

    $timestamp = new DateTime($local, new DateTimeZone($this->timezone));
    $timestamp->setTimeZone(new DateTimeZone("UTC"));
    return $timestamp->format($fmt);
  }

  public function convertUTCToLocal($utc, $inc_time = true) {
    $fmt = $inc_time ? "Y-m-d H:i:s" : "Y-m-d";

    $timestamp = new DateTime($utc, new DateTimeZone("UTC"));
    $timestamp->setTimeZone(new DateTimeZone($this->timezone));
    return $timestamp->format($fmt);
  }

  public function getLocalTime($timezone = null) {
    if($timezone === null)
      $timezone = $this->timezone;

    $timestamp = new DateTime("now", new DateTimeZone($timezone));
    return $timestamp->format("Y-m-d H:i:s");
  }

  public function getLocalDate($timezone = null) {
    if($timezone === null)
      $timezone = $this->timezone;

    return (new DateTime("now", new DateTimeZone($timezone)))
      ->format("Y-m-d");
  }

  public function alterLocalDate($date, $modifier) {
    return (new DateTime("$date $modifier", new DateTimeZone($this->timezone)))
      ->format("Y-m-d");
  }

  public function getUTCBookends($local) {
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
