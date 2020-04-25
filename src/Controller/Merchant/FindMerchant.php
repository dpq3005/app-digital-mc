<?php

namespace App\Controller\Merchant;

use App\Dto\BenefitProduct;
use App\Dto\Merchant;
use App\Service\HttpService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class FindMerchant
{
    private $registry, $http;

    public function __construct(ManagerRegistry $registry, HttpService $httpService)
    {
        $this->registry = $registry;
        $this->http = $httpService;
    }

    /**
     * @param $id
     * @return Merchant
     * @throws \Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface
     * @Route(
     *     name="fetch_benefit_product",
     *     path="/merchants/{id}",
     *     methods={"GET"},
     *     requirements={"id"=".+"},
     *     defaults={
     *         "_api_resource_class"=Merchant::class,
     *         "_api_item_operation_name"="get",
     *     }
     * )
     */
    public function __invoke($id): Merchant
    {
        /** @var \App\Entity\Merchant\Merchant $merchantFromLocal */
        $merchantFromLocal = $this->registry->getRepository(\App\Entity\Merchant\Merchant::class);

        $merchant = new Merchant();

        if ($merchantFromLocal) {
            $merchant->setName($merchantFromLocal->getName());
            $merchant->setUuid($merchantFromLocal->getUuid());
            return $merchant;
        }

        $res = $this->http->get('merchants', $id);

        $merchantFromApi = $res['body'];
        if (empty($merchant)) {
            throw new NotFoundHttpException('Merchant not found for '.$id);
        }


        $merchant->setName($merchantFromApi->name);
        $merchant->setUuid($merchantFromApi->uuid);
        return $merchant;
    }
}