<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use yii\helpers\ArrayHelper;
use app\assets\AppAsset;

$navModels = [
	'user' => new app\models\User()
];

$loggedInItems = [];
foreach ($navModels as $modelKey => $model) {
	$loggedInItems[] = [
		'label' => $model->getModelConfig('labelPlural'),
		'url' => $model->getUrl('index')
	];
}

$assetBundle = AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<script>
		var m = {'widgets': {}};
	</script>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-default navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            Yii::$app->user->isGuest ? (
                ['label' => 'Login', 'url' => ['/user/login']]
            ) : (
	            [
		            'label' => Yii::$app->user->identity->username(),
		            'items' => [
			            ['label' => 'Settings', 'url' => Yii::$app->user->identity->getUrl("update")],
			            '<li>'
		                . Html::beginForm([Yii::$app->user->identity->getUrl("logout")], 'post')
		                . Html::submitButton(
		                    'Logout',
		                    ['class' => 'btn btn-link logout']
		                )
		                . Html::endForm()
		                . '</li>'
		            ]
	            ]
            )
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; <?=Yii::$app->name?> <?= date('Y') ?></p>

        <p class="pull-right">Powered by <a href="https://www.mozzler.com.au/">Mozzler</a></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
