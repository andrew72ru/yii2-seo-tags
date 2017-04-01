<?php


use andrew72ru\seotag\models\Seotag;
use andrew72ru\seotag\tests\fixtures\PagesFixture;
use andrew72ru\seotag\tests\fixtures\TagsFixtire;
use Codeception\Util\Locator;

class ViewMetaCest
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

    public function tryToViewMetatagsOnMainPage(FunctionalTester $I)
    {
        $fixture = $I->grabFixture('pages', 0);
        $I->amOnPage($fixture['full_url']);
        $I->see('Index');

        $I->seeElement(Locator::find('meta', [
            'name' => 'description'
        ]));
        $I->seeElement(Locator::find('meta', [
            'name' => 'keywords'
        ]));
    }
}
