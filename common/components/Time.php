<?php
namespace common\components;

use yii;
use \DateTime;
use \DateTimeZone;

class Time extends \yii\base\BaseObject implements \common\interfaces\TimeInterface {

  public const EARLIEST_DATE = '2014-01-01';

  public $timezone;

  public function __construct(String $timezone, $config = []) {
    $this->timezone = $timezone;
    parent::__construct($config);
  }

  /*
   * Parses the supplied string into a `\DateTime` object of the
   * given `$format`. It assumes the supplied string is in the
   * timezone specified in $this->timezone.
   *
   * @param string $time the questionable time to parse
   * @param string $format the format `$time` is expected to be in
   * @return \DateTime the parsed time or false
   */
  public function parse(string $time, string $format = 'Y-m-d') {
    $dt = DateTime::createFromFormat($format, $time, new DateTimeZone($this->timezone));
    if($dt) {
      // for some reason, using createFromFromat adds in the time. The regular DateTime constructor _does not_ do this. We manually zero out the time here to make the DateTime objects match.
      $dt->setTime(0, 0, 0);
      $formatted = $dt->format($format);
      if($formatted === $time && $this->inBounds($dt)) {
        return $dt;
      }
    }
    return false;
  }

  /*
   * Checks if the given `\DateTime` is within acceptable date bounds.
   * It does no good to have the date be far in the past or in the future.
   *
   * @param \DateTime $dt
   * @return boolean
   */
  public function inBounds(DateTime $dt) {
    $first = strtotime((new DateTime(self::EARLIEST_DATE))->format('Y-m-d'));
    $test  = strtotime($dt->format('Y-m-d'));
    $now   = strtotime($this->getLocalDate());

    if($first <= $test && $test <= $now) {
      return true;
    } else {
      return false;
    }
  }

  public function convertLocalToUTC($local, $inc_time = true) {
    $fmt = $inc_time ? "Y-m-d H:i:s" : "Y-m-d";

    $timestamp = new DateTime($local, new DateTimeZone($this->timezone));
    $timestamp->setTimeZone(new DateTimeZone("UTC"));
    return $timestamp->format($fmt);
  }

  public function convertUTCToLocal($utc, $iso = true) {
    $fmt = $iso ? Datetime::ATOM : "Y-m-d H:i:s";

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
