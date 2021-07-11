<?php

/* @var $user \app\models\User */

/* @var $this \yii\web\View */


use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

$this->title = 'Сброс пароля'
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
        <?= $form->field($user, 'password')->textInput(['class' => "custom-input"])->label('Новый пароль') ?>

        <div class="form-group">
            <?= Html::submitButton('Обновить', ['class' => 'btn custom-btn px-3']) ?>
        </div>

        <?php ActiveForm::end() ?>
    </div>
</div>
