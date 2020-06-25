<?php

namespace App\Controller\Dummy;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class RedemptionController extends AbstractController
{
    /**
     * @Route("/dummy/redemption/digital-medical-chits/{dmcUuid}/redeem", name="dummy_redemption")
     */
    public function dummyRedemption($dmcUuid)
    {
        $data = ['redemptionUuid' => uniqid('TEST_'), 'dmc' => ['uuid' => $dmcUuid]];
        return new JsonResponse($data);
    }
}
