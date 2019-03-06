<?php
/**
 * Created by PhpStorm.
 * User: wudanyang
 * Date: 2019-01-25
 * Time: 20:42
 */

defined('APP_PATH') or define('APP_PATH', dirname(dirname(__FILE__)));
// 注册 Composer 自动加载器
// 包含 Yii 类文件

require(__DIR__ . '/../../psservice/libs/envFun.php');
require(__DIR__ . '/../../vendor/autoload.php');

// 环境变量配置
defined('YII_DEBUG') or define('YII_DEBUG', getYaconfEnv('YII_DEBUG'));
defined('YII_ENV') or define('YII_ENV', getYaconfEnv('YII_ENV'));
defined('YII_ENABLE_ERROR_HANDLER') or define('YII_ENABLE_ERROR_HANDLER', getYaconfEnv('YII_ENABLE_ERROR_HANDLER'));

require(__DIR__ . '/../../vendor/yiisoft/yii2/Yii.php');

// 暂时向下兼容老文件
require(__DIR__ . '/../../config/errorCode.php');
require(__DIR__ . '/../../config/constant.php');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('@psservice', dirname(dirname(__DIR__)) . '/psservice');
Yii::setAlias('@config', dirname(dirname(__DIR__)) . '/config');

$config = require(APP_PATH . '/../config/web.php');
