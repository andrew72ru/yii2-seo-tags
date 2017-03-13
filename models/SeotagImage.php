<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 10.03.17
 * Time: 15:02
 */

namespace andrew72ru\seotag\models;

use andrew72ru\seotag\traits\ModuleTrait;
use Intervention\Image\ImageManager;
use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;

/**
 * Манипуляции с картинками
 *
 * Class SeotagImage
 * @package common\modules\seotag\models
 */
class SeotagImage extends Model
{
    use ModuleTrait;

    /** Ширина большой картинки */
    const B_WIDTH = '1200';
    /**  Высота большой картинки */
    const B_HEIGHT = '630';
    /** Ширина маленькой картинки */
    const S_WIDTH = '600';
    /** Высота маленькой картинки */
    const S_HEIGHT = '315';
    /** @var  ImageManager */
    private $manager;

    private $dir;
    private $url;

    public function init()
    {
        parent::init();

        $this->dir = Yii::getAlias($this->module->imagePath);
        if(!is_dir($this->dir))
            FileHelper::createDirectory($this->dir);

        $this->url = $this->module->imageUrl;

        $this->manager = new ImageManager(['driver' => 'imagick']);
    }

    /**
     * @param string $url
     * @param integer $id
     *
     * @return null|string
     */
    public function saveBig($url, $id)
    {
        try
        {
            $this->manager->make($url)->fit(self::B_WIDTH, self::B_HEIGHT)
                ->save($this->createDir($id) . DIRECTORY_SEPARATOR . 'big.jpg');
        } catch (\Exception $e)
        {
            return null;
        }

        return $this->module->urlManagerComponent->createAbsoluteUrl([rtrim($this->url, '/') . '/' . $id . '/big.jpg']);
    }

    /**
     * @param string $url
     * @param integer $id
     *
     * @return null|string
     */
    public function saveSmall($url, $id)
    {
        try {
            $this->manager->make($url)->fit(self::S_WIDTH, self::S_HEIGHT)
                ->save($this->createDir($id) . DIRECTORY_SEPARATOR . 'small.jpg');
        } catch (\Exception $e)
        {
            return null;
        }

        return $this->module->urlManagerComponent->createAbsoluteUrl([rtrim($this->url, '/') . '/' . $id . '/small.jpg']);
    }

    /**
     * @param integer $id
     *
     * @return bool|string
     */
    private function createDir($id)
    {
        $dir = Yii::getAlias($this->dir . DIRECTORY_SEPARATOR . $id);
        if(!is_dir($dir))
            FileHelper::createDirectory($dir);
        return $dir;
    }
}