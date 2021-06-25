<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
use yii\helpers\Url;

$this->context->layout = 'bare';
$this->title = "$name - ";
?>
<div class="container">
	<div class=row">
		<div class="col-sm-6 offset-sm-3 text-center">
			 <h1><?= Html::encode($name) ?></h1>

			 <div class="alert alert-danger">
				  <?= nl2br($message) ?>
			 </div>
		</div>
	</div>
</div>
