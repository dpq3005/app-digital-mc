<?php

namespace App\MessageHandler\Dmc;

use App\Dto\BenefitProvider;
use App\Entity\BenefitProvider\BenefitProduct;
use App\Entity\Dmc\MedicalChit;
use App\Entity\EventSourcing\MedicalChitEvent;
use App\Message\Dmc\AssociateMerchant;
use App\Service\ThingService;
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
        if (empty($message->createdAt)) {
            $message->createdAt = json_decode(json_encode(new \DateTime()));
        }

        $medicalChit = $this->cast($message);
        $createdAt = $medicalChit->getCreatedAt();

        $medicalChit->setState(MedicalChit::STATE_NEW);

        if (empty($productId = $medicalChit->getProductUuid())) {
            throw new \InvalidArgumentException('Product cannot be empty');
        }

        $nric = $medicalChit->getBeneficiaryNric();
        $nric = preg_replace('/\s+/', '', $nric);
        $medicalChit->setBeneficiaryNric($nric);

        if ($message->expireAt) {
            $medicalChit->setExpireAt(ThingService::castDateTime($message->expireAt));
        } elseif ($message->expireIn) {
            $medicalChit->setExpireAt($createdAt->modify('+ '.$message->expireIn.' hours'));
        }

        if (empty($medicalChit->getExpireAt())) {
            $expireAt = clone $createdAt;
            $message->expireIn = 36;
            $expireAt->modify('+ '.$message->expireIn.' hours');
            $medicalChit->setExpireAt($expireAt);
            $message->expireAt = json_decode(json_encode($expireAt));
        }

        if (empty($medicalChit->getExpireIn())) {
            $medicalChit->setExpireIn($message->expireIn);
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

        $medicalChit = $this->handleMessage($message);

        if ($message->benefitProductUuid) {
            $benefitProduct = $manager->getRepository(BenefitProduct::class)->findOneByUuid($message->benefitProductUuid);

            if ($benefitProduct) {
                $medicalChit->setBenefitProduct($benefitProduct);
            }
        }

//        $conn = $manager->getConnection();
//        $conn->beginTransaction();
        $medicalChit->setBenefitProvider($bp);
        $medicalChit->setBenefitProduct($benefitProduct);

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