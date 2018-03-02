<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\models\order;
use kartik\date\DatePicker;
use kartik\time\TimePicker;
?>
<div class="row site-index main-container">
<style>
.show
{
    display:none;
}
.row{
    margin-left: 0px;
    margin-right: 0px;
}
</style>
    <?php
    $form = ActiveForm::begin(['id' => 'form-order-report','enableClientValidation' => true, 'enableAjaxValidation' => false,'options' => ['enctype' => 'multipart/form-data'],
                'action' => ['order/ajaxreport'],
                    ]
    );
     ?>
   		
   		<div class="filter">
           <h1><?=$type?> Report</h1>
		<div class="row">
			<div class="col-md-6">
				<div class="col-md-4">
					From Date
				</div>
				<div class="col-md-8">
					<?php
					echo DatePicker::widget([
                    'name' => 'from_date',
                    'id' => 'form-date',
				    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                    'value' => date('Y-m-d', strtotime('-7 days')),
				    'pluginOptions' => [
				        'autoclose'=>true,
				        'format' => 'yyyy-mm-dd'
				    ]
			          ]);
					?>
				</div>
			</div>

			  <div class="col-md-6">
				<div class="col-md-4">
					To Date
				</div>
				<div class="col-md-8">
					<?php
					echo DatePicker::widget([
                    'name' => 'to_date',
                    'id' => 'to_date',
				    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                    'value' => date('Y-m-d'),
				    'pluginOptions' => [
				        'autoclose'=>true,
				        'format' => 'yyyy-mm-dd'
				    ]
			          ]);
					?>
				</div>
			</div>
		</div>
<div class="row" style="padding-top: 20px;">
			<div class="col-md-6">
				<div class="col-md-4">
                User Levels
				</div>
				<div class="col-md-8">
                <?php
    echo $form->field($model, 'all_level')->widget(Select2::classname(), [
        'data' => common\models\UsersLevel::getAllLevels(true),
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
                All User
				</div>
				<div class="col-md-8">
					<?php
				 echo $form->field($model, 'parent_user')->widget(Select2::classname(), [
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => 'Select a  User ...'],
                    'pluginOptions' => [
                      'allowClear' => true,
                      //'autocomplete' => true,
                      'ajax' => [
                        'url' => '../user/get-users',
                          'dataType' => 'json',
                          'data' => new \yii\web\JsExpression('function(params) {  
                            var user_level = $("#order-all_level").val();
                            var current_user_level_id='.Yii::$app->user->identity->user_level_id.';
                            if(current_user_level_id>user_level)
                            {
                                var parent_id = '.Yii::$app->user->identity->parent_id.';
                            }
                            else
                            {
                                var parent_id = '.Yii::$app->user->identity->id.';
                            }
                            return {q:params.term,user_level:user_level,parent_id:parent_id,include_parent:true}; }')
                      ],
                  ],
                  ])->label(false);
					?>
				</div>
            </div>
            
		</div>
               
        <div class="row">
        <input type="hidden" value="<?=$type?>" id="account_type"/>
             <!-- <div class="col-md-6">
				<div class="col-md-4">
                Account Type
				</div>
				<div class="col-md-8"> -->
               
				<?php
                //    echo Select2::widget([
                //         'id'=>'account_type',
                //        'name'=>'account_type',
                //        'options' => ['placeholder' => 'Select Account Type'],
                //        'data' => ['1'=>'Receivable','2'=>'Payable'],
                //        'theme' => Select2::THEME_BOOTSTRAP,
                //        'pluginOptions' => [
                //            'allowClear' => true,
                //        ],
                //    ]); // Classic Theme
               ?>
				<!-- </div>
			</div> -->
		</div>
		<div class="row" style="padding-top: 20px;">
			<div class="col-md-6">
				<div class="col-md-3">
				 <button type="submit" class="btn btn-success submit_button">Get Report</button>
			   </div>
			</div>
		</div>
 </div>

     <?php ActiveForm::end(); ?>

     <div class="row view-report">
     	
     </div>
	 </div>
     <script>

$("body").delegate("#form-order-report .submit_button", "click", function (e) {
    e.preventDefault();
	var from_date =    $('#form-date').val();
	var to_date =   $('#to_date').val();
    var user_id =   $('#order-parent_user').val();
    var account_type =   $('#account_type').val();
    $.ajax({
        type: "POST",
    
        data:  {'from_date':from_date, 'to_date':to_date,'user_id':user_id,'account_type':account_type },
       // data: "id="+id+"status+"+status,
        url: "<?php echo Yii::$app->getUrlManager()->createUrl('reports/receivable-payable-report-result'); ?>",
        success: function (test) {
            $('.filter').hide(1000);
            $('.view-report').html(test);
            $('.view-report').show(1000);
        },
        error: function (exception) {
            alert(exception);
        }
    });

});

</script> 
    