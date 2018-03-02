<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\models\order;
use kartik\file\FileInput;
use yii\db\Query;

/* @var $this yii\web\View */
/* @var $model common\models\Order */
/* @var $form yii\widgets\ActiveForm */
?>

<!-- <h3>Order Items</h3> -->
        <?php if (!Yii::$app->user->isGuest) { ?>
        <div class="row">
            <div class="col-md-2">Total Stock </div>
            <div class="col-md-10" style="margin-bottom: 10px;">
                <?php
                if (!isset($Role['super_admin'])) {
                    $order_quantity = (new Query())
                            ->select('SUM(remaining_quantity) as remaning_stock')
                            ->from('stock_in')
                            ->where("user_id = '$user_id'")
                            ->andWhere("product_id = '1'")
                            ->groupby(['product_id'])
                            ->one();
                    ?>
                    <input type="text" id="available-stock" readonly="true" class="form-control" value="<?= $order_quantity['remaning_stock'] ?>" name="Order[total_stock]" maxlength="45">

                    <?php
                } else {
                    ?>
                    <input type="text" id="available-stock" readonly="true" class="form-control"  name="Order[total_stock]" maxlength="45" value="<?php if(!$model->isNewRecord) echo $model->total_stock; ?>">
                <?php }
                ?>
            </div>
    </div>
    <?php } ?>   
    <div class="col-md-4"><?php echo $form->field($model, 'quantity')->textInput(['maxlength' => true]); ?></div>
    <div class="col-md-4"><?php echo $form->field($model, 'single_price')->textInput(['readonly' => true]); ?></div>
    <div class="col-md-4"><?php echo $form->field($model, 'total_price')->textInput(['readonly' => true]); ?></div>
    <div class="noproduct"></div>
    <?php
        $user_id = Yii::$app->user->getId();
        echo $form->field($model, 'product_id')->hiddenInput(['value' => '1'])->label(false);
    ?>
    <?php if (Yii::$app->user->isGuest) { ?>
        <?php
        if(isset( $_GET['id']))
        {
            echo $form->field($model, 'request_agent_name')->hiddenInput(['value' => $_GET['id']])->label(false);
        }
        echo $form->field($model, 'order_type')->hiddenInput(['value' => 'Order'])->label(false);
    }
    ?>



