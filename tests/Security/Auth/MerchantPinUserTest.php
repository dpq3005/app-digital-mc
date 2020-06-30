<?php

namespace App\Tests\Security\Auth;

use App\Tests\Utils\Auth;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MerchantPinUserTest extends WebTestCase
{
    /**
     * @param $uuid
     * @param $pin
     * @return mixed
     */
    public function testLogin()
    {
        $client = static::createClient();
//        $crawler = $client->request('POST', '/merchant-pin-token', array(), [], ['HTTP_ACCEPT' => 'application/json', 'CONTENT_TYPE' => 'application/json'], sprintf('{"uuid":"%s","pin":"%s"}', $uuid, $pin));

        $token = Auth::getMerchantPinUserToken($client, 'demo-clinic', true);

        $this->assertResponseIsSuccessful();

        $jsonContent = $client->getResponse()->getContent();

        if (!empty($jsonContent)) {
            $res = json_decode($jsonContent);
            $token = $res->token;
        }

        $this->assertNotNull($jsonContent);
        $this->assertNotNull($token);

        return $token;
    }

    public static function merchantPinUserProvider()
    {
        return [
            'demo-clinic' => ['15e6f99ba28c82', '123456']
        ];
    }
}
