<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\models\order;
use kartik\file\FileInput;
use yii\db\Query;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Order */
/* @var $form yii\widgets\ActiveForm */
?>

<style>
    .order-setting-panel
    {
        display:none;
    }
</style>
 <?php
        if (Yii::$app->user->isGuest) {
            $class="col-md-offset-1  col-md-10";
        }
        else{
            $class="col-md-10";
        }
        $referral_id = Yii::$app->request->get('id');// For Customers
        $referral_user=null;
        if(!empty($referral_id))
        {
            $referral_user=\common\models\User::findOne(['id'=>$referral_id]);
        }
        $user_id = Yii::$app->user->getId();
        $Role = Yii::$app->authManager->getRolesByUser($user_id);
        $RoleName='';
        if(!empty($Role))
        {
            $RoleName = array_keys($Role)[0];
        }
if (!Yii::$app->user->isGuest) {        
?>

<section class="box">
    <header class="panel_header">
        <h1 class="title pull-left"><?= Html::encode($this->title) ?></h1>
        <div class="actions panel_actions pull-right">
            <i class="box_toggle fa fa-chevron-down"></i>
        </div>
    </header>
    <div class="content-body">
<?php } ?>
        <div class="order-form">
        <?php if(!$model->isNewRecord){?>
  <!-- <p>Payment failed</p> -->
       <?php } ?>
            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
                    <?php if (!Yii::$app->user->isGuest) { ?>  
                        <div class="row">
                            <div class="<?=$class;?> order-settings">
                                <?=
                                        Yii::$app->controller->renderPartial('_order_setting', [
                                            'model' => $model,
                                            'form' => $form,
                                            'user_id' => $user_id,
                                            'Role' => $Role,
                                            'type' => $type,
                                        ]);
                                ?>
                            </div>
                        </div>
                    <?php } ?>
                    <!-- this is order detail section -->
                    <div class="row order-details">
                        <div class="<?=$class;?>">
                            <?=
                            Yii::$app->controller->renderPartial('_order_detail', [
                                'model' => $model,
                                'form' => $form,
                                'user_id' => $user_id,
                                'Role' => $Role,
                            ]);
                            ?>
                        </div>
                    </div>
                    <!-- this is customer section-->
                    <div class="row shipping-address">
                        <div class="<?=$class;?>">
                            <?=
                            Yii::$app->controller->renderPartial('_shipping', [
                                'model' => $model,
                                'form' => $form,
                                'user_id' => $user_id,
                                'Role' => $Role,
                            ]);
                            ?>
                        </div>
                    </div>
                    <!-- customer section ends here-->
                    <!-- this is order items section -->
                    <div class="row">
                        <div class="<?=$class;?>">
                            <?=
                            Yii::$app->controller->renderPartial('_order_item', [
                                'model' => $model,
                                'form' => $form,
                                'user_id' => $user_id,
                                'Role' => $Role,
                            ]);
                            ?>
                        </div>
                    </div>
                    <!-- order items section end here-->
                    <div class="help-block help-block-error vehcle_not_found" style="color: #a94442;"></div>
                    <?= $form->field($model, 'order_type')->hiddenInput(['value' => $type])->label(false);?>
                    <div class="row no-margin">
                        <div class="<?= $class ?>">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success save-button']) ?>
                                </div>
                            </div>
                        </div>
                    </div>
            <?php ActiveForm::end(); ?>
        </div>
<?php if (!Yii::$app->user->isGuest) { ?>     
    </div>
</section>
<?php } ?>
<script type="text/javascript">
    jQuery(document).ready(function() {
        var type = '<?= $type ?>';
        <?php
        if(!empty($referral_user))
        {
        ?>
            $("#order-representative").val("<?= $referral_user['username'] ?>");
        <?php  
        }
        ?>
        if(type=="Return")
        {
            $('#order-child_user').on('change', function () {
                GetUserStock($(this).val());
         });
        }
        else
        {
            $('#order-request_agent_name,#order-parent_user').on('change', function () {
                GetUserStock($(this).val());
         });
        }
        
         $('#order-postal_code').on('change', function () {
                var data = $('#order-postal_code').select2('data');
                var postal_data=data[0].text;
                var province=postal_data.split('-')[0];
                var district=postal_data.split('-')[1];
                $("#order-district").val(district);
                $("#order-province").val(province);
         });
        
        //this code is to hidden the grid and show for order and request if user login
        $('#order-quantity').on('blur', function () {
            var url="<?=Yii::$app->homeUrl?>user-product-level/getunitsprice?id=" + $('#order-quantity').val() + "&user_level=" + (typeof($('#order-child_level').val())  === "undefined"?$('#order-all_level').val():$('#order-child_level').val()) + "&product_id=" + $('#order-product_id').val();
            
            if (type == "Request" || type == "Transfer"){
                url+="&type="+type;
            $.post(url, function (data) {
                var json = $.parseJSON(data);
                    if (json.price){
                    $(".noproduct").hide();
                    $('#order-single_price').val(json.price);
                    $('#order-total_price').val(parseFloat($('#order-quantity').val()) * parseFloat(json.price));
                } else{
                    $(".noproduct").show();
                    $(".noproduct").html("<h5 style='text-align:center;color:red;'>You cannot purchase less than  " + json.units + " Units</h5>");
                    $('#order-quantity').val('');
                }
            });
        } else{
                if(type!="Return")
                {
                    url="<?=Yii::$app->homeUrl?>product/get-product?id=1";    
                }
                else
                {
                    url+="&type=Return";
                }
                $.post(url, function (data) {
                <?php if (!Yii::$app->user->isGuest) { ?>
                    if (parseInt($('#available-stock').val()) >= parseInt($('#order-quantity').val())){
                        if ($('#order-quantity').val()){
                            if(type=="Return")
                            {
                                 data = $.parseJSON(data);
                            }
                            $(".noproduct").hide();
                            $('#order-single_price').val(data.price);
                            $('#order-total_price').val($('#order-quantity').val() * data.price);
                        } else{
                        $(".noproduct").show();
                            $(".noproduct").html("<h5 style='text-align:center;color:red;'>The value can not empty and must be less than stock.</h5>");
                        }
                    } else{
                        $(".noproduct").show();
                        $(".noproduct").html("<h5 style='text-align:center;color:red;'>Out of Stock </h5>");
                        $('#order-quantity').val('');
                    }
                <?php }
                else
                {
                ?>
                    $('#order-single_price').val(data.price);
                    $('#order-total_price').val($('#order-quantity').val() * data.price);
                <?php
                }
                ?>
            });
        }
    });
    <?php if (!Yii::$app->user->isGuest) { ?>
        
        var role = "<?php echo array_keys($Role)[0]; ?>";
        TypeChange(role);
        if (role == 'super_admin')
        {
            $('.admin').show();
            $('.order-setting-panel').show();
            $('.order-settings').show();
        }
        else if (role == 'general' || role=='seller')
        {
            $('.admin').hide();
            $('.agent').show();
           
            if(type!="Transfer")
            {
                $('.order-setting-panel').hide();
                $('.order-settings').hide();
            }
        }
       
       <?php } ?>

    });
    function TypeChange(role)
        {
            var value = "<?= $type ?>";
           
            if (value == "Request" || value == "Return" || value == "Transfer")
            {
                jQuery(".shipping-address").hide();
                jQuery(".order-details").hide();
                
            }
            else
            {
                jQuery(".shipping-address").show();
                jQuery(".order-details").show();
            }
           
        }
        function GetUserStock(user_id)
        {
            $.post("<?=Yii::$app->homeUrl?>stock-in/getunits?id=" + $('#order-product_id').val() + "&user_id=" + user_id, function (data) {
                $('#available-stock').val(data);
                var data = $('#order-request_agent_name').select2('data');
                var user_name=data[0].text;
                $("#order-representative").val(user_name);
            });
        }
</script>
