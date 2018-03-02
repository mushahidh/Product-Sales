<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\UserProductLevel */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-product-level-form">

    <?php $form = ActiveForm::begin(); ?>
    <?php
            echo $form->field($model, 'user_level_id')->widget(Select2::classname(), [
                'data' => \common\models\UsersLevel::getAllLevels(),
                'theme' => Select2::THEME_BOOTSTRAP,
                'options' => ['placeholder' => 'Select Level  ...'],
                //'initValueText' => isset($model->customerUser->customer_name) ? $model->customerUser->company_name : "",
                'theme' => Select2::THEME_BOOTSTRAP,
                'pluginOptions' => [
                'allowClear' => true,
                'disabled' => !$model->isNewRecord,
                ],

            ]);
            ?>
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

    <?= $form->field($model, 'units')->textInput() ?>

    <?= $form->field($model, 'price')->textInput() ?>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
