<?php


namespace App\Service\Merchant;


use App\Entity\Dmc\MedicalChit;
use App\Message\Dmc\AssociateMerchant;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Messenger\MessageBusInterface;

class MerchantAssignor
{
    public function __construct(ManagerRegistry $registry, MessageBusInterface $bus)
    {
        $this->registry = $registry;
        $this->bus = $bus;
    }

    public function initialiseMerchantAssignment()
    {
        $dmcs = $this->registry->getRepository(MedicalChit::class)->findBy(['merchantAssignmentsInit' => false]);
        $manager = $this->registry->getManager();

        /** @var MedicalChit $medicalChit */
        foreach ($dmcs as $medicalChit) {
            $associateMerchant = new AssociateMerchant();
            $associateMerchant->dmcUuid = $medicalChit->getUuid();
            try {
                $this->bus->dispatch($associateMerchant);
                $medicalChit->setMerchantAssignmentsInit(true);
                $manager->flush();
            } catch (\Throwable $exception) {
                throw $exception;
            }
        }
    }

}