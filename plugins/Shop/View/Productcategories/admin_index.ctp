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
						<i class="icon-add"></i><a href="<?php echo __SITE_URL."admin/shop/productcategories/add"; ?>">&nbsp;<?php echo __('add_product_category'); ?></a> 
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
						<h2><?php echo __('product_category'); ?></h2>
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
									  <th><?php echo __('slug');?></th> 
									  <th><?php echo __('arrangment');?></th> 
									  <th><?php echo __('status');?></th>  
									  <th><?php echo __('created');?></th> 
									  <th><?php echo __('action'); ?>  </th>                                         
								  </tr>
							  </thead>   
							  <tbody>
							  <?php 
							    
							  	if(!empty($productcategories))
								{
									foreach($productcategories as $product_category)
									{
										
								?>
										<tr>								
											<td class="center"><?php echo $product_category['id']; ?></td>
											<td class="center"><?php echo $product_category['title']; ?></td>
											<td style="direction: ltr;text-align: left;"  >
											<?php echo $product_category['slug']; ?></td>
											<td class="center"><?php echo $product_category['arrangment']; ?></td>
											<td class="center">
											 <?php
											   if($product_category['status']==1)
											     echo "<span class='label label-success'>".__('active')."</span>";
											   else echo "<span class='label label-important'>".__('inactive')."</span>";	 
											 ?>
												
											</td> 
											<td class="center"><?php echo $product_category['created']; ?></td>
											<td class="center ">
												<a href="<?php echo __SITE_URL.'admin/shop/productcategories/edit/'.$product_category['id'];  ?>" class="btn btn-info">
													<i class="icon-edit icon-white"></i>  
													<?php echo __('edit'); ?>                                            
												</a>
												
												<a href="<?php echo __SITE_URL.'admin/shop/productcategories/delete/'.$product_category['id']; ?>" class="btn btn-danger" 
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
	




	