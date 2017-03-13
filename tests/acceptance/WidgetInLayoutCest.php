<?php


use andrew72ru\seotag\models\Seotag;
use andrew72ru\seotag\tests\fixtures\PagesFixture;
use andrew72ru\seotag\tests\fixtures\TagsFixtire;
use andrew72ru\seotag\widgets\metaTags;
use yii\helpers\Url;

class WidgetInLayoutCest
{
    /** @var Seotag $model */
    public $model;

    public function _before(AcceptanceTester $I)
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

        $this->model = Seotag::find()->orderBy(['id' => SORT_ASC])->one();
        $keywords = $I->grabFixture('tags');
        foreach ($keywords as $keyword)
            $this->model->inputKeywords = $keyword['word'];

    }

    public function _after(AcceptanceTester $I)
    {
    }

    // tests
    public function tryToViewMetaTags(AcceptanceTester $I)
    {
        Yii::$app->request->setBaseUrl('http://localhost:8080');
        Yii::$app->request->setScriptUrl('http://localhost:8080/index-test.php');
        Yii::$app->request->setHostInfo('http://localhost:8080');

        $page = $I->grabFixture('pages', 0);

        $I->seeRecord(Seotag::className(), [
            'url' => $page['url'],
        ]);
        /** @var Seotag $model */
        $model = Seotag::find()->orderBy(['id' => SORT_ASC])->one();
        $model->afterSave(false, []);
        $I->assertInstanceOf(Seotag::className(), $model);
        $I->assertNotNull($model->full_url);

        $I->amOnPage(Url::to('/', true));

        $widget = new metaTags();

//        $I->assertInstanceOf(metaTags::className(), $widget);
//        $I->seeElement('meta', ['name' => 'description']);
    }
}
