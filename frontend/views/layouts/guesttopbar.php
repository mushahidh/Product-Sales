<!-- START TOPBAR -->
<?php use yii\helpers\Url;
        use yii\db\Query;

?>
        <div class='page-topbar '>
            <div class='logo-area' >

            </div>
            <div class='quick-area'>
                <div class='pull-left'>
                    <ul class="info-menu left-links list-inline list-unstyled">
                        <!-- <li class="sidebar-toggle-wrap">
                            <a href="#" data-toggle="sidebar" class="sidebar_toggle">
                                <i class="fa fa-bars"></i>
                            </a>
                        </li> -->
                      
                    </ul>
                </div>      
                <div class='pull-right'>
                    <ul class="info-menu right-links list-inline list-unstyled">
                    <?php  if (Yii::$app->user->isGuest) {?>
                      <li><a data-method="POST" href="<?= Yii::$app->request->baseUrl;?>/site/login"><?= Yii::t('app', 'Login');?></a></li>
                      <?php }?>
     
              
                    </ul>           
                </div>      
            </div>

        </div>
        <!-- END TOPBAR -->