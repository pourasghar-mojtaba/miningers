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
			<?php echo $this->Form->create('Newsletter', array('id'=>'EditFrom','name'=>'EditFrom')); ?>
			<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-edit"></i><?php echo __('add_newsletter') ?></h2>
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
								  <input class="input-xlarge focused" name="data[Newsletter][title]" id="focusedInput" type="text"  >
								</div>
	
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('template') ?> :</label>
								   <?php
								   		
										if(!empty($templates))
										{
											foreach($templates as $key=>$template){
												if($key==0){
													echo "<input name='data[Newsletter][newsletter_template_id]' value='".$template['Newslettertemplate']['id']."' checked type='radio' >";
													echo $template['Newslettertemplate']['title'];
													if(fileExistsInPath(__Newsletter_Template_Path.$template['Newslettertemplate']['image'] ) && $template['Newslettertemplate']['image']!='' ) 
													{
														echo "<a target='_blank' href= '".__SITE_URL.__Newsletter_Template_Path.$template['Newslettertemplate']['image']."' >";
														echo $this->Html->image('/'.__Newsletter_Template_Path.$template['Newslettertemplate']['image'],array('id'=>'image_img','height'=>100));
														echo "</a>";
													}
													 
												}else
												{
													echo "<input name='data[Newsletter][newsletter_template_id]' value='".$template['Newslettertemplate']['id']."'  type='radio' >";
													echo $template['Newslettertemplate']['title'];
													if(fileExistsInPath(__Newsletter_Template_Path.$template['Newslettertemplate']['image'] ) && $template['Newslettertemplate']['image']!='' ) 
													{
														echo "<a target='_blank' href= '".__SITE_URL.__Newsletter_Template_Path.$template['Newslettertemplate']['image']."' >";
														echo $this->Html->image('/'.__Newsletter_Template_Path.$template['Newslettertemplate']['image'],array('id'=>'image_img','height'=>100));
														echo "</a>";
													}
													
												}  
											}
										}
								   ?>
								</div>
								 
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('status') ?> :</label>
								  <select id="selectError3" style="width:auto" name="data[Newsletter][status]">
									<option value="1"><?php echo __('active') ?></option>
									<option value='0'><?php echo __('inactive') ?></option>
								  </select>
								</div>
	
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('body') ?> :</label>
								  <textarea class="input-xlarge focused" name="data[Newsletter][body]" id="first_editor" >
								  	
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