<!-- START TOPBAR -->
<?php use yii\helpers\Url;
        use yii\db\Query;
   $user_id = Yii::$app->user->getId();
   $Role =   Yii::$app->authManager->getRolesByUser($user_id);
?>
        <div class='page-topbar '>
            <div class='logo-area'>

            </div>
            <div class='quick-area'>
                <div class='pull-left'>
                    <ul class="info-menu left-links list-inline list-unstyled">
                        <li class="sidebar-toggle-wrap">
                            <a href="#" data-toggle="sidebar" class="sidebar_toggle">
                                <i class="fa fa-bars"></i>
                            </a>
                        </li>
                        <?php 
                        $remaning_percent = '';
                        if (!Yii::$app->user->isGuest) {?>
                            <?php
                            $notificationDetail = \common\models\StockIn::ChildStock(Yii::$app->user->identity->id);
                            ?>
                        <li class="notify-toggle-wrapper">
                        <a href="#" data-toggle="dropdown" class="toggle">
                                <i class="fa fa-bell"></i>
                                <span class="badge badge-orange">
                               <?=  $notificationDetail['count']; ?>
                            </span>
                            </a>
                            <ul class="dropdown-menu notifications animated fadeIn">
                                <li class="total">
                                    <span class="small">
     <?= Yii::t('app', 'You have');?> <strong><?= $notificationDetail['count']; ?>&nbsp;</strong><?= Yii::t('app', 'New Notifications.');?>
                              </span>
                                </li>
                                <li class="list">
                                    <ul class="dropdown-menu-list list-unstyled ps-scrollbar">
                                                <?= $notificationDetail['detail'];?>
                                    </ul>
                                </li>
                                <li class="external">
                                    <a href="javascript:;">
                                        <!-- <span>Read All Notifications</span> -->
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="message-toggle-wrapper">
								<a class="data-toggle" data-toggle="dropdown">
                                <span class="languageDropdown"><i class="fa fa-globe"></i></span>
	 							   <!-- <p class="hidden-lg hidden-md"><?= Yii::t('app', 'Profile');?></p> -->
		 						</a>
		 							<ul class="dropdown-menu language">
                                        <li><a href="<?php echo Yii::$app->request->baseUrl; ?>/site/language?language=th-TH"><img src="<?= Yii::$app->homeUrl; ?>images/thai-flag.png" alt="user-image" class="img-circle">TH</a></li>
									<li><a  href="<?php echo Yii::$app->request->baseUrl;?>/site/language?language=en-US"><img src="<?= Yii::$app->homeUrl; ?>images/uk-flag.png" alt="user-image" class="img-circle">EN</a></li>
									
								</ul>
							</li>
                        <?php } ?>
                    </ul>
                </div>      
                <div class='pull-right'>
                    <ul class="info-menu right-links list-inline list-unstyled">
                    <?php  if (Yii::$app->user->isGuest) {?>
                      <li><a data-method="POST" href="<?= Yii::$app->request->baseUrl;?>/site/login"><?= Yii::t('app', 'Login');?></a></li>
                      <?php }else{?>
                        <li class="profile">
                            <a href="#" data-toggle="dropdown" class="toggle">
                                <?php if(Yii::$app->user->identity->profile){ ?>
                                     <img src="<?=\yii\helpers\Url::to('@web/uploads/'.Yii::$app->user->identity->profile, true)?>" class="img-circle img-inline">
                                <?php    
                                }
                                else{
                                ?>
                                <img src="<?= Yii::$app->homeUrl; ?>images/profile.jpg" class="img-circle img-inline">
                                    <?php  }?>                            
                                <span><?php echo Yii::$app->user->identity->username ?> <i class="fa fa-angle-down"></i></span>
                            </a>
                            <ul class="dropdown-menu profile animated fadeIn">
                                <li>
                                    <a href="<?= Yii::$app->homeUrl;?>user/view/<?= Yii::$app->user->identity->id?>">
                                        <i class="fa fa-user"></i>
                                        <?= Yii::t('app', 'PROFILE');?>
                                    </a>
                                </li>
                               
                                <li class="last">
                                    <a data-method="post" href="<?= Yii::$app->request->baseUrl;?>/site/logout">
                                        <i class="fa fa-lock"></i>
                                        <?= Yii::t('app', 'Logout');?>
                                    </a>
                                </li>
                            </ul>
                        </li>
                     
                      <?php } ?>
              
                    </ul>           
                </div>      
            </div>

        </div>
        <!-- END TOPBAR -->