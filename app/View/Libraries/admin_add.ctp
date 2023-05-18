
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
				<div>
					  <?php echo $this->Session->flash(); ?>
				</div>
				<?php } ?>
			</div>
			<?php echo $this->Form->create('Library', array('id'=>'AddFrom','name'=>'AddFrom','enctype'=>'multipart/form-data')); ?>
			<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-edit"></i><?php echo __('add_library') ?></h2>
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
								  <input class="input-xlarge focused" style="width:500px" value="" name="data[Library][title]" id="focusedInput" type="text" >
								</div>
                                
                                <div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('year') ?> :</label> 
								  <input class="input-xlarge focused" style="width:100px" value="" name="data[Library][year]" id="focusedInput" type="text"  > 
								</div>	
                                
                                
                                <div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('author') ?> :</label>
								  <div id="messageForm">
									  <div id="friends" class="ui-helper-clearfix"   >
											<input id='author_input' type='text' dir='rtl' size='30' >
											<input class="btn btn-small btn-success" type="button" id="add_author" value="<?php echo __('add') ?>" />											
									  </div>
								  </div>
								</div>
                                
                                <div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('tag') ?> :</label>
								  <div id="messageForm">
									  <div id="friends" class="ui-helper-clearfix"   >
											<input id='friend_input' type='text' dir='rtl' size='30' >
											<input class="btn btn-small btn-success" type="button" id="add_tag" value="<?php echo __('add') ?>" />
									  </div>
								  </div>
								</div>
                                
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('pdf') ?> :</label>
								  <input type="file" name="data[Library][pdf]" id="image_no_01" maxlength="255"  />
								</div>	
								
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('link') ?> :</label> 
								  <input class="input-xlarge focused" style="width:400px" value="" name="data[Library][link]" id="focusedInput" type="text"  > 
								</div>	
                                
                                <div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('arrangment') ?> :</label> 
								  <input class="input-xlarge focused" style="width:30px" value="0" name="data[Library][arrangment]" id="focusedInput" type="text"  > 
								</div>	
                                
                                <div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('status') ?> :</label>
								  <select id="selectError3" style="width:auto" name="data[Library][status]">
									<option value="1"><?php echo __('active') ?></option>
									<option value='0'><?php echo __('inactive') ?></option>
								  </select>
								</div>	
								
								
							  </div>
							  
							  <div class="form-actions">
								<button type="submit" class="btn btn-primary"><?php echo __('save_change') ?></button>
								<button type="reset" class="btn"><?php echo __('cancel') ?></button>
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
	
	$('#add_author').click(function(){
		 var author = $('#author_input').val();
		 span = $("<span>").text(author),
			a = $("<a>").addClass("remove").attr({
				href: "javascript:",
				title: "Remove " + author,
			}).text("x").appendTo(span);
		var hide_input= "<input type='hidden' name='new_authors[]' value='"+author+"' >";
		//hide_input.appendTo(span);
		span.append(hide_input);
		//add author to author div
		 
		span.insertBefore("#author_input");
		$('#author_input').val('');
	});
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
		
	/**
	* *****************************************************************************************************************
	*/	
	var tarr =[];
		var arr = [];
 
	$("#friend_input").autocomplete({
					//define callback to format results
				
					source: function(req, add){
					
						//pass request to server
						$.getJSON(_url+"libraries/tag_search?callback=?", req, function(data) {
							
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
						var hide_input= "<input type='hidden' name='data[Libraryrelatetag][library_tag_id][]' value='"+id+"' >";
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
					//$("#friend_input").focus();
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
    
    
    $("#author_input").autocomplete({
					//define callback to format results
				
					source: function(req, add){
					
						//pass request to server
						$.getJSON(_url+"libraries/author_search?callback=?", req, function(data) {
							
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
						var author = ui.item.value;
						 
						var user_count= $("#authors span").length+1; 
						var id= ui.item.id;
							span = $("<span>").text(author),
							a = $("<a>").addClass("remove").attr({
								href: "javascript:",
								title: "Remove " + author,
								id   : id,
							}).text("x").appendTo(span);
						var hide_input= "<input type='hidden' name='data[Libraryrelateauthor][library_author_id][]' value='"+id+"' >";
						//hide_input.appendTo(span);
						span.append(hide_input);
						//add author to author div
						 
						span.insertBefore("#author_input");
					},
					
					//define select handler
					change: function() {
						
						//prevent 'to' field being updated and correct position
						$("#author_input").val("").css("top", 2);
					}
				});
				
				//add click handler to user_ids div
				$("#authors").click(function(){
					
					//focus 'to' field
					//$("#author_input").focus();
				});
				
				//add live handler for clicks on remove links
				$(".remove", document.getElementById("authors")).live("click", function(){
				
					//remove current author
					$(this).parent().remove();
					
					//correct 'to' field position
					if($("#authors span").length === 0) {
						$("#author_input").css("top", 0);
					}				
				});	
	/**
	* *****************************************************************************************************************
	*/	
    
    
	</script>
    
    