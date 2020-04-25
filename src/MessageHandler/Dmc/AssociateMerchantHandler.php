<?php

namespace App\MessageHandler\Dmc;

use App\Entity\Dmc\MedicalChit;
use App\Entity\Dmc\MerchantAssignment;
use App\Entity\Merchant\Merchant;
use App\Message\Dmc\AssociateMerchant;
use App\Message\Dmc\CreateDmc;
use App\Service\HttpService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class AssociateMerchantHandler implements MessageHandlerInterface
{
    private $registry, $http;

    public function __construct(ManagerRegistry $registry, HttpService $http)
    {
        $this->registry = $registry;
        $this->http = $http;
    }

    private function handleMerchant($merchantFromApi, MedicalChit $dmc, $merchantRepo)
    {
        if (empty($merchantFromApi)) {
            throw new \Exception('No Merchant returned from Entity Api');
        }

        /** @var Merchant $m */
        if (empty($m = $merchantRepo->findOneByUuid($merchantFromApi->uuid))) {
            $m = new Merchant();
            $m->setName($merchantFromApi->name);
            $m->setUuid($merchantFromApi->uuid);
            $m->setEnabled(true);
        }

        if (empty($m->getMerchantAssignmentByDmcUuid($dmc->getUuid()))) {
            $merchantAssignment = new MerchantAssignment();
            $merchantAssignment->setMerchant($m);
            $merchantAssignment->setMedicalChit($dmc);
        } else {
            return null;
        };

        return ['merchant' => $m, 'merchantAssignment' => $merchantAssignment,
        ];
    }

    public function __invoke(AssociateMerchant $message)
    {
        $repo = $this->registry->getRepository(MedicalChit::class);
        $merchantRepo = $this->registry->getRepository(Merchant::class);

        $manager = $this->registry->getManager();

        /** @var MedicalChit $dmc */
        $dmc = $repo->findOneByUuid($message->dmcUuid);
        $productUuid = $dmc->getProductUuid();
        if (empty($merchantUuids = $dmc->getMerchantUuids())) {
            $page = 1;
            $resourcePath = 'merchants?pageSize=100&productUuid='.$productUuid.'&page='.$page;
            $res = $this->http->get($resourcePath);
            $merchants = $res['body'];
            while (!empty($merchants)) {
                foreach ($merchants as $merchantFromApi) {
                    $handled = $this->handleMerchant($merchantFromApi, $dmc, $merchantRepo);
                    if (!empty($handled)) {
                        $m = $handled['merchant'];
                        $merchantAssignment = $handled['merchantAssignment'];
                        $manager->persist($m);
                        $manager->persist($merchantAssignment);
                    }

                }

                $manager->flush();

                $page++;
                $resourcePath = 'merchants?pageSize=100&productUuid='.$productUuid.'&page='.$page;
                $res = $this->http->get($resourcePath);
                $merchants = $res['body'];
            }
        } else {
            foreach ($merchantUuids as $merchantUuid) {
                $resourcePath = 'merchants';
                $res = $this->http->get($resourcePath, $merchantUuid->uuid);
                $merchantFromApi = $res['body'];
                $handled = $this->handleMerchant($merchantFromApi, $dmc, $merchantRepo);
                if (!empty($handled)) {
                    $merchant = $handled['merchant'];
                    $merchantAssignment = $handled['merchantAssignment'];
                    $manager->persist($merchant);
                    $manager->persist($merchantAssignment);
                    $manager->flush();
                }
            }
        }
    }
}