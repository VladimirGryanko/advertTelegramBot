<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

$this->title = 'Вход';
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    .invalid-feedback{
        color: #868b98;
    }

    .custom-chek {
        height: 10px;
        align-self: flex-end;
    }

</style>


<div class="site-login min-vh-100">

    <div class="row align-items-center min-vh-100">
        <div class="col-sm-12">
            <div class="row justify-content-center">
                <div class="col-md-12  ">
                    <div class="neumorphic-card mx-auto">
                        <?php $form = ActiveForm::begin([
                            'layout' => 'horizontal',
                            'fieldConfig' => [
                                'template' => "<div class='row neumorphic-label'>{label}</div>{input}{error}",
                                'labelOptions' => ['class' => 'col-lg-1 control-label'],
                            ],
                        ]); ?>
                        <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'class' => 'neumorphic-input']) ?>
                        <?= $form->field($model, 'password')->passwordInput(['class' => 'neumorphic-input']) ?>
                        <?= $form->field($model, 'rememberMe')->checkbox([
                            'template' => "<button type='button' class='btn neumorphic-btn neumorphic-checkbox custom-chek'>{input}</button><label class='neumorphic-label'>Запомнить меня?</label>{error}",
                        ]) ?>
                        <div class="form-group">
                            <div class="col-lg-offset-1 col-lg-11">
                                <?= Html::submitButton('Login', ['class' => 'btn neumorphic-btn', 'name' => 'login-button']) ?>
                            </div>
                        </div>

                        <?php ActiveForm::end(); ?>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>
