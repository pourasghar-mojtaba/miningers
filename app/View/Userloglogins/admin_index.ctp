<?php
	echo $this->Html->css('/js/admin/datetimepicker-master/jquery.datetimepicker');	
	echo $this->Html->script('/js/admin/datetimepicker-master/jquery.datetimepicker');
?>
<!-- topbar starts -->
	  
	<!-- topbar ends -->
		<div class="container-fluid">
		<div class="row-fluid">
				
			<!-- right menu starts -->
				<?php echo $this->element('Admin/right_menu'); ?>       
			<!-- right menu ends -->
			
			<div id="content" class="span10">
			<!-- content starts -->
				<?php if($this->Session->check('Message.flash')) {?>
				<div >
					  <?php echo $this->Session->flash(); ?>
				</div>
				<?php } ?>
				 
			 <div class="row-fluid sortable ui-sortable">	
				<div class="box span12">
					<div data-original-title="" class="box-header well">
						<h2><?php echo __('userloglogins'); ?></h2>
						<div class="box-icon">
							<a class="btn btn-minimize btn-round" href="#"><i class="icon-chevron-up"></i></a>
						</div>
					</div>
					<div class="box-content">
					<?php echo $this->Form->create('User', array('id'=>'SearchFrom','name'=>'SearchFrom')); ?>
						<div class="row-fluid">
							<div class="span6">
								<div id="DataTables_Table_0_length" class="dataTables_length">
									<label>
									 <a id="all_export_link" href="<?php echo  __SITE_URL."admin/userloglogins/alldate_export" ?>"><?php echo $this->Html->image('/img/icons/excel.png'); ?></a>
									 <?php echo __('records_per_page'); ?> :
									<select size="1" aria-controls="DataTables_Table_0" onchange="if (this.value) window.location.href=this.value">
										<?php
	
											if(isset($_REQUEST['filter']) && $_REQUEST['filter']==50)
												echo "<option value='". __SITE_URL."admin/userloglogins/index?filter=50 ' selected='selected'>50</option>";
												else echo"<option value='". __SITE_URL."admin/userloglogins/index?filter=50 '>50</option>";	
											if(isset($_REQUEST['filter']) && $_REQUEST['filter']==100)
												echo "<option value='". __SITE_URL."admin/userloglogins/index?filter=100 ' selected='selected'>100</option>";
												else echo"<option value='". __SITE_URL."admin/userloglogins/index?filter=100 '>100</option>";			
												if(isset($_REQUEST['filter']) && $_REQUEST['filter']==150)
												echo "<option value='". __SITE_URL."admin/userloglogins/index?filter=150 ' selected='selected'>150</option>";
												else echo"<option value='". __SITE_URL."admin/userloglogins/index?filter=150 '>150</option>";
											if(isset($_REQUEST['filter']) && $_REQUEST['filter']==200)
												echo "<option value='". __SITE_URL."admin/userloglogins/index?filter=200 ' selected='selected'>200</option>";
												else echo"<option value='". __SITE_URL."admin/userloglogins/index?filter=200 '>200</option>";
										?>
									</select> 
									</label>
								</div>
							</div>
							<div class="span6">
								
								<div class="dataTables_filter" id="DataTables_Table_0_filter">
								<label>
                                <?php echo __('from_date'); ?>: 
                                    <input class="input-xlarge focused"  style="width:100px"  value="<?php /*echo date('Y/m/d h:i ', time()); */?>" name="data[Homeimage][from_date]" id="from_date" type="text"  >
                                <?php    echo __('to_date'); ?>: 
                                    <input class="input-xlarge focused"  style="width:100px"  value="<?php /*echo date('Y/m/d h:i ', time()); */?>" name="data[Homeimage][to_date]" id="to_date" type="text"  >
								</div>
							  	
							</div>
						</div>
						</form>
						<table class="table table-bordered table-striped table-condensed">
							  <thead>
								  <tr>
									  <th><?php echo  $this->Paginator->sort('Userloglogin.sum_login', __('sum_login') ) ;?></th>
									  <th><?php echo  $this->Paginator->sort('Userloglogin.created',__('created'));?></th> 
									  <th><?php echo __('action'); ?>  </th>                                         
								  </tr>
							  </thead>   
							  <tbody>
							  <?php
                              //pr($userloglogins); 
							  	if(!empty($userloglogins))
								{
									foreach($userloglogins as $userloglogin)
									{
										
								?>
										<tr>								
											<td class="center"><?php echo $userloglogin['0']['sum_login']; ?></td>
											<td class="center"><?php echo $userloglogin['0']['created']; ?></td>
											<td class="center ">
												<a href="<?php echo __SITE_URL.'admin/userloglogins/onedate_export/'.$userloglogin['0']['created'];  ?>" class="btn btn-success">
													<i class="icon32 icon-white icon-xls"></i>  
													<?php echo __('export_excel'); ?>                                            
												</a>
												  
												<!--<a href="<?php echo __SITE_URL.'admin/userloglogins/delete/'.$userloglogin['0']['id']; ?>" class="btn btn-danger" 
												onclick="return confirm('<?php echo __('r_u_sure'); ?>')">
													<i class="icon-trash icon-white"></i> 
													<?php echo __('delete'); ?>
												</a>-->
											</td>                                     
										</tr>  
								<?php
								
									}
								}
							  ?>                                
							  </tbody>
						 </table>  
						 <div class="pagination pagination-centered">
						  <ul>
						  <?php echo $this->Paginator->prev(__('prev'), array('tag'=>'li'), null, array('disabledTag'=>'a','tag'=>'li','class' => 'prev disabled'));?>
							<!--<li><a href="#">Prev</a></li>->
							<!--<li class="active">
							  <a href="#">1</a>
							</li>
							<li><a href="#">2</a></li>
							<li><a href="#">3</a></li>
							<li><a href="#">4</a></li>-->
							<?php echo $this->Paginator->numbers(array('tag'=>'li','separator'=>'','currentClass'=>'active','currentTag'=>'a'));?>
							<!--<li><a href="#">Next</a></li>-->
							<?php echo $this->Paginator->next(__('next'), array('tag'=>'li'), null, array('disabledTag'=>'a','tag'=>'li','class' => 'next disabled'));?>
						  </ul>
						</div>     
					</div>
				</div><!--/span-->
			</div>
		
    
					<!-- content ends -->
			</div><!--/#content.span10-->
	</div><!--/fluid-row-->
</div>				
	

<script>
		 
         var valid_link =$("#all_export_link").attr("href"); 
          function   change_from_date(){
            $("#all_export_link").attr('href','') ; 
            $("#all_export_link").attr('href', valid_link+'/'+$('#from_date').val()) ; 
          } 
          
          function   change_to_date(){
            $("#all_export_link").attr('href','') ; 
            $("#all_export_link").attr('href', valid_link+'/'+$('#from_date').val()+'/'+$('#to_date').val()) ; 
          }   
            
         
        
        $('#from_date').datetimepicker();
		$('#from_date').datetimepicker({value:'',timepicker:false,format:'Y-m-d'});
		$('#to_date').datetimepicker();
		$('#to_date').datetimepicker({value:'',timepicker:false,format:'Y-m-d',onChangeDateTime:change_to_date});
        
        
        
        
</script>


	