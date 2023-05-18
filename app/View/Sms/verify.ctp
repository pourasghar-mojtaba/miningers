
<?php 
	  $this->Gilace->autoCompileLess(ROOT.DS.APP_DIR.DS.'webroot'.DS.'less_'.$locale. DS .'setting.less', ROOT.DS.APP_DIR.DS.'webroot'.DS.'css'.DS.'setting_'.$locale.'.css');		
	echo $this->Html->css('setting_'.$locale);
?>
 	<div class="profileCover">
		<?php echo $this->element('cover_edit_profile'); ?> 
    </div>
	<div class="settingContent">
		<?php echo $this->element('edit_right_panel',array('active'=>'send_post_with_sms')); ?> 
        <div class="col-sm-6 settingForms">
            <div id="generalSetting">
				<?php echo $this->Form->create('User', array('id'=>'UserMobile','name'=>'UserMobile','enctype'=>'multipart/form-data','class'=>'myForm')); ?>
					<div class="col-md-12">
						<?php  echo $this->Session->flash(); ?>
					</div>
                    <div class="col-md-12">
                        <label class="myFormComponent"><?php echo __('send_post_with_sms_help') ?></label>
                    </div>
                    <div class="col-md-12">
                        <label class="myFormComponent"><?php echo __('number_sms_help') ?></label>
                    </div>
                    <div class="col-md-12">
                        <label class="myFormComponent">
						<?php                               
                          if($info['User']['verify']=='1' || $info['User']['verify']=='0') 
                            echo __('mobile_number'); 
                            else echo __('enter_verify_code'); 
                        
                        ?>
						:</label>
						<?php
						 if($info['User']['verify']=='1' || $info['User']['verify']=='0')  
				           echo"<input name='data[User][mobile]' class='myFormComponent  notTrans' value='".$info['User']['mobile']."' type='text'  />";
						   else  echo"<input name='data[User][verify]' class='myFormComponent  notTrans'  type='text'   />";
						?>
                       
                    </div>
					<div class="col-md-12">
                        <label class="myFormComponent"><?php echo __('sample_mobile_number') ?></label>
                    </div>
                    <div class="col-sm-6 col-sm-offset-6">
                        <button type="submit" class="green myFormComponent">
                            <span class="text"><?php echo __('ok'); ?></span>
                            <span class="icon icon-left-open"></span>
                        </button>
                    </div>
                    <div class="clear"></div>
                </form>
       		</div>
        </div>
        <div class="col-sm-3">
            <?php echo $this->element('left_edit_profile'); ?> 
        </div>
        <div class="clear"></div>
    </div>