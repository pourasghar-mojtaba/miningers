<?php

    echo $this->Html->css('/css/ListSelector/autocomplete.css'); 
	echo $this->Html->css('/css/ListSelector/ui-lightness/jquery-ui-1.8.custom'); 
	echo $this->Html->script('/js/ListSelector/admin-jquery-ui-custom.min');
	//pr($categories);
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
			<?php echo $this->Form->create('User', array('id'=>'EditFrom','name'=>'EditFrom','enctype'=>'multipart/form-data')); ?>
			<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-edit"></i><?php echo __('edit_user') ?></h2>
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
								  <input class="input-xlarge focused" value="<?php echo $user['User']['name'] ?>" name="data[User][name]" id="focusedInput" type="text"  >
								</div>
								
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('user_name') ?> :</label>
								  <input class="input-xlarge focused" value="<?php echo $user['User']['user_name'] ?>" name="data[User][user_name]" id="focusedInput" type="text"  >
								</div>
								
								
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('status') ?> :</label>
								  <select id="selectError3" style="width:auto" name="data[User][status]">
									<?php 
										if($user['User']['status']==1) 
											echo "<option value='1' selected>". __('active')."</option>";
										else echo "<option value='1'>". __('active')."</option>"; 
										
										if($user['User']['status']==0) 
											echo "<option value='0' selected>". __('inactive')."</option>";
										else echo "<option value='0'>". __('inactive')."</option>"; 
										
									?>
								  </select>
								</div>
								
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('user_type') ?> :</label>
								  <select id="selectError3" style="width:auto" name="data[User][user_type]">
									<?php 
										if($user['User']['user_type']==1) 
											echo "<option value='1' selected>". __('company')."</option>";
										else echo "<option value='1'>". __('company')."</option>"; 
										
										if($user['User']['user_type']==0) 
											echo "<option value='0' selected>". __('single')."</option>";
										else echo "<option value='0'>". __('single')."</option>"; 
										
									?>
								  </select>
								</div>
								
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('sex') ?> :</label>
								  <select id="selectError3" style="width:auto" name="data[User][sex]">
									<?php 
										if($user['User']['sex']==1) 
											echo "<option value='1' selected>". __('man')."</option>";
										else echo "<option value='1'>". __('man')."</option>"; 
										
										if($user['User']['sex']==0) 
											echo "<option value='0' selected>". __('woman')."</option>";
										else echo "<option value='0'>". __('woman')."</option>"; 
                                        /*
                                        if($user['User']['sex']==2) 
											echo "<option value='2' selected>". __('company')."</option>";
										else echo "<option value='2'>". __('company')."</option>"; */
										
									?>
								  </select>
								</div>
								
								 
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('role') ?> :</label>
								  <select id="selectError3" style="width:auto" name="data[User][role_id]">
									<?php 
										
										if(!empty($roles)){
											foreach($roles as $role)
											{
												if($role['Role']['id']==$user['User']['role_id']) 
													echo "<option value='".$role['Role']['id']."' selected>".$role['Role']['name']."</option>";	
													else echo "<option value='".$role['Role']['id']."'>".$role['Role']['name']."</option>"; 
											}
										}
										
										
										
									?>
								  </select>
								</div>
								
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('industry') ?> :</label>
								  <select name="data[User][industry_id]">
								 
								<?php
								   
									  
									if(!empty($industries)){
										foreach($industries as $industry){
										  if($user['User']['industry_id']==$industry['Industry']['id']){
										  echo"<option  selected   value='".$industry['Industry']['id']."'>";
											echo $industry['Industry']['title'];
										  echo "</option>";	
										  }else
										  {
										  	echo"<option  value='".$industry['Industry']['id']."'>";
											echo $industry['Industry']['title'];
											echo "</option>";	 
										  }
										  
										  
										}
									}
								
								?>
								</select>
								</div>
								
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('profile_image') ?> :</label>
								  <input name="data[User][image]" type="file" id="image" />
								  <input name=data[User][old_image] type="hidden" value="<?php echo $user['User']['image']; ?>">
								  <?php  
						  
									if(fileExistsInPath(__USER_IMAGE_PATH.$user['User']['image'] ) && $user['User']['image']!='' ) 
										{
											echo $this->Html->image('/'.__USER_IMAGE_PATH.$user['User']['image'],array('id'=>'image_img','height'=>100));
										}
										else{
											if($user['User']['sex']==0)
											  echo $this->Html->image('profile_women.png',array('id'=>'image_img')); else echo $this->Html->image('profile_men.png',array('id'=>'image_img'));
										}
										
									 ?>
								</div>
								
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('profile_cover_image') ?> :</label>
								  <input name="data[User][cover_image]" type="file" id="cover_image" />
								  <input name=data[User][old_cover_image] type="hidden" value="<?php echo $user['User']['cover_image']; ?>">
								  
								  <?php  
										if(fileExistsInPath(__USER_IMAGE_PATH.$user['User']['cover_image'] ) && $user['User']['cover_image']!='') {
											echo $this->Html->image('/'.__USER_IMAGE_PATH.$user['User']['cover_image'],array('id'=>'cover_image_img','width'=>400,'height'=>150));
										}
										else{
											if($user['User']['sex']==0)
											  echo $this->Html->image('cover_women.jpg',array('id'=>'cover_image_img')); else echo $this->Html->image('cover_men.jpg',array('id'=>'cover_image_img','width'=>400,'height'=>150));
										}
										
									 ?>
								  
								</div>
								
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('location') ?> :</label>
								  <input class="input-xlarge focused" value="<?php echo $user['User']['location'] ?>" name="data[User][location]" id="focusedInput" type="text"  >
								</div>
								
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('web_site') ?> :</label>
								  <input class="input-xlarge focused" value="<?php echo $user['User']['site'] ?>" name="data[User][site]" id="focusedInput" type="text"  >
								</div>
								
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('email') ?> :</label>
								  <input class="input-xlarge focused" value="<?php echo $user['User']['email'] ?>" name="data[User][email]" id="focusedInput" type="text"  >
								</div>
								
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('password') ?> :</label>
								  <input class="input-xlarge focused" value="" name="password" id="focusedInput" type="password"  >
								</div>
								
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('repassword') ?> :</label>
								  <input class="input-xlarge focused" value="" name="repassword" id="focusedInput" type="password"  >
								</div>
								
								
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('letter_of_introduction') ?> :</label>
								  <?php echo $this->Form->textarea('details',array('label'=>'','type'=>'text','id'=>'details','value'=>$user['User']['details'],'class'=>'contableTXB','maxlength'=>200)); ?>
								</div>
								<div class="controls">
									<label class="control-label" for="focusedInput"><?php echo __('tag') ?> :</label>
								  <div id="messageForm">
									  <div id="friends" class="ui-helper-clearfix"   >
									     <?php 
										 	if(!empty($userrelatetags))
											{
												foreach($userrelatetags as $userrelatetag)
												{
													echo"
														<span>".$userrelatetag['Usertag']['title']."<a class='remove' 
														 	href='javascript:'' title='Remove ".$userrelatetag['Usertag']['title']."'' id='".$userrelatetag['Usertag']['id']."'>x</a>
														 <input type='hidden' value='".$userrelatetag['Usertag']['id']."' name='data[Userrelatetag][usertag_id][]'	>
														 <input type='hidden' value='".$userrelatetag['Usertag']['id']."' name='data[Usertag][id][]'	>
														 </span>
													";
												}
											}
										 ?>
											<input id='friend_input' type='text' dir='rtl' size='30' >
											<input class="btn btn-small btn-success" type="button" id="add_tag" value="<?php echo __('add') ?>" />
									  </div>
								  </div>
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
	//===========================================================================================
	$('#add_tag').click(function(){
		 var tag = $('#friend_input').val();
		 span = $("<span>").text(tag),
			a = $("<a>").addClass("remove").attr({
				href: "javascript:",
				title: "Remove " + tag,
			}).text("x").appendTo(span);
		var hide_input= "<input type='hidden' name='new_tags[]' value='"+tag+"' >";
		//hide_input.appendTo(span);
		span.append(hide_input);
		//add tag to tag div
		 
		span.insertBefore("#friend_input");
		$('#friend_input').val('');
	});	
	//===========================================================================================
	/**
	* *****************************************************************************************************************
	*/	
	var tarr =[];
		var arr = [];
 
	$("#friend_input").autocomplete({
					//define callback to format results
				
					source: function(req, add){
					
						//pass request to server
						$.getJSON(_url+"users/tag_search?callback=?", req, function(data) {
							
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
						var hide_input= "<input type='hidden' name='data[Userrelatetag][usertag_id][]' value='"+id+"' >";
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
	/**
	* *****************************************************************************************************************
	*/	
	</script>