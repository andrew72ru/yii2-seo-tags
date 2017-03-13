<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 06.03.17
 * Time: 13:40
 *
 * @var \yii\web\View $this
 * @var \common\modules\seotag\models\Seotag $model
 */

$this->title = Yii::t('app.seotag', 'Create seotag for a new page');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app.seotag', 'Tags and descriptions management'), 'url' => ['/seotag']];
$this->params['breadcrumbs'][] = $this->title;

echo $this->render('_form', ['model' => $model]);