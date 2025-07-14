<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\assets\AppAsset;
use app\assets\BacktotopAsset;

if(Yii::$app->active_petition->config && (int)Yii::$app->active_petition->config->backtotop_plugin)
	BacktotopAsset::register($this);

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="<?= Yii::$app->active_petition->config ? Yii::$app->active_petition->config->page_description : NULL?>">
	<link rel="icon" href="<?= Yii::$app->active_petition->config ? Yii::getAlias('@petitions/'.Yii::$app->active_petition->id.'/images/'.Yii::$app->active_petition->config->favicon) : "favicon.png" ?>">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode(Yii::$app->active_petition->config ? Yii::$app->active_petition->config->page_title : "Петиция") ?></title>
	<?php
		if(isset($this->params['additional_head_tags']))
			foreach($this->params['additional_head_tags'] AS $tagInfo)
				echo Html::tag($tagInfo['tag'],$tagInfo['content'],$tagInfo['attributes']);
	?>
	<?= Yii::$app->active_petition->config ? Yii::$app->active_petition->config->code_snippets_header : NULL ?>
    <?php $this->head() ?>
</head>
<body class="page-<?= $this->context->action->id ?>">
<?php $this->beginBody() ?>
<main>
	<?= $content ?>
</main>
<?= Yii::$app->active_petition->config ? Yii::$app->active_petition->config->code_snippets_footer : NULL ?>
<?php $this->endBody() ?>
<?= Yii::$app->active_petition->config ? Yii::$app->active_petition->config->code_snippets_end_body ?? 'hello' : NULL ?>
</body>
</html>
<?php $this->endPage() ?>
