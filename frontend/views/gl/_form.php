<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\Gl */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="gl-form">

    <?php $form = ActiveForm::begin(); ?>
<?php
echo $form->field($model, 'user_level')->widget(Select2::classname(), [
    'data' => common\models\UsersLevel::getalllevel(),
    'theme' => Select2::THEME_BOOTSTRAP,
    'options' => ['placeholder' => 'Select a current user Level ...'],
    'pluginOptions' => [
      'allowClear' => true,
      //'autocomplete' => true,
  
  ],
  ]);
?>
    <?php 
           echo $form->field($model, 'receivable_user')->widget(Select2::classname(), [
                                            'theme' => Select2::THEME_BOOTSTRAP,
                                            'options' => ['placeholder' => 'Select a Parent User ...'],
                                            'pluginOptions' => [
                                                'allowClear' => true,
                                                //'autocomplete' => true,
                                                'ajax' => [
                                                    'url' => '../user/parentuser',
                                                    'dataType' => 'json',
                                                    'data' => new \yii\web\JsExpression('function(params) { var type = $("#gl-user_level").val();return {q:params.term,type:type}; }')
                                                ],
                                            ],
                                        ]);
    ?>
       <?php 
           echo $form->field($model, 'payable_user')->widget(Select2::classname(), [
                                            'theme' => Select2::THEME_BOOTSTRAP,
                                            'options' => ['placeholder' => 'Select a  User ...'],
                                            'pluginOptions' => [
                                                'allowClear' => true,
                                                //'autocomplete' => true,
                                                'ajax' => [
                                                    'url' => '../user/childusers',
                                                    'dataType' => 'json',
                                                    'data' => new \yii\web\JsExpression('function(params) { var type = $("#gl-receivable_user").val();return {q:params.term,type:type}; }')
                                                ],
                                            ],
                                        ]);
    ?>
    <?= $form->field($model, 'amount')->textInput(['maxlength' => true]) ?>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
