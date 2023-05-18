 
<div class="container-fluid">
		<div class="row-fluid">
				
			<!-- right menu starts -->
				<?php echo $this->element('Admin/right_menu'); ?>       
			<!-- right menu ends -->
			
			
			<div id="content" class="span10">
			<!-- content starts -->
			

			<div>
				<?php if($this->Session->check('Message.flash')) {?>
				<div class="alert alert-error">
					<button data-dismiss="alert" class="close" type="button">×</button>
					  <?php echo $this->Session->flash(); ?>
				</div>
				<?php } ?>
			</div>
			<?php echo $this->Form->create('Postad', array('id'=>'EditFrom','name'=>'AddFrom','enctype'=>'multipart/form-data')); ?>
			<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-edit"></i><?php echo __('edit_postdds') ?></h2>
						<div class="box-icon">
							<a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
						</div>
					</div>
					<div class="box-content">
						<form class="form-horizontal">
							<fieldset>
							  <div class="control-group">

								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('post_id') ?> :</label>
								  <input class="input-xlarge focused" value="<?php echo $postads['Postad']['post_id']; ?>" name="data[Postad][post_id]" id="focusedInput" type="text"  disabled=""  >
								</div>
								
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('status') ?> :</label>
								  <select id="selectError3" style="width:auto" name="data[Postad][status]">
									<?php 
										if($postads['Postad']['status']==1) 
											echo "<option value='1' selected>". __('active')."</option>";
										else echo "<option value='1'>". __('active')."</option>"; 
										
										if($postads['Postad']['status']==0) 
											echo "<option value='0' selected>". __('inactive')."</option>";
										else echo "<option value='0'>". __('inactive')."</option>"; 
										
									?>
								  </select>
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