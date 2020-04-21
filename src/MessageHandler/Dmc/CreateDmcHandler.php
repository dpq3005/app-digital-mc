<?php

namespace App\MessageHandler\Dmc;

use App\Dto\BenefitProvider;
use App\Entity\Dmc\MedicalChit;
use App\Entity\EventSourcing\MedicalChitEvent;
use App\Message\Dmc\AssociateMerchant;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Exception\InvalidArgumentException;
use App\Message\Dmc\CreateDmc;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class CreateDmcHandler extends DmcHandler implements MessageHandlerInterface
{
    private $bus;

    public function __construct(ManagerRegistry $registry, LoggerInterface $logger, MessageBusInterface $bus)
    {
        parent::__construct($registry, $logger);
        $this->bus = $bus;
    }

    public function handleMessage(CreateDmc $message): MedicalChit
    {
        $medicalChit = $this->cast($message);
        $medicalChit->setState(MedicalChit::STATE_NEW);

        if (empty($productId = $medicalChit->getProductUuid())) {
            throw new \InvalidArgumentException('Product cannot be empty');
        }

        return $medicalChit;
    }

    public function __invoke(CreateDmc $message)
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

//        $conn = $manager->getConnection();
//        $conn->beginTransaction();

        $medicalChit = $this->handleMessage($message);
        $medicalChit->setBenefitProvider($bp);

        if ($message->isEventSourcingEnabled) {
            $event = new MedicalChitEvent();
            $event->action = MedicalChitEvent::EVENT_DMC_CREATED;
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

//            $conn->commit();
        $associateMerchant = new AssociateMerchant();
        $associateMerchant->dmcUuid = $medicalChit->getUuid();
        $this->bus->dispatch($associateMerchant);
    }
}