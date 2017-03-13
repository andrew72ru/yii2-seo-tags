<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 07.03.17
 * Time: 15:15
 *
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \andrew72ru\seotag\models\SeotagKeywordsSearch $searchModel
 */

use andrew72ru\seotag\models\SeotagKeywords;
use rmrevin\yii\fontawesome\FA;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\bootstrap\Modal;

$this->title = Yii::t('app.seotag', 'Keywords');

$this->params['breadcrumbs'][] = ['label' => Yii::t('app.seotag', 'Tags and descriptions management'), 'url' => ['/seotag']];
$this->params['breadcrumbs'][] = $this->title;

$modalJs = <<<JS
$(document).on('show.bs.modal', '#editModal', function(e) {
  
  var link = e.relatedTarget;
  var modal = this;
  
  $.get($(link).attr('href')).done(function(data) {
    $(modal).find('.edit-modal-content').collapse('show').html(data);
    $(modal).find('.edit-modal-error').collapse('hide').html('');
  }).fail(function(data) {
    $(modal).find('.edit-modal-content').collapse('hide').html('');
    $(modal).find('.edit-modal-error').collapse('show').html(data.responseText);
  }).always(function() {
    $(modal).find('.edit-modal-holder').collapse('hide');
  });
  
}).on('hide.bs.modal', '#editModal', function() {
  var modal = this;
  $(modal).find('.edit-modal-content').collapse('hide').html('');
  $(modal).find('.edit-modal-error').collapse('hide').html('');
  $(modal).find('.edit-modal-holder').collapse('show');
})
JS;
$this->registerJs($modalJs);
?>

<div class="box">
    <div class="box-body">
        <?= GridView::widget([
            'filterModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'columns' => [
                'word',
                [
                    'attribute' => 'urlSearch',
                    'label' => $searchModel->getAttributeLabel('urlSearch'),
                    'value' => function(SeotagKeywords $model)
                    {
                        $html = Html::beginTag('ul', ['class' => 'list-unstyled']);
                        $html .= Html::tag('li', Html::tag('strong', Yii::t('app.seotag', 'Uses in urls')));
                        foreach ($model->tags as $tag)
                        {
                            $html .= Html::tag('li', Html::a($tag->full_url, $tag->full_url, ['target' => '_blank']));
                        }
                        return $html;
                    },
                    'format' => 'raw',
                ],
                [
                    'label' => null,
                    'value' => function(SeotagKeywords $model)
                    {
                        return Html::a(FA::i(FA::_PENCIL), ['update', 'id' => $model->id], [
                            'class' => 'btn',
                            'data' => [
                                'toggle' => 'modal',
                                'target' => '#editModal'
                            ]
                        ]);
                    },
                    'format' => 'raw',
                    'contentOptions' => ['class' => 'text-nowrap', 'style' => ['width' => '10px']],
                ]
            ]
        ])?>
    </div>
</div>

<?php Modal::begin([
    'id' => 'editModal',
    'size' => Modal::SIZE_LARGE,
    'header' => Html::tag('h4', Yii::t('app.seotag', 'Update keyword'), ['class' => 'modal-title'])
])?>

<div class="edit-modal-holder collapse in text-center">
    <?= FA::i(FA::_SPINNER, ['class' => 'fa-pulse'])->size(FA::SIZE_5X)->addCssClass('text-muted')?>
</div>
<div class="edit-modal-content collapse"></div>
<div class="edit-modal-error collapse alert alert-danger"></div>

<?php Modal::end(); ?>