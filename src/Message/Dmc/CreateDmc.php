<?php

namespace App\Message\Dmc;

use App\Message\AbstractMessage;

class CreateDmc extends AbstractDmc
{
    public static function newInstance($beneficiaryNric, $beneficiaryName, $productUuid, $benefitProductUuid, $merchantUuids, $telemedEnabled): CreateDmc
    {
        $dmc = new self();
        $dmc->isEventSourcingEnabled = true;
        $dmc->beneficiaryNric = $beneficiaryNric;
        $dmc->beneficiaryName = $beneficiaryName;
        $dmc->productUuid = $productUuid;
        $dmc->benefitProductUuid = $benefitProductUuid;
        $dmc->merchantUuids = $merchantUuids;
        $dmc->telemedEnabled = $telemedEnabled;
        return $dmc;
    }
}