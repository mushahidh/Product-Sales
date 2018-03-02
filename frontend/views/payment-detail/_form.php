<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PaymentDetail */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="payment-detail-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'payment_method')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
