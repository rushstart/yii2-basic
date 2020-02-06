<?php

use app\modules\user\models\forms\LoginForm;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model LoginForm */


$this->title = 'Вход';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="login-box">
    <div class="login-logo">
        <?= Html::encode($this->title) ?>
    </div>
    <div class="login-box-body">
        <p class="login-box-msg">Заполните поля чтобы начать работу</p>

        <?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => false]); ?>

        <?= $form
            ->field($model, 'email')
            ->label(false)
            ->textInput(['placeholder' => $model->getAttributeLabel('email')]); ?>
        <?= $form
            ->field($model, 'password')
            ->label(false)
            ->passwordInput(['placeholder' => $model->getAttributeLabel('password')]); ?>
        <?= $form->field($model, 'rememberMe')->checkbox() ?>

        <div class="form-group">
            <?= Html::submitButton('Войти', [
                'class' => 'btn btn-primary btn-block btn-flat',
                'name' => 'login-button'
            ]) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
