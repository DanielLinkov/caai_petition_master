<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use app\admin\assets\AppAsset;
AppAsset::register($this);

$this->registerCss("
#wrapper > main{
	padding:3px;
}
");
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, maximum-scale=1.0, minimum-scale=1.0, initial-scale=1.0, user-scalable=no">
    <?= Html::csrfMetaTags() ?>
	 <title><?= Html::encode($this->title) ?><?=Yii::$app->name?></title>
    <?php $this->head() ?>
</head>
<body class="controller-<?=$this->context->id;?>">
<?php $this->beginBody() ?>

<div id="wrapper">
		<main class="container">
		<?= therendstudio\modules\messages\widgets\FlashWidget::widget(); ?>
        <?= $content ?>
		</main>
</div>
<footer>
	<div class="container">
		<h4 class="text-center"><?=Yii::$app->name?></h4>
	</div>
</footer>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
