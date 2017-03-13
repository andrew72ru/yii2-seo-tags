<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 09.03.17
 * Time: 19:17
 *
 * @var \yii\web\View $this
 * @var \andrew72ru\seotag\models\SeotagKeywords $model
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<?php $form = ActiveForm::begin([
    'action' => ['update', 'id' => $model->id]
]); ?>

<?= $form->field($model, 'word')->textInput(['class' => 'form-control input-lg'])?>

<div class="form-group">
    <?= Html::submitButton(Yii::t('app', 'Save'), [
        'class' => 'btn btn-lg btn-flat btn-success btn-block'
    ])?>
</div>

<?php ActiveForm::end(); ?>
