<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use yii\bootstrap\NavBar;
use yii\bootstrap\Nav;
use app\admin\assets\AppAsset;
AppAsset::register($this);

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
	<aside>
<?php
NavBar::begin(['brandLabel' => Yii::$app->name,'brandUrl'=>Yii::$app->homeUrl,'options'=>['class'=>'navbar-inverse default']]);
echo Nav::widget([
	'encodeLabels'=>false,
    'items' => [
			['label' => '<i class="fa fa-tachometer-alt fa-lg fa-fw"></i> Dashboard', 'url' => ['/dashboard']],
			['label' => '<i class="fa fa-file-signature fa-lg fa-fw"></i> Petitions', 'url' => ['/petitions']],
			['label' => '<i class="fa fa-puzzle-piece fa-lg fa-fw"></i> Sections', 'url' => ['/sections'],'visible'=>(boolean)Yii::$app->active_petition->id],
			['label' => '<i class="fa fa-images fa-lg fa-fw"></i> Images', 'url' => ['/images'],'visible'=>(boolean)Yii::$app->active_petition->id],
			['label' => '<i class="fab fa-css3-alt fa-lg fa-fw"></i> Css', 'url' => ['/css'],'visible'=>(boolean)Yii::$app->active_petition->id],
		],
    'options' => ['class' => 'navbar-nav'],
]);
echo Nav::widget([
	'encodeLabels'=>false,
	'items' => [
		['label'=>'<i class="fa fa-users fa-lg fa-fw"></i> Users', 'url' => ['/users'],'visible'=>Yii::$app->user->identity->role == 'superadmin'],
		['label'=>Yii::$app->user->identity->name,
			'items'=>[
				'<li class="dropdown-header"><strong>'.Yii::$app->user->identity->username.'</strong></li>',
				'<li class="divider"></li>',
				['label'=>'<i class="fa fa-cog fa-lg fa-fw"></i> Preferences','url'=>['/account/preferences']],
				['label'=>'<i class="fa fa-lock fa-lg fa-fw"></i> Change Password','url'=>['/account/change_password']],
				'<li class="divider"></li>',
				['label'=>'<i class="fa fa-sign-out-alt fa-lg fa-fw"></i> Sign out','url'=>['/account/signout']],
			],
		],
	],
    'options' => ['class' => 'navbar-nav navbar-right'],
]);
NavBar::end();
?>
	</aside>
    <main id="main-container">
		<div class="container">
			<?php
			if(isset($this->params['breadcrumbs']) && Yii::$app->active_petition->id){
				if(($index = array_search('active_petition',$this->params['breadcrumbs'])) !== FALSE)
					$this->params['breadcrumbs'][$index] = 'Active petition: <q>'.Yii::$app->active_petition->name.'</q>';
			}
			echo Breadcrumbs::widget([
			  'homeLink'=>['label'=>'Dashboard','url'=>['/dashboard']],
			  'encodeLabels'=>false,
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
			]) ?>
			<?= therendstudio\modules\messages\widgets\FlashWidget::widget(); ?>
        <?= $content ?>
		</div>
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
