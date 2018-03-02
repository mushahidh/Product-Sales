<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
body
{
    background-color: rgb(81, 81, 70);
    background-image: url('../images/login-bg.png');
    background-attachment: fixed;
    height: 100%;
    max-height: 100%;
    min-height: 99%;
    overflow: hidden;
    width: 100%;
}
.white-text
{
	color:white;
	text-color:white;
}
</style>

<div class="login-wrapper">
    <div id="login" class="login loginpage col-lg-offset-4 col-lg-4 col-md-offset-3 col-md-6 col-sm-offset-3 col-sm-6 col-xs-offset-0 col-xs-12">    
              
        <div style="text-align: center;">
            <img src="../images/logo-white.png"/>
        </div>

        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
            <p>
                <?= $form->field($model, 'username')->textInput(['autofocus' => true])->label('username',['class'=>'white-text']) ?>
            </p>

            <p>
                <?= $form->field($model, 'password')->passwordInput()->label('password',['class'=>'white-text']) ?>
            </p>

            <p>
                <?= $form->field($model, 'rememberMe')->checkbox()->label('remember me',['class'=>'white-text']) ?>
            </p>
        
            <p class="submit">
                <?= Html::submitButton('Login', ['class' => 'btn btn-primary btn-block', 'name' => 'login-button']) ?>
            </p>
        <?php ActiveForm::end(); ?>
    </div>
</div>
