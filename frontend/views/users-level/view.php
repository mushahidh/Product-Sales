<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\UsersLevel */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users Levels'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="users-level-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
         <?php //Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
          //  'class' => 'btn btn-danger',
           // 'data' => [
                //'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
               // 'method' => 'post',
          //  ],
        //]) 
         ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'display_name',
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
        ],
    ]) ?>

</div>
