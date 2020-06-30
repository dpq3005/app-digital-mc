<?php

namespace App\Tests\Controller\Redemption;

use App\Tests\Utils\Auth;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RedeemMedicalChitTest extends WebTestCase
{
    public function testRedeemNonExistentMedicalChitAsMerchant()
    {
        $client = static::createClient();
//        $crawler = $client->request('POST', '/supervisor-token', array(
//            'org-code' => 'B2b-employer',
//            'username' => 'b2bemployersup',
//            'password' => '123456'
//        ), [], [], 'org-code=B2b-employer&username=b2bemployersup&password=123456');

        $token = Auth::getMerchantPinUserToken($client, 'demo-clinic', false);
        $headers = ['HTTP_AUTHORIZATION' => 'Bearer '.$token, 'HTTP_ACCEPT' => 'application/json'];

        $requestPayload = json_encode(['uuid' => 'MC_NON_EXIST', 'redeemedAtMerchantUuid' => '15e6f99ba28c82', 'pin' => '123456']);

        $crawler = $client->request('POST', "/digital-medical-chits/MC_NON_EXIST/redeem", [], [], array_merge(['CONTENT_TYPE' => 'application/json',
        ], $headers), $requestPayload);
        $isNotFound = $client->getResponse()->isNotFound();
        $this->assertEquals(true, $isNotFound);
    }

    public function testRedeemAsMerchantDoctor()
    {
        $client = static::createClient();
//        $crawler = $client->request('POST', '/supervisor-token', array(
//            'org-code' => 'B2b-employer',
//            'username' => 'b2bemployersup',
//            'password' => '123456'
//        ), [], [], 'org-code=B2b-employer&username=b2bemployersup&password=123456');

        $token = Auth::getMerchantPinUserToken($client, 'demo-clinic', false);
        $headers = ['HTTP_AUTHORIZATION' => 'Bearer '.$token, 'HTTP_ACCEPT' => 'application/json'];

        $requestPayload = json_encode([
            'uuid' => 'MC_5ef4af6525a2c',
            'redeemedAtMerchantUuid' => '15e6f99ba28c82',
            'pin' => '123456',
            'redeemedByDoctorUuid' => 'DOC_123'
        ]);

        $crawler = $client->request('POST', "/digital-medical-chits/MC_5ef4af6525a2c/redeem", [], [], array_merge(['CONTENT_TYPE' => 'application/json',
        ], $headers), $requestPayload);

        $this->assertResponseIsSuccessful();

//        echo $client->getResponse()->getContent();

        $crawler = $client->request('GET', "/digital-medical-chits/MC_5ef4af6525a2c", [], [], $headers);

        $jsonContent = $client->getResponse()->getContent();
        $dmc = json_decode($jsonContent);

        $this->assertEquals(true, $dmc->redeemed);

//        echo $jsonContent;

//        {"uuid":"MC_5ef4a02e3d97c","redeemedAtMerchantUuid":"15e6f99ba28c82","pin":"123456"}

//        $this->assertSelectorTextContains('h1', 'Hello World');
    }

    public function testRedeemAsMerchant()
    {
        $client = static::createClient();
//        $crawler = $client->request('POST', '/supervisor-token', array(
//            'org-code' => 'B2b-employer',
//            'username' => 'b2bemployersup',
//            'password' => '123456'
//        ), [], [], 'org-code=B2b-employer&username=b2bemployersup&password=123456');

        $token = Auth::getMerchantPinUserToken($client, 'demo-clinic', false);
        $headers = ['HTTP_AUTHORIZATION' => 'Bearer '.$token, 'HTTP_ACCEPT' => 'application/json'];

        $requestPayload = json_encode(['uuid' => 'MC_5ef4af6525a2c', 'redeemedAtMerchantUuid' => '15e6f99ba28c82', 'pin' => '123456']);

        $crawler = $client->request('POST', "/digital-medical-chits/MC_5ef4af6525a2c/redeem", [], [], array_merge(['CONTENT_TYPE' => 'application/json',
        ], $headers), $requestPayload);

        $this->assertResponseIsSuccessful();

//        echo $client->getResponse()->getContent();

        $crawler = $client->request('GET', "/digital-medical-chits/MC_5ef4af6525a2c", [], [], $headers);

        $jsonContent = $client->getResponse()->getContent();
        $dmc = json_decode($jsonContent);

        $this->assertEquals(true, $dmc->redeemed);

//        echo $jsonContent;

//        {"uuid":"MC_5ef4a02e3d97c","redeemedAtMerchantUuid":"15e6f99ba28c82","pin":"123456"}

//        $this->assertSelectorTextContains('h1', 'Hello World');
    }
}