<!-- topbar starts -->
	 
	<!-- topbar ends -->
		<div class="container-fluid">
		<div class="row-fluid">
				
			<!-- right menu starts -->
				<?php echo $this->element('Admin/right_menu'); ?>       
			<!-- right menu ends -->
			
			<div id="content" class="span10">
			<!-- content starts -->
			
			<div>
				<ul class="breadcrumb">
					<li>
						<i class="icon-add"></i><a href="<?php echo __SITE_URL."admin/newsletters/add"; ?>">&nbsp;<?php echo __('add_newsletter'); ?></a> 
					</li>
				</ul>
			</div>
			
			<?php if($this->Session->check('Message.flash')) {?>
				<div >
					  <?php echo $this->Session->flash(); ?>
				</div>
				<?php } ?>
				 
			 <div class="row-fluid sortable ui-sortable">	
				<div class="box span12">
					<div data-original-title="" class="box-header well">
						<h2><?php echo __('newsletters'); ?></h2>
						<div class="box-icon">
							<a class="btn btn-minimize btn-round" href="#"><i class="icon-chevron-up"></i></a>
						</div>
					</div>
					<div class="box-content">
					<?php echo $this->Form->create('Newsletter', array('id'=>'SearchFrom','name'=>'SearchFrom')); ?>
						<div class="row-fluid">
							<div class="span6">
								<div id="DataTables_Table_0_length" class="dataTables_length">
									<label>
									 <?php echo __('records_per_page'); ?> :
									<select size="1" aria-controls="DataTables_Table_0" onchange="if (this.value) window.location.href=this.value">
										<?php
											if(isset($_REQUEST['filter']) && $_REQUEST['filter']==10)
												echo "<option value='". __SITE_URL."admin/newsletters/index?filter=10 ' selected='selected'>10</option>";
												else echo"<option value='". __SITE_URL."admin/newsletters/index?filter=10 '>10</option>";
											if(isset($_REQUEST['filter']) && $_REQUEST['filter']==25)
												echo "<option value='". __SITE_URL."admin/newsletters/index?filter=25 ' selected='selected'>25</option>";
												else echo"<option value='". __SITE_URL."admin/newsletters/index?filter=25 '>25</option>";	
											if(isset($_REQUEST['filter']) && $_REQUEST['filter']==50)
												echo "<option value='". __SITE_URL."admin/newsletters/index?filter=50 ' selected='selected'>50</option>";
												else echo"<option value='". __SITE_URL."admin/newsletters/index?filter=50 '>50</option>";	
											if(isset($_REQUEST['filter']) && $_REQUEST['filter']==100)
												echo "<option value='". __SITE_URL."admin/newsletters/index?filter=100 ' selected='selected'>100</option>";
												else echo"<option value='". __SITE_URL."admin/newsletters/index?filter=100 '>100</option>";			
										?>
									</select> 
									</label>
								</div>
							</div>
							<div class="span6">
							
								<div class="dataTables_filter" id="DataTables_Table_0_filter">
								<label><?php echo __('search'); ?>: <input type="text" name="data[Newsletter][search]" aria-controls="DataTables_Table_0"></label>
								</div>
							  	
							</div>
						</div>
						</form>
						<table class="table table-bordered table-striped table-condensed">
							  <thead>
								  <tr>
									  <th><?php echo $this->Paginator->sort('Newsletter.id', __('id'));?></th>
									  <th><?php echo $this->Paginator->sort('Newsletter.title',__('title'));?></th>
									  <th><?php echo $this->Paginator->sort('Newsletter.status',__('status'));?></th>  
									  <th><?php echo $this->Paginator->sort('Newsletter.created', __('created'));?></th> 
									  <th><?php echo __('action'); ?>  </th>                                         
								  </tr>
							  </thead>   
							  <tbody>
							  <?php 
							   
							  	if(!empty($newsletters))
								{
									foreach($newsletters as $newsletter)
									{
										
								?>
										<tr>								
											<td class="center"><?php echo $newsletter['Newsletter']['id']; ?></td>
											<td class="center"><?php echo $newsletter['Newsletter']['title']; ?></td>
											<td class="center">
											 <?php
											   if($newsletter['Newsletter']['status']==1)
											     echo "<span class='label label-success'>".__('active')."</span>";
											   else echo "<span class='label label-important'>".__('inactive')."</span>";	 
											 ?>
												
											</td> 
											<td class="center"><?php echo $newsletter['Newsletter']['created']; ?></td>
											<td class="center ">
												<a href="<?php echo __SITE_URL.'admin/newsletters/edit/'.$newsletter['Newsletter']['id'];  ?>" class="btn btn-info">
													<i class="icon-edit icon-white"></i>  
													<?php echo __('edit'); ?>                                            
												</a>
												
												<a href="<?php echo __SITE_URL.'admin/newsletters/delete/'.$newsletter['Newsletter']['id']; ?>" class="btn btn-danger" 
												onclick="return confirm('<?php echo __('r_u_sure'); ?>')">
													<i class="icon-trash icon-white"></i> 
													<?php echo __('delete'); ?>
												</a>
												
											</td>                                     
										</tr>  
								<?php
								
									}
								}
							  ?>                                
							  </tbody>
						 </table>  
						       
					</div>
				</div><!--/span-->
			</div>
		
    
					<!-- content ends -->
			</div><!--/#content.span10-->
	</div><!--/fluid-row-->
</div>				
	




	