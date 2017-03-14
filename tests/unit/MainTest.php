<?php

use andrew72ru\seotag\Module;
use andrew72ru\seotag\widgets\metaTags;

class MainTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    public function testApplication()
    {
        $this->tester->assertInstanceOf(\yii\base\Application::className(), Yii::$app);
    }

    // tests
    public function testModuleIsLoadable()
    {
        $this->tester->assertInstanceOf(Module::className(), Yii::$app->getModule('seotag'));
    }

    public function testModuleHasUrlManager()
    {
        /** @var Module $module */
        $module = Yii::$app->getModule('seotag');
        $this->tester->assertInstanceOf(\yii\web\UrlManager::className(), $module->urlManagerComponent);

        $this->tester->assertNotNull($module->urlManagerComponent->createAbsoluteUrl(['/site/index']));

        Yii::$app->urlManager->showScriptName = false;
        Yii::$app->urlManager->enablePrettyUrl = true;
        $this->tester->assertEquals(Yii::$app->urlManager->createAbsoluteUrl(['/site/index']), $module->urlManagerComponent->createAbsoluteUrl(['/site/index']));

        $this->tester->amOnPage($module->urlManagerComponent->createAbsoluteUrl(['/seotag/main']));
        $this->tester->seeElement('div.box-body');
    }

    public function testWidgetIsLoadableAndHasModule()
    {
        $this->tester->wantTo('Test widget');
        $widget = new metaTags();
        $this->tester->assertInstanceOf('andrew72ru\seotag\widgets\metaTags', $widget);
        $this->tester->assertInstanceOf(Module::className(), $widget->module);
    }

    public function testTranslationsIsSet()
    {
        $this->tester->assertNotEmpty(Yii::$app->get('i18n')->translations);
        $this->tester->assertArrayHasKey('app.seotag*', Yii::$app->get('i18n')->translations);
    }
}