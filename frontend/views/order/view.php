<?php

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\widgets\DetailView;
use yii2assets\printthis\PrintThis;
use yii\bootstrap\Modal;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model common\models\Order */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'ORDERS'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Payment Slip</h4>
      </div>
      <div class="modal-body">
       <?php
        if($model->payment_slip){
            echo '<div id="PaymentSlip"> <img src="'.\yii\helpers\Url::to('@web/uploads/' . $model->payment_slip, true).'"></div>';
        }else{
            echo '<div id="modalImageContent"> <h3>Slip not found</h3></div>';
        }
       ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

                    <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                        <div class="page-title">

                            <div class="pull-left">
                                <h1 class="title"><?php Yii::t('app', 'Invoice')?></h1>                            </div>

                            <div class="pull-right hidden-xs">
                            <?=Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
])?>

                            </div>

                        </div>
                    </div>
                    <style>
                        #viewslip {
    cursor: pointer;
    color: #e1b451;
}
                        </style>
                    <div class="clearfix"></div>

                    <div class="col-lg-12">
                        <section class="box " id="orderbtn">
                            <header class="panel_header">
                                <h2 class="title pull-left"><?=Yii::t('app', 'Invoice')?></h2>
                                <div class="actions panel_actions pull-right">
                                    <i class="box_toggle fa fa-chevron-down"></i>
                                    <i class="box_setting fa fa-cog" data-toggle="modal" href="#section-settings"></i>
                                    <i class=""></i>
                                </div>
                            </header>
                            <div class="content-body">    <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">



                                        <!-- start -->

                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <div class="invoice-head">
                                                    <div class="col-md-2 col-sm-12 col-xs-12 invoice-title">
                                                        <h2 class="text-center bg-primary "><?=Yii::t('app', 'Invoice')?></h2>
                                                    </div>
                                                    <div class="col-md-3 col-sm-12 col-xs-12 invoice-head-info">
                                                    <?php if ($model->status == '1') {?>
                                                        <span class='text-muted'>
                                                        <?php if (isset($model->shippingAddresses)) {?>
                                                        <?=$model->shippingAddresses->address?>

                                                            <?=$model->shippingAddresses->district . ', ' . $model->shippingAddresses->province . ', ' . $model->shippingAddresses->country . ', ' . $model->shippingAddresses->postal_code?><br>
                                                            Phone: <?php if (!empty($model->shippingAddresses->phone_no)){ 
                                                            echo $model->shippingAddresses->phone_no;}
                                                            else {echo $model->shippingAddresses->mobile_no;} ?>
                                                        <?php }?>
                                                        </span>
                                                    <?php }?>
                                                    </div>
                                                    <div class="col-md-3 col-sm-12 col-xs-12 invoice-head-info"><span class='text-muted'>Order #<?=$model->id;?><br><?=$model->created_at?></span></div>
                                                    <div class="col-md-3 col-sm-12 col-xs-12 invoice-log col-md-offset-1">
                                                    <img style="padding-top:10px;" src="<?= \yii\helpers\Url::to('@web/images/logo.png', true) ?>">

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div><br>
                                            <?php
                                            $Role = Yii::$app->authManager->getRolesByUser($model->user_id);
                                            if(isset($Role['customer']))
                                            {
                                            ?>
                                                <div class="col-xs-6 invoice-infoblock pull-left">
                                                <h4>External Code: <a href="<?=Yii::$app->homeUrl . 'order/history?external_code=' . $model->order_external_code?>" target="_blank"><?=$model->order_external_code?> </h4>
                                                
                                                </div>
                                                <div class="col-xs-6 invoice-infoblock text-right">
                                                
                                                    <h4>Tracking Code: <?=$model->shipping_status?>  (<?=$model->order_tracking_code?>)</h4>
                                                </div>
                                            <?php
                                            }
                                            ?>
                                            <div class="clearfix"></div><br>
                                            <div class="col-xs-6 invoice-infoblock pull-left">
                                            <h4><?=Yii::t('app', 'Billed To:')?></h4>
                                                <address>
                                                <?php $billed_to = \common\models\ShippingAddress::find()->where(['order_id' => $model->id])->one();?>
                                                    <h3><?=$billed_to->name?></h3>
                                                    <p class='text-muted'><?=$billed_to->address;?></p>
                                                    <p class='text-muted'><?=$billed_to->district;?></p>
                                                    <p class='text-muted'><?=$billed_to->province.', '.$billed_to->country . ', ' . $billed_to->postal_code;?></p>
                                                </address>
                                            </div>

                                            <div class="col-xs-6 invoice-infoblock text-right">
                                                <h4><?=Yii::t('app', 'Payment Method')?>:</h4>
                                                <address>
                                                    <!-- <h3>Credit Card</h3> -->
                                                    <span class='text-muted'><?php 
                                                    if (isset($model->payment_method)) { echo \common\models\Lookup::$order_status[$model->payment_method];} else {echo 'Out of System';}?>
                                                                       <?php  $paymentMethod = array_search('Bank Transfer', \common\models\Lookup::$order_status);
                                                                       if(!empty($model->payment_method) && $model->payment_method == (int)$paymentMethod ){?>
                                                                       /<span id="viewslip" >View Slip</span><br>
                                                                      <?php } ?>
                                                   
                                                </address>

                                                <div class="invoice-due">

                                                <?php
$order_sum = 0;
foreach ($model->productOrders as $orders) {

    $order_sum += $orders->quantity * $orders->order_price;
}?>
                                                    <h3 class="text-muted"><?=Yii::t('app', 'Total Due:')?></h3> &nbsp; <h2 class="text-primary">฿<?=$order_sum;?> </h2>
                                                </div>

                                            </div>


                                            <div class="clearfix"></div><br>

                                        </div>

                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <h3> <?=Yii::t('app', 'Order Summary')?></h3><br>
                                                <div class="table-responsive">
                                                    <table class="table table-hover invoice-table">
                                                        <thead>
                                                            <tr>
                                                                <td><h4> <?=Yii::t('app', 'Product Name')?></h4></td>
                                                                <td class="text-center"><h4> <?=Yii::t('app', 'Price')?></h4></td>
                                                                <td class="text-center"><h4> <?=Yii::t('app', 'Quantity')?></h4></td>
                                                                <td class="text-right"><h4><?=Yii::t('app', 'Total')?></h4></td>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <!-- foreach ($order->lineItems as $line) or some such thing here -->
                                                            <?php
$sum = 0;
foreach ($model->productOrders as $orders) {
    $product_name = \common\models\Product::find()->where(['id' => $orders->product_id])->one();?>


                                                            <tr>
                                                                <td><?=$product_name['name'];?></td>
                                                                <td class="text-center"><?=$orders['order_price'];?></td>
                                                                <td class="text-center"><?=$orders['quantity'];?></td>
                                                                <td class="text-right"><?=$sum += $orders['quantity'] * $orders['order_price'];?> </td>
                                                            </tr>
<?php }?>
                                                            <tr>
                                                                <td class="thick-line"></td>
                                                                <td class="thick-line"></td>
                                                                <td class="thick-line text-center"><h4><?=Yii::t('app', 'Sub total')?></h4></td>
                                                                <td class="thick-line text-right"><h4><?=$sum?></h4></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="no-line"></td>
                                                                <td class="no-line"></td>
                                                                <td class="no-line text-center"><h4><?=Yii::t('app', 'Shipping')?></h4></td>
                                                                <td class="no-line text-right"><h4>฿0</h4></td>
                                                            </tr>

                                                            <tr>
                                                                <td class="no-line"></td>
                                                                <td class="no-line"></td>
                                                                <td class="no-line text-center"><h4><?=Yii::t('app', 'Total')?></h4></td>
                                                                <td class="no-line text-right"><h3 style='margin:0px;' class="text-primary"><?=$sum?></h3></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                   


                                        <div class="clearfix"></div><br>

                              
                                </div>
                            </div>
                        </section>
         


                                        <!-- end -->
                                        <div class="row">
                                        <div class="col-md-12 not_in_print">
                                        <?php if (isset($model->payment_slip)) {?>


                                        <div class="col-md-6 col-sm-6 col-xs-6 text-center">
                                        <div class="sliptransfer" >
                                        <?php
$items = array();
    $items[] = [
        'url' => \yii\helpers\Url::to('@web/uploads/' . $model->payment_slip, true),
        'src' => \yii\helpers\Url::to('@web/uploads/' . $model->payment_slip, true),
        'options' => array('title' => ''),
    ];
    ?>
                                    <?php //dosamigos\gallery\Gallery::widget(['items' => $items]);?>
                                        </div>
                                        </div>
                                            <div class="col-md-6 col-sm-6 col-xs-6 text-center">
                                        <?php } else {?>
                                            <div class="col-md-12 col-sm-12 col-xs-12 text-center">

                                        <?php }?>
                                                <?php
                                                $pamentMethod = array_search('Credit Card', \common\models\Lookup::$payment_method);
                                                
                                                if($model->payment_method == $pamentMethod){?>
                                                <form method="post" action="https://www.thaiepay.com/epaylink/payment.aspx">
                                                        <input type="hidden" name="refno" value="<?=$model->id?>">
                                                        <input type="hidden" name="merchantid" value="46511428">
                                                        <input type="hidden" name="customeremail" value="customer@gmail.com">
                                                        <input type="hidden" name="c">
                                                        <input type="hidden" name="productdetail" value="BeyDey">
                                                        <input type="hidden" name="total" value="<?=$sum?>">
                                                        <input type="submit" name="Submit" class="btn btn-primary" value="Checkout">
                                                        
                                                    </form>
                                                <?php
                                                }else{
echo PrintThis::widget([
    'htmlOptions' => [
        'id' => 'orderbtn',
        'btnClass' => 'btn btn-primary',
        'btnId' => 'btnmanifests',
        'btnText' => 'Print',
        'btnIcon' => 'fa fa-print'
    ],
    'options' => [
        'debug' => false,
        'importCSS' => true,
        'importStyle' => false,
       // / 'loadCSS' => "path/to/my.css",
        'pageTitle' => "",
        'removeInline' => false,
        'printDelay' => 200,
        'header' => null,
        'formValues' => true,
    ]
]);
    }
?>
                                      </div>
                                        </div>
                                        </div>
                                    </div>
                      </div>


</div>
<script>
$(document).ready(function(){

$("body").delegate("#viewslip","click",function(){
        $('#myModal').modal('show');
        return false;
        
     });
})
</script>
