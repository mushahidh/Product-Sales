<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\models\order;
use kartik\file\FileInput;
use yii\db\Query;
use yii\helpers\Url;
?>
<!-- Agent order starts from here-->
<div class="request-setting">
    <div class="admin">
        <div class="row">
            <div class="col-md-2">
                <?=Yii::t('app', 'User Level')?>
            </div>
            <div class="col-md-10">
                <?php
                    echo $form->field($model, 'all_level')->widget(Select2::classname(), [
                    'data' => common\models\UsersLevel::getAllLevels(),
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
        </div>
        <div class="row">
            <div class="col-md-2">
                <?=Yii::t('app', 'Parent User')?>
            </div>
            <div class="col-md-10">
                <?php
                    if (isset($Role['super_admin'])) {
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
                    } else {
                        echo $form->field($model, 'parent_user')->widget(Select2::classname(), [
                            'theme' => Select2::THEME_BOOTSTRAP,
                            'initValueText' => isset($model->parent_user) ? $model->username($model->parent_user) : "",
                            'options' => ['placeholder' => 'Select a Parent User ...', 'value' => Yii::$app->user->identity->parent_id],
                        ])->label(false);
                    }
            ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-2">
                <?=Yii::t('app', 'Child Level')?>
            </div>
            <div class="col-md-10">
                <?php
                    if (isset($Role['super_admin'])) {
                        echo $form->field($model, 'child_level')->widget(Select2::classname(), [
                            'theme' => Select2::THEME_BOOTSTRAP,
                        'initValueText' => isset($model->child_level) ? $model->leveluser($model->child_level) : "",
                            'options' => ['placeholder' => 'Select a child user Level ...'],
                            'pluginOptions' => [
                                'allowClear' => true,
                                //'autocomplete' => true,
                                'ajax' => [
                                    'url' => Url::base().'/user/get-levels',
                                    'dataType' => 'json',
                                    'data' => new \yii\web\JsExpression('function(params) { var parent_id = $("#order-all_level").val(); return {q:params.term,parent_id:parent_id}; }'),
                                ],
                            ],
                        ])->label(false);
                    } else {
                        echo $form->field($model, 'child_level')->widget(Select2::classname(), [
                            'theme' => Select2::THEME_BOOTSTRAP,
                            'initValueText' => isset($model->child_level) ? $model->leveluser($model->child_level) : "",
                            'options' => ['placeholder' => 'Select a Parent User ...', 'value' => Yii::$app->user->identity->user_level_id],
                        ])->label(false);
                    }
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-2">
                <?=Yii::t('app', 'Child Name')?>
            </div>
            <div class="col-md-10">
                <?php
                    if (isset($Role['super_admin'])) {
                        echo $form->field($model, 'child_user')->widget(Select2::classname(), [
                            'theme' => Select2::THEME_BOOTSTRAP,
                        'initValueText' => isset($model->child_user) ? $model->username($model->child_user) : "",
                            'options' => ['placeholder' => 'Select a current user Level ...'],
                            'pluginOptions' => [
                                'allowClear' => true,
                                //'autocomplete' => true,
                                'ajax' => [
                                    'url' => Url::base().'/user/get-users',
                                    'dataType' => 'json',
                                    'data' => new \yii\web\JsExpression('function(params) { var parent_id = $("#order-parent_user").val();
                                                                        var user_level = $("#order-child_level").val();
                                                                        return {q:params.term,parent_id:parent_id,user_level:user_level}; }'),
                                ],
                            ],
                        ])->label(false);
                    } else {
                        echo $form->field($model, 'child_user')->widget(Select2::classname(), [
                            'theme' => Select2::THEME_BOOTSTRAP,
                            'options' => ['placeholder' => 'Select a current user Level ...', 'value' => Yii::$app->user->identity->id],
                        ])->label(false);
                    }
                ?>
            </div>
        </div>
    </div>
</div>