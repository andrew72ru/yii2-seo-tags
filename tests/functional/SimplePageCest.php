<?php
use common\modules\seotag\fixtures\TagsFixtire;
use common\modules\seotag\models\Seotag;
use common\modules\seotag\models\SeotagKeywords;
use common\modules\seotag\tests\fixtures\PagesFixture;

/**
 * Тест для звгрузки и проверки тэгов и ключевых слов к ним
 *
 * Class SimplePageCest
 */
class SimplePageCest
{
    /**
     * Загрузка фикстур
     *
     * @param FunctionalTester $I
     */
    public function _before(FunctionalTester $I)
    {
        $I->haveFixtures(['pages' => [
            'class' => PagesFixture::className(),
            'dataFile' => codecept_data_dir() . 'pagesTemplate.php'
        ]]);

        $I->haveFixtures(['tags' => [
            'class' => TagsFixtire::className(),
            'dataFile' => codecept_data_dir() . '/keywordTemplate.php'
        ]]);

    }

    public function _after(FunctionalTester $I)
    {
    }

    /**
     * Убеждаемся, что запрись впринципе есть
     *
     * @param FunctionalTester $I
     */
    public function tryToTest(FunctionalTester $I)
    {
        $I->wantTo('See records');
        $I->seeRecord(Seotag::className(), ['url' => 'page0']);
    }

    /**
     * Находим к этой записи relation-ы и убеждаемся, что каждый из них – экхемпляр SeotagKeyworgs
     *
     * @param FunctionalTester $I
     */
    public function fingRelation(FunctionalTester $I)
    {
        $model = Seotag::find()->one();
        $I->expect($model instanceof Seotag);
        $keywords = $model->keywords;
        $I->expect($keywords !== 0);

        foreach ($keywords as $keyword)
        {
            $I->expect($keyword instanceof SeotagKeywords);
        }
    }
}
