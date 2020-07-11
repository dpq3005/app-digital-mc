<?php

namespace App\Controller\Redemption;

use App\Dto\BenefitProduct;
use App\Dto\DigitalMedicalChit;
use App\Dto\Redemption;
use App\Entity\Dmc\MedicalChit;
use App\Entity\Merchant\Merchant;
use App\Message\Dmc\RedeemDmc;
use App\Security\MerchantPinUser;
use App\Service\HttpService;
use App\Service\ThingService;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class RedeemMedicalChit
{
    private $registry, $http, $bus, $tokenStorage;

    public function __construct(HttpService $httpService, ManagerRegistry $registry, MessageBusInterface $bus, TokenStorageInterface $tokenStorage)
    {
        $this->registry = $registry;
        $this->http = $httpService;
        $this->bus = $bus;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param $id
     * @return array
     * @throws \Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface
     */
    public function __invoke($id, Request $request): Redemption
    {
        $token = $this->tokenStorage->getToken();
        if (empty($token)) {
            throw new UnauthorizedHttpException('Token not found', 'Token not found');
        }
        $user = $token->getUser();
        if (empty($user)) {
            throw new UnauthorizedHttpException('User not found', 'Empty User');
        }

        $contentJson = $request->getContent();

        $content = json_decode($contentJson);

        /** @var DigitalMedicalChit $dmc */
        $dmc = ThingService::cast($content, DigitalMedicalChit::class);

        $redeemDmc = new RedeemDmc();
        $redeemDmc->isEventSourcingEnabled = true;

        $redeemDmc->uuid = $id;
        $redeemDmc->merchantUuid = $dmc->getRedeemedAtMerchantUuid();
        if (property_exists($content, 'telemedRedeemed')) {
            $redeemDmc->telemedRedeemed = $content->telemedRedeemed;
        }

//        $dmc = new DigitalMedicalChit();
//        $dmc->setUuid('UUID');
//        $dmc->setBeneficiaryName($medicalChit->getBeneficiaryName());

        if ($user instanceof MerchantPinUser) {
            // to check here;
//            $medicalChit->hasMerchantId($user->getUsername());
        }

        $this->bus->dispatch($redeemDmc);

        $dmcRepo = $this->registry->getRepository(MedicalChit::class);

        /** @var MedicalChit $medicalChit */
        $medicalChit = $dmcRepo->findOneByUuid($id);
        if (empty($medicalChit)) {
            throw new NotFoundHttpException('Empty medicalChit');
        }

        $merchantRepo = $this->registry->getRepository(Merchant::class);
        $merchant = $merchantRepo->findOneByUuid($user->getUsername());
        if (empty($merchant)) {
            throw new NotFoundHttpException('Empty Merchant');
        }

        $r = new Redemption();
        $r->setName('UUID_'.$dmc->getBeneficiaryName().'  '.$redeemDmc->merchantUuid.' ::: '.($medicalChit->getRedeemed() ? 'true' : 'false'));
        $r->setUuid($dmc->getRedemptionUuid());


        return $r;
    }
}