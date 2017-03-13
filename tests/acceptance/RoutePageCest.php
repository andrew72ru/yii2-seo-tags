<?php


class RoutePageCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    // tests
    public function tryToTest(AcceptanceTester $I)
    {
        $I->wantTo('Module Page');
        $I->amOnUrl(\yii\helpers\Url::toRoute(['/seotag/main'], true));
    }
}
