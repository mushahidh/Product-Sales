<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">
<?php $form = ActiveForm::begin(); ?>
    <div class="row no-margin">
    <div class="col-md-6">
    <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>
    
    </div>
    <div class="col-md-6">
    <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>
    
    </div>
</div>   
    <div class="row no-margin">
    <div class="col-md-6">
    <?= $form->field($model, 'username')->textInput(['maxlength' => true,'readonly' => !$model->isNewRecord]) ?>
    
    </div>
    <div class="col-md-6">
    <?= $form->field($model, 'password')->textInput(['maxlength' => true]) ?>
    
    </div>
</div>    


  
    <div class="row no-margin">
    <div class="col-md-6">
    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
    
    </div>
    <div class="col-md-6">
    <?= $form->field($model, 'phone_no')->textInput(['maxlength' => true]) ?>
    </div>
</div>   
    
     <div class="row no-margin">
    <div class="col-md-6">
    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>
    
    </div>
    <div class="col-md-6">
    <?= $form->field($model, 'city')->textInput() ?>
    </div>
    </div>
    
    <div class="row no-margin">
        <div class="col-md-6">
    <?= $form->field($model, 'country')->textInput(['readonly' =>true,'value'=>'Thailand ']) ?>
    </div>
</div>  

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
