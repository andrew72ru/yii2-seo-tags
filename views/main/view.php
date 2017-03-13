<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 07.03.17
 * Time: 14:10
 *
 * @var \yii\web\View $this
 * @var \common\modules\seotag\models\Seotag $model
 */

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = Yii::t('app.seotag', 'Meta-tags for {page}', [
    'page' => $model->url
]);

$this->params['breadcrumbs'][] = ['label' => Yii::t('app.seotag', 'Tags and descriptions management'), 'url' => ['/seotag']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="box box-default">
    <div class="box-body">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                [
                    'label' => Yii::t('app.seotag', 'Target Page'),
                    'value' => $model->getPageTitle(),
                    'format' => 'raw'
                ],
                'description',
                [
                    'attribute' => 'keywords',
                    'value' => implode(', ', $model->inputKeywords),
                ],
                [
                    'attribute' => 'small_image_url',
                    'value' => Html::img($model->small_image_url, ['class' => 'img-responsive', 'style' => ['max-height' => '250px']]),
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'big_image_url',
                    'value' => Html::img($model->big_image_url, ['class' => 'img-responsive', 'style' => ['max-height' => '250px']]),
                    'format' => 'raw',
                ],
            ]
        ])?>
    </div>
    <div class="box-footer">
        <?= Html::a(Yii::t('app.seotag', 'Update meta-tags'), ['update', 'id' => $model->id], [
            'class' => 'btn btn-flat btn-danger'
        ])?>
    </div>
</div>
