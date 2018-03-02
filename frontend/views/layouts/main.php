<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head();
    ?>
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo Yii::$app->getHomeUrl(); ?>images/favicon-16x16.png">
    <!-- <link rel="shortcut icon" href="<?php echo Yii::$app->getHomeUrl(); ?>images/favicon.ico" type="image/x-icon" /> -->
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
<?php  if (!Yii::$app->user->isGuest) {?>
    <?php $this->beginContent('@app/views/layouts/topbar.php'); ?>
            <?php $this->endContent();
}else{ ?>
 <style>
                #main-content{
                        margin-left: 0px;
                }
                </style>
                <?php } ?>
    <div class="page-container row-fluid">
    <?php  if (!Yii::$app->user->isGuest) {?>
       
   <?php $this->beginContent('@app/views/layouts/sidebar.php'); ?>
            <?php $this->endContent(); 
    }
            ?>
            <section id="main-content">
                 <section class="wrapper main-wrapper"  style="margin-bottom: 50px;">
        <?= $content ?>
 </section>
            </section>
               <?php $this->beginContent('@app/views/layouts/rightside.php'); ?>
            <?php $this->endContent(); ?>
           
            
    </div>
    
</div>
<?php $this->beginContent('@app/views/layouts/footer.php'); ?>
            <?php $this->endContent(); ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
