<?php
use yii\helpers\Html;
use yii\widgets\Menu;

\yii\web\YiiAsset::register($this);

/**
 * @var \yii\web\View $this
 * @var string $content
 */
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta charset="<?= Yii::$app->charset ?>"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?= Html::encode($this->title) ?></title>
	<link rel="stylesheet" href="<?= Yii::$app->request->getBaseUrl() ?>/css/site.css"/>
	<?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>
	<div class="header">
		<h1><?= Html::a('My company', ['/site/index']) ?></h1>
	<?= Menu::widget([
		'items' => [
			['label' => 'Home', 'url' => ['/site/index']],
			['label' => 'About', 'url' => ['/site/about']],
			],
		]);
	?>
	</div>

	<div class="content">
		<?= $content ?>
	</div>

	<footer class="footer">
		&copy; My Company <?= date('Y') ?>, <?= Yii::powered() ?>
	</footer>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
