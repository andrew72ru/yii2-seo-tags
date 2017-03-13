<?php


class RecordExistsCest
{
    public function _before(AcceptanceTester $I)
    {

        $I->haveFixtures(['pages' => [
            'class' => \common\modules\seotag\tests\fixtures\PagesFixture::className(),
            'dataFile' => codecept_data_dir() . 'pagesTemplate.php'
        ]]);

        $I->haveFixtures(['tags' => [
            'class' => \common\modules\seotag\fixtures\TagsFixtire::className(),
            'dataFile' => codecept_data_dir() . '/keywordTemplate.php'
        ]]);

    }

    public function _after(AcceptanceTester $I)
    {
    }

    public function tryToTest(AcceptanceTester $I)
    {

    }
}
