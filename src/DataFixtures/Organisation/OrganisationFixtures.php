<?php

namespace App\DataFixtures\Organisation;

use App\Entity\Organisation\Organisation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class OrganisationFixtures extends Fixture
{
    const ORG_B2B_EMPLOYER = 'ORG_B2B_EMPLOYER';

    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $org = $b2b = new Organisation();
        $org->setUuid('5e820ef18a3ed')
            ->setName('B2B-EMPLOYER')
            ->setSlug('b2b-employer')
            ->setLegacyId(8)
            ->setCode('B2B-EMPLOYER')
            ->setSupervisorCode('B2B-EMPLOYER')
        ;

        $manager->persist($org);
        $manager->flush();

        $this->addReference(self::ORG_B2B_EMPLOYER, $b2b);

    }
}
