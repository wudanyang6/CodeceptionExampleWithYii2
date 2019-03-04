<?php
namespace tests\Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I
use Codeception\Module\REST;

class RestApi extends \Codeception\Module
{
    public function amUserLogin($mobile)
    {
        /**
         * @var $REST REST
         */
        $REST = $this->getModule('REST');
        $REST->sendPOST('/user/user/login', [
            'mobile' => $mobile,
            'code' => '7873',
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
        codecept_debug($token);
        return $token;
    }

    public function amTouristLogin($deviceId)
    {
        /**
         * @var $REST RestApi
         */
        $REST = $this->getModule('REST');
        $REST->sendPOST('http://shark.heavi.cn:8213/user/user/login', [
            'deviceId' => '2B72D405-82FD-487D-9F82-E9D696C16708',
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
