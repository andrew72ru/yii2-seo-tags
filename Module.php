<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 01.03.17
 * Time: 10:04
 */

namespace andrew72ru\seotag;

/**
 * Class Module
 * @package common\modules\seotag
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'andrew72ru\seotag\controllers';
    public $defaultRoute = 'main';

    public function init()
    {
        parent::init();
    }
}