
<?php echo $this->Html->css('edit_profile_'.$locale); 
	  echo $this->Html->script('profile');
  
  	  echo $this->Html->css('/css/ListSelector/autocomplete.css'); 
	  echo $this->Html->css('/css/ListSelector/ui-lightness/jquery-ui-1.8.custom'); 
	  //echo $this->Html->script('/js/ListSelector/admin-jquery-ui-custom.min');
	  echo $this->Html->script('/js/ListSelector/tagcount-jquery-ui-custom.min');
?>
<script type="text/javascript">
	_save_cover_image_success = "<?php echo __('save_cover_image_success');  ?>";
	_save_cover_image_notsuccess = "<?php echo __('save_cover_image_notsuccess');  ?>";
	_save_image_success = "<?php echo __('save_image_success');  ?>";
	_save_image_notsuccess = "<?php echo __('save_image_notsuccess');  ?>";
    _not_repeated_select_tag= "<?php echo __('not_repeated_select_tag') ?>";
_your_tag_is_maxtag= "<?php echo __('your_tag_is_maxtag') ?>";
</script>	 
 <?php echo $this->element('edit_right_panel',array('active'=>'edit_profile')); ?>        

	  <section id="mainPanel">
            <div class="mainBox">
			<h1><div class="editProfile_icon"></div><?php echo __('edit_all_info') ?></h1>
                <div class="seperator_Hor"></div>
                <span> <?php echo __('edit_cover') ?></span>
                <span class="help" style="margin-bottom: 10px;">  <?php echo __('cover_size') ?></span>
				 
				<?php echo $this->Form->create('User', array('id'=>'ChangeCoverImage','name'=>'ChangeCoverImage','enctype'=>'multipart/form-data','action'=>'/edit_cover_image','class'=>'cover_image')); ?>
                   
					<?php  
						if(fileExistsInPath(__USER_IMAGE_PATH.$user['User']['cover_image'] ) && $user['User']['cover_image']!='') {
							echo $this->Html->image('/'.__USER_IMAGE_PATH.$user['User']['cover_image'],array('id'=>'cover_image_img'));
							echo "<div class='delete_cover_image'> ".__('delete')." </div>";
						}
						else{
							if($user['User']['sex']==0)
							  	   echo $this->Html->image('cover_women.jpg',array('id'=>'cover_image_img')); 
							  else echo $this->Html->image('cover_men.jpg',array('id'=>'cover_image_img'));
						}
						
					 ?>
                    
					
					<input name="data[User][cover_image]" type="file" id="cover_image" />
					<div id="cover_image_loading" style="float: right;"></div>
                    <input class="btn ok" type="submit" value="<?php echo __('updates') ?>"  />
					
                            
                </form>
				
				
                <div class="seperator_Hor"></div>
                <span> <?php echo __('edit_profile_image') ?></span>
				<span class="help" style="margin-bottom: 10px;"> <?php echo __('profile_image_size') ?></span>
				
                 
				<?php echo $this->Form->create('User', array('id'=>'ChangeImage','name'=>'ChangeImage','enctype'=>'multipart/form-data','action'=>'/edit_image','class'=>'profile_image')); ?>
                    <?php  
						  
						if(fileExistsInPath(__USER_IMAGE_PATH.$user['User']['image'] ) && $user['User']['image']!='' ) 
						{
							echo $this->Html->image('/'.__USER_IMAGE_PATH.$user['User']['image'],array('id'=>'image_img'));
							echo "<div class='delete_image'> ".__('delete')." </div>";
						}
						else{
							if($user['User']['sex']==0)
							  echo $this->Html->image('profile_women.png',array('id'=>'image_img')); 
							elseif($user['User']['sex']==1) echo $this->Html->image('profile_men.png',array('id'=>'image_img'));
							elseif($user['User']['sex']==2) echo $this->Html->image('company.png',array('id'=>'image_img'));
						}
						
					 ?>
                     
				 
					<input name="data[User][image]" type="file" id="image" />
					<div id="image_loading" style="float: right;"></div>
                    <input type="submit" class="btn ok" value="<?php echo __('updates') ?>" />
					
                </form>
            
            
				<?php echo $this->Form->create('User', array('id'=>'ChangeProfile','name'=>'ChangeProfile','enctype'=>'multipart/form-data','class'=>'edit_profile')); ?>
                	<table border="0" cellpadding="2">
                       <tr>
                            <td>
								<?php  echo $this->Session->flash(); ?>
							</td>
                        </tr>
                        <tr>
                            <td><label> <?php echo __('resume_file') ?></label>
							    <input name="data[User][pdf]" type="file" id="pdf" />
								<input name="data[User][old_pdf]" id="old_pdf" type="hidden" value="<?php echo $user['User']['pdf'] ?>" />
								<?php
								  if(!empty($user['User']['pdf'])) {
                                        echo $this->Html->image('/img/icons/pdf.png',array('style'=>'height:100px;float:left','id'=>'pdf_image'));
										echo "<div class='delete_pdf'> ".__('delete')." </div>";
                                  }
								  		
								?>
							</td>
                            <td><span class="help"> <?php echo __('pdf_help') ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td><label> <?php echo __('full_name') ?></label>
							    <?php echo $this->Form->input('name',array('label'=>'','type'=>'text','id'=>'name','value'=>$user['User']['name'])); ?>
							</td>
                            <td><span class="help"> <?php echo __('name_help') ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td><label> <?php echo __('industry') ?></label>
								<select name="data[User][industry_id]">
								 
								<?php
								   if($user['User']['industry_id']==0){
									  	echo"<option value='0' selected >--------</option>";
									  } else echo"<option value='0'>--------</option>";
									  
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
							</td>
                            <td><span class="help"> <?php echo __('industry_help') ?></span></td>
                        </tr>
						
						
						<!--
						<tr>
                            <td><label> <?php echo __('job_status') ?></label>
							    <select name="data[User][job_status]" id="job_status">
									<?php 
										if($user['User']['job_status']==0)
										    echo "<option value='0' selected>---- ". __('job_status') ." ----</option>";
											else echo"<option value='0'>---- ". __('job_status') ." ----</option>";
										if($user['User']['job_status']==1)
										    echo "<option value='1' selected>". __('student') ."</option>";
											else echo"<option value='1'>". __('student') ."</option>";	
										/*if($user['User']['job_status']==2)
										    echo "<option value='2' selected>". __('hvyay_work') ."</option>";
											else echo"<option value='2'>". __('hvyay_work') ."</option>";	*/	
										if($user['User']['job_status']==3 || $user['User']['job_status']==2)
										    echo "<option value='3' selected>". __('employed') ."</option>";
											else echo"<option value='3'>". __('employed') ."</option>";		
									?>		
								 </select> 
							</td>
                            <td></td>
                        </tr>
						
						<?php if($user['User']['job_status']==1 || $user['User']['job_status']==2)
							echo "<tr id='degree_tr' >";
							else echo "<tr id='degree_tr' style='display:none'>";
						 ?>
						 
                            <td><label> <?php echo __('degree') ?></label>
							     <select name="data[User][degree]" id="degree" >
								 	<?php 
										if($user['User']['degree']==0)
										    echo "<option value='0' selected>---- ". __('degree') ." ----</option>";
											else echo"<option value='0'>---- ". __('degree') ." ----</option>";
										if($user['User']['degree']==1)
										    echo "<option value='1' selected>". __('phd') ."</option>";
											else echo"<option value='1'>". __('phd') ."</option>";
										if($user['User']['degree']==2)
										    echo "<option value='2' selected>". __('ma') ."</option>";
											else echo"<option value='2'>". __('ma') ."</option>";
										if($user['User']['degree']==3)
										    echo "<option value='3' selected>". __('bachelor') ."</option>";
											else echo"<option value='3'>". __('bachelor') ."</option>";
										if($user['User']['degree']==4)
										    echo "<option value='4' selected>". __('diploma') ."</option>";
											else echo"<option value='4'>". __('diploma') ."</option>";
										if($user['User']['degree']==5)
										    echo "<option value='5' selected>". __('diplom') ."</option>";
											else echo"<option value='5'>". __('diplom') ."</option>";			
									?>
								 </select>
							</td>
                            <td></td>
                        </tr>
						<?php if($user['User']['job_status']==1 || $user['User']['job_status']==2)
							echo "<tr id='university_name_tr' >";
							else echo "<tr id='university_name_tr' style='display:none'>";
						 ?>
						
                            <td><label> <?php echo __('university_name') ?></label>
							    <?php echo $this->Form->input('university_name',array('label'=>'','placeholder'=>__('university_name'),'id'=>'university_name','value'=>$user['User']['location'])); ?> 
							</td>
                            <td></td>
                        </tr>
						
						<?php if($user['User']['job_status']==3)
							echo "<tr id='job_title_tr' >";
							else echo "<tr id='job_title_tr' style='display:none'>";
						 ?>
                            <td><label> <?php echo __('job_title') ?></label>
							     <?php echo $this->Form->input('job_title',array('label'=>'','placeholder'=>__('job_title'),'id'=>'job_title','value'=>$user['User']['job_title'])); ?>
							</td>
                            <td></td>
                        </tr>
						
						<?php if($user['User']['job_status']==3)
							echo "<tr id='company_name_tr' >";
							else echo "<tr id='company_name_tr' style='display:none'>";
						 ?>
                            <td><label> <?php echo __('company_name') ?></label>
							     <?php echo $this->Form->input('company_name',array('label'=>'','placeholder'=>__('company_name'),'id'=>'company_name','value'=>$user['User']['company_name'])); ?>
							</td>
                            <td></td>
                        </tr> -->
						
                        <tr>
                            <td><label> <?php echo __('location') ?></label>
							<?php echo $this->Form->input('location',array('label'=>'','type'=>'text','id'=>'location','value'=>$user['User']['location'])); ?>
			
							</td>
                            <td><span class="help"> <?php echo __('location_help') ?></span></td>
                        </tr>
                        <tr>
                            <td><label> <?php echo __('web_site') ?></label>
								<?php 
								if($user['User']['site']==''){
									$user['User']['site']='http://';
								}
								echo $this->Form->input('site',array('label'=>'','type'=>'text','id'=>'site','value'=>$user['User']['site'],'dir'=>'ltr')); 
								?>
							</td>
                            <td><span class="help"><?php echo __('web_site_help') ?></span></td>
                        </tr>
                        <tr>
                            <td><label> <?php echo __('letter_of_introduction') ?></label> 
							<?php /*echo $this->Form->textarea('details',array('label'=>'','type'=>'text','id'=>'details','value'=>$user['User']['details'],'class'=>'contableTXB','maxlength'=>200));*/ ?>
							  <div id="messageForm">
								  <div id="friends" class="ui-helper-clearfix"   >
								    <div>
										<input id='friend_input' type='text' dir='rtl' size='30' >
										<input class="btn btn-small btn-success" type="button" id="add_tag" value="<?php echo __('add') ?>" />
									</div>
									<div id="tag_place">
									<?php
								    if(!empty($tags)){
										foreach($tags as $tag){
											echo"<span>".$tag['Usertag']['title']." <a class='remove' href='javascript:' title='Remove کوشا' id='".$tag['Usertag']['id']."'>x</a><input type='hidden' title='".$tag['Usertag']['title']."' id='users_profile_tag' value='".$tag['Usertag']['id']."' name='data[Userrelatetag][usertag_id][]'></span>";
										}
									}
								  	
								  ?>
									</div>																  
								  </div>

							   </div>
							</td>
                            <td><span class="help"><?php echo __('char_detail1') ?> <span class="countedText" id="profilecounter">200</span>  <?php echo __('char_detail2') ?>.</span>
							
							</td>
							
							
							
							
                        </tr>
						 
                        <tr>
                            <td><input class="btn ok" name="" type="submit" value="<?php echo __('updates') ?>" /></td>
                        </tr>
                    </table>

                </form>
            </div>
        </section>
 
<script>

//===========================================================================================================	
	var tarr =[];
	var arr = [];
	
	$('#friends input#users_profile_tag').each(function() {
       tarr.push($(this).attr('title'));
    });
 
	$("#friend_input").autocomplete({
					//define callback to format results
				
					source: function(req, add){
					
						//pass request to server
						$.getJSON(_url+"usertags/tag_search?callback=?", req, function(data) {
							
							//create array for response objects
							var suggestions = [];
							//var suggestions1 = [];
							//process response
							$.each(data, function(i, val){	
							 						
								suggestions.push({'title':val.title,'id':val.id,'tag_count':val.tag_count});
								 
								//suggestions.push(val.name+'<input type=hidden id='+val.user_id+'>');
							 
							});
							
							//pass array to callback
							
							add(suggestions);
							 
							 
						});
					},
					select: function(e, ui) {
						
						//create formatted tag
						var friend = ui.item.value;
						 
                         
                         if(tarr.length>10){                          
                            show_warning_msg(_your_tag_is_maxtag);
							return false;
                        }
                        
						for (var i=0;i<=tarr.length;i++)
						{
                                if(tarr[i]==friend){
								show_warning_msg(_not_repeated_select_tag);
                                //tarr.pop();
								return false;
							 } 
						}
						tarr.push(friend); 
                         
                         
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
						 
						span.insertBefore("#tag_place");
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
				
				Array.prototype.remove = function(x) { 
                    var i;
                    for(i in this){
                        if(this[i].toString() == x.toString()){
                            this.splice(i,1)
                        }
                    }
                }
				
				//add live handler for clicks on remove links
				$(".remove", document.getElementById("friends")).live("click", function(){
				
					//remove current friend
					tarr.remove($(this).attr('title'));
					$(this).parent().remove();
					
					//correct 'to' field position
					if($("#friends span").length === 0) {
						$("#friend_input").css("top", 0);
					}				
				});	
//===========================================================================================================	
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
		 
		span.insertBefore("#tag_place");
		$('#friend_input').val('');
	});	
//===============================================================================================================

_are_you_sure_for_delete =  "<?php echo __('are_you_sure_for_delete') ?>";
	//character counter
	$("#details").keydown(function(e) {
		var numb = 200 - $(this).val().length;
        $("#profilecounter").text(numb);
    });
	
	$('#details').keypress(function(e){
		if(e.which===13){
			return false;
		}
	})
	
  
    $('#job_status').change(function(e){
		var select_id=$(this).val();
		//alert(select_id);
		if(select_id==0){
			$('.register').height(325);
			$('#degree_tr').fadeOut('slow');
			$('#university_name_tr').fadeOut('slow'); 
			$('#job_title_tr').fadeOut('slow');
			$('#company_name_tr').fadeOut('slow'); 
		}
		if(select_id==1 || select_id==2){
			$('.register').height(400);
			$('#job_title_tr').fadeOut('slow');
			$('#company_name_tr').fadeOut('slow'); 
			$('#degree_tr').fadeIn('slow');
			$('#university_name_tr').fadeIn('slow');  
		}
		
		if(select_id==3){
			$('#degree_tr').fadeOut('slow');
			$('#university_name_tr').fadeOut('slow'); 
			$('#job_title_tr').fadeIn('slow');
			$('#company_name_tr').fadeIn('slow');  
			$('.register').height(400);
		}
		
		
	});
	/**
	* /==============================================================================================
	*/
	$('.delete_image').click(function(){ delete_image_alarm(); });
	
	function delete_image_alarm()
	{
		$.Zebra_Dialog(_are_you_sure_for_delete, {
				    'type':     'warning',
				    'title':    _warning,
					'modal': true ,
				    'buttons':  [
				                    {caption: _yes, callback: function() {delete_image();}},
									{caption: _no, callback: function() { }}
				                ]
		  });	
	}
	
	function delete_image()
	{
		$(".delete_image").html('<img src="'+_url+'/img/loader/ui-anim_basic_16x16.gif" >');
		
		$.ajax({
			type: "POST",
			url: _url+'users/delete_image',
			data: '',
			success: function(response)
			{	 		
				$('#ajax_result').html(response);
			}
		
		  });
	}
	/**
	* /==============================================================================================
	*/
	$('.delete_cover_image').click(function(){ delete_cover_image_alarm(); });
	
	function delete_cover_image_alarm()
	{
		$.Zebra_Dialog(_are_you_sure_for_delete, {
				    'type':     'warning',
				    'title':    _warning,
					'modal': true ,
				    'buttons':  [
				                    {caption: _yes, callback: function() {delete_cover_image();}},
									{caption: _no, callback: function() { }}
				                ]
		  });	
	}
	
	function delete_cover_image()
	{
		$(".delete_cover_image").html('<img src="'+_url+'/img/loader/ui-anim_basic_16x16.gif" >');
		
		$.ajax({
			type: "POST",
			url: _url+'users/delete_cover_image',
			data: '',
			success: function(response)
			{	 		
				$('#ajax_result').html(response);
			}
		
		  });
	}
	/**
	* /==============================================================================================
	*/
	$('.delete_pdf').click(function(){ delete_pdf_alarm(); });
	
	function delete_pdf_alarm()
	{
		$.Zebra_Dialog(_are_you_sure_for_delete, {
				    'type':     'warning',
				    'title':    _warning,
					'modal': true ,
				    'buttons':  [
				                    {caption: _yes, callback: function() {delete_pdf();}},
									{caption: _no, callback: function() { }}
				                ]
		  });	
	}
	
	function delete_pdf()
	{
		$(".delete_pdf").html('<img src="'+_url+'/img/loader/ui-anim_basic_16x16.gif" >');
		
		$.ajax({
			type: "POST",
			url: _url+'users/delete_pdf',
			data: 'old_pdf='+'<?php echo $user['User']['pdf'] ?>',
			success: function(response)
			{	 		
				$('#ajax_result').html(response);
			}
		
		  });
	}
	/**
	* /==============================================================================================
	*/
	
</script>
 