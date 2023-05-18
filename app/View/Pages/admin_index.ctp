<!-- topbar starts -->
	 
	<!-- topbar ends -->
		<div class="container-fluid">
		<div class="row-fluid">
				
			<!-- right menu starts -->
				<?php echo $this->element('Admin/right_menu'); ?>       
			<!-- right menu ends -->
			
			<div id="content" class="span10">
			<!-- content starts -->
			<div class="btn-group pull-right">
				<a href="#" data-toggle="dropdown" class="btn dropdown-toggle">
					<i class="icon-add"></i><span class="hidden-phone"> <?php echo __('add'); ?></span>
					<span class="caret"></span>
				</a>
				<ul class="dropdown-menu">
					<li>
						<a href="<?php echo __SITE_URL."admin/pages/add?language=per"; ?>">&nbsp;<?php echo __('add_page_per'); ?></a> 
					</li>
					<li class="divider"></li>
					<li>
						<a href="<?php echo __SITE_URL."admin/pages/add?language=eng"; ?>">&nbsp;<?php echo __('add_page_eng'); ?></a> 
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
						<h2><?php echo __('pages'); ?></h2>
						<div class="box-icon">
							<a class="btn btn-minimize btn-round" href="#"><i class="icon-chevron-up"></i></a>
						</div>
					</div>
					<div class="box-content">
					<?php echo $this->Form->create('Page', array('id'=>'SearchFrom','name'=>'SearchFrom')); ?>
						<div class="row-fluid">
							 
			
						</div>
						</form>
						<table class="table table-bordered table-striped table-condensed">
							  <thead>
								  <tr>
									  <th><?php echo  __('id');?></th>
									  <th><?php echo __('title');?></th> 
									  <th><?php echo __('arrangment');?></th> 
									  <th><?php echo __('status');?></th>  
									  <th><?php echo __('created');?></th> 
									  <th><?php echo __('action'); ?>  </th>                                         
								  </tr>
							  </thead>   
							  <tbody>
							  <?php 
							   
							  	if(!empty($pages))
								{
									foreach($pages as $page)
									{
										
								?>
										<tr>								
											<td class="center"><?php echo $page['id']; ?></td>
											<td class="center"><?php echo $page['title']; ?></td>
											<td class="center"><?php echo $page['arrangment']; ?></td>
											<td class="center">
											 <?php
											   if($page['status']==1)
											     echo "<span class='label label-success'>".__('active')."</span>";
											   else echo "<span class='label label-important'>".__('inactive')."</span>";	 
											 ?>
												
											</td> 
											<td class="center"><?php echo $page['created']; ?></td>
											<td class="center ">
												 
												
												<div class="btn-group pull-right">
													<a href="#" data-toggle="dropdown" class="btn dropdown-toggle">
														<i class="icon-add"></i><span class="hidden-phone"> <?php echo __('edit'); ?></span>
														<span class="caret"></span>
													</a>
													<ul class="dropdown-menu">
														<li>
															<a href="<?php echo __SITE_URL."admin/pages/edit/".$page['id']."?language=per"; ?>">&nbsp;<?php echo __('edit_page_per'); ?></a> 
														</li>
														<li class="divider"></li>
														<li>
															<a href="<?php echo __SITE_URL."admin/pages/edit/".$page['id']."?language=eng"; ?>">&nbsp;<?php echo __('edit_page_eng'); ?></a> 
														</li>
													</ul>
												</div>
												
												<a href="<?php echo __SITE_URL.'admin/pages/delete/'.$page['id']; ?>" class="btn btn-danger" 
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
	




	