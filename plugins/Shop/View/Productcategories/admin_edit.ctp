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
						<h2><i class="icon-edit"></i><?php echo __('edit_product_category') ?></h2>
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
								  <input class="input-xlarge focused" name="data[Productcategory][title]" id="focusedInput" type="text"  value="<?php echo $product_category['Productcategory']['title'] ; ?>">
								</div>
								
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('slug') ?> :</label>
								  <input class="input-xlarge focused" name="data[Productcategory][slug]" id="focusedInput" type="text"  value="<?php echo $product_category['Productcategory']['slug'] ; ?>">
								</div>
								
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('product_category') ?> :</label>
								  <select name="data[Productcategory][parent_id]">
								  <option value="0"><?php echo __('main_page') ?></option>
								  <?php
										 if(!empty($productcategories)){
										 	foreach($productcategories as $productcategory)
											{
												if($product_category['Productcategory']['parent_id']==$productcategory['id']){
													echo "<option selected='selected' value='".$productcategory['id']."'>".$productcategory['title']."</option>";
												}
												else 
												{
													if($productcategory['id']!=$product_category['Productcategory']['id']){
														echo "<option value='".$productcategory['id']."'>".$productcategory['title']."</option>";
													}
												}
												
											}
										 }							  
								  ?> 
								  </select>
								</div>
								
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('arrangment') ?> :</label>
								  <input class="input-xlarge focused" name="data[Productcategory][keyword_per]" id="focusedInput" type="text"  value="<?php echo $product_category['Productcategory']['arrangment'] ; ?>">
								 
								</div>
								
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('image') ?> :</label>
								  <input name="data[Productcategory][image]" type="file" id="image" />
								  <input name=data[Productcategory][old_image] type="hidden" value="<?php echo $product_category['Productcategory']['image']; ?>">
								  <?php  
						  
									if(fileExistsInPath(__PRODUCT_CATEGORY_IMAGE_PATH.$product_category['Productcategory']['image'] ) && $product_category['Productcategory']['image']!='' ) 
										{
											echo "<a target='_blank' href= '".__SITE_URL.__PRODUCT_CATEGORY_IMAGE_PATH.$product_category['Productcategory']['image']."' >";
											echo $this->Html->image('/'.__PRODUCT_CATEGORY_IMAGE_PATH.$product_category['Productcategory']['image'],array('id'=>'image_img','height'=>100));
											echo "</a>";
										}
										
										
									 ?>
								</div>
								
								
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('status') ?> :</label>
								  <select id="selectError3" style="width:auto" name="data[Productcategory][status]">
									<?php 
										if($product_category['Productcategory']['status']==1) 
											echo "<option value='1' selected>". __('active')."</option>";
										else echo "<option value='1'>". __('active')."</option>"; 
										
										if($product_category['Productcategory']['status']==0) 
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
	
	 