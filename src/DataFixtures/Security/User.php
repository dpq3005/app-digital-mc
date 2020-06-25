<?php

namespace App\DataFixtures\Security;

use App\DataFixtures\Organisation\OrganisationFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class User extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $b2bOrg = $this->getReference(OrganisationFixtures::ORG_B2B_EMPLOYER);
        $user = $b2buser = new \App\Entity\Security\User();
        $user->setOrganisation($b2bOrg)
            ->setPlainPassword('123456')
            ->setUsername('b2bemployersup')
            ->setRoles(["ROLE_SUPERVISOR"])
            ->setUuid('5ea1d1c1e5963');

        $manager->persist($user);
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            OrganisationFixtures::class,
        );
    }
}
