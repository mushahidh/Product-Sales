<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'User',
]) . $model->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="user-update" >
    <?php
    $user_id = Yii::$app->user->getId();
 $Role =   Yii::$app->authManager->getRolesByUser($model->id);
 if(isset($Role['customer'])){?>
   <?= $this->render('_form_customer_update', [
        'model' => $model,
    ]) ?>
<?php
 }else{ ?>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    <?php
 }
  
?>
</div>
