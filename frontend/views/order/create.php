<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Order */

$this->title = Yii::t('app', 'ORDER');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Orders'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-create" style="background-color:white;">
<?php if (!Yii::$app->user->isGuest) { ?>
    <!-- <h1><?php //echo Html::encode($this->title) ?></h1> -->
<?php } 
else
{
?>
    <div style="margin-bottom:20px;margin-top:20px;" class="col-md-12">
       <img style="display: block;margin: 0 auto;" src="<?=Yii::$app->homeUrl?>images/logo.png" class="img-reponsive">
       <div style="text-align:center;margin-top:10px;">
       <?php if(!empty($product))
       {?>
            <?php //echo $product['description'];?>
       <?php
       }
       ?>
       </div>
   </div>
<?php
}
?>
    <?= $this->render('_form', [
        'model' => $model,
        'type' => $type,
    ]) ?>

</div>
