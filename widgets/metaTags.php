<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 10.03.17
 * Time: 17:35
 */

namespace andrew72ru\seotag\widgets;


use andrew72ru\seotag\models\Seotag;
use andrew72ru\seotag\traits\ModuleTrait;
use yii\base\Widget;
use yii\helpers\Html;
use Yii;

class metaTags extends Widget
{
    use ModuleTrait;

    public $searchUrl = null;

    private $absoluteUrl;

    public function init()
    {
        parent::init();

        $this->findModel();
    }

    private function findModel()
    {
        $model = null;
        $this->absoluteUrl = null;

        if($this->searchUrl === null)
        {
            try {
                $this->searchUrl = Yii::$app->getRequest()->getAbsoluteUrl();
            } catch (\Exception $e) {}
        }

        if($this->searchUrl !== null)
        {
            try {
                $sourceUrl = parse_url($this->searchUrl);
                $this->absoluteUrl = $sourceUrl['scheme'] . '://' . $sourceUrl['host'] . ($sourceUrl['port'] !== 80 ? ':' . $sourceUrl['port'] : '') . $sourceUrl['path'];
            } catch (\Exception $e) {}
        }
        if($this->absoluteUrl !== null)
        {
            /** @var Seotag $model */
            $model = Seotag::find()->where(['like', 'full_url', $this->absoluteUrl])->one();
        }

        if(empty($model))
            $this->defaultTags();
        else
            $this->customTags($model);
    }

    private function defaultTags()
    {
        $this->registerMetaTag(['property' => 'og:locale', 'content' => 'ru_RU'], 'og:locale');
        $this->registerMetaTag(['property' => 'og:site_name', 'content' => Yii::$app->name], 'og:site_name');
        $this->registerMetaTag(['property' => 'og:url', 'content' => $this->absoluteUrl]);
        $this->registerMetaTag(['property' => 'og:type', 'content' => 'website'], 'og:type');

        $this->registerMetaTag(['property' => 'og:image:width', 'content' => '256'], 'og:image:width');
        $this->registerMetaTag(['property' => 'og:image:height', 'content' => '256'], 'og:image:height');

        $this->registerMetaTag(['property' => 'twitter:card', 'content' => 'summary'], 'twitter:card');
        $this->registerMetaTag(['property' => 'twitter:title', 'content' => $this->getView()->title], 'twitter:title');

        $this->getView()->registerLinkTag(['rel' => 'canonical', 'href' => $this->absoluteUrl]);
    }

    private function customTags(Seotag $model)
    {
        $this->registerMetaTag(['name' => 'description', 'content' => $model->description]);
        $this->registerMetaTag(['name' => 'keywords', 'content' => implode(', ', $model->inputKeywords)]);
        $this->registerMetaTag(['name' => 'og:description', 'content' => $model->description]);
        $this->registerMetaTag(['property' => 'og:locale', 'content' => 'ru_RU'], 'og:locale');
        $this->registerMetaTag(['property' => 'og:site_name', 'content' => Yii::$app->name], 'og:site_name');
        $this->registerMetaTag(['property' => 'og:url', 'content' => $model->full_url]);
        $this->registerMetaTag(['property' => 'og:type', 'content' => 'website'], 'og:type');

        if($model->big_image_url)
        {
            $this->registerMetaTag(['property' => 'og:image:width', 'content' => '1200'], 'og:image:width');
            $this->registerMetaTag(['property' => 'og:image:height', 'content' => '630'], 'og:image:height');

            $this->registerMetaTag(['property' => 'og:image', 'content' => $model->big_image_url]);
            $this->registerMetaTag(['property' => 'twitter:card', 'content' => 'summary_large_image'], 'twitter:card');
            $this->registerMetaTag(['property' => 'twitter:image', 'content' => $model->small_image_url], 'twitter:image');
        } else
        {
            $this->registerMetaTag(['property' => 'twitter:card', 'content' => 'summary'], 'twitter:card');
        }

        if(!empty($this->module->twitterUsername))
            $this->registerMetaTag(['property' => 'twitter:site', 'content' => $this->module->twitterUsername], 'twitter:site');

        $this->registerMetaTag(['property' => 'twitter:title', 'content' => $this->getView()->title], 'twitter:title');
        $this->registerMetaTag(['property' => 'twitter:description', 'content' => Html::decode($model->description)], 'twitter:description');

        $this->getView()->registerLinkTag(['rel' => 'canonical', 'href' => $model->full_url]);
    }

    private function registerMetaTag($options, $key = null)
    {
        $this->getView()->registerMetaTag($options, $key);
    }
}