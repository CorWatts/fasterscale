<?php

namespace common\interfaces;

interface UserBehaviorInterface extends \yii\db\ActiveRecordInterface
{
    public function getUser();
    public function getPastCheckinDates();
    public function getUserBehaviorsWithCategory($checkin_date);
    public function getBehaviorsByCategory(array $decorated_behaviors);
    public function getCheckinBreakdown(int $period);
    public static function decorate(array $uo);
    public function getBehaviorsWithCounts($limit);
}
