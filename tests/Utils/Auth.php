<?php

namespace App\Tests\Utils;

use App\Tests\Security\Auth\MerchantPinUserTest;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class Auth
{
    public static $merchantPinUserToken = [];

    public static function getMerchantPinUserToken(KernelBrowser $client, $merchantCode, $forced = false)
    {
        if ($forced || !array_key_exists($merchantCode, self::$merchantPinUserToken)) {
            $merchantPinUsers = MerchantPinUserTest::merchantPinUserProvider();
            $demoClinic = $merchantPinUsers['demo-clinic'];
            $uuid = $demoClinic[0];
            $pin = $demoClinic[1];

            $crawler = $client->request('POST', '/merchant-pin-token', array(), [], ['HTTP_ACCEPT' => 'application/json', 'CONTENT_TYPE' => 'application/json'], sprintf('{"uuid":"%s","pin":"%s"}', $uuid, $pin));
            $jsonContent = $client->getResponse()->getContent();
            if (!empty($jsonContent)) {
                $res = json_decode($jsonContent);
                $token = $res->token;
            }
            $merchantPinUserToken = $token;
        }
        return $merchantPinUserToken;
    }
}