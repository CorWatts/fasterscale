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
}
