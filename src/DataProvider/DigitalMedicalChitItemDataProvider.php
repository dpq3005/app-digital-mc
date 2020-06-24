<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Dto\DigitalMedicalChit;
use App\Entity\Dmc\MedicalChit;
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

        $nric = $medicalChit->getBeneficiaryNric();
        $nric = $nric ? 'XXXX'.substr($nric, -4) : null;
        $dmc
            ->setBeneficiaryName($medicalChit->getBeneficiaryName())
            ->setBeneficiaryNric($nric)
            ->setBenefitProduct($medicalChit->getBenefitProductUuid())
            ->setCode($medicalChit->getCode())
            ->setExpired($medicalChit->getExpired())
            ->setRedeemed($medicalChit->getRedeemed())
            ->setProduct($medicalChit->getProductUuid())
            ->setProductName($medicalChit->getProductName())
            ->setTelemedEnabled($medicalChit->isTelemedEnabled());
        return $dmc;
    }
}