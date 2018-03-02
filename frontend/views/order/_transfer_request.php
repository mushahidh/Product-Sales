<?php
use kartik\select2\Select2;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
<!-- Agent order starts from here-->
<div class="transfer-setting">
    <div class="">
    <div class="row">
                            <?php
if (isset($Role['super_admin'])) {
    ?>
                                <div class="col-md-2">
                                    User Level
                                </div>
                                <div class="col-md-10">
                                    <?php
echo $form->field($model, 'all_level')->widget(Select2::classname(), [
        'data' => common\models\UsersLevel::getAlllevels(),
        'theme' => Select2::THEME_BOOTSTRAP,
        'options' => ['placeholder' => 'Select a Level  ...'],
        'initValueText' => isset($model->all_level) ? $model->leveluser($model->all_level) : "",
        'theme' => Select2::THEME_BOOTSTRAP,
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ])->label(false);
    ?>
                                </div>

                                    <?php
} else {
    echo $form->field($model, 'all_level')->hiddenInput(['value' => Yii::$app->user->identity->user_level_id])->label(false);
}
?>
                    </div>

                        <?php
$user_id = Yii::$app->user->getId();
$Role = Yii::$app->authManager->getRolesByUser($user_id);
if (isset($Role['super_admin'])) {
    ?>
                        <div class="row">
                            <div class="col-md-2">
                                Transfer From
                            </div>
                            <div class="col-md-10">
                             <?php
                                echo $form->field($model, 'parent_user')->widget(Select2::classname(), [
                                        'theme' => Select2::THEME_BOOTSTRAP,
                                        'initValueText' => isset($model->parent_user) ? $model->username($model->parent_user) : "",
                                        'options' => ['placeholder' => 'Select a Parent User ...'],
                                        'pluginOptions' => [
                                            'allowClear' => true,
                                            //'autocomplete' => true,
                                            'ajax' => [
                                                'url' => Url::base().'/user/get-users',
                                                'dataType' => 'json',
                                                'data' => new \yii\web\JsExpression('function(params) { var user_level = $("#order-all_level").val();return {q:params.term,user_level:user_level}; }'),
                                            ],
                                        ],
                                    ])->label(false);
                                    ?>
                            </div>
                        </div>
<?php
    echo "<input type='hidden' id='transfer_parent' value='".Yii::$app->user->identity->parent_id."'>";
} else {
    echo $form->field($model, 'parent_user')->hiddenInput(['value' => $user_id])->label(false);
    echo "<input type='hidden' id='transfer_parent' value='".Yii::$app->user->identity->parent_id."'>";
}
?>
                    <div class="row">
                        <div class="col-md-2">
                            Transfer to
                        </div>
                        <div class="col-md-10">
<?php
if (isset($Role['super_admin'])) {
    echo $form->field($model, 'child_user')->widget(Select2::classname(), [
        'theme' => Select2::THEME_BOOTSTRAP,
        'initValueText' => isset($model->child_user) ? $model->username($model->child_user) : "",
        'options' => ['placeholder' => 'Select a child user Level ...'],
        'pluginOptions' => [
            'allowClear' => true,
            //'autocomplete' => true,
            'ajax' => [
                'url' => Url::base().'/user/get-users',
                'dataType' => 'json',
                'data' => new \yii\web\JsExpression('function(params) {
                          var user_id = $("#order-parent_user").val();
                          var parent_id = $("#transfer_parent").val();
                          var user_level = $("#order-all_level").val();
                          return {q:params.term,user_level:user_level,parent_id:parent_id,user_id:user_id,include_self:false}; }'),
            ],
        ],
    ])->label(false);
} else {
    echo $form->field($model, 'child_user')->widget(Select2::classname(), [
        'theme' => Select2::THEME_BOOTSTRAP,
        'options' => ['placeholder' => 'Select a child user Level ...'],
        'initValueText' => isset($model->child_user) ? $model->username($model->child_user) : "",
        'pluginOptions' => [
            'allowClear' => true,
            //'autocomplete' => true,
            'ajax' => [
                'url' => Url::base().'/user/get-users',
                'dataType' => 'json',
                'data' => new \yii\web\JsExpression('function(params) {
                    var user_id = $("#order-parent_user").val();
                    var parent_id = $("#transfer_parent").val();
                    var user_level = $("#order-all_level").val();
                    return {q:params.term,user_level:user_level,parent_id:parent_id,user_id:user_id,include_self:false}; }'),
            ],
        ],
    ])->label(false);
}
?>
                        </div>
                    </div>



    </div>
</div>
<script>
jQuery(document).ready(function() {
         $('#order-parent_user').on('change', function () {
                var data = $('#order-parent_user').select2('data');
                var user_id=data[0].id;
                var url="/user/get-parent-id?id="+user_id;
                $.post(url, function (data) {
                    $("#transfer_parent").val(data);
                });
         });
});
</script>