<?php


use andrew72ru\seotag\models\Seotag;
use andrew72ru\seotag\tests\fixtures\PagesFixture;
use andrew72ru\seotag\tests\fixtures\TagsFixtire;
use yii\helpers\Url;

class WidgetInLayoutCest
{
    public function _before(FunctionalTester $I)
    {
        $I->haveFixtures([
            'pages' => [
                'class' => PagesFixture::className(),
                'dataFile' => codecept_data_dir() . 'pagesTemplate.php'
            ],
            'tags' => [
                'class' => TagsFixtire::className(),
                'dataFile' => codecept_data_dir() . 'keywordTemplate.php'
            ]
        ]);

        /** @var Seotag $model */
        $model = Seotag::find()->orderBy(['id' => SORT_ASC])->one();
        $keywords = $I->grabFixture('tags');
        foreach ($keywords as $keyword)
            $model->inputKeywords[] = $keyword['word'];

        $model->save();
    }

    public function _after(FunctionalTester $I)
    {
    }

    public function tryToViewIndex(FunctionalTester $I)
    {
        $I->amOnPage(Url::toRoute(['/site/index']));
    }

    public function tryToLoadWidget(FunctionalTester $I)
    {
        /** @var andrew72ru\seotag\Module $module */
        $module = Yii::$app->getModule('seotag');

        $fullUrl = $module->urlManagerComponent->createAbsoluteUrl(['/site/index']);
        $I->seeRecord(Seotag::className(), [
            'full_url' => $fullUrl
        ]);

        $I->amOnPage($fullUrl);

        $meta = $I->grabFixture('pages', 0);

        $I->seeElement('meta', [
            'name' => 'description',
            'content' => $meta['description']
        ]);

    }
}
