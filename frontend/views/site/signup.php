<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\file\FileInput;


$this->title = 'Signup';
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="site-signup">
    <h1 style="text-align: center;">Register Company here</h1>
    <div class="row">
    <div class="container">
        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
        <div class="col-md-8 col-md-offset-2">
        <div class="row">
        <div class="col-lg-12 col-md-12">
        <h3>Company Detail</h3>
        </div>
        <div class="col-lg-6 col-md-6">
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        
        </div>
        <div class="col-lg-6 col-md-6">
        <?= $form->field($model, 'email') ?>
        
        </div>
        </div>
        <div class="row">
        <div class="col-lg-6 col-md-6">
        <?= $form->field($model, 'logo')->widget(FileInput::classname(), [
                    'pluginOptions' => [
                        'showUpload' => true,
                        'initialPreview' => [
                            $model->logo ? Html::img(Yii::$app->request->baseUrl . '../../uploads/' . $model->logo) : null, // checks the models to display the preview
                        ],
                        'overwriteInitial' => false,
                    ],
                ]);  ?>
        </div>
        <div class="col-lg-6 col-md-6">
        <?= $form->field($model, 'country')->textInput(['maxlength' => true]) ?>
        
        </div>
        </div>
        <div class="row">
        <div class="col-lg-6 col-md-6">
        <?= $form->field($model, 'state')->textInput(['maxlength' => true]) ?>
        
        </div>
        <div class="col-lg-6 col-md-6">
        <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>
        
        </div>
        </div>
        <div class="row">
        <div class="col-lg-6 col-md-6">
        <?= $form->field($model, 'zip')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-6 col-md-6">
        </div>
        </div>
        <div class="row">
        <div class="col-lg-12 col-md-12">
        <h3>User Detail</h3>
        </div>
        <div class="col-lg-6 col-md-6">
     
        <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-6 col-md-6">
        <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>
        
        </div>
        </div>
        <div class="row">
        <div class="col-lg-6 col-md-6">
        <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
        </div>
        <div class="col-lg-6 col-md-6">
        <?= $form->field($model, 'password')->passwordInput() ?>
        </div>
        </div>
        <div class="row">
        <div class="col-lg-6 col-md-6">
        <?= $form->field($model, 'profile')->widget(FileInput::classname(), [
                    'pluginOptions' => [
                        'showUpload' => true,
                        'initialPreview' => [
                            $model->profile ? Html::img(Yii::$app->request->baseUrl . '../../uploads/' . $model->profile) : null, // checks the models to display the preview
                        ],
                        'overwriteInitial' => false,
                    ],
                ]);
                ?>
        </div>
        <div class="col-lg-6 col-md-6">
    
        </div>
        </div>
         
                <div class="form-group">
                    <?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>
                </div> 
            <?php ActiveForm::end(); ?>
    </div>
    </div>
</div>
