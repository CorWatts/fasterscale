<?php

namespace common\components;

class UserTrim {
  public $user;

  public function __construct($user) {
    $this->user = $user;
  }

  public function isPartnerEnabled() {
    if((is_integer($this->user->email_threshold)
       && $this->user->email_threshold >= 0)
         && ($this->user->partner_email1
           || $this->user->partner_email2
           || $this->user->partner_email3)) {
      return true;
    }
    return false;
  }

  public function isOverThreshold($score = null) {
    if(!$this->isPartnerEnabled()) return false;

    // not really great...
    if(is_null($score)) {
      $date = Time::getLocalDate();
      list($start, $end) = Time::getUTCBookends($date);

      $score = UserOption::calculateScoreByUTCRange($start, $end);
      $score = reset($score); // get first array item
    }

    $threshold = $this->user->email_threshold;

    return (!is_null($threshold) && $score > $threshold)
            ? true
            : false;
  }
}
