<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = <?php Yii::t('app', 'About') ?>
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?php Yii::t('app', 'This is the About page. You may modify the following file to customize its content') ?></p>

    <code><?= __FILE__ ?></code>
</div>
