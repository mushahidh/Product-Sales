<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\UsersLevelSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Users Levels');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="users-level-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php // Html::a(Yii::t('app', 'Create Users Level'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'display_name',
            'parent_id',
            [
                'label' => 'Parent Name',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->parentName($model->parent_id);
                },
            ],
            [
                'label' => 'Max User',
                'format' => 'raw',
                'value' => function ($model) {
                    if($model->max_user == -1){
                        return 'Un limited';
                    }else{
                        return $model->max_user;
                        
                    }
                },
            ],

            ['class' => 'yii\grid\ActionColumn','template' => '{view} {update}'],
            
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
