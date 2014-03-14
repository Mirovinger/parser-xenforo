<?php
/**
 * @var yii\web\View $this
 * @var \app\components\ParserXenforo $data
 */
$this->title = 'My Yii Application';
?>
<div class="site-index">
	<h1><?= $data->title; ?></h1>
	<p>Created At: <?= date('Y-m-d H:i:s', $data->timestamp)?></p>
	<p><?= $data->content; ?></p>
</div>
