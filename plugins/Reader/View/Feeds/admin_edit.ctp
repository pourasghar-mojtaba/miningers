<?php
    echo $this->Html->css('/reader/css/addrow');
	echo $this->Html->css('/reader/js/datetimepicker-master/jquery.datetimepicker');	
	echo $this->Html->script('/reader/js/datetimepicker-master/jquery.datetimepicker');
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
				<div>
					  <?php echo $this->Session->flash(); ?>
				</div>
				<?php } ?>
			</div>
			<?php echo $this->Form->create('Feed', array('id'=>'EditFrom','name'=>'AddFrom','enctype'=>'multipart/form-data')); ?>
			<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-edit"></i><?php echo __('edit_feed') ?></h2>
						<div class="box-icon">
							<a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
						</div>
					</div>
					<div class="box-content">
                       
						<form class="form-horizontal">
							<fieldset>
							  <div class="control-group">
								
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('name') ?> :</label>
								  <input class="input-xlarge focused" style="width:150px" id="name" type="text" width="250" value="<?php echo $feed['User']['name']; ?>" >
								    <span id="loading"></span>
								    <select id="user_place" name="data[Feed][user_id]"></select>
								  
								</div>
								
                                <div class="controls">
                                  <label class="control-label" for="focusedInput"><?php echo __('feed_url') ?> :</label>
								<table id="url_table" class="expense_table" cellspacing="0" cellpadding="0">
                                        <thead>
                                            <tr>
                                                <th><?php echo __('url'); ?></th>
												<th><?php echo __('rss_count'); ?></th>
                                                <th><?php echo __('arrangment'); ?></th>
												<th><?php echo __('status'); ?></th>
                                                <th>&nbsp;</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                            <?php
                                               //pr($technicalinfoitems);
                                                if(!empty($feedurls)){
                                                    foreach($feedurls as $key =>$feedurl)
                                                    {
                                                        $row_id = $key +1;
                                                        if($row_id<10){
                                                            $row_id = '0'.$row_id;
                                                        }
                                                        echo "
                                                            <tr>
                                                                <td>
                                                                    <input type='text' name='data[Feedurl][url][]' id='url_no_".$row_id."' maxlength='255' required value='".$feedurl['Feedurl']['url']."' />
                                                                </td>
																<td>
                                                                    <input type='text' name='data[Feedurl][rss_count][]' id='rss_count_no_".$row_id."' maxlength='255' style='width:60px' required value='".$feedurl['Feedurl']['rss_count']."' />
                                                                </td>
                                                                <td>
                                                                    <input type='text' style='width:50px' name='data[Feedurl][arrangment][]' id='arrangment_no_".$row_id."' maxlength='20' required value='".$feedurl['Feedurl']['arrangment']."' />
                                                                </td>
                												<td>
                													<select id='status_no_".$row_id."' style='width:auto' name='data[Feedurl][status][]'>";
                														if($feedurl['Feedurl']['status']==1) 
                                											echo "<option value='1' selected>". __('active')."</option>";
                                										else echo "<option value='1'>". __('active')."</option>"; 
                                										
                                										if($feedurl['Feedurl']['status']==0) 
                                											echo "<option value='0' selected>". __('inactive')."</option>";
                                										else echo "<option value='0'>". __('inactive')."</option>"; 
                												echo"
                                                                    </select>
                												</td>";
                                                                if($row_id>'01')
                                                                  echo"<td><input type='button' value='Delete' class='del_UrlRow' /></td>";
                                                                  else echo "<td>&nbsp;</td>";
																  echo "<input type='hidden' value='".$feedurl['Feedurl']['id']."' name='data[Feedurl][id][]'>";
                                                            echo"</tr>
                                                        ";
                                                    }
                                                }
                                            ?>
  
                                        </tbody>
                                    </table>
                                    <input class="add_UrlRow" type="button" value="<?php echo __('add_row'); ?>" id="add_UrlRow" />
                                </div>  
								
								<div class="controls">
                                  <label class="control-label" for="focusedInput"><?php echo __('feed_time') ?> :</label>
								<table id="time_table" class="expense_table" cellspacing="0" cellpadding="0">
                                        <thead>
                                            <tr>
                                                <th><?php echo __('time'); ?></th>
                                                <th>&nbsp;</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                            <?php
                                               //pr($technicalinfoitems);
                                                if(!empty($feedtimes)){
                                                    foreach($feedtimes as $key =>$feedtime)
                                                    {
                                                        $row_id = $key +1;
                                                        if($row_id<10){
                                                            $row_id = '0'.$row_id;
                                                        }
                                                        echo "
                                                            <tr>
                                                                <td>
                                                                    <input type='text' name='data[Feedtime][read_time][]' id='url_no_".$row_id."' maxlength='255' style='width:70px' onmousedown='view_time(this)' required value='".$feedtime['Feedtime']['read_time']."' />
                                                                </td>
																";
                                                                if($row_id>'01')
                                                                  echo"<td><input type='button' value='Delete' class='del_TimeRow' /></td>";
                                                                  else echo "<td>&nbsp;</td>";
																  echo "<input type='hidden' value='".$feedtime['Feedtime']['id']."' name='data[Feedtime][id][]'>";
                                                            echo"</tr>
                                                        ";
                                                    }
                                                }
                                            ?>
  
                                        </tbody>
                                    </table>
                                    <input class="add_TimeRow" type="button" value="<?php echo __('add_row'); ?>" id="add_TimeRow" />
                                </div>  
								
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('status') ?> :</label>
								  <select id="selectError3" style="width:auto" name="data[Feed][status]">
									<?php 
										if($feed['Feed']['status']==1) 
											echo "<option value='1' selected>". __('active')."</option>";
										else echo "<option value='1'>". __('active')."</option>"; 
										
										if($feed['Feed']['status']==0) 
											echo "<option value='0' selected>". __('inactive')."</option>";
										else echo "<option value='0'>". __('inactive')."</option>"; 
										
									?>
								  </select>
								</div>
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('tag') ?> :</label>
								  <input class="input-xlarge focused" value="<?php echo $feed['Feed']['tag'] ?>" style="width:700px" name="data[Feed][tag]" type="text" width="250" > <?php echo __('separate_with_sharp'); ?>
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
	
	function view_time(obj){
		$(obj).datetimepicker({
			datepicker:false,
			format:'H:i',
			step:5
		});
	}
	
	$("#loading").html('<img src="'+_durl+'reader/img/loader/indicator.gif" >');	
		var name = $('#name').val();
         
		$.ajax({
			type:"POST",
			url:_url+'reader/feeds/user_search/',
			data:'name='+name,
			success:function(response){
				$("#user_place").html(response);								
			    $("#loading").empty();
			}
			
		});
    
    
	 $('#name').keyup(function(){
	 	$("#loading").html('<img src="'+_durl+'reader/img/loader/indicator.gif" >');	
		var name = $('#name').val();
		$.ajax({
			type:"POST",
			url:_url+'reader/feeds/user_search/',
			data:'name='+name,
			success:function(response){
				$("#user_place").html(response);								
			    $("#loading").empty();
			}
			
		});	
	 })
	 
	//=============================================================================================
		$(function(){
			
        	// GET ID OF last row and increment it by one
        	var $lastUrlChar =1, $newUrlRow;
        	$get_lastID = function(){
        		var $id = $('#url_table tr:last-child td:first-child input').attr("id");
        		$lastUrlChar = parseInt($id.substr($id.length - 2), 10);
        		console.log('GET id: ' + $lastUrlChar + ' | $id :'+$id);
        		$lastUrlChar = $lastUrlChar + 1;
        		$newUrlRow = "<tr> \
        					<td><input type='text' name='data[Feedurl][url][]' id='url_no_0"+$lastUrlChar+"' maxlength='255' /></td> \<td><input type='text' name='data[Feedurl][rss_count][]' id='rss_count_no_0"+$lastUrlChar+"' style='width:60px' maxlength='255' /></td> \
                            <td><input type='text' name='data[Feedurl][arrangment][]' style='width:50px' id='arrangment_no_0"+$lastUrlChar+"' maxlength='20' /></td> \
							<td><select id='status_no_0"+$lastUrlChar+"' style='width:auto' name='data[Feedurl][status][]'><option value='1'><?php echo __('active') ?></option><option value='0'><?php echo __('inactive') ?></option></td> \
        					<td><input type='button' value='Delete' class='del_UrlRow' /></td> \
        				</tr>"
        		return $newUrlRow;
        	}
        	
        	// ***** -- START ADDING NEW ROWS
        	$('#add_UrlRow').live("click", function(){ 
        		//if($lastUrlChar <= 9){
        			$get_lastID();
        			$('#url_table tbody').append($newUrlRow);
        		/*} else {
        			alert("Reached Maximum Rows!");
        		};*/
        	});
        	
        	$(".del_UrlRow").live("click", function(){ 
        		$(this).closest('tr').remove();
        		$lastUrlChar = $lastUrlChar-2;
        	});	
		//=================add_TimeRow====================================================================================
		// GET ID OF last row and increment it by one
        	var $lastTimeChar =1, $newTimeRow;
        	$get_lastTimeID = function(){
        		var $id = $('#url_table tr:last-child td:first-child input').attr("id");
        		$lastTimeChar = parseInt($id.substr($id.length - 2), 10);
        		console.log('GET id: ' + $lastTimeChar + ' | $id :'+$id);
        		$lastTimeChar = $lastTimeChar + 1;
        		$newTimeRow = "<tr> \
        					<td><input type='text' name='data[Feedtime][read_time][]' onmousedown='view_time(this)' style='width:70px' id='time_no_0"+$lastTimeChar+"' maxlength='255' /></td> \<td><input type='button' value='Delete' class='del_TimeRow'  /></td> \
        				</tr>"
        		return $newTimeRow;
        	}
        	
        	// ***** -- START ADDING NEW ROWS
        	$('#add_TimeRow').live("click", function(){ 
        		//if($lastTimeChar <= 9){
        			$get_lastTimeID();

					
        			$('#time_table tbody').append($newTimeRow);
        		/*} else {
        			alert("Reached Maximum Rows!");
        		};*/
        	});
        	
        	$(".del_TimeRow").live("click", function(){ 
        		$(this).closest('tr').remove();
        		$lastTimeChar = $lastTimeChar-2;
        	});	
		//=====================================================================================================
		
			
        });	
	</script>
	