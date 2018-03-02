<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

                    <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                        <div class="page-title">

                            <div class="pull-left">
                                <h1 class="title">User Profile</h1>                            </div>

                            <div class="pull-right hidden-xs">
                            <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
                              
                            </div>
                

                        </div>
                    </div>
                    <div class="clearfix"></div>


                    <div class="col-lg-12">
                        <section class="box nobox">
                            <div class="content-body">    <div class="row">
                                    <div class="col-md-3 col-sm-4 col-xs-12">
                                        <div class="uprofile-image">
                                       
                                        <?php if($model->profile){ ?>
                                            <?php 
                                            $items =array();
                                                                                 $items[] = [
                                                                                    'url' => \yii\helpers\Url::to('@web/uploads/'.$model->profile, true),
                                                                                    'src' => \yii\helpers\Url::to('@web/uploads/'.$model->profile, true),
                                                                                    'options' => array('title' => 'Camposanto monumentale (inside)')
                                                                                  ];
                                            ?>
                                    <?= dosamigos\gallery\Gallery::widget(['items' => $items]);?>
                                    <?php    }else{?>
                                    
                                <img src="<?= Yii::$app->homeUrl; ?>images/profile.jpg" class="img-responsive">
                                    <?php  }?>
                                        </div>
                                        <div class="uprofile-name">
                                            <h3>
                                                <a href="#"><?= $model->first_name.''.$model->last_name; ?></a>
                                                <!-- Available statuses: online, idle, busy, away and offline -->
                                                <span class="uprofile-status online"></span>
                                            </h3>
                                            <p class="uprofile-title"><?php if($model->userLevel){ echo $model->userLevel->display_name; } ?></p>
                                        </div>
                                        <div class="uprofile-info">
                                            <ul class="list-unstyled">
                                            <li><i class="fa fa-envelope"></i><?php echo $model->email; ?></li>
                                                <li><i class='fa fa-home'></i><?php echo $model->address; ?></li>
                                                <li><i class='fa fa-user'></i><?= $model->city.''.$model->country; ?></li>
                                                <?php 
                                                $user_id = Yii::$app->user->getId();
                                                $Role = Yii::$app->authManager->getRolesByUser($user_id);
                                                if(!isset($Role['super_admin'])){
                                                ?>
                                                    <li><i class="fa fa-external-link" aria-hidden="true"></i><a target="_blank" href="<?= Yii::$app->getUrlManager()->getBaseUrl(); ?>/order/create/<?= $model->id ?>"> <?= Yii::t('app', 'Generate Customer Link') ?></a></li>
                                                <?php
                                                    }
                                                ?>
                                                <li><i class='fa fa-suitcase'></i><?php echo $model->phone_no; ?></li>
                                            </ul>
                                        </div>
                                        <?php
                                            $user_id = Yii::$app->user->getId();
                                            $Role = Yii::$app->authManager->getRolesByUser($user_id);
                                            if(isset($Role['super_admin']) ||  $model->id==$user_id){
                                        ?>
                                        <div class="uprofile-buttons">
                                        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php 
        // echo Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
        //     'class' => 'btn btn-danger',
        //     'data' => [
        //         'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
        //         'method' => 'post',
        //     ],
        // ]);
        ?>
                                        </div>
                                    <?php }?>
                                    </div>
                                    <div class="col-md-9 col-sm-8 col-xs-12">
                                    <div class="row">
                                   <!-- statics start -->
                                   <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="r4_counter db_box">
                        <i class='pull-left fa fa-user fa-2x icon-md icon-rounded icon-primary'></i>
                            <div class="stats">
                                <h4><strong><?= $all_status['current_level'] ?></strong></h4>
                                <span><?= Yii::t('app', 'User Level') ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="r4_counter db_box">
                            <i class='pull-left fa fa-users fa-2x icon-md icon-rounded icon-orange'></i>
                            <div class="stats">
                                <h4><strong><?= $all_status['current_user'] ?></strong></h4>
                                <span> <?= Yii::t('app', 'Total Users') ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="r4_counter db_box">
                            <i class='pull-left fa fa-users fa-2x icon-md icon-rounded icon-purple'></i>
                            <div class="stats">
                                <h4><strong><?= $all_status['user_limit'] ?></strong></h4>
                                <span> <?= Yii::t('app', 'User Creation Limit') ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <div class="r4_counter db_box">
                            <i class='pull-left fa fa-list-alt fa-2x icon-md icon-rounded icon-warning'></i>
                            <div class="stats">
                                <h4><strong><?= $all_status['current_stock'] ?></strong></h4>
                                <span><?= Yii::t('app', 'Current Stock') ?></span>
                            </div>
                        </div>
                    </div>
            </div> <!-- End .row -->    
            <div class="row">
                <!-- <div class="col-md-6 col-sm-6 col-xs-6">
                    <div class="r4_counter db_box">
                        <i class='pull-left fa fa-thumbs-up icon-md icon-rounded icon-primary'></i>
                        <div class="stats">
                            <h4><strong><?php //$all_status['current_profit'] ?></strong></h4>
                            <span> <?php // Yii::t('app', 'Profit') ?></span>
                        </div>
                    </div>
                </div> -->
                <div class="col-md-6 col-sm-6 col-xs-6">
                    <div class="r4_counter db_box">
                        <i class='pull-left fa fa-exchange fa-2x icon-md icon-rounded icon-orange'></i>
                        <div class="stats">
                            <h4><strong><?= $all_status['transfered_order'] ?></strong></h4>
                            <span><?= Yii::t('app', 'Transferred Units') ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-6">
                    <div class="r4_counter db_box">
                        <i class='pull-left fa fa-list-ul fa-2x icon-md icon-rounded icon-orange'></i>
                        <div class="stats">
                            <h4><strong><?= $all_status['total_order'] ?></strong></h4>
                            <span><?= Yii::t('app', 'Total Processed Orders') ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-6">
                    <div class="r4_counter db_box">
                        <i class='pull-left fa fa-list-ul fa-2x icon-md icon-rounded icon-purple'></i>
                        <div class="stats">
                            <h4><strong><?= $all_status['pending_order'] ?></strong></h4>
                            <span><?= Yii::t('app', 'Total Pending Orders') ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-6">
                    <div class="r4_counter db_box">
                        <i class='pull-left fa fa-check fa-2x icon-md icon-rounded icon-warning'></i>
                        <div class="stats">
                            <h4><strong><?= $all_status['approved_order'] ?></strong></h4>
                            <span> <?= Yii::t('app', 'Total Approved Orders') ?></span>
                        </div>
                    </div>
                </div>
            </div> <!-- End .row -->             
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-6">
                    <div class="r4_counter db_box">
                        <i class='pull-left fa fa-undo fa-2x icon-md icon-rounded icon-primary'></i>
                        <div class="stats">
                            <h4><strong><?= $all_status['returned_order'] ?></strong></h4>
                            <span> <?= Yii::t('app', 'Returned Units') ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-6">
                    <div class="r4_counter db_box">
                        <i class='pull-left fa fa-undo fa-2x icon-md icon-rounded icon-primary'></i>
                        <div class="stats">
                            <h4><strong><?= round($all_status['total_sales'],2) ?></strong></h4>
                            <span> <?= Yii::t('app', 'Total Sales') ?></span>
                        </div>
                    </div>
                </div>
            </div> <!-- End .row --> 
                             <!-- statistic end -->
                                                </div>	
                                                <div class="clearfix"></div>						
                                            </div>


                                        </div>

                                    </div>
                                </div>
                            </div>
                        </section></div>

