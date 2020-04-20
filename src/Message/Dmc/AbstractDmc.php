<?php

namespace App\Message\Dmc;

use App\Message\AbstractMessage;

abstract class AbstractDmc extends AbstractMessage
{
    public $beneficiaryName;
    public $beneficiaryNric;
    public $productUuid;
    public $productName;
    public $merchantUuids;
}