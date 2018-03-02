<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\Attendence;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $model common\models\Order */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="attendence-index">
    <h1>EPAYLINK Testing</h1>
    <form method="post" action="https://www.thaiepay.com/epaylink/payment.aspx">
        <input type="hidden" name="refno" value="99999">
        <input type="hidden" name="merchantid" value="46511428">
                    <input type="hidden" name="customeremail" value="">
        <input type="hidden" name="c">
        <input type="hidden" name="productdetail" value="Testing Product">
        <input type="hidden" name="total" value="400">
        <input type="hidden" name="postbackurl" value="http://salesmanagement.dev:8080/order/view/787">
        <input type="submit" name="Submit" value="Comfirm Order">
    </form>
    <div class="form-group">
                                   
                                </div>
      
</div>
