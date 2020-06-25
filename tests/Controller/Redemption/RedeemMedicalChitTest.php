<?php

namespace App\Tests\Controller\Redemption;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RedeemMedicalChitTest extends WebTestCase
{
    public function testSomething()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
//        $this->assertSelectorTextContains('h1', 'Hello World');
    }
}
