<?php

namespace App\MessageHandler\Dmc;

use App\Entity\Dmc\MedicalChit;
use App\Entity\EventSourcing\MedicalChitEvent;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Messenger\Exception\InvalidArgumentException;
use App\Message\Dmc\CreateDmc;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreateDmcHandler extends DmcHandler implements MessageHandlerInterface
{
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

        /** @var EntityManager $manager */
        $manager = $this->registry->getManager();
//        $conn = $manager->getConnection();
//        $conn->beginTransaction();

        $medicalChit = $this->handleMessage($message);

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
    }

}