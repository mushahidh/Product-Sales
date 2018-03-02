<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model common\models\Gl */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="recieveable-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="col-md-6">
<?php
echo $form->field($model, 'user_level')->widget(Select2::classname(), [
    'data' => common\models\UsersLevel::getAlllevels(),
    'theme' => Select2::THEME_BOOTSTRAP,
    'options' => ['placeholder' => 'Select a current user Level ...'],
    'pluginOptions' => [
      'allowClear' => true,
      //'autocomplete' => true,
  
  ],
  ]);
?>
</div>
<div class="col-md-6">
    <?php 
           echo $form->field($model, 'receivable_user')->widget(Select2::classname(), [
                                            'theme' => Select2::THEME_BOOTSTRAP,
                                            'options' => ['placeholder' => 'Select a Parent User ...'],
                                            'pluginOptions' => [
                                                'allowClear' => true,
                                                //'autocomplete' => true,
                                                'ajax' => [
                                                    'url' => '../user/get-users',
                                                    'dataType' => 'json',
                                                    'data' => new \yii\web\JsExpression('function(params) { var user_level = $("#gl-user_level").val();return {q:params.term,user_level:user_level}; }')
                                                ],
                                            ],
                                        ]);
    ?>
    </div>
<div class="col-md-6">
       <?php 
           echo $form->field($model, 'payable_user')->widget(Select2::classname(), [
                                            'theme' => Select2::THEME_BOOTSTRAP,
                                            'options' => ['placeholder' => 'Select a  User ...'],
                                            'pluginOptions' => [
                                                'allowClear' => true,
                                                //'autocomplete' => true,
                                                'ajax' => [
                                                    'url' => '../user/get-users',
                                                    'dataType' => 'json',
                                                    'data' => new \yii\web\JsExpression('function(params) { var parent_id = $("#gl-receivable_user").val();return {q:params.term,parent_id:parent_id}; }')
                                                ],
                                            ],
                                        ]);
    ?>
    </div>
    
<div class="col-md-6">
    
        <?= $form->field($model, 'recieveable_amount')->textInput(['maxlength' => true,'readOnly'=> true]) ?>
        </div>
<div class="col-md-6">
        
    <?= $form->field($model, 'amount')->textInput(['maxlength' => true]) ?>
    </div>
<div class="col-md-6">
    
    <?=
                $form->field($model, 'payment_slip')->widget(FileInput::classname(), [
                    
                    'pluginOptions' => [
                        'showUpload' => true,
                        'initialPreview' => [
                            $model->payment_slip ? Html::img(Yii::$app->request->baseUrl . '/uploads/' . $model->payment_slip) : null, // checks the models to display the preview
                        ],
                        'overwriteInitial' => false,
                    ],
                ]);
                ?>
                </div>
                <div class="col-md-6">
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>
</div>
    <?php ActiveForm::end(); ?>

</div>
<script>
        jQuery(document).ready(function() {
      $('#gl-payable_user').on('change', function () {
            $.post("../recieveable/getrecieveableamount?id=" + $('#gl-receivable_user').val() + "&payable_id=" + $(this).val(), function (data) {
            $('#gl-recieveable_amount').val(data);
            });
            });
                        $('#gl-amount').on('blur', function () {

           if (parseInt($('#gl-recieveable_amount').val()) < parseInt($('#gl-amount').val())){
          $('#gl-amount').val('');
            }
          
            });
             });
</script>


