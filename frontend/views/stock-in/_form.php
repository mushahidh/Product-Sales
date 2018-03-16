<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\db\Query;
use kartik\select2\Select2;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model common\models\StockIn */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="stock-in-form">

    <?php $form = ActiveForm::begin(); ?>
    <?php
            echo $form->field($model, 'product_id')->widget(Select2::classname(), [
                'data' => common\models\Product::getallproduct(),
                'theme' => Select2::THEME_BOOTSTRAP,
                'options' => ['placeholder' => 'Select Product  ...'],
                //'initValueText' => isset($model->customerUser->customer_name) ? $model->customerUser->company_name : "",
                'theme' => Select2::THEME_BOOTSTRAP,
                'pluginOptions' => [
                'allowClear' => true,
            
                ],

            ]);
            ?>
    <?php
         $user_id = Yii::$app->user->identity->id;
        
          ?>
          <label class="control-label" for="stockin-initial_quantity">Already Stock</label>
    <input type="text" id="totaStock" readonly="true" class="form-control" value="" name="Order[total_stock]" maxlength="45">

    <?= $form->field($model, 'initial_quantity')->textInput() ?>
    <?= $form->field($model, 'price')->textInput() ?>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

    <script>
      $('#stockin-product_id').on('change', function () {
          var user_id = "<?= $user_id;?>";
        $.post("../product/total-stock?id="+$(this).val()+"&user_id="+user_id, function (data) {
    $('#totaStock').val(data);
        });
    });
    </script>