<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\LevelPercentage */

$this->title = Yii::t('app', 'Create Level Percentage');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Level Percentages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="level-percentage-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
