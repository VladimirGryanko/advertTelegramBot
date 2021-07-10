<?php

/* @var $this \yii\web\View */

/* @var $content string */

use app\models\User;
use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    if (Yii::$app->view->title !== 'Вход') {
        NavBar::begin([
            'brandLabel' => Yii::$app->name,
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar nav nav-masthead justify-content-center sticky-top navbar-expand-lg bg-dark navbar-dark',
            ],
        ]);
        echo Nav::widget([
            'items' => [
                [
                    'label' => 'Home',
                    'url' => ['/site/index'],
                    'options' => ['class' => 'nav-item'],
                    'visible' => !Yii::$app->user->isGuest,
                ],
                [
                    'label' =>
                        sprintf(
                            'Logout(%s)',
                            !Yii::$app->user->isGuest
                                ? User::findOne(['id' => Yii::$app->user->id])->username
                                : null
                        ),
                    'url' => ['/site/logout'],
                    'visible' => !Yii::$app->user->isGuest,
                    'options' => ['class' => 'nav-item']
                ],
            ],
            'options' => ['class' => 'navbar-nav ml-4 justify-content-between'],
        ]);
        NavBar::end();
    }
    ?>

    <div class="container-fluid min-vh-100">
        <?= $content ?>
    </div>
</div>

<?php if (Yii::$app->view->title !== 'Вход') {
    echo '<footer class="footer">
            <div class="container-fluid">
                <p class="pull-left">&copy; AdvertTelegramBot '. date("Y") . '</p>
            </div>
           </footer>';
} ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
