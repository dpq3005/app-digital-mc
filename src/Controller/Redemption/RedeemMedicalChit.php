<?php

namespace App\Controller\Redemption;

use App\Dto\BenefitProduct;
use App\Dto\Dmc\DigitalMedicalChit;
use App\Dto\Redemption;
use App\Entity\Dmc\MedicalChit;
use App\Message\Dmc\RedeemDmc;
use App\Service\HttpService;
use App\Service\ThingService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class RedeemMedicalChit
{
    private $registry, $http;

    public function __construct(HttpService $httpService, ManagerRegistry $registry)
    {
        $this->registry = $registry;
        $this->http = $httpService;
    }

    /**
     * @param $id
     * @return array
     * @throws \Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface
     */
    public function __invoke($id, Request $request): Redemption
    {
        $dmcRepo = $this->registry->getRepository(MedicalChit::class);
        /** @var MedicalChit $medicalChit */
        $medicalChit = $dmcRepo->findOneByUuid($id);
        if (empty($medicalChit)) {
            throw new NotFoundHttpException('Empty medicalChit');
        }


        $contentJson = $request->getContent();

        $content = json_decode($contentJson);

        /** @var DigitalMedicalChit $dmc */
        $dmc = ThingService::cast($content, DigitalMedicalChit::class);

        $redeemDmc = new RedeemDmc();
        $redeemDmc->uuid = $id;
        $redeemDmc->merchantUuid = $dmc->getRedeemedAtMerchantUuid();

//        $dmc = new DigitalMedicalChit();
//        $dmc->setUuid('UUID');
//        $dmc->setBeneficiaryName($medicalChit->getBeneficiaryName());


        $r = new Redemption();
        $r->setUuid('UUID_'.$dmc->getBeneficiaryName().'  '.$redeemDmc->merchantUuid);
        return $r;
    }
}