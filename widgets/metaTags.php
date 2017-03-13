<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 10.03.17
 * Time: 17:35
 */

namespace andrew72ru\seotag\widgets;


use common\models\CompanyData;
use common\modules\seotag\models\Seotag;
use yii\base\Widget;
use yii\helpers\Url;
use yii\helpers\Html;
use Yii;

class metaTags extends Widget
{
    public function init()
    {
        parent::init();
        $this->findModel();
    }

    private function findModel()
    {
        $sourceUrl = parse_url(Yii::$app->request->absoluteUrl);
        $url = $sourceUrl['scheme'] . '://' . $sourceUrl['host'] . $sourceUrl['path'];

        /** @var Seotag $model */
        $model = Seotag::find()->where(['like', 'full_url', $url])->one();
        if(empty($model))
            $this->defaultTags();
        else
            $this->customTags($model);
    }

    private function defaultTags()
    {
        /** @var CompanyData $company */
        $company = CompanyData::find()->orderBy(['id' => SORT_DESC])->one();

        $this->registerMetaTag(['name' => 'description', 'content' => $company->meta_description]);
        $this->registerMetaTag(['name' => 'keywords', 'content' => $company->meta_keywords]);
        $this->registerMetaTag(['name' => 'og:description', 'content' => $company->meta_description]);
        $this->registerMetaTag(['property' => 'og:locale', 'content' => 'ru_RU'], 'og:locale');
        $this->registerMetaTag(['property' => 'og:site_name', 'content' => Yii::$app->name], 'og:site_name');
        $this->registerMetaTag([
            'property' => 'og:image',
            'content' => Yii::$app->urlManagerFrontend->createAbsoluteUrl(['/static/icon-256.png'])
        ]);
        $this->registerMetaTag(['property' => 'og:url', 'content' => Yii::$app->request->absoluteUrl]);
        $this->registerMetaTag(['property' => 'og:type', 'content' => 'website'], 'og:type');

        $this->registerMetaTag(['property' => 'og:image:width', 'content' => '256'], 'og:image:width');
        $this->registerMetaTag(['property' => 'og:image:height', 'content' => '256'], 'og:image:height');

        $this->registerMetaTag(['property' => 'twitter:card', 'content' => 'summary'], 'twitter:card');
        $this->registerMetaTag(['property' => 'twitter:site', 'content' => '@myroom72'], 'twitter:site');
        $this->registerMetaTag(['property' => 'twitter:title', 'content' => $this->getView()->title], 'twitter:title');
        $this->registerMetaTag(['property' => 'twitter:description', 'content' => Html::decode($company->meta_description)], 'twitter:description');
        $this->registerMetaTag(['property' => 'twitter:image', 'content' => Yii::$app->urlManagerFrontend->createAbsoluteUrl(['/static/icon-150.jpg'])], 'twitter:image');

        $this->getView()->registerLinkTag(['rel' => 'canonical', 'href' => Yii::$app->request->absoluteUrl]);
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

        $this->registerMetaTag(['property' => 'twitter:site', 'content' => '@myroom72'], 'twitter:site');
        $this->registerMetaTag(['property' => 'twitter:title', 'content' => $this->getView()->title], 'twitter:title');
        $this->registerMetaTag(['property' => 'twitter:description', 'content' => Html::decode($model->description)], 'twitter:description');

        $this->getView()->registerLinkTag(['rel' => 'canonical', 'href' => $model->full_url]);
    }

    private function registerMetaTag($options, $key = null)
    {
        $this->getView()->registerMetaTag($options, $key);
    }
}