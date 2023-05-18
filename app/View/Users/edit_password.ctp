<?php 
	  $this->Gilace->autoCompileLess(ROOT.DS.APP_DIR.DS.'webroot'.DS.'less_'.$locale. DS .'setting.less', ROOT.DS.APP_DIR.DS.'webroot'.DS.'css'.DS.'setting_'.$locale.'.css');		
	echo $this->Html->css('setting_'.$locale);
?>
 	<div class="profileCover">
		<?php echo $this->element('cover_edit_profile'); ?> 
    </div>
	<div class="settingContent">
		<?php echo $this->element('edit_right_panel',array('active'=>'edit_password')); ?> 
        <div class="col-sm-6 settingForms">
            <div id="generalSetting">
				<?php echo $this->Form->create('User', array('id'=>'ChangePassword','name'=>'ChangePassword','enctype'=>'multipart/form-data','class'=>'myForm')); ?>
                    <div class="col-md-12">
						<?php  echo $this->Session->flash(); ?>
					</div>
                    <div class="col-md-12">
                        <label class="myFormComponent"><?php echo __('old_password') ?> :</label>
						<?php echo $this->Form->input('old_password',array('label'=>'','type'=>'password','id'=>'old_password' ,'class'=>'myFormComponent','required' )); ?>
                    </div>
                    <div class="col-md-12">
                        <label class="myFormComponent"> <?php echo __('new_password') ?> :</label>
						<?php echo $this->Form->input('new_password',array('label'=>'','type'=>'password','id'=>'new_password','class'=>'myFormComponent','required' )); ?>
                    </div>
					<div class="col-md-12">
                        <label class="myFormComponent"> <?php echo __('repeat_new_password') ?> :</label>
						<?php echo $this->Form->input('re_new_password',array('label'=>'','type'=>'password','id'=>'re_new_password','class'=>'myFormComponent','required')); ?>
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
        <div class="col-sm-3">
            <?php echo $this->element('left_edit_profile'); ?> 
        </div>
        <div class="clear"></div>
    </div>	