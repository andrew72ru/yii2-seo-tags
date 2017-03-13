<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 06.03.17
 * Time: 13:46
 *
 * @var \yii\web\View $this
 * @var \common\modules\seotag\models\Seotag $model
 */

use common\modules\seotag\models\SeotagKeywords;
use kartik\select2\Select2;
use kartik\typeahead\Typeahead;
use rmrevin\yii\fontawesome\FA;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

$template = '<div> <span class="text-danger">{{url}}</span> <span class="text-default">{{name}}</span> </div>';

$descriptionField = Html::getInputId($model, 'description');
$keywordFields = Html::getInputId($model, 'inputKeywords');
$smallPictField = Html::getInputId($model, 'small_pict');
$bigPictField = Html::getInputId($model, 'large_pict');

$loadExistsJS = <<<JS
function onlyData(data) {
    $("#{$descriptionField}").val(data.description);
    $("#{$keywordFields}").html('');
    data.inputKeywords.forEach(function(el) {
      $("#{$keywordFields}").append('<option selected value="' + el + '">' + el + '</option>');
    });
    
    if(typeof data.images.forEach === 'function') {
      var row = document.createElement('div');
      $(row).addClass('row');
      data.images.forEach(function(el) {
        $(row).append(el);
      });
      $("#images-preview").html(row);
    }
    wCount();
}

function loadExistData(s, elem) {
  $("#images-preview").collapse('show');
  var url = $(elem).data('loadurl');
  $.post(url, s)
    .done(function(data) {
        onlyData(data)
    }).fail(function(data) {
      console.log(data.responseText);
    });
}

function simpleClose(elem) {
  $("#images-preview").collapse('show');
  var url = $(elem).data('loadurl');
  $.post(url, {'simple_url': $(elem).val()})
    .done(function(data) {
        onlyData(data);
    }).fail(function(data) {
      console.log(data.responseText);
    })
}

$(document).on('click', '[data-target="setbig"]', function() {
  var btn = this;
  $("#{$bigPictField}").val($(btn).data('src'));
}).on('click', '[data-target="setsmall"]', function() {
  var btn = this;
  $("#{$smallPictField}").val($(btn).data('src'));
})

JS;
$this->registerJs($loadExistsJS);

$wordCountJs = <<<JS

function wCount() {
    var hintElem = $("#{$descriptionField}").parents('.form-group').find('.hint-block');
    var remaming = 255 - parseInt($("#{$descriptionField}").val().length);
    var cssClass = 'text-muted';
    if(remaming < 25)
        cssClass = 'text-danger';
    
    $(hintElem).html('<p class="' + cssClass + '">' + remaming + '</p>');
}

wCount();
$("#{$descriptionField}").on('keyup', function() {
  wCount();
});
JS;
$this->registerJs($wordCountJs);

$fetchImagesUrl = Url::to(['fetch-images', 'url' => $model->full_url]);
$onlyImagesJs = <<<JS
$("#images-preview").collapse('show');
$.post('{$fetchImagesUrl}').done(function(data) {
  if(typeof data.forEach === 'function') {
      var row = document.createElement('div');
      $(row).addClass('row');
      data.forEach(function(el) {
        $(row).append(el);
      });
      $("#images-preview").html(row);
  }
})
JS;
if(!$model->isNewRecord)
    $this->registerJs($onlyImagesJs);

?>

<div class="box">
    <div class="box-body">

        <div id="ceo-form-container">
            <?php $form = ActiveForm::begin([
                'id' => 'seo-tag-form',
                'enableAjaxValidation' => true,
                'options' => [
                    'class' => 'form-horizontal',
                ],
                'fieldConfig' => [
                    'template' => "{label}\n<div class='col-sm-10'>{input}\n{hint}\n{error}</div>",
                    'labelOptions' => ['class' => 'col-sm-2 control-label']
                ]
            ]); ?>

            <?= Html::hiddenInput('check-url', 1)?>

            <?= $form->field($model, 'url')->widget(Typeahead::className(), [
                'options' => [
                    'placeholder' => Yii::t('app.seotag', 'Start a type of page url'),
                    'data' => ['loadurl' => Url::to(['load-exist-data'])]
                ],
                'pluginEvents' => [
//                    'typeahead:select' => 'function(e, s) { loadExistData(s, this); }',
                    'typeahead:close' => 'function() { simpleClose(this); }'
                ],
                'dataset' => [
                    [
                        'prefetch' => Url::to('pages-list'),
                        'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('value')",
                        'display' => 'url',
                        'remote' => [
                            'url' => Url::to('pages-list') . '?q=%QUERY',
                            'wildcard' => '%QUERY'
                        ],
                        'templates' => [
                            'suggestion' => new JsExpression("Handlebars.compile('$template')")
                        ]
                    ]
                ],
            ])?>

            <?= $form->field($model,'description')->textarea(['rows' => 4])?>

            <?= $form->field($model, 'small_pict')->textInput()?>

            <?= $form->field($model, 'large_pict')->textInput()?>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10 collapse" id="images-preview">
                    <?= FA::i(FA::_SPINNER)->size(FA::SIZE_LARGE)->addCssClass(['fa-pulse text-muted'])?>
                </div>
            </div>

            <?= $form->field($model, 'inputKeywords')->widget(Select2::className(), [
                'theme' => Select2::THEME_BOOTSTRAP,
                'showToggleAll' => false,
                'options' => [
                    'placeholder' => Yii::t('app.seotag', 'Please, type a comma-separated tags'),
                    'multiple' => true
                ],
                'data' => $model->isNewRecord ? SeotagKeywords::fullList() : null,
                'pluginOptions' => [
                    'tags' => true,
                    'tokenSeparators' => [',', ';'],
                ]
            ])?>

            <hr>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <?= Html::submitButton(Yii::t('app.seotag', 'Save'), [
                        'class' => 'btn btn-flat btn-success'
                    ])?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>

    </div>
</div>

