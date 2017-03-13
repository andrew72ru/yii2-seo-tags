<?php

namespace andrew72ru\seotag\models;

use andrew72ru\seotag\traits\ModuleTrait;
use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "{{%seotag}}".
 *
 * @property integer $id
 * @property string $url
 * @property string $small_pict
 * @property string $large_pict
 * @property string $description
 * @property string $full_url
 * @property string $big_image_url
 * @property string $small_image_url
 * @property SeotagKeywords[] keywords
 */
class Seotag extends \yii\db\ActiveRecord
{
    use ModuleTrait;
    public $inputKeywords;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%seotag}}';
    }

    /**
     * Установка ключевых слов обратно в форму
     */
    public function afterFind()
    {
        parent::afterFind();
        $this->inputKeywords = $this->getKeywords()
            ->select('word')->column();
    }

    /**
     * Сохранение переданных ключевых слов
     *
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert))
        {
            return true;
        }

        return false;
    }

    /**
     * Сохранение ключевых слов, формирование полного url-а
     * сохранение картинок с заданными размерами
     *
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $keywordIds = $this->getKeywords()->select('id')->column();
        foreach ($keywordIds as $keyword_id)
            $this->unlink('keywords', SeotagKeywords::findOne($keyword_id), false);

        if(!is_array($this->inputKeywords))
            $this->inputKeywords = [];

        foreach ($this->inputKeywords as $inputKeyword)
        {
            $inputKeyword = trim($inputKeyword);
            $keyword = SeotagKeywords::findOne(['word' => $inputKeyword]);
            if($keyword === null)
                $keyword = new SeotagKeywords(['word' => $inputKeyword]);

            if($keyword->save())
                $this->link('keywords', $keyword);
        }

        $this->updateAttributes([
            'full_url' => $this->module->urlManagerComponent->createAbsoluteUrl($this->url)
        ]);

        $imageModel = new SeotagImage();
        $this->updateAttributes([
            'big_image_url' => $imageModel->saveBig($this->large_pict, $this->id),
            'small_image_url' => $imageModel->saveSmall($this->small_pict, $this->id),
        ]);

        if($imageModel->hasErrors('large_url'))
            $this->addError('large_pict', $imageModel->getErrors('large_url'));

        if($imageModel->hasErrors('small_url'))
            $this->addError('small_pict', $imageModel->getErrors('small_url'));

    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['url', 'required'],
            [['url', 'full_url'], 'unique'],
            [['description'], 'string'],
            [['url', 'small_pict', 'large_pict', 'big_image_url', 'small_image_url'], 'string', 'max' => 255],
            ['inputKeywords', 'safe']
        ];
    }

    /**
     * @param null|string $url
     */
    public function setCurrentMeta($url = null)
    {
        if($url !== null)
        {
            $currentMeta = get_meta_tags($this->module->urlManagerComponent->createAbsoluteUrl($url));

            if(array_key_exists('description', $currentMeta))
                $this->description = $currentMeta['description'];

            if(array_key_exists('keywords', $currentMeta))
                $this->inputKeywords = $currentMeta['keywords'];
        }
    }

    /**
     * Возвращает весь контент целевой страницы
     *
     * @return string
     */
    public function getTargetPage()
    {
        $absoluteUrl = $this->module->urlManagerComponent->createAbsoluteUrl($this->url);
        return self::getPage($absoluteUrl);
    }

    /**
     * @param $url
     * @return string
     */
    public static function getPage($url)
    {
        $resURL = curl_init();
        curl_setopt($resURL, CURLOPT_URL, $url);
        curl_setopt($resURL, CURLOPT_FAILONERROR, 1);
        curl_setopt($resURL, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($resURL, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($resURL, CURLOPT_HEADER, false);
        curl_setopt($resURL, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($resURL, CURLOPT_AUTOREFERER, true);
        curl_setopt($resURL, CURLOPT_CONNECTTIMEOUT, 120);
        curl_setopt($resURL, CURLOPT_TIMEOUT, 120);
        curl_setopt($resURL, CURLOPT_MAXREDIRS, 10);

        $content = curl_exec( $resURL );
        $header  = curl_getinfo( $resURL );
        curl_close($resURL);

        if($header['http_code'] !== 200)
            return Html::tag('p', Yii::t('app.seotag', 'Warning! This page not exists!'), ['class' => 'text-danger']);

        return $content;
    }

    /**
     * Заголовок страницы со ссылкой
     *
     * @return string
     */
    public function getPageTitle()
    {
        $document = \phpQuery::newDocument($this->getTargetPage());
        $title = $document->find('title')->text();
        return Html::a($title, $this->module->urlManagerComponent->createAbsoluteUrl($this->url), ['target' => '_blank']);
    }

    /**
     * Функция, извлекающая картинки со страницы
     *
     * @param \search $url абсолютный url целевой страницы
     * @return array|string
     */
    public static function getImages($url)
    {
        $document = \phpQuery::newDocument(self::getPage($url));
        $imgs = $document->find('section[role="main"]')->find('img');

        $result = [];
        foreach ($imgs as $img)
            $result[] = $img->getAttribute('src');

        return $result;
    }

    /**
     * Проверка существования url
     *
     * @param string $url
     * @return bool
     */
    public static function checkUrlExists($url)
    {
        try {
            $headers = get_headers($url);
            if(!$headers || $headers[0] == 'HTTP/1.1 404 Not Found')
                return false;
            else
                return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @param string $url
     *
     * @return string
     */
    public static function mainFromPreview($url)
    {
        $mainUrl = str_replace('preview.jpg', 'main.jpg', $url);
        if(self::checkUrlExists($url))
            return $mainUrl;

        return $url;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app.seotag', 'ID'),
            'url' => Yii::t('app.seotag', 'Url'),
            'small_pict' => Yii::t('app.seotag', 'Small Picture'),
            'large_pict' => Yii::t('app.seotag', 'Large Picture'),
            'keywords' => Yii::t('app.seotag', 'Keywords'),
            'description' => Yii::t('app.seotag', 'Description'),
            'inputKeywords' => Yii::t('app.seotag', 'Keywords'),
            'big_image_url' => Yii::t('app.seotag', 'Large Picture'),
            'small_image_url' => Yii::t('app.seotag', 'Small Picture'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeHints()
    {
        return [
            'description' => Yii::t('app.seotag', 'No more 255 words, please'),
        ];
    }

    /**
     * @return \yii\db\Query
     */
    public function getKeywords()
    {
        return $this->hasMany(SeotagKeywords::className(), ['id' => 'word_id'])
            ->viaTable('{{%tag_to_keyword}}', ['tag_id' => 'id'])->inverseOf('tags');
    }

    /**
     * @return bool
     */
    public function checkIsEmpty()
    {
        return !self::find()->exists();
    }
}
