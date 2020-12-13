<?php

namespace common\interfaces;

interface TimeInterface
{
    public function convertLocalToUTC($local, $inc_time = true);
    public function convertUTCToLocal($utc, $inc_time = true);
    public function getLocalTime($timezone = null);
    public function getLocalDate($timezone = null);
    public function alterLocalDate($date, $modifier);
    public function getUTCBookends($local);
}
