<?php


use andrew72ru\seotag\models\Seotag;
use andrew72ru\seotag\models\SeotagKeywords;
use andrew72ru\seotag\tests\fixtures\PagesFixture;
use andrew72ru\seotag\tests\fixtures\TagsFixtire;
use Codeception\Util\Locator;
use yii\helpers\Html;
use yii\helpers\Url;

class ShowViewsCest
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

    public function tryToViewSeotagIndex(FunctionalTester $I)
    {
        /** @var Seotag $model */
        $model = Seotag::find()->orderBy(['id' => SORT_ASC])->one();
        $I->amOnPage(Url::toRoute(['/seotag/main']));
        $I->seeElement('table');
        $I->see($model->full_url);
    }

    public function tryToUpdateSeotagPage(FunctionalTester $I)
    {
        /** @var Seotag $model */
        $model = Seotag::find()->orderBy(['id' => SORT_ASC])->one();
        $I->amOnPage(Url::toRoute(['/seotag/main']));
        $I->seeElement('a', ['href' => Url::to(['update', 'id' => $model->id])]);

        $I->amOnPage(Url::to(['update', 'id' => $model->id]));
        $I->see(Yii::t('app.seotag', 'Update seotag for page {page}', ['page' => $model->url]));
        $I->seeElement('form', ['id' => 'seo-tag-form']);
        $I->seeInField(['name' => Html::getInputName($model, 'url')], $model->url);
    }

    public function tryToDeleteCreatedModel(FunctionalTester $I)
    {
        $exist = $I->grabFixture('pages', 0);
        $I->assertArrayHasKey( 'url', $exist);
        $model = new Seotag($exist);
        $I->assertInstanceOf(Seotag::className(), $model);

        $I->seeRecord($model::className(), [
            'description' => $model->description
        ]);

        $I->amOnPage(Url::to(['/seotag/main']));
        $deleteLink = Locator::href(Url::to(['/seotag/main/delete', 'id' => $model->id]));
        $I->click($deleteLink);

        $I->sendAjaxPostRequest(Url::to(['/seotag/main/delete', 'id' => $model->id]), []);
        $I->dontSeeRecord($model::className(), [
            'description' => $model->description
        ]);
    }

    public function tryToCreateSeotagPage(FunctionalTester $I)
    {
        $model = new Seotag();

        $I->amOnPage(Url::to(['/seotag/main/create']));
        $I->see(Yii::t('app.seotag', 'Create seotag for a new page'));

        $I->seeElement('form', ['id' => 'seo-tag-form']);
        $I->submitForm('#seo-tag-form', [
            $model->formName() => [
                'url' => '/some-url',
                'description' => 'Test description',
                'small_pict' => 'http://localhost:8080/17.jpg',
                'large_pict' => 'http://localhost:8080/17.jpg',
                'inputKeywords' => [
                    'keyword1',
                    'keyword2',
                ]
            ]
        ]);
        $I->dontSeeElement('.has-error');

        $I->seeRecord($model::className(), [
            'description' => 'Test description'
        ]);
        $I->seeRecord(SeotagKeywords::className(), [
            'word' => 'keyword1'
        ]);
    }

    public function tryToDeleteWithGet(FunctionalTester $I)
    {
        /** @var Seotag $model */
        $model = Seotag::find()->orderBy(['id' => SORT_ASC])->one();

        $I->amOnPage(Url::to(['/seotag/main/delete', 'id' => $model->id]));
        $I->see('Method Not Allowed');
    }

    public function tryToViewKeywordsIndex(FunctionalTester $I)
    {
        $I->amOnPage(Url::to(['/seotag/main']));
        $I->click(Yii::t('app.seotag', 'Keywords'));
        $I->see(Yii::t('app.seotag', 'Keywords'));

        $tags = $I->grabFixture('tags');
        foreach ($tags as $tag)
            $I->see($tag['word']);
    }

    public function tryToUpdateKeyword(FunctionalTester $I)
    {
        /** @var SeotagKeywords $model */
        $model = SeotagKeywords::find()->orderBy(['id' => SORT_DESC])->one();

        $url = Url::to(['/seotag/keywords/update', 'id' => $model->id]);
        $I->amOnPage($url);
        $I->seeElement('form', ['action' => $url]);
        $field = Locator::find('input', ['id' => Html::getInputId($model, 'word')]);
        $I->seeInField($field, $model->word);

        $I->fillField($field, 'new keyword value');
        $I->click(Yii::t('app', 'Save'));

        $I->seeRecord($model::className(), [
            'id' => $model->id,
            'word' => 'new keyword value',
        ]);

        $I->amOnPage(Url::to(['/seotag/keywords']));
        $I->see('new keyword value');
    }
}
