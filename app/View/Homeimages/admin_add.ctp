
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
			<?php echo $this->Form->create('Post', array('id'=>'AddFrom','name'=>'AddFrom','enctype'=>'multipart/form-data')); ?>
			<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-edit"></i><?php echo __('add_home_image') ?></h2>
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
								  <input class="input-xlarge focused" style="width:500px" value="" name="data[Homeimage][title]" id="focusedInput" type="text" width="250" >
								</div>
								
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('from_date') ?> :</label>
								  <input class="input-xlarge focused"  style="width:150px"  value="<?php /*echo date('Y/m/d h:i ', time()); */?>" name="data[Homeimage][from_date]" id="from_date" type="text"  >
								</div>
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('to_date') ?> :</label>
								  <input class="input-xlarge focused"  style="width:150px"  value="" name="data[Homeimage][to_date]" id="to_date" type="text"  >
								</div>
								
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('arrangment') ?> :</label>
								  <input class="input-xlarge focused" value="" name="data[Homeimage][arrangment]" id="focusedInput" type="text"   style="width:50px">
								</div>
								
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('status') ?> :</label>
								  <select id="selectError3" style="width:auto" name="data[Homeimage][status]">
									<option value="1"><?php echo __('active') ?></option>
									<option value='0'><?php echo __('inactive') ?></option>
								  </select>
								</div>
								
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('image') ?> :</label>
								  <input name="data[Homeimage][image]" type="file" id="image" />
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
		$('#from_date').datetimepicker({value:'0000/00/00 00:00',step:5});
		$('#to_date').datetimepicker();
		$('#to_date').datetimepicker({value:'0000/00/00 00:00',step:5});
	</script>
	