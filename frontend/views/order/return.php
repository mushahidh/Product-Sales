<?php

use kartik\select2\Select2;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Return Order";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <h1><?=Html::encode($this->title)?></h1>
    <?php Pjax::begin();?>
    <?php // echo $this->render('_search', ['model' => $searchModel]);
?>

<p>
<?=Html::a(Yii::t('app', 'Return Order'), ['create?type=Return'], ['class' => 'btn btn-success'])?>
    </p>

    <?=GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

        [
            'attribute' => 'user_id',
            'label' => Yii::t('app', 'Return from'),
            'value' => function ($model, $key, $index, $widget) {
                return $model->username($model->user_id);
            },
            'filter' => Select2::widget([
                'model' => $searchModel,
                'initValueText' => isset($model->user_id) ? $model->username($model->user_id) : "",
                'attribute' => 'user_id',
                'options' => ['placeholder' => 'Select User Name ...'],
                'pluginOptions' => [
                    'allowClear' => true,
                    //'autocomplete' => true,
                    'ajax' => [
                        'url' => Url::base() . '/user/get-users',
                        'dataType' => 'json',
                        'data' => new \yii\web\JsExpression('function(params) { return {q:params.term}; }'),
                    ],
                ],
                // ... other params
            ]),
        ],
        [
            'attribute' => 'Return to',
            'label' => Yii::t('app', 'Transfer to'),
            'value' => function ($model, $key, $index, $widget) {
                return $model->username($model->order_request_id);
            },
            'filter' => Select2::widget([
                'model' => $searchModel,
                'attribute' => 'order_request_id',
                'initValueText' => isset($model->order_request_id) ? $model->username($model->order_request_id) : "",
                'options' => ['placeholder' => 'Select User Name ...'],
                'pluginOptions' => [
                    'allowClear' => true,
                    //'autocomplete' => true,
                    'ajax' => [
                        'url' => Url::base() . '/user/get-users',
                        'dataType' => 'json',
                        'data' => new \yii\web\JsExpression('function(params) { return {q:params.term}; }'),
                    ],
                ],
                // ... other params
            ]),
        ],

        'order_ref_no',
        [
            'label' => 'Quantity and Price',
            'format' => 'raw',
            'value' => function ($model) {
                return $model->productorder($model->id);
            },
        ],
        [
            'header' => Yii::t('app', 'Approve'),
            'format' => 'raw',
            'value' => function ($model) {
                $loggedInUserRole = Yii::$app->authManager->getRolesByUser(Yii::$app->user->identity->id);

                if ($model->status == '3') {
                    if ($model->order_request_id == Yii::$app->user->identity->id || isset($loggedInUserRole['super_admin'])) {
                        return "<div class='payment_button_general_approve' ><a user_id='" . $model->order_request_id . "' ref_id='" . $model->user_id . "' class='" . $model->id . "' >".Yii::t('app', 'Approve')."</a></div>";
                    } else {
                        return "<div class='pending_approval'>".Yii::t('app','Pending')."</div>";
                    }
                } else {
                    return "<div class='payment_button_general_approved' ><a>".Yii::t('app','Approved')."</a></div>";
                }
            },
        ],
        [
            'header' => 'Is Rejected',
            'format' => 'raw',
            'value' => function ($model) {
                $loggedInUserRole = Yii::$app->authManager->getRolesByUser(Yii::$app->user->identity->id);
                if ($model->status == '3') {
                    if ($model->order_request_id == Yii::$app->user->identity->id || isset($loggedInUserRole['super_admin'])) {
                        return "<div class='payment_button_general_cancel' ><a class='" . $model->id . "' >".Yii::t('app', 'Reject')."</a></div>";
                    } else {
                        return "<div class='pending_approval'>".Yii::t('app','Pending')."</div>";
                    }
                } else {
                    return "<div class='payment_button_general_cancel' ><a class='" . $model->id . "' >".Yii::t('app', 'No')."</a></div>";
                }
            },
        ],
        ['class' => 'yii\grid\ActionColumn',
            'template' => '{view}{edit}{delete}',
            'buttons' => [
                'view' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Yii::$app->homeUrl . 'order/view/' . $model->id);
                },
                'edit' => function ($url, $model) {
                    if ($model->status == array_search('Return Request', \common\models\Lookup::$status) && $model->created_by == Yii::$app->user->identity->id) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->homeUrl . 'order/update/' . $model->id);
                    }
                },

            ],
            'visibleButtons' => [
                'delete' => function ($model) {
                    if ($model->status == array_search('Return Request', \common\models\Lookup::$status) && $model->created_by == Yii::$app->user->identity->id) {
                        return true;
                    }

                },
            ],
        ],
    ],
]);?>
    <?php Pjax::end();?>


<script>
        $("body").delegate(".payment_button_general_cancel a", "click", function () {
            if (window.confirm("Are you sure?")) {
       var id =    $(this).attr('class');
            $.ajax({
                type: "POST",
                context: this,
                data:  {id:id},
               // data: "id="+id+"status+"+status,
                url: "<?php echo Yii::$app->getUrlManager()->createUrl('stock-in/cancel'); ?>",
                success: function (test) {
                   $(this).parent().removeClass('payment_button_general_cancel');
                   $(this).text('Cancled');

                },
                error: function (exception) {
                    alert(exception);
                }
            });
            }
        });
        $("body").delegate(".payment_button_general_approve a", "click", function () {
            if (window.confirm("Are you sure?")) {
       var id =    $(this).attr('class');
       var user_id =    $(this).attr('user_id');
       var order_request_id =    $(this).attr('ref_id');

            $.ajax({
                context: this,
                type: "POST",
                data:  {id:id, user_id:user_id,order_request_id: order_request_id },
               // data: "id="+id+"status+"+status,
                url: "<?php echo Yii::$app->getUrlManager()->createUrl('stock-in/approve'); ?>",
                success: function (test) {
                    if(test == '1'){
                        $(this).parent().removeClass('payment_button_general_approve');
                       $(this).text('Approved');
                       }else{
                        $(this).text('Out of Stock');
                        $(this).css("color", "red");
                       }
                },
                error: function (exception) {
                    alert(exception);
                }
            });
        }
        });
    </script>

