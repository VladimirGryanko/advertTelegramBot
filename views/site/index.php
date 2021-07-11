<?php

/* @var $dataProvider \yii\data\ActiveDataProvider */

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Dashboard';
?>
<div class="site-index">
    <div class="row justify-content-center">
        <div class="col-md-6 my-5 mx-5 custom-panel custom-scroll-table px-2">
            <?php echo \yii\grid\GridView::widget([
                'dataProvider' => $dataProvider,
                'summaryOptions' => ['class' => 'custom-table'],
                'columns' => [
                    [
                        'attribute' => 'id',
                        'label' => 'ID',
                    ],
                    'username',
                    'email'
                ],
                'tableOptions' => ['class' => 'table custom-table my-3',],
            ]); ?>
        </div>
        <div class="col-md-3 my-5 mx-5 custom-panel">
            <div class="row justify-content-center mb-3"> Боты: </div>
            <div class="row justify-content-center">
            </div>
        </div>
    </div>
</div>
