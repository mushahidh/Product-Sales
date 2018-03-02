<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
    <div class="page-title">
        <div class="pull-left">
            <h1 class="title"> <?php Yii::t('app', 'Dashboard') ?></h1>                            
        </div>
    </div>
</div>

<div class="clearfix"></div>

<div class="col-lg-12">
    <section class="box nobox">
        <div class="content-body" >
            <div class="row">
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="r4_counter db_box">
                        <i class='pull-left fa fa-user fa-2x icon-md icon-rounded icon-primary'></i>
                            <div class="stats">
                                <h4><strong><?= $all_status['current_level'] ?></strong></h4>
                                <span><?= Yii::t('app', 'User Level') ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="r4_counter db_box">
                            <i class='pull-left fa fa-users fa-2x icon-md icon-rounded icon-orange'></i>
                            <div class="stats">
                                <h4><strong><?= $all_status['current_user'] ?></strong></h4>
                                <span> <?= Yii::t('app', 'Total Users') ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="r4_counter db_box">
                            <i class='pull-left fa fa-users fa-2x icon-md icon-rounded icon-purple'></i>
                            <div class="stats">
                                <h4><strong><?= $all_status['user_limit'] ?></strong></h4>
                                <span> <?= Yii::t('app', 'User Creation Limit') ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
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
                <!-- <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="r4_counter db_box">
                        <i class='pull-left fa fa-thumbs-up icon-md icon-rounded icon-primary'></i>
                        <div class="stats">
                            <h4><strong><?php //$all_status['current_profit'] ?></strong></h4>
                            <span> <?php // Yii::t('app', 'Profit') ?></span>
                        </div>
                    </div>
                </div> -->
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="r4_counter db_box">
                        <i class='pull-left fa fa-exchange fa-2x icon-md icon-rounded icon-orange'></i>
                        <div class="stats">
                            <h4><strong><?= $all_status['transfered_order'] ?></strong></h4>
                            <span><?= Yii::t('app', 'Transferred Units') ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="r4_counter db_box">
                        <i class='pull-left fa fa-list-ul fa-2x icon-md icon-rounded icon-orange'></i>
                        <div class="stats">
                            <h4><strong><?= $all_status['total_order'] ?></strong></h4>
                            <span><a class="" href="<?= Yii::$app->homeUrl; ?>order"><?= Yii::t('app', 'Total Processed Orders') ?></a></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="r4_counter db_box">
                        <i class='pull-left fa fa-list-ul fa-2x icon-md icon-rounded icon-purple'></i>
                        <div class="stats">
                            <h4><strong><?= $all_status['pending_order'] ?></strong></h4>
                            <span><a class="" href="<?= Yii::$app->homeUrl; ?>order/pending"><?= Yii::t('app', 'Total Pending Orders') ?></a></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="r4_counter db_box">
                        <i class='pull-left fa fa-check fa-2x icon-md icon-rounded icon-warning'></i>
                        <div class="stats">
                            <h4><strong><?= $all_status['approved_order'] ?></strong></h4>
                            <span> <a href="<?= Yii::$app->homeUrl; ?>order/approved"><?= Yii::t('app', 'Total Approved Orders') ?></a></span>
                        </div>
                    </div>
                </div>
            </div> <!-- End .row -->             
            <div class="row">
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="r4_counter db_box">
                        <i class='pull-left fa fa-undo fa-2x icon-md icon-rounded icon-primary'></i>
                        <div class="stats">
                            <h4><strong><?= $all_status['returned_order'] ?></strong></h4>
                            <span><a href="<?= Yii::$app->homeUrl; ?>order/return"> <?= Yii::t('app', 'Returned Units') ?></a></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="r4_counter db_box">
                        <i class='pull-left fa fa-undo fa-2x icon-md icon-rounded icon-primary'></i>
                        <div class="stats">
                            <h4><strong><?= round($all_status['total_sales'],2) ?></strong></h4>
                            <span> <?= Yii::t('app', 'Total Sales') ?></span>
                        </div>
                    </div>
                </div>
            </div> <!-- End .row -->             
      </div>
    </section>
</div>



               