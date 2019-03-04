<?php
namespace tests\api;

use backend\models\user\EduOcrTourist;
use backend\models\user\EduOcrUser;
use backend\modules\user\services\UserEntity;
use config\UserCacheKey;
use psservice\models\user\WxEduUser;
use tests\ApiTester;

class UserControllerCest
{
    private $token;

    public function _before(ApiTester $I)
    {
    }

    /**
     * 用户登录
     * @param ApiTester $I
     */
    public function actionLoginTest(ApiTester $I)
    {
        $mobile = '18510473853';
        $token = $I->amUserLogin($mobile);
        $I->sendCommandToRedis('select', 11);

        $userRedisInfo = $I->grabFromRedis(sprintf(UserCacheKey::USER_INFO, $token));
        $userRedisInfo = json_decode($userRedisInfo, 1);
        codecept_debug($userRedisInfo);

        $I->assertGreaterThan(0, $userRedisInfo['userId']);
        $I->assertEquals(0, $userRedisInfo['touristUserId']);
        $I->assertEquals(UserEntity::$roleMap['user'], $userRedisInfo['role']);
        $I->assertEquals($mobile, $userRedisInfo['mobile']);

        $I->assertLessOrEquals(10, (time() - strtotime($userRedisInfo['loginTime'])));

        $I->seeRecord(WxEduUser::className(), ['UserId' => $userRedisInfo['commonUserId']]);
    }

    /**
     * 游客登录
     * @param ApiTester $I
     */
    public function actionTouristLoginTest(ApiTester $I)
    {
        $token = $I->amTouristLogin('6CB7B05E-DE62-415A-96CF-6D57B666F939');
        $I->sendCommandToRedis('select', 11);

//        $userRedisInfo = $I->grabFromRedis(sprintf(UserCacheKey::TOURIST_INFO, $token)); // 机缘巧合下，没有使用 touristInfo 常量
        $userRedisInfo = $I->grabFromRedis(sprintf(UserCacheKey::USER_INFO, $token));
        $userRedisInfo = json_decode($userRedisInfo, 1);
        codecept_debug($userRedisInfo);

        $I->assertGreaterThan(0, $userRedisInfo['id']);


        $I->seeRecord(EduOcrTourist::className(), ['deviceId' => '6CB7B05E-DE62-415A-96CF-6D57B666F939']);
    }

    /**
     * 登出
     * @param ApiTester $I
     */
    public function actionLogoutTest(ApiTester $I)
    {
        $this->token = $I->amUserLogin('18510473853');
        $I->sendPOST('/user/user/logout', [
            'token' => $this->token,
            'deviceId' => '6CB7B05E-DE62-415A-96CF-6D57B666F939',
        ]);

        $I->seeResponseContainsJson(['code' => 99999]);
    }

    /**
     * 登录用户信息
     */
    public function actionInfoTest(ApiTester $I)
    {
        $mobile = '18510473853';
        $this->token = $I->amUserLogin($mobile);
        $I->sendGET('/user/user/info', [
            'token' => $this->token,
        ]);
        $ret = json_decode($I->grabResponse(), 1);
        $I->seeRecord(EduOcrUser::className(), ['id' => $ret['data']['userId'], 'mobile' => $mobile]);
    }
}
