<?php

namespace App\Controller\BenefitProvider;

use App\Dto\BenefitProduct;
use App\Service\HttpService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class FindBenefitProducts
{
    private $registry, $http;

    public function __construct(HttpService $httpService)
    {
        $this->http = $httpService;
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
            $bProduct->setUuid($benefitProduct->uuid);

            $bProducts[] = $bProduct;
        }
        return $bProducts;
    }
}