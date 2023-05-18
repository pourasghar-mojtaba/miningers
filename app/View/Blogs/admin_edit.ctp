 <?php
	echo $this->Html->css('/css/ListSelector/autocomplete.css'); 
	echo $this->Html->css('/css/ListSelector/ui-lightness/jquery-ui-1.8.custom'); 
	echo $this->Html->script('/js/ListSelector/admin-jquery-ui-custom.min');
	
	echo $this->Html->script('sumoselect/jquery.sumoselect.min');
	echo $this->Html->css('/js/sumoselect/sumoselect');
	//pr($blog);
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
						<h2><i class="icon-edit"></i><?php echo __('edit_blog') ?></h2>
						<div class="box-icon">
							<a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
						</div>
					</div>
					<div class="box-content">
						<form class="form-horizontal">
							<fieldset>
							  <div class="control-group">
								
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('title') ?> :</label>
								  <input class="input-xlarge focused" name="data[Blog][title]" id="focusedInput" type="text"  value="<?php echo $blog['Blog']['title'] ; ?>">
								  
								</div>
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('image') ?> :</label>
								  <?php 
								  	if(fileExistsInPath(__USER_BLOG_PATH.$blog['Blog']['image'] ) && $blog['Blog']['image']!='' ) {
									$backimg =__SITE_URL.__USER_BLOG_PATH.$blog['Blog']['image']; 
									$check_image =$backimg;
									}
									else{
										 $backimg = $default_back ;  
										  
										}
																   
								   ?> 
								   <img  src="<?php echo $backimg; ?>" width="400"/><br /><br />
								   <input type="file" class="cropit-image-input" style="display:block" id="blog_image" name="data[Blog][image]">
								</div>  
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('channels') ?> :</label>
								  <?php
									//print_r($cur_channels);
									$channel_arr = array();
									if(!empty($cur_channels)){
										foreach($cur_channels as $cur_channel){
											$channel_arr[] = $cur_channel['Channel']['id'];
										}
									}
									 
									if(!empty($channels)){
										echo "<select name='data[Blog][channel][]' multiple='multiple' placeholder='".__('select_max_3_channel')."' class='SlectBox'>";
										foreach($channels as $channel){
											if(in_array($channel['Channel']['id'],$channel_arr)){
												echo "<option selected value='".$channel['Channel']['id']."'>".$channel['Channel']['title']."</option>";
											}else
											echo "<option value='".$channel['Channel']['id']."'>".$channel['Channel']['title']."</option>";
										}
										echo " </select>";
									}
								?>
								</div>  
								
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('status') ?> :</label>
								  <select id="selectError3" style="width:auto" name="data[Blog][status]">
									<?php 
										if($blog['Blog']['status']==1) 
											echo "<option value='1' selected>". __('active')."</option>";
										else echo "<option value='1'>". __('active')."</option>"; 
										
										if($blog['Blog']['status']==0) 
											echo "<option value='0' selected>". __('inactive')."</option>";
										else echo "<option value='0'>". __('inactive')."</option>"; 
										
									?>
								  </select>
								</div>
								
																
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('cbody') ?> :</label>
								  <textarea class="input-xlarge focused" name="data[Blog][body]" id="last_editor" >
								  	<?php echo $this->Gilace->convert_character_editor($blog['Blog']['body']) ; ?>
								  </textarea>
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
		//LoadEditor("data[Blog][little_body]",580);
		//LoadEditor("data[Blog][body]",580);
	</script>