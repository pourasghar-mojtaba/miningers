
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
			<?php echo $this->Form->create('Feed', array('id'=>'AddFrom','name'=>'AddFrom','enctype'=>'multipart/form-data')); ?>
			<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-edit"></i><?php echo __('add_feed') ?></h2>
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
								  <input class="input-xlarge focused" style="width:150px" id="name" type="text" width="250" >
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
                                            <tr>
                                                <td>
                                                    <input type="text" name="data[Feedurl][url][]" id="url_no_01" maxlength="255" required />
                                                </td>
												<td>
                                                    <input type="text" name="data[Feedurl][rss_count][]" id="rss_count_no_01" maxlength="255" style='width:60px' required />
                                                </td>
                                                <td>
                                                    <input type="text" style='width:50px' name="data[Feedurl][arrangment][]" id="arrangment_no_01" maxlength="20" required />
                                                </td>
												<td>
													<select id="status_no_01" style="width:auto" name="data[Feedurl][status][]">
														<option value="1"><?php echo __('active') ?></option>
														<option value='0'><?php echo __('inactive') ?></option>
												    </select>
												</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                   
                                    <input class="add_UrlRow" type="button" value="<?php echo __('add_row'); ?>" id="add_UrlRow" />
                                </div>
								
								<div class="controls">
                                  <label class="control-label" for="focusedInput"><?php echo __('feed_time') ?> :</label>
                                    <table id="time_table" class="expense_table"  cellspacing="0" cellpadding="0">
                                        <thead>
                                            <tr>
                                                <th><?php echo __('time'); ?></th>
                                                <th>&nbsp;</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <input type="text" onmousedown='view_time(this)' name="data[Feedtime][read_time][]" id="time_no_01" maxlength="255" required style="width:70px" />
                                                </td>
                                                <td>&nbsp;</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                   
                                    <input type="button" value="<?php echo __('add_row'); ?>" id="add_TimeRow" class="add_TimeRow" />
                                </div>
								
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('status') ?> :</label>
								  <select id="selectError3" style="width:auto" name="data[Feed][status]">
								    <option value='1' selected><?php echo  __('active') ?></option>
									<option value='0'><?php echo  __('inactive'); ?></option>
								  </select>
								</div>
								
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('tag') ?> :</label>
								  <input class="input-xlarge focused" style="width:700px" name="data[Feed][tag]" type="text" width="250" > <?php echo __('separate_with_sharp'); ?>
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
	