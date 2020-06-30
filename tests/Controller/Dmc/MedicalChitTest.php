<?php

namespace App\Tests\Controller\Dmc;

use App\Tests\Utils\Auth;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MedicalChitTest extends WebTestCase
{
    /**
     * @dataProvider medicalChitProvider
     */
    public function testMerchantFindAll($uuid)
    {
        $client = static::createClient();
//        $crawler = $client->request('POST', '/supervisor-token', array(
//            'org-code' => 'B2b-employer',
//            'username' => 'b2bemployersup',
//            'password' => '123456'
//        ), [], [], 'org-code=B2b-employer&username=b2bemployersup&password=123456');

        $token = Auth::getMerchantPinUserToken($client, 'demo-clinic', false);

        $headers = ['HTTP_AUTHORIZATION' => 'Bearer '.$token, 'HTTP_ACCEPT' => 'application/json'];
        $crawler = $client->request('GET', '/digital-medical-chits', [], [], $headers);
        $this->assertResponseIsSuccessful();

        $jsonContent = $client->getResponse()->getContent();
        if (!empty($jsonContent)) {
            $dmcRes = json_decode($jsonContent);
            $dmc = $dmcRes[0];
        }
        $this->assertEquals('MC_5ef4af6525a2c', $dmc->uuid);
    }

    public static function medicalChitProvider()
    {
        return [
            'demo-dmc' => ['MC_5ef4af6525a2c']
        ];
    }
}
