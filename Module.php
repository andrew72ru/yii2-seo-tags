<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 01.03.17
 * Time: 10:04
 */

namespace andrew72ru\seotag;

use yii\base\InvalidConfigException;
use yii\web\UrlManager;

/**
 * Class Module
 * @property-read UrlManager urlManagerComponent
 * @package common\modules\seotag
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'andrew72ru\seotag\controllers';
    public $defaultRoute = 'main';

    public $urlManager = 'urlManager';
    public $twitterUsername = '';
    public $imagePath = '@webroot/assets/share';
    public $imageUrl = '/share';

    public function init()
    {
        parent::init();

        if(!((\Yii::$app->{$this->urlManager}) instanceof UrlManager))
            throw new InvalidConfigException('Module::urlManager must be instanse of yii\web\UrlManager');

    }

    /**
     * @return \yii\web\UrlManager
     */
    public function getUrlManagerComponent()
    {
        return \Yii::$app->{$this->urlManager};
    }
}