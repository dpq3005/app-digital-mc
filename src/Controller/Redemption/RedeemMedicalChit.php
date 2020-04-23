<?php

namespace App\Controller\Redemption;

use App\Dto\BenefitProduct;
use App\Dto\Dmc\DigitalMedicalChit;
use App\Dto\Redemption;
use App\Entity\Dmc\MedicalChit;
use App\Message\Dmc\RedeemDmc;
use App\Service\HttpService;
use App\Service\ThingService;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class RedeemMedicalChit
{
    private $registry, $http, $bus;

    public function __construct(HttpService $httpService, ManagerRegistry $registry, MessageBusInterface $bus)
    {
        $this->registry = $registry;
        $this->http = $httpService;
        $this->bus = $bus;
    }

    /**
     * @param $id
     * @return array
     * @throws \Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface
     */
    public function __invoke($id, Request $request): Redemption
    {

        $contentJson = $request->getContent();

        $content = json_decode($contentJson);

        /** @var DigitalMedicalChit $dmc */
        $dmc = ThingService::cast($content, DigitalMedicalChit::class);

        $redeemDmc = new RedeemDmc();
        $redeemDmc->isEventSourcingEnabled = true;

        $redeemDmc->uuid = $id;
        $redeemDmc->merchantUuid = $dmc->getRedeemedAtMerchantUuid();

//        $dmc = new DigitalMedicalChit();
//        $dmc->setUuid('UUID');
//        $dmc->setBeneficiaryName($medicalChit->getBeneficiaryName());

        $this->bus->dispatch($redeemDmc);

        $dmcRepo = $this->registry->getRepository(MedicalChit::class);
        /** @var MedicalChit $medicalChit */
        $medicalChit = $dmcRepo->findOneByUuid($id);
        if (empty($medicalChit)) {
            throw new NotFoundHttpException('Empty medicalChit');
        }

        $r = new Redemption();
        $r->setUuid('UUID_'.$dmc->getBeneficiaryName().'  '.$redeemDmc->merchantUuid.' ::: '.($medicalChit->getRedeemed() ? 'true' : 'false'));

        return $r;
    }
}