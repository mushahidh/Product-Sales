<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\select2\Select2;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $searchModel common\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'ORDERS');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
    <?php
    $user_id = Yii::$app->user->getId();
   $Role =   Yii::$app->authManager->getRolesByUser($user_id);
//if(!isset($Role['seller'])){ 
    ?>
        <?= Html::a(Yii::t('app', 'Create Customer Order'), ['create?type=Order'], ['class' => 'btn btn-success']) ?>
<?php
//}
?>
        <?= Html::a(Yii::t('app', 'Create Agent Order'), ['create?type=Request'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'order_request_id',
                'label'=>Yii::t('app', 'Transfer from'),
                'value'=>function ($model, $key, $index, $widget) { 
                    return $model->username($model->order_request_id);
                },
                'filter'=>Select2::widget([
                'model' => $searchModel,
                'initValueText' => isset($model->order_request_id) ? $model->username($model->order_request_id) : "",
                'attribute' => 'order_request_id',
                'options' => ['placeholder' => 'Select User Name ...'],
                'pluginOptions' => [
                'allowClear' => true,
                //'autocomplete' => true,
                'ajax' => [
                    'url' => Url::base().'/user/get-users',
                    'dataType' => 'json',
                    'data' => new \yii\web\JsExpression('function(params) { return {q:params.term}; }')
                ],
        ],
    // ... other params
]),
 ],
 [
    'attribute' => 'user_id',
    'label'=>Yii::t('app', 'Transfer to'),
    'value'=>function ($model, $key, $index, $widget) { 
        if($model->username($model->user_id)!="customer")
            {
                return $model->username($model->user_id);
            }
            else
            {
                return $model->name;
            }
    },
    'filter'=>Select2::widget([
    'model' => $searchModel,
    'attribute' => 'user_id',
    'initValueText' => isset($model->user_id) ? $model->username($model->user_id) : "",
   'options' => ['placeholder' => 'Select User Name ...'],
   'pluginOptions' => [
    'allowClear' => true,
    //'autocomplete' => true,
    'ajax' => [
        'url' => Url::base().'/user/get-users',
        'dataType' => 'json',
        'data' => new \yii\web\JsExpression('function(params) { return {q:params.term}; }')
    ],
],
// ... other params
]),
],    
   
        [
            'label' => Yii::t('app', 'Status'),
      'attribute' => 'status',
      'format' => 'raw',
      'value' => function ($model) {                      
           return  $model->status == 0 ? "Pending" : ($model->status == 1 ? "Approved" : ($model->status == 2 ? "Request Canceled" :($model->status == 3 ? "Return Request" :($model->status == 4 ? "Return Approved" :($model->status == 5 ? " Transfer Request" :($model->status == 6 ? "Transfer Approved" :($model->status == 7 ? "Transfer Cancelled" :($model->status == 8 ? "Return  Cancelled " : "Unknown"))))))));
      },
       'filter'=>[  0 => "Pending",
       1 => "Approved",
       2 => "Request Canceled",
       3 => "Return Request",
       4 => "Return Approved",
       5 => "Transfer Request",
       6 => "Transfer Approved",
       7 => "Transfer Canceled",
       8 => "Return Canceled",],
   ],
            'order_ref_no',
            [
                'label' => 'Payment Mode',
                'format' => 'raw',
                'value' => function($model) {
                    if(isset($model->payment_method))
                    {
                        return  \common\models\Lookup::$payment_method[$model->payment_method];
                    }
                    return '';
                },
            ],
            'shipper',
             ['class' => 'yii\grid\ActionColumn',
            'template' => '{view}{edit}{delete}',
            'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Yii::$app->homeUrl.'order/view/'.$model->id);
                    },
                    'edit' => function ($url, $model) {
                        $Role = Yii::$app->authManager->getRolesByUser($model->user_id);
                        if($model->status == array_search('Pending', \common\models\Lookup::$status) && $model->created_by == Yii::$app->user->identity->id){
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->homeUrl.'order/update/'.$model->id);
                        }
                    },
                    
                ],
                'visibleButtons' => [
                    'delete' => function ($model) {
                        if($model->status == array_search('Pending', \common\models\Lookup::$status) && $model->created_by == Yii::$app->user->identity->id){
                            return true;
                        }
                    
                    },
                ],
             ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
