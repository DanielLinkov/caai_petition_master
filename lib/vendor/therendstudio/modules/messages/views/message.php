<?php $this->title=\Yii::$app->name . " - $title"; ?>

<?php
$levelMap = array(
	'error'=>'danger',
);
?>
<div class="container">
	<div class="panel panel-<?=isset($levelMap[$level]) ? $levelMap[$level] : $level;?>">
		<div class="panel-heading"><?=$title?></div>
		<div class="panel-body">
			<?=$message;?>
		</div>
	</div>
</div>

