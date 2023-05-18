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
			<?php echo $this->Form->create('Productcategory', array('id'=>'EditFrom','name'=>'EditFrom','enctype'=>'multipart/form-data')); ?>
			<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-edit"></i><?php echo __('add_product_category') ?></h2>
						<div class="box-icon">
							<a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
						</div>
					</div>
					<div class="box-content">
						<form class="form-horizontal">
							<fieldset>
							  <div class="control-group">
								
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('title') ?> :</label>
								  <input class="input-xlarge focused" name="data[Productcategory][title]" id="focusedInput" type="text"  >
								</div>
								
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('slug') ?> :</label>
								  <input class="input-xlarge focused" name="data[Productcategory][slug]" id="focusedInput" type="text"  >
								</div>
	
								
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('parent_product_category') ?> :</label>
								  <select name="data[Productcategory][parent_id]">
								  <option value="0"><?php echo __('main_product_category') ?></option>
								  <?php
										 if(!empty($productcategories)){
										 	foreach($productcategories as $product_category)
											{
												echo "<option value='".$product_category['id']."'>".$product_category['title']."</option>";
											}
										 }							  
								  ?> 
								  </select>
								</div>

								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('arrangment') ?> :</label>
								  <input class="input-xlarge focused" name="data[Productcategory][arrangment]" id="focusedInput" type="text" style="width:50px">
								</div>
								
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('image') ?> :</label>
								  <input name="data[Productcategory][image]" type="file" id="image" />
								</div>
								
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('status') ?> :</label>
								  <select id="selectError3" style="width:auto" name="data[Productcategory][status]">
									<option value="1"><?php echo __('active') ?></option>
									<option value='0'><?php echo __('inactive') ?></option>
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
	
	 