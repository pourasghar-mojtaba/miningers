
<?php 
	  $this->Gilace->autoCompileLess(ROOT.DS.APP_DIR.DS.'webroot'.DS.'less_'.$locale. DS .'setting.less', ROOT.DS.APP_DIR.DS.'webroot'.DS.'css'.DS.'setting_'.$locale.'.css');		
	echo $this->Html->css('setting_'.$locale);
?>
 	<div class="profileCover">
		<?php echo $this->element('cover_edit_profile'); ?> 
    </div>
	<div class="settingContent">
		<?php echo $this->element('edit_right_panel',array('active'=>'edit_privacy')); ?> 
        <div class="col-sm-6 settingForms">
            <div id="generalSetting">
				<?php echo $this->Form->create('Privacy', array('id'=>'PrivacyEdit','name'=>'PrivacyEdit','enctype'=>'multipart/form-data','class'=>'myForm')); ?>
                    <div class="col-md-12">
						 <?php  echo $this->Session->flash(); ?>
					</div>
                    <div class="col-md-12">
                        <label class="myFormComponent"><?php echo __('comment_privacy'); ?> :</label>
						<div class="col-md-12">
                            <?php if(isset($privacy['Privacy']['commenting']) && $privacy['Privacy']['commenting']==0) 
    									   echo"<input name='data[Privacy][commenting]' checked id='single' type='radio' value='0' />"; else echo"<input name='data[Privacy][commenting]' id='single' type='radio' value='0' />";
    						?>
    						<span> <?php echo __('everyone'); ?></span>
                        </div>
						<div class="col-md-12">
                            <?php if(isset($privacy['Privacy']['commenting']) && $privacy['Privacy']['commenting']==1) 
							   echo"<input name='data[Privacy][commenting]' checked id='single' type='radio' value='1' />"; else echo"<input name='data[Privacy][commenting]' id='single' type='radio' value='1' />";
							  ?>
                              <span> <?php echo __('followers'); ?></span>
                        </div>
                        <div class="col-md-12">
                            <?php if(isset($privacy['Privacy']['commenting']) && $privacy['Privacy']['commenting']==2) 
							   echo"<input name='data[Privacy][commenting]' checked id='single' type='radio' value='2' />"; else echo"<input name='data[Privacy][commenting]' id='single' type='radio' value='2' />";
							  ?>
                              <span> <?php echo __('follow_payees'); ?></span>
                        </div>
                        <div class="col-md-12">
                            <?php if(isset($privacy['Privacy']['commenting']) && $privacy['Privacy']['commenting']==3) 
							   echo"<input name='data[Privacy][commenting]' checked id='single' type='radio' value='3' />"; else echo"<input name='data[Privacy][commenting]' id='single' type='radio' value='3' />";
							  ?>
                            <span> <?php echo __('followers_and_follow_payee'); ?></span>  
                        </div>
                        <div class="col-md-12">
                            <?php if(isset($privacy['Privacy']['commenting']) && $privacy['Privacy']['commenting']==4) 
							   echo"<input name='data[Privacy][commenting]' checked id='single' type='radio' value='4' />"; else echo"<input name='data[Privacy][commenting]' id='single' type='radio' value='4' />";
							  ?>
                            <span> <?php echo __('no_one'); ?> </span>  
                        </div>
						 
                         
                    </div>
                    
                    <div class="col-md-12">
                        <label class="myFormComponent"><?php echo __('share_privacy'); ?> :</label>
						<div class="col-md-12">
                            <?php if(isset($privacy['Privacy']['sharing']) && $privacy['Privacy']['sharing']==0) 
							   echo"<input name='data[Privacy][sharing]' checked id='single' type='radio' value='0' />"; else echo"<input name='data[Privacy][sharing]' id='single' type='radio' value='0' />";
							  ?>
                              <span><?php echo __('everyone'); ?></span>
                        </div>
                        <div class="col-md-12">
                            <?php if(isset($privacy['Privacy']['sharing']) && $privacy['Privacy']['sharing']==1) 
							   echo"<input name='data[Privacy][sharing]' checked id='single' type='radio' value='1' />"; else echo"<input name='data[Privacy][sharing]' id='single' type='radio' value='1' />";
							  ?>
                              <span><?php echo __('followers'); ?></span>
                        </div>
                        <div class="col-md-12">
                            <?php if(isset($privacy['Privacy']['sharing']) && $privacy['Privacy']['sharing']==2) 
							   echo"<input name='data[Privacy][sharing]' checked id='single' type='radio' value='2' />"; else echo"<input name='data[Privacy][sharing]' id='single' type='radio' value='2' />";
							  ?>
                              <span><?php echo __('follow_payees'); ?></span>
                        </div>
                        <div class="col-md-12">
                            <?php if(isset($privacy['Privacy']['sharing']) && $privacy['Privacy']['sharing']==3) 
							   echo"<input name='data[Privacy][sharing]' checked id='single' type='radio' value='3' />"; else echo"<input name='data[Privacy][sharing]' id='single' type='radio' value='3' />";
							  ?>
                              <span><?php echo __('followers_and_follow_payee'); ?></span>
                        </div>
                        <div class="col-md-12">
                            <?php if(isset($privacy['Privacy']['sharing']) && $privacy['Privacy']['sharing']==4) 
							   echo"<input name='data[Privacy][sharing]' checked id='single' type='radio' value='4' />"; else echo"<input name='data[Privacy][sharing]' id='single' type='radio' value='4' />";
							  ?>
                              <span><?php echo __('no_one'); ?></span>
                        </div>
                    </div> 
                    
                    <div class="col-md-12">
                        <label class="myFormComponent"><?php echo __('message_privacy'); ?> :</label>
						<div class="col-md-12">
                            <?php if(isset($privacy['Privacy']['messaging']) && $privacy['Privacy']['messaging']==0) 
							   echo"<input name='data[Privacy][messaging]' checked id='single' type='radio' value='0' />"; else echo"<input name='data[Privacy][messaging]' id='single' type='radio' value='0' />";
							  ?>
                              <span><?php echo __('everyone'); ?></span>
                        </div>
                        <div class="col-md-12">
                            <?php if(isset($privacy['Privacy']['messaging']) && $privacy['Privacy']['messaging']==1) 
							   echo"<input name='data[Privacy][messaging]' checked id='single' type='radio' value='1' />"; else echo"<input name='data[Privacy][messaging]' id='single' type='radio' value='1' />";
							  ?>
                              <span><?php echo __('followers'); ?></span>
                        </div>
                        <div class="col-md-12">
                            <?php if(isset($privacy['Privacy']['messaging']) && $privacy['Privacy']['messaging']==2) 
							   echo"<input name='data[Privacy][messaging]' checked id='single' type='radio' value='2' />"; else echo"<input name='data[Privacy][messaging]' id='single' type='radio' value='2' />";
							  ?>
                              <span><?php echo __('follow_payees'); ?></span>
                        </div>
                        <div class="col-md-12">
                            <?php if(isset($privacy['Privacy']['messaging']) && $privacy['Privacy']['messaging']==3) 
							   echo"<input name='data[Privacy][messaging]' checked id='single' type='radio' value='3' />"; else echo"<input name='data[Privacy][messaging]' id='single' type='radio' value='3' />";
							  ?>
                              <span><?php echo __('followers_and_follow_payee'); ?></span>
                        </div>
                        <div class="col-md-12">
                            <?php if(isset($privacy['Privacy']['messaging']) && $privacy['Privacy']['messaging']==4) 
							   echo"<input name='data[Privacy][messaging]' checked id='single' type='radio' value='4' />"; else echo"<input name='data[Privacy][messaging]' id='single' type='radio' value='4' />";
							  ?>
                              <span><?php echo __('no_one'); ?></span>
                        </div>
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
 

