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
<?php $this->beginPage();

?>
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
<?php $this->beginBody();
if (!Yii::$app->user->isGuest) {
    echo '<style>
    body {
        background-color: #1f202e;
    }
</style>';
}
?>
<div class="wrap">
            <?php
            if (!Yii::$app->user->isGuest) {
                NavBar::begin([
                    'innerContainerOptions' => ['class' => 'container-fluid'],
                    'options' => [
                        'class' => 'navbar navbar-expand-lg navbar-dark',
                    ],
                ]);
                echo Nav::widget([
                    'items' => [
                        [
                            'label' => 'Home',
                            'url' => ['/site/index'],
                            'linkOptions' => ['class' => 'custom-btn px-3'],
                            'options' => ['class' => 'nav-item my-3 mx-3'],
                        ],
                        [
                            'label' => 'Пользователи',
                            'url' => ['/users/index'],
                            'linkOptions' => ['class' => 'custom-btn px-3'],
                            'options' => ['class' => 'nav-item my-3 mx-3'],
                        ],
                        [
                            'label' =>
                                sprintf(
                                    'Выход(%s)',
                                    !Yii::$app->user->isGuest
                                        ? User::findOne(['id' => Yii::$app->user->id])->username
                                        : null
                                ),
                            'linkOptions' => ['class' => 'custom-btn px-3'],
                            'url' => ['/site/logout'],
                            'options' => ['class' => 'nav-item my-3 mx-3']
                        ],
                    ],
                    'options' => ['class' => 'navbar-nav mx-4'],
                ]);
                NavBar::end();
            }
            ?>


    <div class="container-fluid min-vh-100">
        <?= $content ?>
    </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
