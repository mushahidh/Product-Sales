<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data'],
       'action' => ['user/customer'],
       ]
           
           ); ?>
    <div class="row no-margin">
    <div class="col-md-6">
        <div class="col-md-4">
            First Name
        </div>
        <div class="col-md-8">
    <?= $form->field($model, 'first_name')->textInput(['maxlength' => true])->label(false) ?>
    </div>
    </div>
    <div class="col-md-6">
        <div class="col-md-4">
            Last Name
        </div>
        <div class="col-md-8">
    <?= $form->field($model, 'last_name')->textInput(['maxlength' => true])->label(false) ?>
    </div>
    </div>
</div>   
    <div class="row no-margin">
    <div class="col-md-6">
        <div class="col-md-4">
           Username
        </div>
        <div class="col-md-8">
    <?= $form->field($model, 'username')->textInput(['maxlength' => true,'readonly' => !$model->isNewRecord])->label(false) ?>
    </div>
    </div>
    <div class="col-md-6">
        <div class="col-md-4">
            Password
        </div>
        <div class="col-md-8">
    <?= $form->field($model, 'password')->textInput(['maxlength' => true])->label(false) ?>
    </div>
    </div>
</div>    


  
    <div class="row no-margin">
    <div class="col-md-6">
        <div class="col-md-4">
            Email
        </div>
        <div class="col-md-8">
    <?= $form->field($model, 'email')->textInput(['maxlength' => true])->label(false) ?>
    </div>
    </div>
    <div class="col-md-6">
        <div class="col-md-4">
            Phone No
        </div>
        <div class="col-md-8">
    <?= $form->field($model, 'phone_no')->textInput(['maxlength' => true])->label(false) ?>
</div>
    </div>
</div>   
    
     <div class="row no-margin">
    <div class="col-md-6">
        <div class="col-md-4">
            Address
        </div>
        <div class="col-md-8">
    <?= $form->field($model, 'address')->textInput(['maxlength' => true])->label(false) ?>
</div>
    
    </div>
    <div class="col-md-6">
        <div class="col-md-4">
            City
        </div>
        <div class="col-md-8">
    <?= $form->field($model, 'city')->textInput()->label(false) ?>
</div>
    </div>
    </div>
    
    <div class="row no-margin">
        <div class="col-md-6">
            <div class="col-md-4">
            Country
        </div>
        <div class="col-md-8">
    <?= $form->field($model, 'country')->textInput(['readonly' =>true,'value'=>'Thailand '])->label(false) ?>
</div>
    </div>
</div>  

<div class="row no-margin">
        <div class="col-md-6">
            <div class="col-md-4">
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
</div>
</div>
</div>

    <?php ActiveForm::end(); ?>

</div>
