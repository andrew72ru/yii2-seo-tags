<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 10.03.17
 * Time: 15:02
 */

namespace andrew72ru\seotag\models;

use andrew72ru\seotag\traits\ModuleTrait;
use Intervention\Image\ImageManagerStatic as StaticImage;
use Intervention\Image\ImageManager;
use yii\base\Model;
use yii\helpers\FileHelper;
use Yii;

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

    public $large_url;
    public $small_url;

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
            $this->addError('large_url', $e->getMessage());
            Yii::info($e->getMessage(), 'Failed to save image in' . \yii\helpers\StringHelper::basename(__METHOD__));
            return null;
        }

        return $this->retUrl($id, 'big.jpg');
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
            $this->addError('small_url', $e->getMessage());
            Yii::info($e->getMessage(), 'Failed to save image in' . \yii\helpers\StringHelper::basename(__METHOD__));
            return null;
        }

        return $this->retUrl($id, 'small.jpg');
    }

    private function retUrl($id, $pict)
    {
        $imageUrl = $this->module->imageUrl;
        return $this->module->urlManagerComponent->createAbsoluteUrl([$imageUrl . '/' . $id . '/' . $pict]);
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

    /**
     * Возвращает base64-кодированную строку картинки
     *
     * @param int $id
     * @param string $name
     * @param int $w
     * @return \Intervention\Image\Image|null|string
     */
    public static function imagePreview($id, $name = 'big.jpg', $w = 150)
    {
        /** @var \andrew72ru\seotag\Module|\yii\base\Module $module */
        $module = Yii::$app->getModule('seotag');
        $model = Seotag::findOne($id);
        if($model === null)
            return null;

        $dir = Yii::getAlias($module->imagePath . DIRECTORY_SEPARATOR . $model->id);
        if(!is_file($dir . DIRECTORY_SEPARATOR . $name))
            return null;

        $file = $dir . DIRECTORY_SEPARATOR . $name;
        $h = (int) ($w / (self::B_WIDTH / self::B_HEIGHT));
        if($name !== 'big.jpg')
        {
            $h = (int)($w / (self::S_WIDTH / self::S_HEIGHT));
        }

        return StaticImage::make($file)->fit($w, $h)->encode('data-url');
    }
}