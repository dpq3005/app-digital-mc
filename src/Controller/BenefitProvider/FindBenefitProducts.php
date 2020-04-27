<?php

namespace App\Controller\BenefitProvider;

use App\Dto\BenefitProduct;
use App\Service\HttpService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class FindBenefitProducts
{
    private $registry, $http, $tokenStorage;

    public function __construct(HttpService $httpService, ManagerRegistry $registry, TokenStorageInterface $tokenStorage)
    {
        $this->registry = $registry;
        $this->http = $httpService;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param $id
     * @return array
     * @throws \Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface
     *
     * @Route(
     *     name="find_benefit_products",
     *     path="/benefit-providers/{id}/find-benefit-products",
     *     methods={"GET"},
     *     requirements={"id"=".+"},
     *     defaults={
     *         "_api_resource_class"=BenefitProduct::class,
     *         "_api_item_operation_name"="get",
     *     }
     * )
     */
    public function __invoke($id)
    {
        $token = $this->tokenStorage->getToken();
        if (empty($token)) {
            throw new UnauthorizedHttpException('Token not found', 'Token not found');
        }
        $user = $token->getUser();
        if (empty($user)) {
            throw new UnauthorizedHttpException('User not found', 'Empty User');
        }

        $res = $this->http->get(sprintf('benefit-products?benefitProviderUuid=%s', $id), null, false, 'product');
//        15e6f99b961376
        $benefitProducts = $res['body'];
        if ($benefitProducts === null) {
            throw new NotFoundHttpException('BenefitProvider cannot be found '.$id);
        }
        $bProducts = [];
        foreach ($benefitProducts as $benefitProduct) {
            $bProduct = new BenefitProduct();
            $bProduct->setName($benefitProduct->name);
            $bProduct->setProductUuid($benefitProduct->productUuid);
            $bProduct->setUuid($benefitProduct->uuid);

            $bProducts[] = $bProduct;
        }
        return $bProducts;
    }
}