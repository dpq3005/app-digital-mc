<?php

namespace App\DataFixtures\Dmc;

use App\DataFixtures\BenefitProvider\BenefitProviderFixtures;
use App\Dto\DigitalMedicalChit;
use App\Entity\Dmc\MedicalChit;
use App\Message\Dmc\CreateDmc;
use App\Service\Merchant\MerchantAssignor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Messenger\MessageBusInterface;

class MedicalChitFixtures extends Fixture implements DependentFixtureInterface
{
    private $bus, $merchantAssignor;

    public function __construct(MessageBusInterface $bus, MerchantAssignor $merchantAssignor)
    {
        $this->bus = $bus;
        $this->merchantAssignor = $merchantAssignor;
    }

    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        /**
         * {"beneficiaryNric='024290123$beneficiaryName='Binh 01  Le 001$product='15e6f99ba31269$benefitProduct='15e6f99b961b97$merchants":["15e6f99ba28c82"],"telemedEnabled":false}
         */
        $beneficiaryNric = '024290123';
        $beneficiaryName = 'Binh 01  Le 001';
        $productUuid = '15e6f99ba31269';
        $benefitProductUuid = '15e6f99b961b97';
        $merchantUuids = ["15e6f99ba28c82"];
        $telemedEnabled = false;

        $dmc = $b2bDmc = CreateDmc::newInstance($beneficiaryNric, $beneficiaryName, $productUuid, $benefitProductUuid, $merchantUuids, $telemedEnabled);
        $dmc->benefitProviderUuid = $this->getReference(BenefitProviderFixtures::BP_B2B_EMPLOYER)->getUuid();

        $this->bus->dispatch($dmc);
        $this->merchantAssignor->initialiseMerchantAssignment();
    }

    public function getDependencies()
    {
        return [BenefitProviderFixtures::class];
    }
}
