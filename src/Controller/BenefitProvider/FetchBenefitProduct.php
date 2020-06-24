<?php

namespace App\Controller\BenefitProvider;

use App\Dto\BenefitProduct;
use App\Dto\BenefitProvider\Beneficiary;
use App\Service\HttpService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class FetchBenefitProduct
{
    private $registry, $http;

    public function __construct(HttpService $httpService)
    {
        $this->http = $httpService;
    }

    /**
     * @param $id
     * @return BenefitProduct
     * @throws \Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface
     * @Route(
     *     name="fetch_benefit_product",
     *     path="/benefit-products/{id}",
     *     methods={"GET"},
     *     requirements={"id"=".+"},
     *     defaults={
     *         "_api_resource_class"=BenefitProduct::class,
     *         "_api_item_operation_name"="get",
     *     }
     * )
     */
    public function __invoke($id): BenefitProduct
    {
        $res = $this->http->get('benefit-products', $id);

        $benefitProduct = $res['body'];
        if (empty($benefitProduct)) {
            throw new NotFoundHttpException('BProduct not found for '.$id);
        }
        $bproduct = new BenefitProduct();
        $bproduct->setName($benefitProduct->name);
        $bproduct->setUuid($benefitProduct->uuid);
        $bproduct->setShortDescription($benefitProduct->shortDescription);
        $bproduct->setDescription($benefitProduct->description);
        $bproduct->setTelemedPrice($benefitProduct->telemedPrice);
        $bproduct->setTelemedEnabled($benefitProduct->telemedEnabled);

        return $bproduct;
    }
}