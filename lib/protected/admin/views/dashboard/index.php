<?php
use yii\helpers\Url;
use app\models\Petition;

$this->title = 'Dashboard - ';
?>
<div class="alert alert-info"><em>* Tip:</em> <b>To edit a petition page first select one from <u><a href="<?= Url::to(['/petitions']) ?>" class="alert-link">the list</a></u></b></div>
<div class="row">
	<div class="col-sm-6">
		<div class="panel panel-primary">
			<div class="panel-heading"><div class="panel-title">Available Petitions (click to open)</div></div>
			<div class="panel-body">
				<div class="list-group">
					<?php foreach(Petition::find()->all() AS $petition): ?>
					  <a href="http://<?=$petition->hostname?>" target=_blank class="list-group-item" title="Open the petition page">
					  	<span class="badge"><?= $petition->code?></span>
						<h4 class="list-group-item-heading"><?= $petition->name ?></h4>
						<p class="list-group-item-text"><?= $petition->hostname ?></p>
					  </a>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</div>
