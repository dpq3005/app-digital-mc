<?php

namespace App\Controller\BenefitProvider;

use App\Dto\BenefitProvider\Beneficiary;
use App\Service\HttpService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FindOneBeneficiaryByNric
{
    private $registry, $http;

    public function __construct(HttpService $httpService)
    {
        $this->http = $httpService;
    }

    public function __invoke($id, $nric): Beneficiary
    {

        $res = $this->http->get(sprintf('beneficiaries?nric=%s&benefitProviderUuid=%s', $nric, $id));

        $beneficiaries = $res['body'];
        if (empty($beneficiaries)) {
            throw new NotFoundHttpException('Beneficiary not found for '.$id.' - '.$nric);
        }
        $beneficiary = $beneficiaries[0];
        $ben = new Beneficiary();
        $ben->setNric($beneficiary->nric);
        $ben->setName($beneficiary->name);
        $ben->setUuid($beneficiary->uuid);
        return $ben;
    }
}