<?php

namespace App\DataFixtures\BenefitProvider;

use App\DataFixtures\Organisation\OrganisationFixtures;
use App\Entity\BenefitProvider\BenefitProvider;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class BenefitProviderFixtures extends Fixture implements DependentFixtureInterface
{
    const BP_B2B_EMPLOYER = 'BP_B2B_EMPLOYER';

    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $b2bOrg = $this->getReference(OrganisationFixtures::ORG_B2B_EMPLOYER);
        $bp = $b2b = new BenefitProvider();
        $bp
            ->setUuid('15e6f99b961376')
            ->setName('B2B-EMPLOYER')
            ->setSlug('b2b-employer')
            ->setOrganisation($b2bOrg);

        $manager->persist($bp);
        $manager->flush();

        $this->addReference(self::BP_B2B_EMPLOYER, $b2b);
    }

    public function getDependencies()
    {
        return array(
            OrganisationFixtures::class,
        );
    }
}
