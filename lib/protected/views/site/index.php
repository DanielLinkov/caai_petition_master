<?php
use yii\helpers\Html;

foreach($sections AS $section)
	echo $section->render($page_mask),"\n";