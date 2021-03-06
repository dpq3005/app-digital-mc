<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\Exception\ResourceClassNotSupportedException;
use App\Dto\DigitalMedicalChit;
use App\Entity\Dmc\MedicalChit;
use App\Entity\Merchant\Merchant;
use App\Entity\Organisation\Organisation;
use App\Entity\Security\User;
use App\Security\ApiKeyNricUser;
use App\Security\JWTUser;
use App\Security\MerchantPinUser;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class DigitalMedicalChitCollectionDataProvider implements CollectionDataProviderInterface, RestrictedDataProviderInterface
{
    protected $requestStack, $registry, $tokenStorage, $authChecker;

    public function __construct(ManagerRegistry $registry, RequestStack $requestStack, TokenStorageInterface $tokenStorage, AuthorizationCheckerInterface $authChecker)
    {
        $this->requestStack = $requestStack;
        $this->registry = $registry;
        $this->tokenStorage = $tokenStorage;
        $this->authChecker = $authChecker;
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
        if ($beneficiaryName = $request->query->get('beneficiaryName')) {
            $beneficiaryName = trim($beneficiaryName);
            $qb->andWhere(
                $expr->like('dmc.beneficiaryName', $expr->literal('%'.$beneficiaryName.'%'))
            );
        }
        if ($beneficiaryNric = $request->query->get('beneficiaryNric')) {
            $qb->andWhere(
                $expr->like('dmc.beneficiaryNric', $expr->literal('%'.$beneficiaryNric.'%'))
            );
        }
        if (($telemedEnabledParam = $request->query->get('telemedEnabled')) !== null && trim($telemedEnabledParam) !== '') {
            $telemedEnabled = filter_var($telemedEnabledParam, FILTER_VALIDATE_BOOLEAN);
            if ($telemedEnabled) {
                $qb->andWhere(
                    $expr->eq('dmc.telemedEnabled', $expr->literal(true))
                );
            } else {
                $qb->andWhere(
                    $expr->neq('dmc.telemedEnabled', $expr->literal(true))
                );
            }
        }
        if (($redeemed = $request->query->get('redeemed')) !== null && trim($redeemed) !== '') {
            $qb->andWhere(
                $expr->eq('dmc.redeemed', $expr->literal(boolval($redeemed)))
            );
        }
        if (($expiredParam = $request->query->get('expired')) !== null && trim($expiredParam) !== '') {
            $now = new \DateTime();
            $qb->andWhere($expr->eq('dmc.redeemed', $expr->literal(false)));
            $expired = filter_var($expiredParam, FILTER_VALIDATE_BOOLEAN);

            if (boolval($expired)) {
                $now->modify('+1 day');
                $qb->andWhere(
                    $expr->lt('dmc.expireAt', $expr->literal($now->format('Y-m-d').' '.'00:00:00'))
                );
            } else {
                $qb->andWhere(
                    $expr->gte('dmc.expireAt', $expr->literal($now->format('Y-m-d').' '.'00:00:00'))
                );
            }
        }
    }

    public function getCollection(string $resourceClass, string $operationName = null): ?\Generator
    {
        $request = $this->requestStack->getCurrentRequest();

        $page = $request->query->getInt('page', 1);

        /** @var EntityManagerInterface $manager */
        $manager = $this->registry->getManager();
        $qb = $manager->createQueryBuilder();
        $expr = $qb->expr();

        $qb->select('dmc')->from(MedicalChit::class, 'dmc')
            ->join('dmc.benefitProvider', 'bp');

        $this->applyFilters($qb, $request);

        $user = $this->tokenStorage->getToken()->getUser();
        if ($user instanceof JWTUser) {
            if ($this->authChecker->isGranted(JWTUser::ROLE_SUPERVISOR)) {
                $orgUuid = $user->getOrganisationUuid();
                /** @var Organisation $org */
                $org = $this->registry->getRepository(Organisation::class)->findOneByUuid($orgUuid);

                $benefitProviderUuid = 0;
                if ($org) {
                    $benefitProviderUuid = $org->getBenefitProvider()->getUuid();
                }

                $qb->andWhere(
                    $expr->like('bp.uuid', $expr->literal($benefitProviderUuid))
                );
            } elseif ($user->hasRole(MerchantPinUser::ROLE_USER)) {
                $merchantUuid = $user->getUsername();
                $merchantRepo = $this->registry->getRepository(Merchant::class);
                $merchant = $merchantRepo->findOneByUuid($merchantUuid);
                if (!empty($merchant)) {
                    $qb->join('dmc.merchantAssignments', 'merchantAssignment')
                        ->join('merchantAssignment.merchant', 'merchant');
                    $qb->andWhere($expr->like('merchant.uuid', $expr->literal($merchantUuid)));

                } else {
                    throw new UnauthorizedHttpException('Merchant not authenticated', 'Unauthorised empty Merchant!!! with UUID '.$merchantUuid);
                }
            } elseif ($user->hasRole(ApiKeyNricUser::ROLE_USER)) {
                $qb->andWhere($expr->like('dmc.beneficiaryNric', $expr->literal($user->getUsername())));
            } else {
                throw new UnauthorizedHttpException('Merchant not authenticated', 'Unauthorised Merchant!!!');
            }
        } else {
            throw new UnauthorizedHttpException('Not a JWTUser', 'Unauthorised JWT!!!');
        }

        $pageSize = $request->query->getInt('pageSize', 100);
        $pageIndex = $page - 1;

        $qb->orderBy('dmc.id', 'DESC');
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
                $dmc->setMerchants(null);
            } else {
                $dmc->setMerchants($merchants);
            }

            $benefitProviderOrg = $medicalChit->getBenefitProvider()->getOrganisation();

            $dmc->setBeneficiaryPhone($medicalChit->getBeneficiaryPhone());
            $dmc->setBeneficiaryMedDeliveryAddress($medicalChit->getBeneficiaryMedDeliveryAddress());
            $dmc->setProductName($medicalChit->getProductName());
            $dmc->setProductUuid($medicalChit->getProductUuid());

            $dmc->setCode($medicalChit->getCode());
            $dmc->setCreatedAt($medicalChit->getCreatedAt());
            $dmc->setExpired($medicalChit->getExpired());
            $dmc->setRedeemed($medicalChit->getRedeemed());
            $dmc->setTelemedEnabled($medicalChit->isTelemedEnabled());
            $dmc->setBenefitProviderName($benefitProviderOrg->getName());
            $dmc->setBenefitProviderOrganisationUuid($benefitProviderOrg->getUuid());

            yield $dmc;
        }

        if (count($medicalChits) === 0) {
            return null;
        }
    }
}