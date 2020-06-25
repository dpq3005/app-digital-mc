<?php

namespace App\Tests\Controller\Redemption;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RedeemMedicalChitTest extends WebTestCase
{
    public function testRedeem()
    {
        $client = static::createClient();
//        $crawler = $client->request('POST', '/supervisor-token', array(
//            'org-code' => 'B2b-employer',
//            'username' => 'b2bemployersup',
//            'password' => '123456'
//        ), [], [], 'org-code=B2b-employer&username=b2bemployersup&password=123456');

        $crawler = $client->request('POST', '/merchant-pin-token', array(), [], ['HTTP_ACCEPT' => 'application/json', 'CONTENT_TYPE' => 'application/json'], '{"uuid":"15e6f99ba28c82","pin":"123456"}');

        $this->assertResponseIsSuccessful();

        $jsonContent = $client->getResponse()->getContent();

        if (!empty($jsonContent)) {
            $res = json_decode($jsonContent);
            $token = $res->token;
        }

        $headers = ['HTTP_AUTHORIZATION' => 'Bearer '.$token, 'HTTP_ACCEPT' => 'application/json'];
        $crawler = $client->request('GET', '/digital-medical-chits', [], [], $headers);
        $this->assertResponseIsSuccessful();

        $jsonContent = $client->getResponse()->getContent();
        if (!empty($jsonContent)) {
            $dmcRes = json_decode($jsonContent);
            $dmc = $dmcRes[0];
        }

        $requestPayload = json_encode(['uuid' => $dmc->uuid, 'redeemedAtMerchantUuid' => '15e6f99ba28c82', 'pin' => '123456']);
        $crawler = $client->request('POST', "/digital-medical-chits/{$dmc->uuid}/redeem", [], [], array_merge(['CONTENT_TYPE' => 'application/json',
        ], $headers), $requestPayload);
        $this->assertResponseIsSuccessful();
        echo $client->getResponse()->getContent();

        $crawler = $client->request('GET', "/digital-medical-chits/{$dmc->uuid}", [], [], $headers);

        $jsonContent = $client->getResponse()->getContent();
        $dmc = json_decode($jsonContent);

        $this->assertEquals(true, $dmc->redeemed);

        echo $jsonContent;

//        {"uuid":"MC_5ef4a02e3d97c","redeemedAtMerchantUuid":"15e6f99ba28c82","pin":"123456"}


//        $this->assertSelectorTextContains('h1', 'Hello World');
    }
}
