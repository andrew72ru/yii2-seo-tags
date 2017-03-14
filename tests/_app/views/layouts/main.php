<?php
/**
 * @var string $content
 * @var \yii\web\View $this
 */

use andrew72ru\seotag\widgets\metaTags;
use yii\helpers\Html;

metaTags::widget();

?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= $this->title == Yii::$app->name ? Html::encode(Yii::$app->name) : $this->title ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<?= $content; ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

