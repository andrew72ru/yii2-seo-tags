<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 13.03.17
 * Time: 9:35
 */

namespace andrew72ru\seotag;

use yii\base\BootstrapInterface;
use yii\i18n\PhpMessageSource;

class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        if($app->hasModule('seotag') && ($module = $app->getModule('seotag') instanceof Module))
        {
            if (!isset($app->get('i18n')->translations['app.seotag*'])) {
                $app->get('i18n')->translations['app.seotag*'] = [
                    'class' => PhpMessageSource::className(),
                    'basePath' => __DIR__ . '/messages',
                    'sourceLanguage' => 'en-US'
                ];
            }

            if ($app instanceof \yii\console\Application)
            {
                $module->controllerNamespace = 'andrew72ru\seotag\commands';
            }
        }

    }
}