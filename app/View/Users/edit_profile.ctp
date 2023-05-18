<?php 
    $this->Gilace->autoCompileLess(ROOT.DS.APP_DIR.DS.'webroot'.DS.'less_'.$locale. DS .'setting.less', ROOT.DS.APP_DIR.DS.'webroot'.DS.'css'.DS.'setting_'.$locale.'.css');		
	echo $this->Html->css('setting_'.$locale);
    
	
	  
	echo $this->Html->script('jquery.form');
	$User_Info= $this->Session->read('User_Info');
  
  	  echo $this->Html->css('/css/ListSelector/autocomplete.css'); 
	  
	  //echo $this->Html->script('/js/ListSelector/admin-jquery-ui-custom.min');
	  
?>
<script type="text/javascript">
	_save_cover_image_success = "<?php echo __('save_cover_image_success');  ?>";
	_save_cover_image_notsuccess = "<?php echo __('save_cover_image_notsuccess');  ?>";
	_save_image_success = "<?php echo __('save_image_success');  ?>";
	_save_image_notsuccess = "<?php echo __('save_image_notsuccess');  ?>";
    _not_repeated_select_tag= "<?php echo __('not_repeated_select_tag') ?>";
_your_tag_is_maxtag= "<?php echo __('your_tag_is_maxtag') ?>";
</script>	 
 
    <div class="profileCover">
		<?php echo $this->element('cover_edit_profile'); ?> 
    </div>
		<div class="settingContent">
			<?php echo $this->element('edit_right_panel',array('active'=>'edit_profile')); ?> 
            <div class="col-sm-6 settingForms">
                <div id="generalSetting">                   
                    <!--<form class="myForm">
                       <div class="col-sm-12" >
                            <label >
                                <strong> <?php echo __('add_resume'); ?>:</strong>
                                <span class="description"><?php echo __('add_resume_hint'); ?></span>
                            </label>
                       </div>
					    
                       <div class="col-sm-6">
                            <button type="button" class="green myFormComponent" id="resume_upload_btn"
							onclick="popUp('<?php echo  __SITE_URL.'users/pdf_upload_window/'.$user['User']['pdf'] ?>')" >
                                <?php
									if($user['User']['pdf']=='') echo "<span class='text'>".__('upload_resume')."</span>";else
									  echo "<span class='text'>".__('edit_and_delete_resume')."</span>";
								?>
								
                                <span class="icon icon-plus"></span>
                            </button>
							
                        </div>
                        <div class="col-sm-12">
                            <span class="description">
							<?php echo __('resume_hint'); ?>.
                            </span>
                        </div>
                        <div class="clear"></div>
                    </form>-->
					<?php
						echo $this->Form->create('User', array('id'=>'ChangeProfile','name'=>'ChangeProfile','enctype'=>'multipart/form-data','class'=>'myForm')); ?>
					 
                        <div class="col-md-12">						
                            <div class="col-md-12">
                                <?php  echo $this->Session->flash(); ?>
                            </div>
							<div class="col-md-12">
							<label style="display: inline;margin-left: 5px"><?php echo __('iam'); ?></label>
				            
							<?php
								echo __('user');
								if($user['User']['user_type']==0) echo "<input style='width:20px' type='radio' value='0' class='myFormComponent' id='user_type' name='data[User][user_type]' checked>"; else echo "<input style='width:20px' type='radio' value='0' class='myFormComponent' id='user_type' name='data[User][user_type]' >";
								
								echo __('company');
								if($user['User']['user_type']==1) echo "<input style='width:20px' type='radio' value='1' checked='' class='myFormComponent' id='user_type' name='data[User][user_type]'>"; else echo "<input style='width:20px' type='radio' value='1' class='myFormComponent' id='user_type' name='data[User][user_type]'>";
							?>
							
							</div>
							<div class="col-md-12">
                                <label class="myFormComponent"><strong><?php echo __('full_name') ?>:</strong></label>
                            </div>
                            <div class="col-md-12">
								<?php echo $this->Form->input('name',array('label'=>'','type'=>'text','id'=>'name','value'=>$user['User']['name'],'placeholder'=>__('name_help'),'class'=>'myFormComponent')); ?>
                            </div>
                            <!--<div class="col-md-12">
                                <span class="description">
								<?php echo __('name_help') ?>
								</span>
                            </div>-->
                        </div>
                        <div class="col-md-12">						
							<div class="col-md-12">
                                <label class="myFormComponent"><strong><?php echo __('headline') ?>:</strong></label>
                            </div>
                            <div class="col-md-12">
								<?php echo $this->Form->input('headline',array('label'=>'','type'=>'text','id'=>'headline','value'=>$user['User']['headline'],'placeholder'=>__('headline_help'),'class'=>'myFormComponent','maxlenght'=>80)); ?>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-12">
                            <label class="myFormComponent"><strong><?php echo __('industry') ?></strong></label>
                            </div>
                            <div class="col-md-12">
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
                            </div>
                            <div class="col-md-12">
                                <!--<span class="description"><?php echo __('industry_help') ?></span>-->
                            </div>
                        </div>
                        
						<div class="col-md-12">
                            <div class="col-md-12">
                            <label class="myFormComponent"><strong><?php echo __('web_site') ?></strong></label>
                            </div>
                            <div class="col-md-12">
							<?php 
							if($user['User']['site']==''){
									$user['User']['site']='http://';
								}
								echo $this->Form->input('site',array('label'=>'','type'=>'text','id'=>'site','value'=>$user['User']['site'],'dir'=>'ltr','placeholder'=>__('web_site_help'),'class'=>'myFormComponent'));
							?>
                            </div>
                             
                        </div>
						<div class="col-md-12">
                            <div class="col-md-12">
                            <label class="myFormComponent"><strong><?php echo __('letter_of_introduction') ?></strong></label>
                            </div>
                            <div class="col-md-12">
							<?php 
							 echo $this->Form->textarea('details',array('label'=>'','type'=>'text','id'=>'details','value'=>$user['User']['details'],'class'=>'contableTXB','maxlength'=>200,'placeholder'=>__('char_detail1').' 200 '.__('char_detail2'),'class'=>'myFormComponent notTrans fixHeight'));
							 ?>
                            </div>
                            <div class="col-md-12">
                                <!--<span class="description"><?php echo __('char_detail1') ?> <span class="countedText" id="profilecounter">200</span>  <?php echo __('char_detail2') ?></span>-->
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-12">
                            <label class="myFormComponent"><strong><?php echo __('tags') ?> </strong></label>
                            </div>
                            <div class="col-md-12">
							  <div id="messageForm">
								  <div id="friends" class="ui-helper-clearfix"   >
								    <div>
		                            	<input type="text" class="myFormComponent notTrans" id='friend_input' placeholder="<?php echo __('tag_hint') ?>" style="width:79%" >
										
										<input class="green myFormComponent"   type="button" id="add_tag" value="<?php echo __('add') ?>" style="width:19%" />	
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
                            </div>
                             
                        </div>
						<h3><label><?php echo __('contact_info'); ?>:</label></h3>
						<div class="col-md-12">
                            <div class="col-md-12">
                            <label class="myFormComponent"><strong><?php echo __('country') ?></strong></label>
                            </div>
                            <div class="col-md-12">
                            	<select name="data[User][country_id]">
								 
								<?php
								   if($user['User']['country_id']==0){
									  	echo"<option value='0' selected >--------</option>";
									  } else echo"<option value='0'>--------</option>";
									  
									if(!empty($countries)){
										foreach($countries as $country){
										  if($user['User']['country_id']==$country['Country']['id']){
										  echo"<option  selected   value='".$country['Country']['id']."'>";
											echo $country['Country']['name'];
										  echo "</option>";	
										  }else
										  {
										  	echo"<option  value='".$country['Country']['id']."'>";
											echo $country['Country']['name'];
											echo "</option>";	 
										  }
										}
									}
								
								?>
								</select>
                            </div>
                            <div class="col-md-12">
                                <!--<span class="description"><?php echo __('country_help') ?></span>-->
                            </div>
                        </div>
						<div class="col-md-12">
                            <div class="col-md-12">
                            <label class="myFormComponent"><strong><?php echo __('location') ?></strong></label>
                            </div>
                            <div class="col-md-12">
							<?php echo $this->Form->input('location',array('label'=>'','type'=>'text','id'=>'location','value'=>$user['User']['location'],'placeholder'=>__('location_help'),'class'=>'myFormComponent')); ?>
                            </div>
                             
                        </div>
						<div class="col-md-12">
                            <div class="col-md-12">
                            <label class="myFormComponent"><strong><?php echo __('address') ?></strong></label>
                            </div>
                            <div class="col-md-12">
							<?php echo $this->Form->input('address',array('label'=>'','type'=>'text','id'=>'address','value'=>$user['User']['address'],'placeholder'=>__('address_help'),'class'=>'myFormComponent')); ?>
                            </div>
                             
                        </div>
						<div class="col-md-12">
                            <div class="col-md-12">
                            <label class="myFormComponent"><strong><?php echo __('telephon') ?></strong></label>
                            </div>
                            <div class="col-md-12">
							<?php echo $this->Form->input('telephon',array('label'=>'','type'=>'text','id'=>'telephon','value'=>$user['User']['telephon'],'placeholder'=>__('telephon_help'),'class'=>'myFormComponent')); ?>
                            </div>
                             
                        </div>
						<div class="col-md-12">
                            <div class="col-md-12">
                            <label class="myFormComponent"><strong><?php echo __('fax') ?></strong></label>
                            </div>
                            <div class="col-md-12">
							<?php echo $this->Form->input('fax',array('label'=>'','type'=>'text','id'=>'fax','value'=>$user['User']['fax'],'placeholder'=>__('fax_help'),'class'=>'myFormComponent')); ?>
                            </div>
                             
                        </div>
                        <div class="col-sm-6 col-sm-offset-6">
                            <button type="submit" class="green myFormComponent">
                                <span class="text"><?php echo __('updates') ?></span>
                                <span class="icon icon-left-open"></span>
                            </button>
                        </div>
                        <div class="clear"></div>
                    </form>
                </div>
                
            </div>
            <div class="col-sm-3">
                <?php echo $this->element('left_edit_profile'); ?> 
            </div>
            <div class="clear"></div>
        </div>
 
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
		//$(".delete_image").html('<img src="'+_url+'/img/loader/ui-anim_basic_16x16.gif" >');
		
		$.ajax({
			type: "POST",
			url: _url+'users/delete_image',
			data: '',
			success: function(response)
			{	 		
				$('#ajax_result').html(response);
				//$(".delete_image").html('');
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
		//$(".delete_cover_image").html('<img src="'+_url+'/img/loader/ui-anim_basic_16x16.gif" >');
		
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
	
	/**
	* /==============================================================================================
	*/
	
</script>
 