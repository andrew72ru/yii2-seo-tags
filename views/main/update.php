<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 06.03.17
 * Time: 13:44
 *
 * @var \yii\web\View $this
 * @var \andrew72ru\seotag\models\Seotag $model
 */

$this->title = Yii::t('app.seotag', 'Update seotag for page {page}', ['page' => $model->url]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app.seotag', 'Tags and descriptions management'), 'url' => ['/seotag']];
$this->params['breadcrumbs'][] = $this->title;

echo $this->render('_form', ['model' => $model]);