<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 13.03.17
 * Time: 9:35
 */

namespace andrew72ru\seotag;

use yii\base\BootstrapInterface;
use Yii;

class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        if (!isset($app->get('i18n')->translations['app.seotag*'])) {
            $app->get('i18n')->translations['app.seotag*'] = [
                'class' => PhpMessageSource::className(),
                'basePath' => __DIR__ . '/messages',
                'sourceLanguage' => 'en-US'
            ];
        }
    }
}