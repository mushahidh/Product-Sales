<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SearchPaymentDetail */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Payment Details';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payment-detail-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Payment Detail', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'payment_method',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
