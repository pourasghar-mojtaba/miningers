<?php 
	  $this->Gilace->autoCompileLess(ROOT.DS.APP_DIR.DS.'webroot'.DS.'less_'.$locale. DS .'setting.less', ROOT.DS.APP_DIR.DS.'webroot'.DS.'css'.DS.'setting_'.$locale.'.css');		
	echo $this->Html->css('setting_'.$locale);
?>
 	<div class="profileCover">
		<?php echo $this->element('cover_edit_profile'); ?> 
    </div>
	<div class="settingContent">
		<?php echo $this->element('edit_right_panel',array('active'=>'edit_send_email')); ?> 
        <div class="col-sm-6 settingForms">
            <div id="generalSetting">
				<?php echo $this->Form->create('Sendemail', array('id'=>'SendemailEdit','name'=>'SendemailEdit','enctype'=>'multipart/form-data','class'=>'myForm')); ?>
					<div class="col-md-12">
						<?php  echo $this->Session->flash(); ?>
					</div>
                    <div class="col-md-12">
                        <label class="myFormComponent"><?php echo __('send_email_help') ?> :</label>                      
                    </div>
					<div class="col-md-12">
						<b><label class="myFormComponent"><?php echo __('send_email_for_me_on'); ?> :</label></b>
						<div class="col-md-12">
							<?php if(isset($sendemail['Sendemail']['onsharing']) && $sendemail['Sendemail']['onsharing']==1) 
							   echo"<input name='data[Sendemail][onsharing]' checked id='single' type='checkbox' value='1' />"; else echo"<input name='data[Sendemail][onsharing]' id='single' type='checkbox' value='1' />";
							  ?>	 
                              <span> <?php echo __('send_email_forme_onshared'); ?></span>
						</div>
						<div class="col-md-12">
							<?php if(isset($sendemail['Sendemail']['oncomment']) && $sendemail['Sendemail']['oncomment']==1) 
							   echo"<input name='data[Sendemail][oncomment]' checked id='single' type='checkbox' value='1' />"; else echo"<input name='data[Sendemail][oncomment]' id='single'  type='checkbox' value='1' />";
							  ?>
                              <span> <?php echo __('send_email_forme_oncomment'); ?></span>
						</div>
						<div class="col-md-12">
							<?php if(isset($sendemail['Sendemail']['onfollow']) && $sendemail['Sendemail']['onfollow']==1) 
							   echo"<input name='data[Sendemail][onfollow]' checked id='single' type='checkbox' value='1' />"; else echo"<input name='data[Sendemail][onfollow]' id='single' type='checkbox' value='1' />";
							  ?>
                              <span> <?php echo __('send_email_forme_onfollow'); ?></span>
						</div>
						<div class="col-md-12">
							<?php if(isset($sendemail['Sendemail']['onmessage']) && $sendemail['Sendemail']['onmessage']==1) 
							   echo"<input name='data[Sendemail][onmessage]' checked id='single' type='checkbox' value='1' />"; else echo"<input name='data[Sendemail][onmessage]' id='single' type='checkbox' value='1' />";
							  ?>
                              <span> <?php echo __('send_email_forme_onmessage'); ?></span>
						</div>
					</div>
					<div class="col-md-12">
						<b><label class="myFormComponent"><?php echo __('update_madaner'); ?> :</label></b>
						<div class="col-md-12">
							<?php if(isset($sendemail['Sendemail']['onlastloginemail']) && $sendemail['Sendemail']['onlastloginemail']==1) 
							   echo"<input name='data[Sendemail][onlastloginemail]' checked id='single' type='checkbox' value='1' />"; else echo"<input name='data[Sendemail][onlastloginemail]' id='single' type='checkbox' value='1' />";
							  ?>
                              <span> <?php echo __('send_email_forme_onlastloginemail'); ?></span>
						</div>
						<div class="col-md-12">
							<?php if(isset($sendemail['Sendemail']['onnewsletteremail']) && $sendemail['Sendemail']['onnewsletteremail']==1) 
							   echo"<input name='data[Sendemail][onnewsletteremail]' checked id='single' type='checkbox' value='1' />"; else echo"<input name='data[Sendemail][onnewsletteremail]' id='single' type='checkbox' value='1' />";
							  ?>
                              <span> <?php echo __('send_email_forme_onnewsletteremail'); ?></span>
						</div>
					</div>	
                    <div class="col-sm-6 col-sm-offset-6">
                        <button type="submit" class="green myFormComponent">
                            <span class="text"><?php echo __('updates'); ?></span>
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