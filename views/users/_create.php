<?php

/* @var $this yii\web\View */

/* @var $model \app\models\User */

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

$this->title = 'Создание нового пользователя'
?>

<div class="row justify-content-center">
    <div class="col-md-10 my-5 mx-5 custom-panel">
        <?php
        $form = ActiveForm::begin([
            'options' => ['class' => 'form-horizontal'],
            'fieldConfig' => [
                'template' => "<div class='row neumorphic-label'>{label}</div>{input}{error}",
                'labelOptions' => ['class' => 'col-lg-2 control-label'],

            ],
        ])
        ?>
        <?= $form->field($model, 'username')->textInput(['class' => "custom-input"])->label('Имя пользователя') ?>
        <?= $form->field($model, 'email')->textInput(['class' => "custom-input"]) ?>
        <?= $form->field($model, 'password')->textInput(['class' => "custom-input"])->label('Пароль') ?>

        <div class="form-group">
            <?= Html::submitButton('Создать', ['class' => 'btn custom-btn px-3']) ?>
        </div>

        <?php ActiveForm::end() ?>
    </div>
</div>


