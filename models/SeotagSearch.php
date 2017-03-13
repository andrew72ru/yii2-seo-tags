<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 06.03.17
 * Time: 10:47
 */

namespace andrew72ru\seotag\models;

use yii\data\ActiveDataProvider;

/**
 * Поиск по таблице тэгов
 *
 * Class SeotagSearch
 * @package common\modules\seotag\models
 */
class SeotagSearch extends Seotag
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [parent::attributes(), 'safe']
        ];
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params = [])
    {
        $query = Seotag::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        if(!$this->load($params))
            return $dataProvider;

        $query->andFilterWhere(['like', 'url', $this->url]);
        $query->andFilterWhere(['like', 'small_pict', $this->small_pict]);
        $query->andFilterWhere(['like', 'large_pict', $this->large_pict]);
        $query->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }


}