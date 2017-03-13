<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 01.03.17
 * Time: 11:17
 *
 * @var \yii\web\View $this
 * @var \andrew72ru\seotag\models\SeotagSearch $searchModel
 * @var \yii\data\ActiveDataProvider $dataProvider
 */

use andrew72ru\seotag\models\Seotag;
use rmrevin\yii\fontawesome\FA;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = Yii::t('app.seotag', 'Tags and descriptions management');
$this->params['breadcrumbs'][] = $this->title;

$this->params['page-subHeader'] = Html::a(FA::i(FA::_PLUS_CIRCLE, [
    'title' => Yii::t('app.seotag', 'Create seotag for a new page'),
    'data' => ['toggle' => 'tooltip', 'placement' => 'right']
]), ['create']);

?>

<div class="box box-solid box-default">
    <?= $searchModel->checkIsEmpty()
        ? Html::tag('div', Html::tag('h4', Yii::t('app.seotag', 'No one page has a meta-tags'), ['class' => 'box-title']), ['class' => 'box-header']) : ''?>

    <div class="box-body">
        <?= GridView::widget([
            'filterModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'showOnEmpty' => false,
            'columns' => [
                [
                    'attribute' => 'url',
                    'value' => function(Seotag $model)
                    {
                        return $model->full_url;
                    },
                    'format' => 'url',
                ],
                'small_pict',
                'large_pict',
                'description:ntext',
                [
                    'attribute' => 'keywords',
                    'value' => function(Seotag $model)
                    {
                        return implode(', ', $model->inputKeywords);
                    },
                ],
                [
                    'class' => ActionColumn::className(),
                ]
            ]
        ])?>
    </div>

    <div class="box-footer">
        <div class="form-group">
            <?= Html::a(Yii::t('app.seotag', 'Keywords'), ['/seotag/keywords'], [
                'class' => 'btn btn-xs btn-flat btn-success'
            ])?>
        </div>
    </div>
</div>