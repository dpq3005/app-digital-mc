<?php

namespace App\MessageHandler\Dmc;

use App\Entity\Dmc\MedicalChit;
use App\Entity\EventSourcing\MedicalChitEvent;
use App\Message\Dmc\DeleteDmc;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Messenger\Exception\InvalidArgumentException;
use App\Message\Dmc\CreateDmc;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class DeleteDmcHandler extends DmcHandler implements MessageHandlerInterface
{
    public function handleMessage(DeleteDmc $message): MedicalChit
    {
        $medicalChit = $this->registry->getRepository(MedicalChit::class)->findOneByUuid($message->uuid);

        return $medicalChit;
    }

    public function __invoke(DeleteDmc $message)
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
            $event->action = MedicalChitEvent::EVENT_DMC_DELETED;
            $event->payload = $message->getPayload();
            $event->objectId = $medicalChit->getId();

            $event->objectUuid = $medicalChit->getUuid();

            $manager->persist($event);
        }

        $manager->remove($medicalChit);

        $manager->flush();
//            $conn->commit();
    }

}