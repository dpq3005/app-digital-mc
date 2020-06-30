<?php

namespace App\Message\Dmc;

use App\Message\AbstractMessage;

class RedeemDmc extends AbstractMessage
{
    public $uuid;
    public $merchantUuid;
    public $redeemedAt;
    public $doctorUuid;
}