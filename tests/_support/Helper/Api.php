<?php
namespace tests\Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use Codeception\Module\REST;

class Api extends \Codeception\Module
{
    public function amUserLogin($mobile, $deviceId = '6CB7B05E-DE62-415A-96CF-6D57B666F939')
    {
        /**
         * @var $REST REST
         */
        $REST = $this->getModule('REST');
        $REST->sendPOST('/user/user/login', [
            'mobile' => $mobile,
            'code' => '7873',
            'source' => 'codeception',
            'version' => 111,
            'deviceId' => $deviceId,
        ]);
        $REST->seeResponseIsJson();
        $REST->seeResponseContainsJson(
            [
                'code' => 99999,
                'msg' => '成功',
            ]
        );
        $REST->seeResponseJsonMatchesJsonPath('$.data.token');
        $data = json_decode($REST->grabResponse(), true);
        $token = $data['data']['token'];
        codecept_debug($token);
        return $token;
    }

    public function amTouristLogin($deviceId)
    {
        /**
         * @var $REST REST
         */
        $REST = $this->getModule('REST');
        $REST->sendPOST('/user/user/tourist-login', [
            'deviceId' => $deviceId,
            'source' => 'codeception'
        ]);
        $REST->seeResponseIsJson();
        $REST->seeResponseContainsJson(
            [
                'code' => 99999,
                'msg' => '成功',
            ]
        );
        $REST->seeResponseJsonMatchesJsonPath('$.data.token');
        $data = json_decode($REST->grabResponse(), true);
        $token = $data['data']['token'];

        return $token;
    }
}
