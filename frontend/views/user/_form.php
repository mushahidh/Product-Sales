<?php

use kartik\file\FileInput;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
.row{
    magin-left: 0px;
    magin-right: 0px;

}
</style>

<section class="box">
    <header class="panel_header">
        <h1 class="title pull-left"><?=Html::encode($this->title)?></h1>
        <div class="actions panel_actions pull-right">
            <i class="box_toggle fa fa-chevron-down"></i>
        </div>
    </header>
    <div class="content-body">
        <?php
$form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);
$user_id = Yii::$app->user->getId();
$Role = Yii::$app->authManager->getRolesByUser($user_id);
?>

        <div class="row no-margin">
            <div class="col-md-6">
                <div class="col-md-4">
                    <?=Yii::t('app', 'First Name')?>
                </div>
                <div class="col-md-8">
                <?=$form->field($model, 'first_name')->textInput(['maxlength' => true, 'required' => true])->label(false)?>
            </div>
        </div>

        <div class="col-md-6">
            <div class="col-md-4">
                <?=Yii::t('app', 'Last Name');?>
            </div>
            <div class="col-md-8">
                <?=$form->field($model, 'last_name')->textInput(['maxlength' => true, 'required' => true])->label(false)?>
            </div>
        </div>
    </div>
    <div class="row no-margin">
        <div class="col-md-6">
            <div class="col-md-4">
                <?=Yii::t('app', 'Username');?>
            </div>
            <div class="col-md-8">
                <?=$form->field($model, 'username')->textInput(['maxlength' => true, 'readonly' => !$model->isNewRecord, 'required' => true])->label(false)?>
            </div>
        </div>

        <div class="col-md-6">
            <div class="col-md-4">
                <?=Yii::t('app', 'Password');?>
            </div>
            <div class="col-md-8">
                <?=$form->field($model, 'password')->textInput(['maxlength' => true, 'required' => $model->isNewRecord])->label(false)?>
            </div>
        </div>
    </div>

    <div class="row no-margin">
        <div class="col-md-6">
            <div class="col-md-4">
                <?=Yii::t('app', 'Email');?>
            </div>
            <div class="col-md-8">
                <?=$form->field($model, 'email')->textInput(['maxlength' => true, 'required' => true])->label(false)?>
            </div>
        </div>

        <div class="col-md-6">
            <div class="col-md-4">
                <?=Yii::t('app', 'Phone');?>
            </div>
            <div class="col-md-8">
                <?=$form->field($model, 'phone_no')->textInput(['maxlength' => true])->label(false)?>
            </div>
        </div>
    </div>

    <div class="row no-margin">
        <div class="col-md-6">
            <div class="col-md-4">
                <?= Yii::t('app', 'Postal Code') ?>
            </div>
            <div class="col-md-8">
                    <?php
                    echo $form->field($model, 'postal_code')->widget(Select2::classname(), [
                        'theme' => Select2::THEME_BOOTSTRAP,
                        'options' => ['placeholder' => 'Select a Postal Code ...'],
                        'initValueText' => isset($model->postal_code) ? $model->province."-".$model->district."-".$model->postal_code : "",
                        'pluginOptions' => [
                            'allowClear' => true,
                            //'autocomplete' => true,
                            'ajax' => [
                                'url' => Url::base().'/postcode/all-code',
                                'dataType' => 'json',
                                'data' => new \yii\web\JsExpression('function(params) { ; return {q:params.term}; }')
                            ],
                        ],
                    ])->label(false);
                    ?>
            </div>
         </div>
        <div class="col-md-6">
            <div class="col-md-4">
             <?= Yii::t('app', 'District') ?>
            </div>
            <div class="col-md-8">
                <?= $form->field($model, 'district')->textInput(['readonly' => true,])->label(false) ?>
            </div>
        </div>
    </div>
    <div class="row no-margin">
        <div class="col-md-6">
            <div class="col-md-4">
                <?= Yii::t('app', 'Province') ?>
            </div>
            <div class="col-md-8">
                <?= $form->field($model, 'province')->textInput(['readonly' => true,])->label(false) ?>
            </div>
         </div>
        <div class="col-md-6">
            <div class="col-md-4">
                <?= Yii::t('app', 'Country') ?>
            </div>
            <div class="col-md-8">
                <?= $form->field($model, 'country')->textInput(['readonly' => true, 'value' => 'Thailand'])->label(false) ?>
            </div>
        </div>
    </div>
     <div class="row no-margin">
        <div class="col-md-6">
            <div class="col-md-4">
                <?=Yii::t('app', 'Address');?>
            </div>
            <div class="col-md-8">
                <?=$form->field($model, 'address')->textInput(['maxlength' => true])->label(false)?>
            </div>
         </div>
        <div class="col-md-6">
            <div class="col-md-4">
                <?=Yii::t('app', 'City');?>
            </div>
            <div class="col-md-8">
                <?=$form->field($model, 'city')->textInput()->label(false)?>
            </div>
        </div>
    </div>

    <div class="row no-margin">
        <div class="col-md-6">
            <div class="col-md-4">
                <?=Yii::t('app', 'Upload Profile');?>
            </div>
            <div class="col-md-8">
                <?=
$form->field($model, 'profile')->widget(FileInput::classname(), [

    'pluginOptions' => [
        'showUpload' => true,
        'initialPreview' => [
            $model->profile ? Html::img(Yii::$app->request->baseUrl . '/uploads/' . $model->profile) : null, // checks the models to display the preview
        ],
        'overwriteInitial' => true,
    ],
])->label(false);
?>
            </div>
        </div>
        <div class="col-md-6">

        </div>
    </div>
    <?php

if (!$model->isNewRecord && isset($Role['super_admin'])) {

    if (array_search($model->user_level_id, array_keys(\common\models\Lookup::$seller_levels)) > 0) {
        $user_level_name = $model->userDetail($model->id);
        ?>
    <div class="row no-margin">
        <div class="col-md-6">
            <div class="col-md-4">
                User Level
            </div>
            <div class="col-md-8">
                <?php
echo $form->field($model, 'user_level_id')->widget(Select2::classname(), [
            'initValueText' => $user_level_name['name'],
            'theme' => Select2::THEME_BOOTSTRAP,
            'options' => ['placeholder' => 'Select a current user Level ...'],
            'pluginOptions' => [
                'allowClear' => true,
                'ajax' => [
                    'url' => Url::base() . '/user/get-seller-levels',
                    'dataType' => 'json',
                    'data' => new \yii\web\JsExpression('function(params) {
                             return {q:params.term,parent_level:' . $user_level_name["parent_id"] . '}; }'),
                ],
            ],
        ])->label(false);
        ?>
            </div>
         </div>
    </div>
    <?php }
}
?>
<?php if ($model->isNewRecord) {
    if (isset($Role['super_admin'])) {?>
<div class="row no-margin">
    <div class="col-md-6">
        <div class="col-md-4">
            Company User
        </div>
        <div class="col-md-8">
        <?=$form->field($model, 'company_user')->checkbox(['label' => null])?>
        </div>
    </div>
    <div class="col-md-6">
 
    </div>
</div>

<div class="row no-margin">

    <div class="col-md-6">
        <div class="col-md-4">
          <b>  All Level </b>
        </div>
        <div class="col-md-8">
    <?php
echo $form->field($model, 'all_level')->widget(Select2::classname(), [
        'data' => common\models\UsersLevel::getAllLevels(),
        'theme' => Select2::THEME_BOOTSTRAP,
        'options' => ['placeholder' => 'Select a Level  ...'],
        //'initValueText' => isset($model->customerUser->customer_name) ? $model->customerUser->company_name : "",

        'theme' => Select2::THEME_BOOTSTRAP,
        'pluginOptions' => [
            'allowClear' => true,

        ],

    ])->label(false);
        ?>
    </div>
</div>
    <div class="col-md-6">
        <div class="col-md-4">
            Parent User
        </div>
        <div class="col-md-8">
    <?php
echo $form->field($model, 'parent_user')->widget(Select2::classname(), [
            'theme' => Select2::THEME_BOOTSTRAP,
            'options' => ['placeholder' => 'Select a Parent User ...'],
            'pluginOptions' => [
                'allowClear' => true,
                //'autocomplete' => true,
                'ajax' => [
                    'url' => Url::base() . '/user/get-users',
                    'dataType' => 'json',
                    'data' => new \yii\web\JsExpression('function(params) {
            if($("#user-company_user").is(":checked")){
                var company_user = 1;
            }else{
                var company_user = 0;
            }
             var user_level = $("#user-all_level").val();
            return {
                q:params.term,user_level:user_level,company_user: company_user};
            }'),
                ],
            ],
        ])->label(false);

        ?>
    </div>
</div>
</div>
<?php } else {?>
    <input type="hidden" value="<?=Yii::$app->user->identity->user_level_id;?>" id="user-all_level">
<?php }?>

    <div class="row no-margin">
        <div class="col-md-6">
            <div class="col-md-4">
           Country
        </div>
        <div class="col-md-8">
    <?=$form->field($model, 'country')->textInput(['readonly' => true, 'value' => 'Thailand '])->label(false)?>
</div>
    </div>

         <?php if ($model->isNewRecord) {?>
    <div class="col-md-6">
        <div class="col-md-4">
            User Level
        </div>
        <div class="col-md-8">

   <?php
echo $form->field($model, 'user_level_id')->widget(Select2::classname(), [
        'theme' => Select2::THEME_BOOTSTRAP,
        'options' => ['placeholder' => 'Select a current user Level ...'],
        'pluginOptions' => [
            'allowClear' => true,
            //'autocomplete' => true,
            'ajax' => [
                'url' => Url::base() . '/user/get-levels',
                'dataType' => 'json',
                'data' => new \yii\web\JsExpression('function(params) {
            if($("#user-company_user").is(":checked")){
                var company_user = 1;
            }else{
                var company_user = 0;

            }
            var parent_id = $("#user-all_level").val();return {q:params.term,parent_id:parent_id,company_user: company_user}; }'),
            ],
        ],
    ])->label(false);

        ?>
    </div>
    <?php }?>

</div>
<div class="row no-margin">
    <div class="col-md-6">
    <div class="col-md-4">
            Current Stock
        </div>
        <div class="col-md-8">
        <?php

    if (!isset($Role['super_admin'])) {
        $order_quantity = \common\models\Order::orderQuantity($user_id);
        ?>
    <input type="text" id="order-orde" readonly="true" class="form-control" value="<?=$order_quantity['remaning_stock']?>" name="Order[total_stock]" maxlength="45">

          <?php
} else {
        ?>
        <input type="text" id="order-orde" readonly="true" class="form-control" name="Order[total_stock]" maxlength="45">
         <?php }?>
    </div>
    </div>
    <div class="col-md-6">

        <div class="col-md-4">
            Status
        </div>
        <div class="col-md-8">
        <?php
echo $form->field($model, 'status')->widget(Select2::classname(), [
        'data' => common\models\Lookup::$user_status,
        'theme' => Select2::THEME_BOOTSTRAP,
        'options' => ['placeholder' => 'Select a Status  ...'],
        //'initValueText' => isset($model->customerUser->customer_name) ? $model->customerUser->company_name : "",
        'theme' => Select2::THEME_BOOTSTRAP,
        'pluginOptions' => [
            'allowClear' => true,
        ],

    ])->label(false);
    ?>
        </div>
    </div>

    </div>
    <div class="row">

    <h3>Order Items</h3>
    <div class="row no-margin">
    
<div class="col-md-5">
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
</div>
<div class="col-md-5">
<?php echo $form->field($model, 'quantity')->textInput(['maxlength' => true]); ?>
</div>
<div class="col-md-2">
<label class="control-label" for="order-quantity" style="visibility:hidden;width: 100%">Item</label>
    <button class=" btn btn-brand-primary add-button" id="add-button" type="button"><span class="loading-next-btn"></span>add item</button>
    </div>
    </div>

<input type="hidden" id="order-hidden" class="form-control" name="User[product_order_info]" maxlength="45"  aria-invalid="true">

<div class="row no-margin">
    <div id="items_all"></div>
    <div class="noproduct"></div>
    <div class="vehcle_not_found" style="color: #a94442;"></div>

</div>        

</div>  
    <?php }?>

</div>

<div class="row no-margin">
    <div class="col-md-6">
        <div class="col-md-4">
            <div class="form-group">
                <?=Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success save-button'])?>
            </div>
        </div>
    </div>
</div>

    <?php ActiveForm::end();?>
</div>
</section>
<script>
 <?php 
if(isset($model->product_order_info)){
    \common\models\Order::producOrderGrid($model->product_order_info);
        }
?>
jQuery(document).ready(function() {
 
    $('#user-company_user').change(function(){
    if($('#user-company_user').is(':checked')){
        $("#user-all_level").select2('val', 'All');
        $("#user-parent_user").select2('val', 'All');
        $("#user-user_level_id").select2('val', 'All');
    }else{
        $("#user-all_level").select2('val', 'All');
        $("#user-parent_user").select2('val', 'All');
        $("#user-user_level_id").select2('val', 'All');
    }
    
   });
    $('#user-postal_code').on('change', function () {
        var data = $('#user-postal_code').select2('data');
        var postal_data=data[0].text;
        var province=postal_data.split('-')[0];
        var district=postal_data.split('-')[1];
        $("#user-district").val(district);
        $("#user-province").val(province);
    });
$('#add-button').on('click', function () {
        $(".noproduct").hide();
        var product_id = $('#user-product_id').val();
        $.post("../user-product-level/getunitsprice?id=" + $('#user-quantity').val()+"&user_level="+$('#user-user_level_id').val()+"&product_id="+product_id+"&type=null&check_units="+false, function (data) {
        var json = $.parseJSON(data);
        if($('#user-quantity').val()  * parseFloat(json.price) > 0){
            already_in_table = false;
            for (var i = 0; i < window.db_items.clients.length; i++) {
              if(window.db_items.clients[i].product == json.pname){
                      already_in_table = true;
                  }
            }
            if(already_in_table==false){
                        db_items.clients.push({
                           unit: $('#user-quantity').val(),
                           price: json.price,
                           product: json.pname,
                           product_id: json.pid,
                           total_price: parseFloat($('#user-quantity').val()) * parseFloat(json.price),
                       });
                       console.log(db_items.clients);
            $("#items_all").jsGrid("loadData");
                    }
        }else{
            $('#user-quantity').val(''); 
            $(".noproduct").show();
            $(".noproduct").html("<h5 style='text-align:center;color:red;'>You cannot purchase less than "+json.units+"</h5>");
        }
        });
 
    });

});

    $('#user-product_id').on('change', function () {
        var product_id = $('#user-product_id').val();
        $.post("../stock-in/getunits?id="+product_id+"&user_id="+$('#user-parent_user').val(), function (data) {
    $('#order-orde').val(data);
        });
    });

</script>