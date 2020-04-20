<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\Exception\ResourceClassNotSupportedException;
use App\Dto\BenefitProvider\Beneficiary;
use App\Dto\BenefitProvider\BenefitProvider;
use App\Dto\Dmc\DigitalMedicalChit;
use App\Dto\Merchant\Merchant;
use App\Entity\Dmc\MedicalChit;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class DigitalMedicalChitCollectionDataProvider implements CollectionDataProviderInterface, RestrictedDataProviderInterface
{
    protected $requestStack, $registry;

    public function __construct(ManagerRegistry $registry, RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
        $this->registry = $registry;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return DigitalMedicalChit::class === $resourceClass;
    }

    protected function applyFilters(QueryBuilder $qb, Request $request)
    {
        $expr = $qb->expr();
        if ($benefitProviderUuid = $request->query->get('benefitProviderUuid')) {
            $qb->andWhere(
                $expr->like('bp.uuid', $expr->literal($benefitProviderUuid))
            );
        }
    }

    public function getCollection(string $resourceClass, string $operationName = null): ?\Generator
    {
        $request = $this->requestStack->getCurrentRequest();

        $page = $request->query->getInt('page', 1);

        /** @var EntityManagerInterface $manager */
        $manager = $this->registry->getManager();
        $qb = $manager->createQueryBuilder();
        $e = $qb->expr();

        $qb->select('dmc')->from(MedicalChit::class, 'dmc');

        $this->applyFilters($qb, $request);

        $pageSize = $request->query->getInt('pageSize', 100);
        $pageIndex = $page - 1;

        $qb->setMaxResults($pageSize)->setFirstResult($pageIndex * $pageSize);

        $medicalChits = $qb->getQuery()->getResult();

        /** @var MedicalChit $medicalChit */
        foreach ($medicalChits as $medicalChit) {
            $dmc = new DigitalMedicalChit();

            $dmc->setUuid($medicalChit->getUuid());
            $nric = $medicalChit->getBeneficiaryNric();
            $dmc
                ->setBeneficiaryName($medicalChit->getBeneficiaryName())
                ->setBeneficiaryNric($nric ? 'XXXX'.substr($nric, -4) : null)
                ->setProduct($medicalChit->getProductUuid());
            if (empty($merchants = $medicalChit->getMerchantUuids())) {
                $dmc->setMerchants($merchants);
            } else {
                $dmc->setMerchants(null);
            }

            $dmc->setCode($medicalChit->getCode());
            $dmc->setCreatedAt($medicalChit->getCreatedAt());
            $dmc->setExpired($medicalChit->getExpired());
            $dmc->setRedeemed($medicalChit->getRedeemed());

            yield $dmc;
        }

        if (count($medicalChits) === 0) {
            return null;
        }
    }
}