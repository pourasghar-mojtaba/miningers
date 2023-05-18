<?php
    $AdminUser_Info=$this->Session->read('AdminUser_Info');
   // pr($AdminUser_Info);
?>

		
		<div class="row-fluid">
				
			<!-- right menu starts -->
				<?php echo $this->element('Admin/right_menu'); ?>       
			<!-- right menu ends -->
			
			<noscript>
				<div class="alert alert-block span10">
					<h4 class="alert-heading">Warning!</h4>
					<p>You need to have <a href="http://en.wikipedia.org/wiki/JavaScript" target="_blank">JavaScript</a> enabled to use this site.</p>
				</div>
			</noscript>
			
			<div id="content" class="span10">
			<!-- content starts -->
			
			<div class="sortable row-fluid">
				<a data-rel="tooltip" title="0 <?php echo __('today_members') ?>." class="well span3 top-block" href="#">
					<span class="icon32 icon-red icon-user"></span>
					<div><?php echo __('total_members') ?></div>
					<div>0</div>
					<span class="notification">0</span>
				</a>

				<a data-rel="tooltip" title="0 <?php echo __('today_posts') ?>." class="well span3 top-block" href="#">
					<span class="icon32 icon-color icon-star-on"></span>
					<div><?php echo __('total_posts') ?></div>
					<div>0</div>
					<span class="notification green">0</span>
				</a>
			</div>
			
			<div class="row-fluid">
				<div class="box span12">
					<div class="box-header well">
						<h2><i class="icon-info-sign"></i> <?php echo __('managment_info') ?></h2>
						<div class="box-icon">
							<a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
						</div>
					</div>
					<div class="box-content">
						 
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
			
			<div class="row-fluid">
				<div class="box span12">
					<div class="box-header well">
						<h2><i class="icon-info-sign"></i> <?php echo __('site_viewed_chart') ?></h2>
						<div class="box-icon">
							<a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
						</div>
					</div>
					<div class="box-content">
						 
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
					
			<div class="row-fluid sortable">
						
				<div class="box span4">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-user"></i><?php echo __('new_members') ?>  </h2>
						<div class="box-icon">
							<a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
						</div>
					</div>
					<div class="box-content">
						<div class="box-content">
							<!-- conent -->
						</div>
					</div>
				</div><!--/span-->
						
				<div class="box span4">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-list"></i> <?php echo __('weekly_info') ?> </h2>
						<div class="box-icon">
							<a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
						</div>
					</div>
					<div class="box-content">
						<ul class="dashboard-list">
							<li>
								<a href="#">
									<i class="icon-arrow-up"></i>                               
									<span class="green">92</span>
									<?php echo __('new_registers') ?>                                   
								</a>
							</li>
						  <li>
							<a href="#">
							  <i class="icon-arrow-down"></i>
							  <span class="red">15</span>
							  <?php echo __('new_folows') ?>  
							</a>
						  </li>
						  <li>
							<a href="#">
							  <i class="icon-minus"></i>
							  <span class="blue">36</span>
							  <?php echo __('new_posts') ?>                                    
							</a>
						  </li>
						  <li>
							<a href="#">
							  <i class="icon-comment"></i>
							  <span class="yellow">45</span>
							  <?php echo __('new_comments') ?>                                     
							</a>
						  </li>
						</ul>
					</div>
				</div><!--/span-->
				
				
				<div class="box span4">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-list-alt"></i> <?php echo __('inbound_links') ?></h2>
						<div class="box-icon">
							<a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
						</div>
					</div>
					<div class="box-content">
						 <div id="donutchart" style="height: 300px;">
					</div>
				</div>
			</div> 
				
				 
			</div><!--/row-->			  

		  
       
					<!-- content ends -->
			</div><!--/#content.span10-->
				</div><!--/fluid-row-->
				
		