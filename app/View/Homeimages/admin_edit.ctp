 <?php
	echo $this->Html->css('/js/admin/datetimepicker-master/jquery.datetimepicker');	
	echo $this->Html->script('/js/admin/datetimepicker-master/jquery.datetimepicker');
	
	 
	
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
			<?php echo $this->Form->create('Homeimage', array('id'=>'EditFrom','name'=>'AddFrom','enctype'=>'multipart/form-data')); ?>
			<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-edit"></i><?php echo __('edit_home_image') ?></h2>
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
								  <input class="input-xlarge focused" style="width:500px" value="<?php echo $homeimage['Homeimage']['title']; ?>" name="data[Homeimage][title]" id="focusedInput" type="text" width="250" >
								</div>
                                 
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('from_date') ?> :</label>
								  <input class="input-xlarge focused"  style="width:150px"  value="<?php echo $homeimage['Homeimage']['from_date']; ?>" name="data[Homeimage][from_date]" id="from_date" type="text"  >
								</div>
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('to_date') ?> :</label>
								  <input class="input-xlarge focused"  style="width:150px"  value="<?php echo $homeimage['Homeimage']['to_date']; ?>" name="data[Homeimage][to_date]" id="to_date" type="text"  >
								  <input class="btn btn-mini btn-primary" type="button" value="غیر فعا کردن تقویم" id="destroy" style="font-family: tahoma" />
								</div>
								
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('arrangment') ?> :</label>
								  <input class="input-xlarge focused" value="<?php echo $homeimage['Homeimage']['arrangment']; ?>" name="data[Homeimage][arrangment]" id="focusedInput" type="text"   style="width:50px">
								</div>
								
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('status') ?> :</label>
								  <select id="selectError3" style="width:auto" name="data[Homeimage][status]">
									<?php 
										if($homeimage['Homeimage']['status']==1) 
											echo "<option value='1' selected>". __('active')."</option>";
										else echo "<option value='1'>". __('active')."</option>"; 
										
										if($homeimage['Homeimage']['status']==0) 
											echo "<option value='0' selected>". __('inactive')."</option>";
										else echo "<option value='0'>". __('inactive')."</option>"; 
										
									?>
								  </select>
								</div>
								
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('image') ?> :</label>
								  <input name="data[Homeimage][image]" type="file" id="image" />
								  <input name=data[Homeimage][old_image] type="hidden" value="<?php echo $homeimage['Homeimage']['image']; ?>">
								  <?php  
						  
									if(fileExistsInPath(__HOME_IMAGE_PATH.$homeimage['Homeimage']['image'] ) && $homeimage['Homeimage']['image']!='' ) 
										{
											echo "<a target='_blank' href= '".__SITE_URL.__HOME_IMAGE_PATH.$homeimage['Homeimage']['image']."' >";
											echo $this->Html->image('/'.__HOME_IMAGE_PATH.$homeimage['Homeimage']['image'],array('id'=>'image_img','height'=>100));
											echo "</a>";
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
	
	<script>
		$('#from_date').datetimepicker();
		$('#from_date').datetimepicker({step:5});
		$('#to_date').datetimepicker();
		$('#to_date').datetimepicker({step:5});
		
		$('#destroy').click(function(){
	if( $('#to_date').data('xdsoft_datetimepicker') ){
		$('#to_date').datetimepicker('destroy');
		this.value = 'فعال کردن';
	}else{
		$('#to_date').datetimepicker();
		this.value = 'غیر فهال کردن';
	}
});
		
	</script>
	