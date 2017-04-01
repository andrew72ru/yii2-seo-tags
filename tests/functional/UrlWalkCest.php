<?php


use yii\helpers\Url;

class UrlWalkCest
{
    public function _before(FunctionalTester $I)
    {
    }

    public function _after(FunctionalTester $I)
    {
    }

    /**
     * @param FunctionalTester $I
     */
    public function tryToModuleUrl(FunctionalTester $I)
    {
        $I->amOnPage(Url::toRoute(['/seotag/main']));
        $I->seeElement('div.box-body');
    }

    /**
     * @param FunctionalTester $I
     */
    public function tryToKeywordsUrl(FunctionalTester $I)
    {
        $I->amOnPage(Url::toRoute(['/seotag/keywords']));
        $I->seeElement('div.box-body');
    }

    public function tryToIndexUrl(FunctionalTester $I)
    {
        $I->amOnPage(Url::toRoute(['/site/index']));
        $I->see('Index');
    }
}
