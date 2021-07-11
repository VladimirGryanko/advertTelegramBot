<?php

/* @var $dataProvider \yii\data\ActiveDataProvider */

/* @var $this yii\web\View */

use yii\grid\ActionColumn;
$this->title = 'Пользователи';
?>

<div class="row justify-content-center my-5 px-3">
    <div class="col-md-12 custom-scroll-table">
        <?php echo \yii\grid\GridView::widget([
            'dataProvider' => $dataProvider,
            'summaryOptions' => ['class' => 'custom-table'],
            'columns' => [
                [
                    'attribute' => 'id',
                    'label' => 'ID',
                ],
                'username',
                'email',
                [
                    'label' => 'Роли',
                    'value' => function () {
                        return "<button></button>";
                    },
                    'visible' => Yii::$app->user->identity->isAdmin()
                ],
            ],
            'tableOptions' => ['class' => 'table custom-table my-3 ',],
        ]); ?>
    </div>
</div>
<div class="row justify-content-center my-5 px-3">
    <?php echo \yii\bootstrap4\LinkPager::widget([
        'pagination' => $dataProvider->pagination
    ]) ?>
</div>
