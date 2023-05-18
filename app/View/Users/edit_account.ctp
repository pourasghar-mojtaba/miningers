<?php 
	  $this->Gilace->autoCompileLess(ROOT.DS.APP_DIR.DS.'webroot'.DS.'less_'.$locale. DS .'setting.less', ROOT.DS.APP_DIR.DS.'webroot'.DS.'css'.DS.'setting_'.$locale.'.css');		
	echo $this->Html->css('setting_'.$locale);
		
	echo $this->Html->script('profile');
	 
  //pr($user);
?>
<script type="text/javascript">
	_save_cover_image_success = "<?php echo __('save_cover_image_success');  ?>";
	_save_cover_image_notsuccess = "<?php echo __('save_cover_image_notsuccess');  ?>";
	_save_image_success = "<?php echo __('save_image_success');  ?>";
	_save_image_notsuccess = "<?php echo __('save_image_notsuccess');  ?>";
</script>

	<div class="profileCover">
		<?php echo $this->element('cover_edit_profile'); ?> 
    </div>
	<div class="settingContent">
		<?php echo $this->element('edit_right_panel',array('active'=>'edit_account')); ?> 
        <div class="col-sm-6 settingForms">
            <div id="generalSetting">
                <div id="userAccount">
					<?php echo $this->Form->create('User', array('id'=>'ChangeProfile','name'=>'ChangeProfile','enctype'=>'multipart/form-data','class'=>'myForm')); ?>
                        <div class="col-md-12">
							<?php  echo $this->Session->flash(); ?>
						</div>
                         
                        <div class="col-md-6">
                            <label class="myFormComponent"><?php echo __('user_name') ?> :</label>
							<?php echo $this->Form->input('user_name',array('label'=>'','type'=>'text','id'=>'user_name','value'=>$user['User']['user_name'] ,'class'=>'myFormComponent notTrans')); ?>
                        </div>
                        <div class="col-md-6">
                            <label class="myFormComponent"><?php echo __('email') ?> :</label>
							<?php echo $this->Form->input('old_email',array('label'=>'','type'=>'text','id'=>'old_email','value'=>$user['User']['email'],'dir'=>'ltr' ,'class'=>'myFormComponent notTrans','readonly'=>'readonly')); ?>
                        </div>
                        <div class="col-md-6">
                            <label class="myFormComponent">&nbsp; </label>
							<a href="<?php echo __SITE_URL.'users/edit_email' ?>">
	                            <div class="tile btn dark myFormComponent">
	                                <span class="text"><?php echo __('change_email') ?></span>
	                                <span class="icon icon-cog"></span>
	                            </div>
							</a>
                        </div>
                        <div class="col-md-12">
                            <label class="myFormComponent">
							<?php if($user['User']['search_with_email']==1) 
								 {
								 	echo"
							   			<input name='data[User][search_with_email]'  value='1' class='especialCheckBoxRemoved' type='checkbox' checked />
										";
								 }
							   
							   else  
							    {
									echo" 
									<input name='data[User][search_with_email]' class='especialCheckBoxRemoved' type='checkbox' value='0' /> ";
								}
							   
					     ?>
                            <?php echo __('email_privacy_comment') ?>
                            </label>                                
                        </div>
                        <div class="col-sm-6 col-sm-offset-6">
                            <button type="submit" class="green myFormComponent">
                                <span class="text"><?php echo __('updates') ?></span>
                                <span class="icon icon-left-open"></span>
                            </button>
                        </div>
                        <div class="clear"></div>
                    </form>
            </div>
            
       		 </div>
        </div>
        <div class="col-sm-3">
            <?php echo $this->element('left_edit_profile'); ?> 
        </div>
        <div class="clear"></div>
    </div>


<script>
	$('#user_name').keypress(function(e){
		
		if(e.which===32){
			return false;
		}
	})
</script>