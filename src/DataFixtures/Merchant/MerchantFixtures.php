<?php

namespace App\DataFixtures\Merchant;

use App\Entity\Merchant\Merchant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MerchantFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $merchant = $demoClinic = new Merchant();
        $merchant
            ->setUuid('15e6f99ba28c82')
            ->setName('DEMO Clinic')
            ->setSlug('demo-clinic')
            ->setEnabled(true);
        $manager->persist($merchant);
        $manager->flush();
    }
}
