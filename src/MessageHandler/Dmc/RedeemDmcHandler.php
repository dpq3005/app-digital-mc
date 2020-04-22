<?php

namespace App\MessageHandler\Dmc;

use App\Dto\BenefitProvider;
use App\Entity\BenefitProvider\BenefitProduct;
use App\Entity\Dmc\MedicalChit;
use App\Entity\EventSourcing\MedicalChitEvent;
use App\Message\Dmc\AssociateMerchant;
use App\Message\Dmc\RedeemDmc;
use App\Service\ThingService;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Exception\InvalidArgumentException;
use App\Message\Dmc\CreateDmc;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class RedeemDmcHandler extends DmcHandler implements MessageHandlerInterface
{
    private $bus;

    public function __construct(ManagerRegistry $registry, LoggerInterface $logger, MessageBusInterface $bus)
    {
        parent::__construct($registry, $logger);
        $this->bus = $bus;
    }

    public function handleMessage(RedeemDmc $message): MedicalChit
    {
        /** @var MedicalChit $medicalChit */
        $medicalChit = $this->registry->getRepository(MedicalChit::class)->findOneByUuid($message->uuid);

        $medicalChit->setRedeemedAtMerchantUuid($message->merchantUuid);

        if ($message->redeemedAt) {
            $redeemedAt = ThingService::castDateTime(json_decode(json_encode($message->redeemedAt)));
        }

        $medicalChit->setRedeemedAt($redeemedAt);

        $medicalChit->setRedeemed(true);

        return $medicalChit;
    }

    public function __invoke(RedeemDmc $message)
    {
        if ($message->isEventSourcingEnabled === null) {
            throw new InvalidArgumentException('property $isEventSourcingEnabled cannot be null');
        }

        if (empty($message->benefitProviderUuid)) {
            throw new \InvalidArgumentException('empty Benefit Provider');
        }

        /** @var EntityManager $manager */
        $manager = $this->registry->getManager();
        $bpRepo = $manager->getRepository(\App\Entity\BenefitProvider\BenefitProvider::class);
        $bp = $bpRepo->findOneByUuid($message->benefitProviderUuid);

        $medicalChit = $this->handleMessage($message);

        $dmcApiDto = [];
        $dmcApiDto['uuid'] = $medicalChit->getUuid();
        $dmcApiDto['uuid'] = $medicalChit->getExpireAt();
        $dmcApiDto['uuid'] = $medicalChit->getExpireIn();
//        $dmcApiDto['uuid'] = $medicalChit->get;
        file_put_contents('D:\testDto.txt', json_encode($dmcApiDto));


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
    }
}