<?php
    echo $this->Html->css('/shop/css/addrow');
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
			<?php echo $this->Form->create('Technicalinfoitem', array('id'=>'EditFrom','name'=>'AddFrom','enctype'=>'multipart/form-data')); ?>
			<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-edit"></i><?php echo __('edit_technicalinfoitem') ?></h2>
						<div class="box-icon">
							<a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
						</div>
					</div>
					<div class="box-content">
                       
						<form class="form-horizontal">
							<fieldset>
							  <div class="control-group">
								
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('technicalinfo_title') ?> :</label>
								  <input class="input-xlarge focused" style="width:500px" value="<?php echo $technicalinfo['Technicalinfo']['title']; ?>" name="data[Technicalinfo][title]" id="focusedInput" type="text" width="250" >
								</div>
								
                                <div class="controls">
                                  <label class="control-label" for="focusedInput"><?php echo __('technicalinfoitem_title') ?> :</label>
								<table id="expense_table" cellspacing="0" cellpadding="0">
                                        <thead>
                                            <tr>
                                                <th><?php echo __('items'); ?></th>
                                                <th><?php echo __('arrangment'); ?></th>
												<th><?php echo __('status'); ?></th>
                                                <th>&nbsp;</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                            <?php
                                               //pr($technicalinfoitems);
                                                if(!empty($technicalinfoitems)){
                                                    foreach($technicalinfoitems as $key =>$technicalinfoitem)
                                                    {
                                                        echo "
                                                            <tr>
                                                                <td>
                                                                    <input type='text' name='data[Technicalinfoitem][item][]' id='title_no_".$key."' maxlength='255' required value='".$technicalinfoitem['Technicalinfoitem']['item']."' />
                                                                </td>
                                                                <td>
                                                                    <input type='text' style='width:50px' name='data[Technicalinfoitem][arrangment][]' id='arrangment_no_".$key."' maxlength='20' required value='".$technicalinfoitem['Technicalinfoitem']['arrangment']."' />
                                                                </td>
                												<td>
                													<select id='status_no_".$key."' style='width:auto' name='data[Technicalinfoitem][status][]'>";
                														if($technicalinfoitem['Technicalinfoitem']['status']==1) 
                                											echo "<option value='1' selected>". __('active')."</option>";
                                										else echo "<option value='1'>". __('active')."</option>"; 
                                										
                                										if($technicalinfoitem['Technicalinfoitem']['status']==0) 
                                											echo "<option value='0' selected>". __('inactive')."</option>";
                                										else echo "<option value='0'>". __('inactive')."</option>"; 
                												echo"
                                                                    </select>
                												</td>";
                                                                //if($key>0)
                                                                  echo"<td><input type='button' value='Delete' class='del_ExpenseRow' /></td>";
                                                                 // else echo "<td>&nbsp;</td>";
																  echo "<input type='hidden' value='".$technicalinfoitem['Technicalinfoitem']['id']."' name='data[Technicalinfoitem][id][]'>";
                                                            echo"</tr>
                                                        ";
                                                    }
                                                }
                                            ?>
  
                                        </tbody>
                                    </table>
                                    <input type="button" value="<?php echo __('add_row'); ?>" id="add_ExpenseRow" />
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
	    $(function(){
        	// GET ID OF last row and increment it by one
        	var $lastChar =1, $newRow;
        	$get_lastID = function(){
        		var $id = $('#expense_table tr:last-child td:first-child input').attr("id");
        		$lastChar = parseInt($id.substr($id.length - 2), 10);
        		console.log('GET id: ' + $lastChar + ' | $id :'+$id);
        		$lastChar = $lastChar + 1;
        		$newRow = "<tr> \
        					<td><input type='text' name='data[Technicalinfoitem][item][]' id='title_no_0"+$lastChar+"' maxlength='255' /></td> \
                            <td><input type='text' name='data[Technicalinfoitem][arrangment][]' style='width:50px' id='arrangment_no_0"+$lastChar+"' maxlength='20' /></td> \
							<td><select id='status_no_0"+$lastChar+"' style='width:auto' name='data[Technicalinfoitem][status][]'><option value='1'><?php echo __('active') ?></option><option value='0'><?php echo __('inactive') ?></option></td> \
        					<td><input type='button' value='Delete' class='del_ExpenseRow' /></td> \
        				</tr>"
        		return $newRow;
        	}
        	
        	// ***** -- START ADDING NEW ROWS
        	$('#add_ExpenseRow').live("click", function(){ 
        		//if($lastChar <= 9){
        			$get_lastID();
        			$('#expense_table tbody').append($newRow);
        		/*} else {
        			alert("Reached Maximum Rows!");
        		};*/
        	});
        	
        	$(".del_ExpenseRow").live("click", function(){ 
        		$(this).closest('tr').remove();
        		$lastChar = $lastChar-2;
        	});	
        });	
	</script>
	