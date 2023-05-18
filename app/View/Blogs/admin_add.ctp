<?php
	echo $this->Html->css('/css/ListSelector/autocomplete.css'); 
	echo $this->Html->css('/css/ListSelector/ui-lightness/jquery-ui-1.8.custom'); 
	echo $this->Html->script('/js/ListSelector/admin-jquery-ui-custom.min');
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
			<?php echo $this->Form->create('Blog', array('id'=>'AddFrom','name'=>'AddFrom')); ?>
			<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-edit"></i><?php echo __('add_blog') ?></h2>
						<div class="box-icon">
							<a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
						</div>
					</div>
					<div class="box-content">
		
							<fieldset>
							  <div class="control-group">
								
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('title') ?> :</label>
								  <input class="input-xlarge focused" name="data[Blog][title_<?php echo $this->request->params['named']['lang']; ?>]" id="focusedInput" type="text"  >
								</div>
								
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('status') ?> :</label>
								  <select id="selectError3" style="width:auto" name="data[Blog][status]">
									<option value="1"><?php echo __('active') ?></option>
									<option value='0'><?php echo __('inactive') ?></option>
								  </select>
								</div>
								
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('tag') ?> :</label>
								  <div id="messageForm">
									  <div id="friends" class="ui-helper-clearfix"   >
											<input id='friend_input' type='text' dir='rtl' size='30' name="data[Blog][tag]">
											<small><?php  echo __('separate_with_sharp'); ?></small>
									  </div>
								  </div>
								</div>
								<br />
								
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('little_body') ?> :</label>
								  <textarea  name="data[Blog][little_body_<?php echo $this->request->params['named']['lang']; ?>]" id="first_editor" rows="6" style="width:700px" ></textarea>
								</div>
								
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('cbody') ?> :</label>
								  <textarea class="input-xlarge focused" name="data[Blog][body_<?php echo $this->request->params['named']['lang']; ?>]" id="last_editor" ></textarea>
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
		var tarr =[];
		var arr = [];
 
	$("#friend_input").autocomplete({
					//define callback to format results
				
					source: function(req, add){
					
						//pass request to server
						$.getJSON(_url+"blogtags/tag_search?callback=?", req, function(data) {
							
							//create array for response objects
							var suggestions = [];
							//var suggestions1 = [];
							//process response
							$.each(data, function(i, val){	
							 						
								suggestions.push({'title':val.title,'id':val.id});
								 
								//suggestions.push(val.name+'<input type=hidden id='+val.user_id+'>');
							 
							});
							
							//pass array to callback
							
							add(suggestions);
							 
							 
						});
					},
					select: function(e, ui) {
						
						//create formatted tag
						var friend = ui.item.value;
						 
						var user_count= $("#friends span").length+1; 
						var id= ui.item.id;
							span = $("<span>").text(friend),
							a = $("<a>").addClass("remove").attr({
								href: "javascript:",
								title: "Remove " + friend,
								id   : id,
							}).text("x").appendTo(span);
						var hide_input= "<input type='hidden' name='tag_id_arr[]' value='"+id+"' >";
						//hide_input.appendTo(span);
						span.append(hide_input);
						//add friend to friend div
						 
						span.insertBefore("#friend_input");
					},
					
					//define select handler
					change: function() {
						
						//prevent 'to' field being updated and correct position
						$("#friend_input").val("").css("top", 2);
					}
				});
				
				//add click handler to user_ids div
				$("#friends").click(function(){
					
					//focus 'to' field
					$("#friend_input").focus();
				});
				
				//add live handler for clicks on remove links
				$(".remove", document.getElementById("friends")).live("click", function(){
				
					//remove current friend
					$(this).parent().remove();
					
					//correct 'to' field position
					if($("#friends span").length === 0) {
						$("#friend_input").css("top", 0);
					}				
				});	
		
		//CKEDITOR.replace( 'first_editor' );
		CKEDITOR.replace( 'last_editor' );
		//LoadEditor("data[Blog][little_body_<?php echo $this->request->params['named']['lang']; ?>]",580);
		//LoadEditor("data[Blog][body_<?php echo $this->request->params['named']['lang']; ?>]",580);
	</script>
	  
 
 
            
