
<?php 
	  $this->Gilace->autoCompileLess(ROOT.DS.APP_DIR.DS.'webroot'.DS.'less_'.$locale. DS .'setting.less', ROOT.DS.APP_DIR.DS.'webroot'.DS.'css'.DS.'setting_'.$locale.'.css');		
	echo $this->Html->css('setting_'.$locale);
	
	$User_Info= $this->Session->read('User_Info');
?>
 	<div class="profileCover">
		<?php echo $this->element('cover_edit_profile'); ?> 
    </div>
	<div class="settingContent">
		<?php echo $this->element('edit_right_panel',array('active'=>'disable_account')); ?> 
        <div class="col-sm-6 settingForms">
            <div id="generalSetting">
                
			  <?php echo $this->Form->create('Sendemail', array('id'=>'SendemailEdit','name'=>'SendemailEdit','enctype'=>'multipart/form-data','class'=>'edit_profile')); ?>	
				<div class="col-sm-12 col-sm-offset-0">
                    <div class="dataBox"> 
					<?php echo __('disable_account_dtl1'); ?>
					<a href="#"><?php echo __Madaner_Email; ?></a>
                    </div>
                    <div class="dataBox">
                    <?php echo __('disable_account_dtl2'); ?>@<?php echo $User_Info['user_name']; ?> ، :<?php echo __('disable_account_dtl3'); ?>
                        <ul class="List">
                            <li><?php echo __('disable_account_dtl4'); ?></li>
                            <li><?php echo __('disable_account_dtl5'); ?> <a href="<?php echo __SITE_URL.'users/edit_account' ?>"><?php echo __('disable_account_dtl6'); ?> </a> <?php echo __('disable_account_dtl7'); ?> <a href="<a href="<?php echo __SITE_URL.'users/edit_email' ?>"><?php echo __('disable_account_dtl8'); ?></a><?php echo __('disable_account_dtl9'); ?> 
							</li>
                            <li><?php echo __('disable_account_dtl10'); ?>
							 <a href="<?php echo __SITE_URL.'users/edit_profile' ?>">
							 <?php echo __('disable_account_dtl11'); ?></a> <?php echo __('disable_account_dtl12'); ?></li>
                            <li><?php echo __('disable_account_dtl13'); ?></li>
                            <li><?php echo __('disable_account_dtl14'); ?> </li>
                        </ul>
                        <a href="#" class="tile btn red">
                            <span class="text"><?php echo __('disable_account'); ?></span>
                            <span class="icon icon-cancel"></span>
                        </a>
                      <div class="clear"></div>
                    </div>
                </div>
			  </form>	
				
       		</div>
        </div>
        <div class="col-sm-3">
            <?php echo $this->element('left_edit_profile'); ?> 
        </div>
        <div class="clear"></div>
    </div>