<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 13.03.17
 * Time: 15:17
 */

namespace andrew72ru\seotag\traits;

/**
 * Class ModuleTrait
 * @property-read \andrew72ru\seotag\Module $module
 * @package andrew72ru\seotag
 */
trait ModuleTrait
{
    /**
     * @return \andrew72ru\seotag\Module|\yii\base\Module
     */
    public function getModule()
    {
        return \Yii::$app->getModule('seotag');
    }
}