<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\db\Query;

/* @var $this yii\web\View */
/* @var $model common\models\StockIn */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="stock-in-form">

    <?php $form = ActiveForm::begin(); ?>
    <?php
         $user_id = Yii::$app->user->identity->id;
          $order_quantity = (new Query())
          ->select('SUM(remaining_quantity) as remaning_stock')
          ->from('stock_in')   
          ->where("user_id = '$user_id'")
          ->andWhere("product_id = '1'")
          ->groupby(['product_id'])
          ->one();
          ?>
          <label class="control-label" for="stockin-initial_quantity">Already Stock</label>
    <input type="text" id="order-orde" readonly="true" class="form-control" value="<?=  $order_quantity['remaning_stock'] ?>" name="Order[total_stock]" maxlength="45">

    <?= $form->field($model, 'initial_quantity')->textInput() ?>
    <?= $form->field($model, 'price')->textInput() ?>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
