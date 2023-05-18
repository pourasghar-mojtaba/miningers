
<?php 
	  $this->Gilace->autoCompileLess(ROOT.DS.APP_DIR.DS.'webroot'.DS.'less_'.$locale. DS .'setting.less', ROOT.DS.APP_DIR.DS.'webroot'.DS.'css'.DS.'setting_'.$locale.'.css');		
	echo $this->Html->css('setting_'.$locale);
?>
 	<div class="profileCover">
		<?php echo $this->element('cover_edit_profile'); ?> 
    </div>
	
	<div class="settingContent">
		<?php echo $this->element('edit_right_panel',array('active'=>'active_infomadan_app')); ?> 
        <div class="col-sm-6 settingForms">
            <div id="generalSetting">
				<?php
					$showpay = FALSE;
					if(!empty($appuser)){
						if($appuser['Appuser']['sale_reference_id'] <> 0 )
						{
							if(!empty($_REQUEST['salereferenceid'])){
								echo "
									<div class='col-md-12' style='text-align: center;color:green'>
				                        <label class='myFormComponent'>".__('infomadan_app_dtl3')."</label>
				                    </div>
									<div class='col-md-12'>
				                        <label class='myFormComponent'>".__('infomadan_app_dtl4')."</label>
				                    </div>
									<div class='col-md-12'>
				                        <label class='myFormComponent'>".__('infomadan_app_dtl5')."</label>
				                    </div>
									<div class='col-md-12'>
										<a href='".__SITE_URL."users/search' style='text-decoration: none'>
											<div class='btn classic' >
								                <span class='text'>". __('follow_more_users')."</span>
								                <span class='icon icon-left-open'></span>
								            </div>
										</a>
									</div>
								";
							}
							else{
								echo "
									<div class='col-md-12' style='text-align: center;color:green'>
				                        <label class='myFormComponent'>".__('infomadan_app_dtl6')."</label>
				                    </div>";
							}
							echo "
									<div class='col-md-12' >
				                       <div class='col-md-3' > ".__('sale_reference_id')."</label></div>
				                       <div class='col-md-6' > ".$appuser['Appuser']['sale_reference_id']."</label></div>
				                    </div>
								";	
							
							
						}else $showpay = TRUE;
					}else $showpay = TRUE;
				  if($showpay){
				?>
					<?php echo $this->Form->create('User', array('id'=>'UserMobile','name'=>'UserMobile','enctype'=>'multipart/form-data','class'=>'myForm')); ?>
					 	
						<div class="col-md-12">
							<?php  echo $this->Session->flash(); ?>
						</div>
	                    <div class="col-md-12" style="text-align: center">
	                        <label class="myFormComponent"><?php echo __('active_infomadan_app') ?></label>
	                    </div>
	                    <div class="col-md-12">
	                        <label class="myFormComponent"><?php echo __('infomadan_app_dtl1')."<a href='http://www.infomadan.com' target='_blank'>".__('infomadan')."</a>".__('infomadan_app_dtl2') ?></label>
	                    </div>
						
	                    <div class="col-md-12">
	                        <button type="submit" class="green myFormComponent">
	                            <span class="text"><?php echo __('ok'); ?></span>
	                            <span class="icon icon-left-open"></span>
	                        </button>
	                    </div>
	                    <div class="clear"></div>
						<div class="col-md-12">
	                        <label class="myFormComponent"><?php echo __(' ') ?></label>
	                    </div>
	                </form>
				<?php } ?>
       		</div>
        </div>
        <div class="col-sm-3">
            <?php echo $this->element('left_edit_profile'); ?> 
        </div>
        <div class="clear"></div>
    </div>