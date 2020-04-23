<?php

namespace App\Message\Dmc;

use App\Message\AbstractMessage;

abstract class AbstractDmc extends AbstractMessage
{
    public $benefitProviderUuid;
    public $beneficiaryName;
    public $beneficiaryNric;
    public $benefitProductUuid;
    public $productUuid;
    public $productName;
    public $merchantUuids;

    public $expireIn;
    public $expireAt;
}