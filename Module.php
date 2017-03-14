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

    public $urlManager = '\yii\web\UrlManager';
    public $baseUrl = null;
    public $twitterUsername = '';
    public $imagePath = '@webroot/assets/share';
    public $imageUrl = '/share';

    public function init()
    {
        parent::init();

        if(!class_exists($this->urlManager) || !((new $this->urlManager) instanceof UrlManager))
            throw new InvalidConfigException('Module::urlManager must be instanse of yii\web\UrlManager');

        if($this->baseUrl === null)
            $this->baseUrl = \Yii::$app->request->hostInfo;
    }

    /**
     * @return \yii\web\UrlManager
     */
    public function getUrlManagerComponent()
    {
        $urlManager = new $this->urlManager([
            'baseUrl' => $this->baseUrl,
            'enablePrettyUrl' => YII_TEST ? false : \Yii::$app->urlManager->enablePrettyUrl,
            'showScriptName' => YII_TEST ? false : \Yii::$app->urlManager->enablePrettyUrl,
            'rules' => \Yii::$app->urlManager->rules,
        ]);
        return $urlManager;
    }
}