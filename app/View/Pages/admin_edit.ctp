<?php
	$lang = $_REQUEST['language'];
?>
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
			<?php echo $this->Form->create('Page', array('id'=>'EditFrom','name'=>'EditFrom')); ?>
			<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-edit"></i><?php echo __('edit_page') ?></h2>
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
								  <input class="input-xlarge focused" name="data[Page][title_<?php echo $lang; ?>]" id="focusedInput" type="text"  value="<?php echo $page['Page']['title'] ; ?>">
								</div>
								
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('parent_page') ?> :</label>
								  <select name="data[Page][parent_id]">
								  <option value="0"><?php echo __('main_page') ?></option>
								  <?php
										 if(!empty($pages)){
										 	foreach($pages as $parent_page)
											{
												if($page['Page']['parent_id']==$parent_page['id']){
													echo "<option selected='selected' value='".$parent_page['id']."'>".$parent_page['title']."</option>";
												}
												else echo "<option value='".$parent_page['id']."'>".$parent_page['title']."</option>";
											}
										 }							  
								  ?> 
								  </select>
								</div>
								
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('status') ?> :</label>
								  <select id="selectError3" style="width:auto" name="data[Page][status]">
									<?php 
										if($page['Page']['status']==1) 
											echo "<option value='1' selected>". __('active')."</option>";
										else echo "<option value='1'>". __('active')."</option>"; 
										
										if($page['Page']['status']==0) 
											echo "<option value='0' selected>". __('inactive')."</option>";
										else echo "<option value='0'>". __('inactive')."</option>"; 
										
									?>
								  </select>
								</div>
								
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('keyword') ?> :</label>
								  <input class="input-xlarge focused" name="data[Page][keyword_<?php echo $lang; ?>]" id="focusedInput" type="text"  value="<?php echo $page['Page']['keyword'] ; ?>">
								  <small><?php  echo __('separate_with_sharp'); ?></small>
								</div>
								
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('meta') ?> :</label>
								  <textarea class="input-xlarge focused" name="data[Page][meta_<?php echo $lang; ?>]" >
								  	<?php echo $page['Page']['meta'] ; ?>
								  </textarea>
								  
								  <small><?php  echo __('separate_with_sharp'); ?></small>
								</div>	
								
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('body') ?> :</label>
								  <textarea class="input-xlarge focused" name="data[Page][body_<?php echo $lang; ?>]" id="first_editor" >
								  	<?php echo $this->Gilace->convert_character_editor($page['Page']['body']) ; ?>
								  </textarea>
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
	
	<script>
		CKEDITOR.replace( 'first_editor' );
	</script>