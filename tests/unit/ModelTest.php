<?php

use andrew72ru\seotag\models\Seotag;
use andrew72ru\seotag\models\SeotagKeywords;

class ModelTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->tester->haveFixtures([
            'pages' => [
                'class' => \andrew72ru\seotag\tests\fixtures\PagesFixture::className(),
                'dataFile' => codecept_data_dir() . 'pagesTemplate.php'
            ],
            'tags' => [
                'class' => \andrew72ru\seotag\tests\fixtures\TagsFixtire::className(),
                'dataFile' => codecept_data_dir() . 'keywordTemplate.php'
            ]
        ]);

        /** @var Seotag $model */
        $model = Seotag::find()->orderBy(['id' => SORT_ASC])->one();
        $keywords = $this->tester->grabFixture('tags');
        foreach ($keywords as $keyword)
            $model->inputKeywords[] = $keyword['word'];

        $this->tester->assertTrue($model->save(), $model->errors);
    }

    protected function _after()
    {
    }

    public function testCreateModel()
    {
        /** @var Seotag $model */
        $model = Seotag::find()->orderBy(['id' => SORT_ASC])->one();
        $this->tester->assertInstanceOf(Seotag::className(), $model);

        $keywords = $this->tester->grabFixture('tags');
        foreach ($keywords as $keyword)
        {
            $model->inputKeywords[] = $keyword['word'];
        }

        $this->tester->assertTrue($model->save());
        $this->tester->seeRecord(Seotag::className(), [
            'url' => $model->url
        ]);
        $this->tester->assertNotNull($model->full_url);
        $this->tester->seeRecord(Seotag::className(), [
            'full_url' => $model->full_url
        ]);
        $model->refresh();
        $this->tester->assertNotNull($model->big_image_url);
        $this->tester->assertNotNull($model->small_image_url);

        $this->tester->assertFileExists(Yii::getAlias('@tests/_envs/share/' . $model->id . '/big.jpg'));
        $this->tester->assertFileExists(Yii::getAlias('@tests/_envs/share/' . $model->id . '/small.jpg'));

        $this->tester->assertTrue(is_array($model->keywords));

        foreach ($model->keywords as $keywordModel)
        {
            $this->tester->assertInstanceOf(SeotagKeywords::className(), $keywordModel);
        }
    }

    public function testSetInputKeywords()
    {
        /** @var Seotag $model */
        $model = Seotag::find()->orderBy(['id' => SORT_ASC])->one();
        $model->afterSave(false, []);

        $this->tester->assertInstanceOf(Seotag::className(), $model);
        $this->tester->assertTrue(is_array($model->inputKeywords));

        $keywordsQuery = SeotagKeywords::find()
            ->leftJoin('{{%tag_to_keyword}}', SeotagKeywords::tableName() . '.`id` = {{%tag_to_keyword}}.`word_id`')
            ->where(['{{%tag_to_keyword}}.`tag_id`' => $model->id]);

        $this->tester->assertEquals($keywordsQuery->select('word')->column(), $model->inputKeywords);

    }

}