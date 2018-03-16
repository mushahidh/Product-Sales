<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\QuantityBasedPricing */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="quantity-based-pricing-form">

    <?php $form = ActiveForm::begin(); ?>

  
    <?= $form->field($model, 'quantity')->textInput() ?>

    <?= $form->field($model, 'price')->textInput() ?>

    <?php
            echo $form->field($model, 'product_id')->widget(Select2::classname(), [
                'data' => common\models\Product::getallproduct(),
                'theme' => Select2::THEME_BOOTSTRAP,
                'options' => ['placeholder' => 'Select Product  ...'],
                //'initValueText' => isset($model->customerUser->customer_name) ? $model->customerUser->company_name : "",
                'theme' => Select2::THEME_BOOTSTRAP,
                'pluginOptions' => [
                'allowClear' => true,
                'disabled' => !$model->isNewRecord,
                
                ],

            ]);
            ?>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
