<?php

namespace App\Tests\Controller\Redemption;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RedeemMedicalChitTest extends WebTestCase
{
    public function testRedeem()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/digital-medical-chits/{id}/redeem');

        $this->assertResponseIsSuccessful();
//        $this->assertSelectorTextContains('h1', 'Hello World');
    }
}
