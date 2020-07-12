<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Dto\DigitalMedicalChit;
use App\Entity\Dmc\MedicalChit;
use App\Entity\Organisation\Organisation;
use App\Entity\Security\User;
use App\Message\Dmc\CreateDmc;
use App\Message\Dmc\DeleteDmc;
use App\Security\JWTUser;
use App\Service\HttpService;
use App\Service\ThingService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\UsageTrackingTokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class DigitalMedicalChitDataPersister implements ContextAwareDataPersisterInterface
{
    protected $registry, $bus, $http, $tokenStorage, $authChecker;

    public function __construct(MessageBusInterface $bus, ManagerRegistry $registry, HttpService $http, TokenStorageInterface $tokenStorage, AuthorizationCheckerInterface $authChecker)
    {
        $this->bus = $bus;
        $this->registry = $registry;
        $this->http = $http;
        $this->tokenStorage = $tokenStorage;
        $this->authChecker = $authChecker;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof DigitalMedicalChit;
    }

    /**
     * @param DigitalMedicalChit $data
     * @param array $context
     * @return object|void|null
     */
    public function persist($data, array $context = [])
    {
        if (empty($data)) {
            throw new NotFoundHttpException('Benefit Provider not found');
        }

        if (empty($data->getUuid())) {
            /** @var CreateDmc $message */
            $message = new CreateDmc();
        } else {

        }

        if (empty($productUuid = $data->getProduct())) {
            throw new \InvalidArgumentException('Empty Product');
        }

        $res = $this->http->get('products', $productUuid, false, 'product');

        $product = $res['body'];
        if (empty($product)) {
            throw new NotFoundHttpException('Product not found for ID: '.$productUuid);
        }

        $user = $this->tokenStorage->getToken()->getUser();
        if ($user instanceof JWTUser) {
            if ($this->authChecker->isGranted(JWTUser::ROLE_SUPERVISOR)) {
                $uname = $user->getUsername();
                $orgUuid = $user->getOrganisationUuid();
                /** @var Organisation $org */
                $org = $this->registry->getRepository(Organisation::class)->findOneByUuid($orgUuid);

                if ($org) {
//                    /** @var User $supUser
//                     */
//                    $supUser = $org->findOneUserByUsername($uname);
//                    $bp = $supUser->getOrganisation()->getBenefitProvider();
                    $bp = $org->getBenefitProvider();
                    $message->benefitProviderUuid = $bp->getUuid();
                } else {
                    throw new \Exception('Org not found for uuid '.$orgUuid);
                }
            } else {
                throw new \Exception('user does not have Supervisor role');
            }

            $message->productUuid = $productUuid;
            $message->productName = $product->name;
            $message->benefitProductUuid = $data->getBenefitProduct();
            $message->beneficiaryName = $data->getBeneficiaryName();
            $message->beneficiaryNric = $data->getBeneficiaryNric();
            $message->merchantUuids = $data->getMerchants();
            $message->telemedEnabled = $data->isTelemedEnabled();
            $message->beneficiaryPhone = $data->getBeneficiaryPhone();
            $message->medDeliveryAddress = $data->getBeneficiaryMedDeliveryAddress();

            $message->isEventSourcingEnabled = true;
            $this->bus->dispatch($message);

            $data->setUuid($message->uuid);
        } else {
            throw new \Exception('user is not an instance of JWTUser');
        }

        return $data;
    }

    /**
     * @param DigitalMedicalChit $data
     * @param array $context
     */
    public function remove($data, array $context = [])
    {
        if ($data->getRedeemed()) {
            throw new UnauthorizedHttpException('Cannot delete a redeemed DMC');
        }

        $message = new DeleteDmc();
        $message->uuid = $data->getUuid();
        $message->isEventSourcingEnabled = true;

        $this->bus->dispatch($message);
    }

}