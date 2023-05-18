 
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
			<?php echo $this->Form->create('Post', array('id'=>'EditFrom','name'=>'AddFrom','enctype'=>'multipart/form-data')); ?>
			<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-edit"></i><?php echo __('edit_post') ?></h2>
						<div class="box-icon">
							<a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
						</div>
					</div>
					<div class="box-content">
						<form class="form-horizontal">
							<fieldset>
							  <div class="control-group">
								
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('body') ?> :</label>
								  <textarea maxlength="200" name="data[Post][body]" >
								  	<?php echo $post['Post']['body']; ?>
								  </textarea>
								</div>
								
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('link') ?> :</label>
								  <input class="input-xlarge focused" value="<?php echo $post['Post']['url']; ?>" name="data[Post][url]" id="focusedInput" type="text"  dir="ltr" >
								</div>
								
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('image') ?> :</label>
								  <input name="data[Post][newpost_image]" type="file" id="newpost_image" />
								  <input name=data[Post][old_image] type="hidden" value="<?php echo $post['Post']['image']; ?>">
								  <?php  
						  
									if(fileExistsInPath(__POST_IMAGE_PATH.$post['Post']['image'] ) && $post['Post']['image']!='' ) 
										{
											echo $this->Html->image('/'.__POST_IMAGE_PATH.$post['Post']['image'],array('id'=>'image_img','height'=>100));
										}
										
										
									 ?>
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