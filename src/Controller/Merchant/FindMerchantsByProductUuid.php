<?php

namespace App\Controller\Merchant;

use App\Dto\Merchant;
use App\Service\HttpService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class FindMerchantsByProductUuid
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
     *     name="find_merchants_by_product_uuid",
     *     path="/products/{id}/find-merchants-by-product-uuid",
     *     methods={"GET"},
     *     requirements={"id"=".+"},
     *     defaults={
     *         "_api_resource_class"=Merchant::class,
     *         "_api_item_operation_name"="get",
     *     }
     * )
     */
    public function __invoke($id, Request $request)
    {
        $query = sprintf('merchants?productUuid=%s', $id);
        if ($orgName = $request->query->get('organisationName', null)) {
            $query .= '&organisationName='.$orgName;
        }

        $pageSize = $request->query->getInt('pageSize', 0);
        if ($pageSize > 0) {
            $query .= '&pageSize='.$pageSize;
        }

        $res = $this->http->get($query, null, false, 'product');

        $merchants = $res['body'];
        if ($merchants === null) {
            throw new NotFoundHttpException('Product cannot be found '.$id);
        }
        $ms = [];
        foreach ($merchants as $merchant) {
            $m = new Merchant();
            $m->setName($merchant->name);
            $m->setUuid($merchant->uuid);

            $ms[] = $m;
        }
        return $ms;
    }
}