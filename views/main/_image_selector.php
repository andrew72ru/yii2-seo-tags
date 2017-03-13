<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 09.03.17
 * Time: 14:03
 *
 * @var \yii\web\View $this
 * @var string $src
 */
use andrew72ru\seotag\models\Seotag;
use rmrevin\yii\fontawesome\FA;
use yii\helpers\Html;

?>

<div class="col-xs-12 col-sm-6 col-md-3 col-lg-2">
    <?= Html::img($src, ['class' => 'img-responsive'])?>

    <div class="btn-group btn-group-justified" role="group">
        <div class="btn-group" role="group">
            <?= Html::button(FA::i(FA::_PICTURE_O), [
                'class' => 'btn btn-flat bg-olive',
                'title' => Yii::t('app.seotag', 'Set as big picture'),
                'data' => [
                    'src' => Seotag::mainFromPreview($src),
                    'toggle' => 'tooltip',
                    'target' => 'setbig'
                ]
            ])?>
        </div>

        <div class="btn-group" role="group">
            <?= Html::button(FA::i(FA::_PICTURE_O), [
                'class' => 'btn-group btn btn-flat btn-success',
                'title' => Yii::t('app.seotag', 'Set as small picture'),
                'data' => [
                    'src' => Seotag::mainFromPreview($src),
                    'toggle' => 'tooltip',
                    'target' => 'setsmall'
                ]
            ])?>
        </div>
    </div>
</div>
