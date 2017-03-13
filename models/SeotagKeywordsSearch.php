<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 06.03.17
 * Time: 11:28
 */

namespace andrew72ru\seotag\models;

use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;


/**
 * Поиск по ключевым словам
 *
 * Class SeotagKeywordsSearch
 * @package common\modules\seotag\models
 */
class SeotagKeywordsSearch extends SeotagKeywords
{
    public $urlSearch;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ArrayHelper::merge(parent::attributes(), [
                'urlSearch'
            ]), 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'urlSearch' => \Yii::t('app.seotag', 'Uses in urls')
        ]);
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params = [])
    {
        $query = SeotagKeywords::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        if(!$this->load($params))
            return $dataProvider;

        $query->leftJoin('{{%tag_to_keyword}}', self::tableName() . '.`id` = {{%tag_to_keyword}}.`word_id`');
        $query->leftJoin(Seotag::tableName(), '{{%tag_to_keyword}}.`tag_id` = ' . Seotag::tableName() . '.`id`');

        $dataProvider->sort = [
            'defaultOrder' => ['word' => SORT_ASC],
            'attributes' => [
                'word',
                'urlSearch' => [
                    'asc' => [Seotag::tableName() . '.`full_url`' => SORT_ASC],
                    'desc' => [Seotag::tableName() . '.`full_url`' => SORT_DESC],
                ]
            ]
        ];

        $query->andFilterWhere(['like', Seotag::tableName() . '.`full_url`', $this->urlSearch]);
        $query->andFilterWhere(['like', 'word', $this->word]);

        ddd($query->createCommand()->rawSql);

        return $dataProvider;
    }
}