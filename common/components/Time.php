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
   * Returns a \DateTime object of the current time in $this->timezone
   *
   * @return \DateTime the current time in $this->timezone
   */
  public function now() {
    return new DateTime("now", new DateTimeZone($this->timezone));
  }

  /*
   * Parses the supplied string into a `\DateTime` object of the
   * given `$format`. It assumes the supplied string is in the
   * timezone specified in $this->timezone.
   *
   * @param string $time the questionable time to parse
   * @param bool | \DateTime $default the value to return if $time is not a parseable date
   * @param string $format the format `$time` is expected to be in
   * @return \DateTime the parsed time or the default value
   */
  public function parse($time, $default = false, string $format = 'Y-m-d') {
    if(is_string($time)) {
      $dt = DateTime::createFromFormat($format, $time, new DateTimeZone($this->timezone));
      if($dt) {
        // for some reason, using createFromFromat adds in the time. The regular DateTime constructor _does not_ do this. We manually zero out the time here to make the DateTime objects match.
        $dt->setTime(0, 0, 0);
        $formatted = $dt->format($format);
        if($formatted === $time && $this->inBounds($dt)) {
          return $dt;
        }
      }
    }
    return $default;
  }

  /*
   * Checks if the given `\DateTime` is within "acceptable" date bounds.
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

  /*
   * Verifies that what it is given is a parseable date string
   * If it is not a parseable string, it defaults to the current
   * date.
   * 
   * @param $date a date string or null
   * @return string a date string
   */
  public function validate($date = null) {
    if(is_null($date)) {
      return $this->getLocalDate();
    } else if($dt = $this->parse($date)) {
      return $dt->format('Y-m-d');
    } else {
      return $this->getLocalDate();
    }
  }

  /*
   * Returns a \DatePeriod iterable containing a DateTime for each day of the
   * last $period days. The \DatePeriod is in the $this->timezone timezone. The
   * current day (end of the period) is included in the \returned \DatePeriod.
   *
   * @param $period int
   * @return \DatePeriod of length $period, from $period days ago to today
   */
  public function getDateTimesInPeriod(int $period = 30) {
    $start = new DateTime("now", new DateTimeZone($this->timezone));
    $end   = new DateTime("now", new DateTimeZone($this->timezone));
    $oneday = new \DateInterval('P1D');
    $end->add($oneday);      // add a day, so the end date gets included in the intervals
 
    $start->add(new \DateInterval('PT2M'))  // add two minutes, in case they just did a check-in
          ->sub(new \DateInterval("P${period}D")); // subtract `$period` number of days
    $end->add(new \DateInterval('PT2M'));   // add two minutes, in case they just did a check-in

    $periods = new \DatePeriod($start, $oneday, $end, \DatePeriod::EXCLUDE_START_DATE);
    $local_tz = new \DateTimeZone($this->timezone);
    foreach($periods as $period) {
      $period->setTimezone($local_tz);
    }
    return $periods;
  }
}
