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
			<?php echo $this->Form->create('Role', array('id'=>'AddFrom','name'=>'AddFrom')); ?>
			<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-edit"></i><?php echo __('permision').' '.$role_info['Role']['name'] ?></h2>
						<div class="box-icon">
							<a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
						</div>
					</div>
					<div class="box-content">
						<form class="form-horizontal">
							<fieldset>
							  <div class="control-group">
									<table class="table" width="400">
									  <thead>
										  <tr>
											  <th><?php echo __('part')?></th>
											  <th><?php echo __('action')?></th>
											  <th> </th>                                          
										  </tr>
									  </thead>   
									  <tbody>
									  <?php
										 if(!empty($aclitems)){
										 	foreach($aclitems as $aclitem)
											{
												echo" 
													<tr>
														<td class='center'>".$aclitem['Aclitem']['name']."</td>
														<td class='center'>".$aclitem['Aclitem']['action_name']."</td>
														<td class='center' id='prbtn_".$aclitem['Aclitem']['id']."'>";
														if($aclitem['Aclitem']['id']==$aclitem['Aclrole']['aclitem_id'])
															echo "<a href='JavaScript:void(0);' onclick='inactive_permission(".$_REQUEST['role_id'].",".$aclitem['Aclitem']['id'].")' id='aclitem_".$aclitem['Aclitem']['id']."'>
															<span class='label label-success'>".__('active')."</span>
															</a>";
															else echo"<a href='JavaScript:void(0);' onclick='active_permission(".$_REQUEST['role_id'].",".$aclitem['Aclitem']['id'].")' id='aclitem_".$aclitem['Aclitem']['id']."'>
															<span class='label label-important'>".__('inactive')."</span></a>";
												echo"	</td>                                       
													</tr>
												";
											}
										 }
									
									?>
                                  
									  </tbody>
								 </table>
								
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