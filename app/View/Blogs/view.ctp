<?php
	$this->Gilace->autoCompileLess(ROOT.DS.APP_DIR.DS.'webroot'.DS.'less_'.$locale. DS .'index.less', ROOT.DS.APP_DIR.DS.'webroot'.DS.'css'.DS.'index_'.$locale.'.css');
		
	echo $this->Html->css('index_'.$locale);
    
    echo $this->Html->script('jquery.cropit_profile');
	
	echo $this->Html->script('jquery.form');
	$User_Info= $this->Session->read('User_Info');
	
	if(fileExistsInPath(__USER_BLOG_PATH.$blog['Blog']['image'] ) && $blog['Blog']['image']!='' ) {
	$backimg =__SITE_URL.__USER_BLOG_PATH.$blog['Blog']['image']; 
	}
	else{
		  $backimg = '' ; 
		}
	
?>
<?php /*pr($blog);*/ ?>

<section id="homePage">
	 
    <div class="col-md-3 col-md-offset-0 col-sm-3">
        
		<a href="<?php echo __SITE_URL ?>blogs/add_blog" style="text-decoration: none;">
			<div class="btn blue"  style="font-size: 24px;height: 50px;margin-top: 5px;">
	            <span class="textFollow"><?php echo __('add_new_blog') ?></span>
	        </div>
		</a>
		<?php echo $this->element('right_blog',array('user_id'=>$blog['User']['id'],'blog_id'=>$blog['Blog']['id'])); ?>				
    </div>
    <div class="col-md-6 col-sm-6" >
        
	   <div class="post" style="background: #ffffff;">
	   		 
				<div class="image-editor" style="margin-top: 7px;margin-bottom: 115px;">  
			    	 <div class="cropit-image-preview-container">
			    		<div class="cropit-image-preview"  >		
							<div class="blogimagePlace">
			                    <div class="ax">
									<?php
										echo $this->Gilace->user_image($blog['User']['image'],$blog['User']['sex'],$blog['User']['user_name'],''); 
									?>					
								</div>
			                </div>
							<div class="blogUsernamePlace">
			                    <span><?php echo $blog['User']['name']; ?></span>
								<a href="<?php echo __SITE_URL.$blog['User']['user_name']; ?>" class="atSign">@<?php echo $blog['User']['user_name']; ?></a>
			                </div>
							<!--<div class="blogHeadlinePlace">
			                    <?php echo $blog['User']['headline']; ?>					
			                </div>-->
							<?php if(!empty($User_Info)){ ?>
							<div class="blogFollowPlace">
							<?php if($blog['User']['id']!=$User_Info['id'])
								{ ?>
								<?php if($is_follow>0){ ?>
				                    <div id="extraBtn_<?php echo $blog['User']['id']; ?>" class="extraBtn">
										<div id="follow_btn_<?php echo $blog['User']['id']; ?>" onclick="blog_not_follow(<?php echo $blog['User']['id']; ?>);" class="btn green">
						                    <span class="textFollow"><?php echo __('not_follow'); ?></span>
						                </div>
									</div>
								<?php } ?>
								
								<?php if($is_follow==0){ ?>
				                    <div id="extraBtn_<?php echo $blog['User']['id']; ?>" class="extraBtn">
										<div id="follow_btn_<?php echo $blog['User']['id']; ?>" onclick="blog_follow(<?php echo $blog['User']['id']; ?>);" class="btn blue">
						                    <span class="textFollow"><?php echo __('follow'); ?></span>
						                </div>
									</div>
								<?php } ?>
							<?php }else{ ?>
							
								<a href="<?php echo __SITE_URL.'blogs/add_blog/'.$blog['Blog']['id']; ?>" 
									 
								>
								<div id="extraBtn_<?php echo $blog['User']['id']; ?>" class="extraBtn">
										<div  class="btn green">
						                    <span class="textFollow"><?php echo __('edit_blog'); ?></span>
						                </div>
								</div>
								</a>
							<?php } ?>
			                </div>
							<?php } ?>
						</div>
			  		</div>
    			</div>
	   		<div class="clear"></div>	
            <article class="embedEdit">
				               
                <div class="textPlace dataArticle">
                    <div class="fontSize17" style="text-align: center"><span><?php echo $blog['Blog']['title']; ?> </span></div>
                    <a class="date" style="text-align: center">
						<?php
							if($locale =='per')
                                echo $this->Gilace->show_persian_date("Y/m/d - H:i",strtotime($blog['Blog']['created']));  
                            if($locale =='eng')
                                echo date("Y/m/d - H:i",strtotime($blog['Blog']['created'])); 
						?>
					</a>
                     <?php echo $this->Gilace->convert_character_editor($blog['Blog']['body']); ?>
                     <div class="Horizontal_bar"></div>
                     <ul class="socialActivities">
                        <li style="text-align: center"> 
						 <span id="favorite_body_<?php echo $blog['Blog']['id'] ?>">
						<?php
							if(!empty($blog['0']['me_favorite'])){
								if($blog['0']['me_favorite']>0){
							echo "<span id='favorite_btn_".$blog['Blog']['id']."' onclick='blog_unfavorite(".$blog['Blog']['id'].")'>".__('notfavorite')."</span>"; 
							}
							else{
							echo "<span  id='favorite_btn_".$blog['Blog']['id']."' onclick='blog_favorite(".$blog['Blog']['id'].")' >".__('favorite')."</span>"; }
							}
							
						?>
						 </span>
						 <br>	
						 <?php if(!empty($blog['0']['me_favorite'])){ ?>
						<span id="favorite_counter"><?php echo $blog['Blog']['favoritecount']; ?></span>
						<?php } ?>
							
						</li>
                        <li style="text-align: center"><?php echo __('replay'); ?> <br><span id="replay_counter"><?php echo $blog['0']['replay_count']; ?></span></li>
                        <li style="text-align: center"><?php echo __('share'); ?> <br><?php echo $blog['0']['share_count']; ?></li>
                        <li style="text-align: center"><?php echo __('view'); ?> <br><?php echo $blog['Blog']['num_viewed']; ?></li>
						
						
						
                     </ul>
					 <ul class="socialIcons" >
                        <li style="text-align: center"> 
							<a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo __SITE_URL ?>blogs/view/<?php echo $blog_id; ?>">
							<img src="<?php echo __SITE_URL.'img/icons/Facebook.png' ?>" />
							</a>
						</li>
						<li style="text-align: center"> 
							<a target="_blank" href="https://twitter.com/home?status=<?php echo __SITE_URL ?>blogs/view/<?php echo $blog_id; ?>">
								<img src="<?php echo __SITE_URL.'img/icons/twitter.png' ?>" />
							</a>
						</li>
						<li style="text-align: center"> 
							<a target="_blank" href="https://plus.google.com/share?url=<?php echo __SITE_URL ?>blogs/view/<?php echo $blog_id; ?>">
								<img src="<?php echo __SITE_URL.'img/icons/Google +.png' ?>" />
							</a>
						</li>
						<li style="text-align: center"> 
							<a target="_blank" href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo __SITE_URL ?>blogs/view/<?php echo $blog_id; ?>&title=<?php echo $title; ?>">
								<img src="<?php echo __SITE_URL.'img/icons/linkedIn.png' ?>" />
							</a>
						</li>
					</ul>	
				  <?php echo $this->Form->create('Post', array('id'=>'AddPostForm','name'=>'AddPostForm','enctype'=>'multipart/form-data','action'=>'/add_post','class'=>'myForm')); ?>	
				  <?php if(!empty($User_Info)){ ?>
				  <div class="col-sm-12">
	                <div class="textBoxCounter">
	                    <textarea maxlength="500" rows="5" class="myFormComponent notTrans fixHeight" placeholder="<?php echo __('type_a_text_to_blog'); ?>" id="comment_input" name="data[Post][newpost_input]"></textarea>
	                    <span class="counter" id="newpost_charnumber">500</span>
	                </div>
						<button class="myFormComponent green" id="save_comment_btn" type="button" role="button">
                            <span class="text"><?php echo __('send_comment'); ?></span>
                            <span class="icon icon-left-open"></span>
                        </button>
					
	              </div>
				  <?php } ?>
				  
				  
				  </form>	
				  
				  
					
               </div>
			     
            </article>
       </div>
	   	   <div class="clear"></div>
		   
		   
   		
			<div id="blog_answer_body">
			</div>				
   			
		   
	             
    </div>
	
 <aside class="col-md-3 col-md-offset-0 col-sm-3" style="background-color: #ffffff">
 	<?php echo $this->element('left_blog'); ?>	
 </aside>
</section>

<script>

textBoxCounter(500);

$('.image-editor').cropit({
          exportZoom: 0.1,      
          imageState: {
            src: '<?php echo $backimg; ?>' ,
			zoom:'<?php echo $blog["Blog"]["image_zoom"]; ?>',
			offset:{x:'<?php echo $blog["Blog"]["image_x"]; ?>',y:'<?php echo $blog["Blog"]["image_y"]; ?>'} 
          }
        });

function blog_follow(id)
 {
 	$.ajax({
			type:"POST",
			url:_url+'follows/follow',
			data:'id='+id,
			dataType: "json",
			success:function(response){
				// hide table row on success
				if(response.success == true) {
					
					$('#follow_btn_'+id).remove();
						var not_follow_btn=$("<div onclick='blog_not_follow("+id+");' id='follow_btn_"+id+"' class='btn green'><span class='textFollow'>"+_not_follow+"</span></div>");
					    $("#extraBtn_"+id).append(not_follow_btn);
												
						send_privacy_email(id,'onfollow','');
						
					if( response.message ) {
						//showmsg(response.message);
						
					} 
					
				}
				else 
				 {
					if( response.message ) {
						show_error_msg(response.message);
					}  
				 }

			}
		});
 }
 
 function blog_not_follow(id)
 {
 	$.ajax({
			type:"POST",
			url:_url+'follows/not_follow',
			data:'id='+id,
			dataType: "json",
			success:function(response){
				// hide table row on success
				if(response.success == true) {
					$('#follow_btn_'+id).remove();
						var follow_btn=$("<div onclick='blog_follow("+id+");' id='follow_btn_"+id+"' class='btn blue'><span class='textFollow'>"+_follow+"</span></div>");
						
					    $("#extraBtn_"+id).append(follow_btn);
						 
					if( response.message ) {
						//showmsg(response.message);
						
					} 
					
				}
				else 
				 {
					if( response.message ) {
						show_error_msg(response.message);
					}  
				 }

			}
		});
 }


function refresh_blog_comment(){
	$("#blog_answer_body").html('<img src="'+_url+'/img/loader/big_loader.gif" >');
	$.ajax({
			type:"POST",
			url:_url+'blogcomments/refresh_comment/'+'<?php echo $blog["Blog"]["id"] ?>',
			data:'first=',
	
			success:function(response){
				$("#blog_answer_body").empty(); 				
				$('#blog_answer_body').append(response);
			}
		}) ;	
 
} 
refresh_blog_comment();


$('#save_comment_btn').click(function(){
	add_comment();
});

function add_comment()
{  
	$('body').prepend("<div id='modal'></div>");
	$("#modal").html('<div class="loadingPage"><div class="loaderCycle"></div><span>'+_loading+'</span></div>' );
	
	var commant = $('#comment_input').val();
	
	$.ajax({
		type: "POST",
		url: _url+'blogcomments/add_comment/',
		data: 'commant='+commant+'&blog_id='+'<?php echo $blog["Blog"]["id"] ?>',
		dataType: "json",
		success: function(response)
		{	 
			 if(response.success == true) {			
				if( response.message ) {
					show_success_msg(response.message);	
					refresh_blog_comment();
					$('#comment_input').val("");	
					$('#replay_counter').text(parseInt($('#replay_counter').text())+1);
				} 
			}
			else 
			 {
				if( response.message ) {
					show_error_msg(response.message);
				}  
			 }
			 remove_modal(); 		 
		}	
	  });
}


function blog_favorite(blog_id){
		//$("#favorite_loading_"+blog_id).html('<img src="'+_url+'/img/loader/ui-anim_basic_16x16.gif" >');
		$('#favorite_btn_'+blog_id).text(_notfavorite); 
        $('#favorite_btn_'+blog_id).removeAttr('onclick'); 
		$.ajax({
				type:"POST",
				url:_url+'favoriteblogs/favorite',
				data:'blog_id='+blog_id,
				dataType: "json",
				success:function(response){
					// on success
					if(response.success == true) {
						$('#favorite_btn_'+blog_id).remove();					
						var favorite_btn=$("<span  onclick='blog_unfavorite("+blog_id+")' id='favorite_btn_"+blog_id+"' >"+_notfavorite+"</span>");					
						$("#favorite_body_"+blog_id).append(favorite_btn);
						$('#favorite_counter').text(parseInt($('#favorite_counter').text())+1);
					}
					else 
					 {
						if( response.message ) {
							show_error_msg(response.message);
						}  
						
						$('#favorite_btn_'+blog_id).remove();		
						var favorite_btn=$("<span  onclick='blog_favorite("+blog_id+")' id='favorite_btn_"+blog_id+"' >"+_favorite+"</span>");
						$("#favorite_body_"+blog_id).append(favorite_btn);
						$('#favorite_counter').text(parseInt($('#favorite_counter').text())-1);
					 }
					//$("#favorite_loading_"+blog_id).empty();	
			 		 
				}
			});
	};
 
 /* unfavorite */
 function blog_unfavorite(blog_id){
		//$("#favorite_loading_"+blog_id).html('<img src="'+_url+'/img/loader/ui-anim_basic_16x16.gif" >');	
		$('#favorite_btn_'+blog_id).text(_favorite); 		
		$('#favorite_btn_'+blog_id).removeAttr('onclick'); 		
		$.ajax({
				type:"POST",
				url:_url+'favoriteblogs/unfavorite',
				data:'blog_id='+blog_id,
				dataType: "json",
				success:function(response){
					// hide table row on success
					if(response.success == true) {
						$('#favorite_btn_'+blog_id).remove();		
						var favorite_btn=$("<span  onclick='blog_favorite("+blog_id+")' id='favorite_btn_"+blog_id+"' >"+_favorite+"</span>");
						$("#favorite_body_"+blog_id).append(favorite_btn);
						$('#favorite_counter').text(parseInt($('#favorite_counter').text())-1);
					}
					else 
					 {
						if( response.message ) {
							show_error_msg(response.message);
						}  
						$('#favorite_btn_'+blog_id).remove();					
						var favorite_btn=$("<span  onclick='blog_unfavorite("+blog_id+")' id='favorite_btn_"+blog_id+"' >"+_notfavorite+"</span>");					
						$("#favorite_body_"+blog_id).append(favorite_btn);
						$('#favorite_counter').text(parseInt($('#favorite_counter').text())+1);
					 }
					//$("#favorite_loading_"+blog_id).empty();	
				}
			});
			
	};
	
	
function delete_blogcomment_confirm(id) {
	
	$.Zebra_Dialog("<?php echo __('are_you_sure_delete_blog') ?>", {
    'type':     'warning',
    'title':    _warning,
	'modal': true ,
    'buttons':  [
                    {caption: _yes, callback: function() {delete_blogcomment(id);}},
					{caption: _no, callback: function() { }}
                ]
	});						
}
function delete_blogcomment(id)
{
  $.ajax({
		type: "POST",
		url: _url+'blogcomments/delete_commentblog',
		data: 'blogcomment_id='+id,
		dataType: "json",
		success: function(response){
		if(response.success == true) {	
			 $('#answer_'+id).hide(function(){$(this).remove();});	
		}
		else 
		 {
			if( response.message ) {
				show_error_msg(response.message);
			}  
		 }
		} 
	});
}

	

</script>