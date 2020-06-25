<?php

namespace App\Controller\Dummy;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class RedemptionController extends AbstractController
{
    /**
     * @Route("/dummy/redemption", name="dummy_redemption")
     */
    public function index()
    {
        $data = ['redemptionUuid' => uniqid('TEST_'), 'dmc' => []];
        return new JsonResponse($data);
    }
}
