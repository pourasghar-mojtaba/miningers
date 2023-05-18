<div class="container-fluid">
		<div class="row-fluid">
				
			<!-- right menu starts -->
				<?php echo $this->element('Admin/right_menu'); ?>       
			<!-- right menu ends -->
			
			
			<div id="content" class="span10">
			<!-- content starts -->
			

			<div>
				<?php if($this->Session->check('Message.flash')) {?>
				 
					 
					  <?php echo $this->Session->flash(); ?>
				 
				<?php } ?>
			</div>
			<?php echo $this->Form->create('Siteinformation', array('id'=>'EditFrom','name'=>'EditFrom')); ?>
			<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-edit"></i><?php echo __('site_setting') ?></h2>
						<div class="box-icon">
							<a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
						</div>
					</div>
					<div class="box-content">
						<form class="form-horizontal">
							<fieldset>
							  <div class="control-group">
								
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('postads_level1_price') ?> :</label>
								  <input class="input-xlarge focused" name="data[Siteinformation][postads_level1_price]" id="focusedInput" type="text"  value="<?php echo $setting['Siteinformation']['postads_level1_price'] ; ?>">
								</div>

								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('postads_level2_price') ?> :</label>
								  <input class="input-xlarge focused" name="data[Siteinformation][postads_level2_price]" id="focusedInput" type="text"  value="<?php echo $setting['Siteinformation']['postads_level2_price'] ; ?>">
								</div>
								
								
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('site_keywords') ?> :</label>
								  <textarea class="input-xlarge focused" name="data[Siteinformation][site_keywords]" rows="5" style="width: 340px;">
								  	<?php echo $setting['Siteinformation']['site_keywords'] ; ?>
								  </textarea>
								  
								  <small><?php  echo __('separate_with_simicalon'); ?></small>
								</div>
								
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('site_description') ?> :</label>
								  <textarea class="input-xlarge focused" name="data[Siteinformation][site_description]" rows="5"  style="width: 470px;">
								  	<?php echo $setting['Siteinformation']['site_description'] ; ?>
								  </textarea>
								  
								  <small><?php  echo __('separate_with_simicalon'); ?></small>
								</div>
								
														
								
							  </div>
							  
							  <div class="form-actions">
								<button type="submit" class="btn btn-primary"><?php echo __('save_change') ?></button>
								<button class="btn"><?php echo __('cancel') ?></button>
							  </div>
							</fieldset>
						  </form>
					
					</div>
				</div><!--/span-->
			
			</div><!--/row-->
			</form>
			
		
    
					<!-- content ends -->
			</div><!--/#content.span10-->
				</div><!--/fluid-row-->
				
		 
		
	</div><!--/.fluid-container-->
	
 