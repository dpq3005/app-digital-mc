<?php

namespace App\MessageHandler\Dmc;

use App\Dto\BenefitProvider;
use App\Entity\BenefitProvider\BenefitProduct;
use App\Entity\Dmc\MedicalChit;
use App\Entity\EventSourcing\MedicalChitEvent;
use App\Entity\Merchant\Merchant;
use App\Message\Dmc\AssociateMerchant;
use App\Message\Dmc\RedeemDmc;
use App\Service\HttpService;
use App\Service\ThingService;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Messenger\Exception\InvalidArgumentException;
use App\Message\Dmc\CreateDmc;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class RedeemDmcHandler extends DmcHandler implements MessageHandlerInterface
{
    private $bus, $http, $kernel;

    public function __construct(ManagerRegistry $registry, LoggerInterface $logger, MessageBusInterface $bus, HttpService $http, KernelInterface $kernel)
    {
        parent::__construct($registry, $logger);
        $this->bus = $bus;
        $this->http = $http;
        $this->kernel = $kernel;
    }

    public function handleMessage(RedeemDmc $message): MedicalChit
    {
        /** @var MedicalChit $medicalChit */
        $medicalChit = $this->registry->getRepository(MedicalChit::class)->findOneByUuid($message->uuid);

        $medicalChit->setRedeemedAtMerchantUuid($message->merchantUuid);

        if (empty($message->redeemedAt)) {
            $message->redeemedAt = json_decode(json_encode(new \DateTime()));
        }

        $redeemedAt = ThingService::castDateTime(json_decode(json_encode($message->redeemedAt)));
        $medicalChit->setRedeemedAt($redeemedAt);

        return $medicalChit;
    }

    public function __invoke(RedeemDmc $message)
    {
        if ($message->isEventSourcingEnabled === null) {
            throw new InvalidArgumentException('property $isEventSourcingEnabled cannot be null');
        }

        $medicalChit = $this->handleMessage($message);

        if ($medicalChit->getRedeemed() === true) {
            throw new UnauthorizedHttpException('Redeemed already', $message->uuid.' - '.$medicalChit->getUuid().' - Already redeemed', null, 400);
        }

        /** @var EntityManager $manager */
        $manager = $this->registry->getManager();
        $bpRepo = $manager->getRepository(\App\Entity\BenefitProvider\BenefitProvider::class);
        $bp = $medicalChit->getBenefitProvider();

        $merchantRepo = $this->registry->getRepository(Merchant::class);
        /** @var Merchant $redeemedAtMerchant */
        $redeemedAtMerchant = $merchantRepo->findOneByUuid($medicalChit->getRedeemedAtMerchantUuid());
        if (empty($redeemedAtMerchant)) {
            throw new NotFoundHttpException('Merchant not found');
        } else {
            if (empty($redeemedAtMerchant->getMerchantAssignmentByDmcUuid($medicalChit->getUuid()))) {
                throw new UnauthorizedHttpException('Unauthorised', 'This merchant is not assigned to this Medical Chit.');
            };
        }

        $now = new \DateTime();
        if ($medicalChit->isExpired()) {
            throw new \InvalidArgumentException('Medical Chit expired');
        }

//        $conn = $manager->getConnection();
//        $conn->beginTransaction();

        if ($message->isEventSourcingEnabled) {
            $event = new MedicalChitEvent();
            $event->action = MedicalChitEvent::EVENT_DMC_REDEEMED;
            $event->payload = $message->getPayload();
            $event->medicalChit = $medicalChit;
            $medicalChit->initUuid('MC');
            $event->objectUuid = $medicalChit->getUuid();

            $manager->persist($event);
        }

        $manager->persist($medicalChit);

        $manager->flush();

        $message->uuid = $medicalChit->getUuid();

        if ($message->isEventSourcingEnabled) {
            $event->objectId = $medicalChit->getId();
            $manager->persist($event);
            $manager->flush();
        }

        $medicalChit->setRedeemed(true);
        $dmcApiDto = [];
        $dmcApiDto['uuid'] = $medicalChit->getUuid();
        $dmcApiDto['expireAt'] = $medicalChit->getExpireAt();
        $dmcApiDto['expireIn'] = $medicalChit->getExpireIn();
        $dmcApiDto['expired'] = $medicalChit->isExpired();
        $dmcApiDto['benefitProductUuid'] = $medicalChit->getBenefitProductUuid();
        $dmcApiDto['redeemedAtMerchantUuid'] = $medicalChit->getRedeemedAtMerchantUuid();

        $resourcePath = sprintf('digital-medical-chits/%s/redeem', $medicalChit->getUuid());

        $postdata = $dmcApiDto;

        try {
            $res = $this->http->post($resourcePath, $postdata, false, 'redemption');
            $data = $res['body'];
            if ($data) {
                if (array_key_exists('redemptionUuid', $data)) {
                    $medicalChit->setRedemptionUuid($data['redemptionUuid']);
                    $medicalChit->setRedeemed(true);
                    $manager->persist($medicalChit);
                    $manager->flush();
                }
            }
        } catch (\Throwable $exception) {
            $path = $this->kernel->getProjectDir().'/var/log/dmc-redemption-'.$now->format('Ymd-His').'.txt';
            file_put_contents($path, json_encode([$dmcApiDto]));
        }
    }
}
