<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Dto\BenefitProvider\Beneficiary;
use App\Dto\BenefitProvider\BenefitProvider;
use App\Dto\Dmc\DigitalMedicalChit;
use App\Entity\Dmc\MedicalChit;
use App\EntityLegacy\ChannelPartner\ChannelPartnerBP;
use Doctrine\Persistence\ManagerRegistry;

final class DigitalMedicalChitItemDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    /** @var ManagerRegistry */
    private $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return DigitalMedicalChit::class === $resourceClass;
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?DigitalMedicalChit
    {
        /** @var MedicalChit $medicalChit */
        $medicalChit = $this->registry->getRepository(MedicalChit::class)->findOneByUuid($id);
        if (empty($medicalChit)) {
            return null;
        }

        $dmc = new DigitalMedicalChit();
        $dmc
            ->setUuid($medicalChit->getUuid());

        $dmc
            ->setBeneficiaryName($medicalChit->getBeneficiaryName())
            ->setBeneficiaryNric($medicalChit->getBeneficiaryNric());

        return $dmc;
    }
}