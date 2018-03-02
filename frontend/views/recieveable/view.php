<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\Breadcrumbs;
/* @var $this yii\web\View */
/* @var $model common\models\Order */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Receivables'), 'url' => ['index']];
 $this->params['breadcrumbs'][] = $this->title;
?>



                    <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                        <div class="page-title">

                            <div class="pull-left">
                                <h1 class="title"><?php Yii::t('app', 'Invoice') ?></h1>                            </div>

                            <div class="pull-right hidden-xs">
                            <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
                              
                            </div>

                        </div>
                    </div>
                    <div class="clearfix"></div>

                    <div class="col-lg-12">
                        <section class="box ">
                            <header class="panel_header">
                                <h2 class="title pull-left"><?= Yii::t('app', 'Invoice')?></h2>
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
                                                        <h2 class="text-center bg-primary "><?= Yii::t('app', 'Invoice')?></h2>    
                                                    </div>
                                                    <div class="col-md-3 col-sm-12 col-xs-12 invoice-head-info">
                                                    <?php if($model->status == '1') {?>
                                                        <span class='text-muted'>
                                                        <?php if(isset($model->shippingAddresses)) {?>
                                                        <?= $model->shippingAddresses->address ?>
                                                            
                                                            <?= $model->shippingAddresses->district .''.$model->shippingAddresses->province  .''.$model->shippingAddresses->country .''. $model->shippingAddresses->postal_code ?><br>
                                                            P: <?= $model->shippingAddresses->phone_no; ?>
                                                        <?php } ?>
                                                        </span>
                                                    <?php } ?>
                                                    </div>
                                                    <div class="col-md-3 col-sm-12 col-xs-12 invoice-head-info"><span class='text-muted'>Order #<?= $model->id;
                                                     ?><br><?= $model->created_at ?></span></div>
                                                    <div class="col-md-3 col-sm-12 col-xs-12 invoice-log col-md-offset-1">
                                                        <img src="../images/logo.png" class="img-reponsive">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div><br>

                                            <div class="col-xs-6 invoice-infoblock pull-left">
                                                <h4><?= Yii::t('app', 'Billed To:')?></h4>
                                                <address>
                                                <?php $billed_to = \common\models\User::find()->where(['id'=>$model->order_request_id])->one(); ?>
                                                    <h3><?= $billed_to->first_name .'&nbsp;'.$billed_to->last_name;  ?></h3>
                                                    <span class='text-muted'><?= $billed_to->address;?><br><?= $billed_to->district.'&nbsp'.$billed_to->district;?><br>
                                                    <?= $billed_to->country.'&nbsp'.$billed_to->postal_code;?></span>
                                                </address>
                                            </div>

                                            <div class="col-xs-6 invoice-infoblock text-right">
                                                <h4><?= Yii::t('app', 'Payment Method')?></h4>
                                                <address>
                                                    <!-- <h3>Credit Card</h3> -->
                                                    <span class='text-muted'><?php if(isset($model->payment_method)){ \common\models\Lookup::$order_status[$model->payment_method]; }else{ echo 'Out of System';} ?><br>
                                                    <?= $billed_to->email ?></span>
                                                </address>

                                                <div class="invoice-due">
                                                
                                                <?php
                                                $order_sum = 0;
                                                 foreach($model->productOrders as $orders){ 
                                                   
                                                  $order_sum+=   $orders->quantity * $orders->order_price;
                                                 }?>
                                                    <h3 class="text-muted"><?= Yii::t('app', 'Total Due:')?></h3> &nbsp; <h2 class="text-primary">$<?= $order_sum;  ?> </h2>                
                                                </div>

                                            </div>


                                            <div class="clearfix"></div><br>

                                        </div>

                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <h3> <?= Yii::t('app', 'Order summary')?></h3><br>
                                                <div class="table-responsive">
                                                    <table class="table table-hover invoice-table">
                                                        <thead>
                                                            <tr>
                                                                <td><h4> <?= Yii::t('app', 'Product Name')?></h4></td>
                                                                <td class="text-center"><h4>Price <?= Yii::t('app', 'Price')?></h4></td>
                                                                <td class="text-center"><h4> <?= Yii::t('app', 'Quantity')?></h4></td>
                                                                <td class="text-right"><h4><?= Yii::t('app', 'Total')?></h4></td>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <!-- foreach ($order->lineItems as $line) or some such thing here -->
                                                            <?php
                                                            $sum= 0;
foreach($model->productOrders as $orders){ 
     $product_name = \common\models\Product::find()->where(['id'=>$orders->product_id])->one(); ?>
    
    
                                                            <tr>
                                                                <td><?= $product_name['name']; ?></td>
                                                                <td class="text-center"><?= $orders['order_price']; ?></td>
                                                                <td class="text-center"><?= $orders['quantity'];?></td>
                                                                <td class="text-right"><?= $sum+= $orders['quantity'] * $orders['order_price']; ?> </td>
                                                            </tr>
<?php } ?>
                                                            <tr>
                                                                <td class="thick-line"></td>
                                                                <td class="thick-line"></td>
                                                                <td class="thick-line text-center"><h4><?= Yii::t('app', 'SubTotal')?></h4></td>
                                                                <td class="thick-line text-right"><h4><?= $sum ?></h4></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="no-line"></td>
                                                                <td class="no-line"></td>
                                                                <td class="no-line text-center"><h4><?= Yii::t('app', 'Shipping')?></h4></td>
                                                                <td class="no-line text-right"><h4>$0</h4></td>
                                                            </tr>
                                                           
                                                            <tr>
                                                                <td class="no-line"></td>
                                                                <td class="no-line"></td>
                                                                <td class="no-line text-center"><h4><?= Yii::t('app', 'Total')?></h4></td>
                                                                <td class="no-line text-right"><h3 style='margin:0px;' class="text-primary"><?= $sum ?></h3></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>



                                        <div class="clearfix"></div><br>

                                        <div class="row">
                                        <?php if(isset($model->payment_slip)){?>

                                        
                                        <div class="col-md-6 col-sm-6 col-xs-6 text-center">
                                        <?php 
                                            $items =array();
                                                                                 $items[] = [
                                                                                    'url' => \yii\helpers\Url::to('@web/uploads/'.$model->payment_slip, true),
                                                                                    'src' => \yii\helpers\Url::to('@web/uploads/'.$model->payment_slip, true),
                                                                                    'options' => array('title' => 'Camposanto monumentale (inside)')
                                                                                  ];
                                            ?>
                                    <?= dosamigos\gallery\Gallery::widget(['items' => $items]);?>
                                        </div>
                                            <div class="col-md-6 col-sm-6 col-xs-6 text-center">
                                        <?php } else {?>
                                            <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                                        
                                        <?php } ?>
                                                <a href="#" target="_blank" class="btn btn-purple btn-md"><i class="fa fa-print"></i> &nbsp; <?= Yii::t('app', 'Print')?> </a>        
                                                <a href="#" target="_blank" class="btn btn-orange btn-md"><i class="fa fa-send"></i> &nbsp; <?= Yii::t('app', 'Send')?> </a> 
                                                <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>       
                                            </div>
                                        </div>


                                        <!-- end -->


                                    </div>
                                </div>
                            </div>
                        </section></div>


            





        
    </p>
</div>
