<?php

namespace App\Controller\Dummy;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class MerchantController extends AbstractController
{
    const MERCHANTS_BY_UUID = ['15e6f99ba28c82' => ["uuid" => "15e6f99ba28c82",
        "invoiceEmail" => "binh@sunrise.vn",
        "organisationLogoUrl" => "https=>//magenta-wellness.com/media/organisation-logo/view-binary/1241",
        "name" => "DEMO Clinic",
        "phone" => null,
        "email" => "binh@sunrise.vn",
        "address" => null,
        "country" => null,
        "currency" => null,
        "taxRate" => null,
        "organisationLegacyId" => "1241",
        "organisationUuid" => "5e820ef1d5084",
        "taxLabel" => null,
        "taxApplicable" => false,
        "bankAccountNumber" => null,
        "bankAccountName" => null,
        "bankName" => null,
        "bankSwift" => null,
        "beneficiaryNricVisibility" => null,
        "pin" => "123456",
        "slug" => "demo-clinic",
        "agentName" => "  ",
    ]];

    /**
     * @Route("/dummy/entity/merchants/{uuid}", name="dummy_merchant")
     */
    public function merchantDetails($uuid)
    {
        return new JsonResponse(self::MERCHANTS_BY_UUID[$uuid]);
    }
}
