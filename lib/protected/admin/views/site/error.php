<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = "$name - ";
$this->params['breadcrumbs'] = [
	'Error'
];
?>
<div class=row">
	<div class="col-sm-6 col-sm-offset-3 text-center">
		 <h1><?= Html::encode($name) ?></h1>

		 <div class="alert alert-danger">
			  <?= nl2br(Html::encode($message)) ?>
		 </div>
	</div>
</div>
