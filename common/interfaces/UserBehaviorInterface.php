<?php

namespace common\interfaces;

interface UserBehaviorInterface extends \yii\db\ActiveRecordInterface {
  public function getUser();
  public function getPastCheckinDates();
  public function getUserBehaviorsWithCategory($checkin_date);
  public function getDailyScore($date);
  public function getBehaviorsByDate($start, $end);
  public function calculateScoreByUTCRange($start, $end);
  public function calculateScoresOfLastMonth();
  public function calculateScore($usr_bhvrs, $all_cats);
  public function getTopBehaviors(int $limit);
  public function getBehaviorsByCategory(\DateTime $date);
  public static function decorate(array $uo, $with_category);
  public static function decorateWithCategory(array $uo);
  public function getBehaviorsWithCounts($limit);
}
