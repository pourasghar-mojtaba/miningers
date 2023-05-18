
 <?php
  $User_Info= $this->Session->read('User_Info');
 ?>
 
<div class="post inactive answerBox userData" style="display: block;" >
<div class="ax">
	<?php  				  
			if(fileExistsInPath(__USER_IMAGE_PATH.__UPLOAD_THUMB.'/'.$User_Info['image'] ) && $User_Info['image']!='' ) 
			{
				echo $this->Html->image('/'.__USER_IMAGE_PATH.__UPLOAD_THUMB.'/'.$User_Info['image'],array('width'=>160,'height'=>160));
			}
			else{
				if($User_Info['sex']==0)
				  echo $this->Html->image('profile_women.png',array('width'=>160,'height'=>160));
				  else echo $this->Html->image('profile_men.png',array('width'=>160,'height'=>160));
			}
			
		   ?>
</div>
    <header class="data">
        <span class="userName"><?php echo $User_Info['name']; ?></span>
		<a class="userAtSign" href="<?php echo __SITE_URL.$User_Info['user_name']; ?>">@<?php echo $User_Info['user_name']; ?></a>
        <!--<h3 class="minidata">| کامپیوتر</h3>
        <h3 class="minidata">| شهر رشت</h3>-->
        <span class="date"><a href="#">
			<?php  echo $this->Gilace->get_current_persian_date();  ?>
		</a></span>
    </header>
	<?php echo $this->Form->create('Post', array('id'=>'AddCommentForm_'.$post['Post']['id'],'name'=>'AddCommentForm','class'=>'sendAnswer','enctype'=>'multipart/form-data','action'=>'/add_comment')); ?>
		<?php echo $this->Form->textarea('newcomment_input',array('label'=>'','type'=>'text','id'=>'newcomment_input_'.$post['Post']['id'],'cols'=>'70','rows'=>3,'maxlength'=>500,'placeholder'=>__('answer_to').' @'.$post['User']['user_name'])); ?>
        <input type="hidden"  name="data[Post][parent_id]" id="commnet_post_id_<?php echo $post['Post']['id'] ?>" value="<?php echo $post['Post']['id'] ?>"/>
		<input type="hidden" id="parent_commnet_post_id_<?php echo $post['Post']['id'] ?>" value="<?php echo $post['Post']['id'] ?>" />
		<input type="hidden"  id="commnet_post_user_id_<?php echo $post['Post']['id'] ?>" value="<?php echo $post['User']['id']; ?>" name="data[Post][commnet_post_user_id]" />
		<?php echo $this->Form->input('newcomment_image',array('label'=>'','type'=>'file','id'=>'newcomment_image_'.$post['Post']['id'],'style'=>'position:absolute;top:-100000px;' )); ?>
		<div class="iconsHolder">
            <div class="tile size_2 pink1 postImage uploadFile" id="newcomment_add_image_<?php echo $post['Post']['id'] ?>"><div class="symbol"></div></div>
                <div class="tile size_2 violet1 postLink uploadFile" onclick="show_add_comment_link_form();"><div class="symbol"></div></div>
    	    <div id="comment_loading_<?php echo $post['Post']['id'] ?>" style="float:left;margin-left: 5px"> </div>
    	    
        </div>
        <input type="submit" value="500" class="tile blue3 size0" id="comment_counter_<?php echo $post['Post']['id'] ?>">
        <div id="attachment_place_<?php echo $post['Post']['id'] ?>"></div>    
		 <div id="comment_result_<?php echo $post['Post']['id'] ?>" style="float:right"></div>
    </form>
</div>
<div id="comment_place_<?php echo $post['Post']['id'] ?>"></div>
<script>
	function refresh_comment(post_id){
	var comment_place = $("#comment_place_"+post_id);
		$(comment_place).show();
		$(comment_place).html('<img width="130" src="'+_url+'/img/loader/metro/preloader-w8-line-white.gif" >');
	 
	$.ajax({
			type:"POST",
			url:_url+'posts/refresh_comment',
			data:'post_id='+post_id,
	
			success:function(response){
				$(comment_place).html(response);  	  
			}
		}) ;	
} 
</script>
<?php if ($show_comment==1) echo "<script>
   jQuery(document).ready(function(){
   	refresh_comment(".$post['Post']['id'].");
	});
</script>";?>
<script>
	_answer_to = '<?php echo __('answer_to'); ?>';
	_show_comment = '<?php echo $show_comment; ?>';
	jQuery(document).ready(function(){
		
		
		$("#newcomment_input_"+<?php echo $post['Post']['id']; ?>).keydown(function(e) {
			var numb = 500 - $(this).val().length;
	        $("#comment_counter_"+<?php echo $post['Post']['id']; ?>).val(numb);
  		  });
		
		var commnet_post_id = $('#commnet_post_id_'+<?php echo $post['Post']['id'] ?>).val();
		//refresh_comment(commnet_post_id);
		
		$('#AddCommentForm_'+<?php echo $post['Post']['id'] ?>).on('submit', function(e) {
				if($('#newcomment_input_'+<?php echo $post['Post']['id'] ?>).val()==''){
					show_warning_msg(_enter_text);
					e.preventDefault();
					return false;
				}
				e.preventDefault();
                $("#comment_loading_"+<?php echo $post['Post']['id'] ?>).html('<img width="24" src="'+_url+'/img/loader/metro/preloader-w8-cycle-white.gif" >');
                $(this).ajaxSubmit({
                    target: '#comment_result_'+<?php echo $post['Post']['id'] ?>,
                    success:  afterPostSuccess , //call function after success
					error  :  afterPostError
                });
            });
			
			function afterPostSuccess()  {
				$("#comment_loading_"+<?php echo $post['Post']['id'] ?>).empty();
				var post_user_id = $('#commnet_post_user_id_'+<?php echo $post['Post']['id'] ?>).val();
				var body = $('#newcomment_input_'+<?php echo $post['Post']['id'] ?>).val();
				send_privacy_email(post_user_id,'oncomment',body);
				$('#AddCommentForm_'+<?php echo $post['Post']['id'] ?>).resetForm();  // reset form
	            $('#newcomment_link_'+<?php echo $post['Post']['id'] ?>).parent().fadeOut(200);
			    $('#newcomment_link_'+<?php echo $post['Post']['id'] ?>).parent().remove();
				
				$('#newcomment_image_attachment_'+<?php echo $post['Post']['id'] ?>).parent().fadeOut(200);
			    $('#newcomment_image_attachment_'+<?php echo $post['Post']['id'] ?>).parent().remove();
				
			    $('#newcomment_input_'+<?php echo $post['Post']['id'] ?>).val('');
				$("#comment_counter_"+<?php echo $post['Post']['id']; ?>).val(500);
				fadeOutNewPost();
				var commnet_post_id = $('#parent_commnet_post_id_'+<?php echo $post['Post']['id'] ?>).val();
			    show_success_msg(_save_post_success);
				if(_show_comment==1){
					refresh_comment(commnet_post_id);
				}else   new_refresh_home();	
				
       	  }
		  
		  function afterPostError()  {
			show_error_msg(_save_post_notsuccess);
       	  }
		
		
		$('#newcomment_image_'+<?php echo $post['Post']['id'] ?>).change(function(){

			var count = 0;
			var arr = $("#newcomment_image_attachment_"+<?php echo $post['Post']['id'] ?>).map(function() {
				  count+=1;
			  });
			if(count>=1){
				show_warning_msg(_exist_image);return;
			}
			
			var attach="<div class='attachment' id='newcomment_image_attachment_"+<?php echo $post['Post']['id'] ?>+"'><div class='close closethis'></div>"+_image_added+"</div>";
			$('#attachment_place_'+<?php echo $post['Post']['id'] ?>).append(attach);
			$(".closethis").click(function(e) {
		       $(this).parent().fadeOut(200);
		    });
		});
		
		
		
		 $('#newcomment_add_image_'+<?php echo $post['Post']['id'] ?>).click(function(){
			 $('#newcomment_image_'+<?php echo $post['Post']['id'] ?>).trigger('click');
	        //$(this).parent().find('input').click();
			$(".closethis").click(function(e) {
		        $(this).parent().fadeOut(200);
				$(this).parent().remove();
	    	});
			
	      });	
	});
	
function show_add_comment_link_form() {
	
	TINY.box.show({url:_url+'posts/add_comment_link',
			   post:'action=load_page&post_id='+<?php echo $post['Post']['id'] ?>,
			   opacity:50,
			   topsplit:2}
			   );
}
 
	




/* 
   	like function
   */
 	function comment_paginate_like(post_id){
		$("#comment_like_loading_"+post_id).html('<img src="'+_url+'/img/loader/waiting24.gif" >');
		$.ajax({
				type:"POST",
				url:_url+'likeunlikes/like',
				data:'post_id='+post_id,
				dataType: "json",
				success:function(response){
					// hide table row on success
					if(response.success == true) {
						$('#comment_like_count_'+post_id).text(parseInt($('#comment_like_count_'+post_id).text())+response.like);
						$('#comment_unlike_count_'+post_id).text(parseInt($('#comment_unlike_count_'+post_id).text())+response.unlike);
						
						$('#like_count_'+post_id).text(parseInt($('#like_count_'+post_id).text())+response.like);
						$('#unlike_count_'+post_id).text(parseInt($('#unlike_count_'+post_id).text())+response.unlike);
						 
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
					$("#comment_like_loading_"+post_id).empty();	
				}
			});
	};
 
 /* unlike */
 function comment_paginate_unlike(post_id){
		$("#comment_unlike_loading_"+post_id).html('<img src="'+_url+'/img/loader/waiting24.gif" >');
		$.ajax({
				type:"POST",
				url:_url+'likeunlikes/unlike',
				data:'post_id='+post_id,
				dataType: "json",
				success:function(response){
					// hide table row on success
					if(response.success == true) {
						$('#comment_like_count_'+post_id).text(parseInt($('#comment_like_count_'+post_id).text())+response.like);
						$('#comment_unlike_count_'+post_id).text(parseInt($('#comment_unlike_count_'+post_id).text())+response.unlike);
						
						$('#like_count_'+post_id).text(parseInt($('#like_count_'+post_id).text())+response.like);
						$('#unlike_count_'+post_id).text(parseInt($('#unlike_count_'+post_id).text())+response.unlike);
						 
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
					$("#comment_unlike_loading_"+post_id).empty();	
				}
			});
	};




function comment_paginate_share(post_id){
		$("#comment_share_loading_"+post_id).html('<img src="'+_url+'/img/loader/waiting24.gif" >');
		var post_user_id = $('#comment_share_post_user_id_'+post_id).val();
		$.ajax({
				type:"POST",
				url:_url+'shareposts/share',
				data:'post_id='+post_id+'&post_user_id='+post_user_id,
				dataType: "json",
				success:function(response){
					// hide table row on success
					if(response.success == true) {
						
						var share_count = parseInt($('#comment_share_count_'+post_id).text())+ 1;
						
						$('#comment_share_btn_'+post_id).remove();
						var share_btn=$("<li id='comment_share_btn_"+post_id+"' onclick='comment_paginate_unshare("+post_id+");'><img src='"+_url+"img/icons/share_r.png'><span id='comment_share_count_"+post_id+"'>"+share_count+"</span><span id='comment_share_loading_"+post_id+"'></span></li>");
						$("#comment_share_body_"+post_id).append(share_btn);
						
						$('#share_btn_'+post_id).remove();
						var share_btn=$("<li id='share_btn_"+post_id+"' onclick='paginate_unshare("+post_id+");'><img src='"+_url+"img/icons/share_r.png'><span id='share_count_"+post_id+"'>"+share_count+"</span><span id='share_loading_"+post_id+"'></span></li>");
						$("#share_body_"+post_id).append(share_btn);
						
						if( response.message ) {
							 show_error_msg(response.message);
						} 
						send_privacy_email(post_user_id,'onsharing','');
					}
					else 
					 {
						if( response.message ) {
							show_error_msg(response.message);
						}  
					 }
					$("#comment_share_loading_"+post_id).empty();	
			 		 
				}
			});
	};
 
 /* unshare */
 function comment_paginate_unshare(post_id){
		$("#comment_share_loading_"+post_id).html('<img src="'+_url+'/img/loader/waiting24.gif" >');
		$.ajax({
				type:"POST",
				url:_url+'shareposts/unshare',
				data:'post_id='+post_id,
				dataType: "json",
				success:function(response){
					// hide table row on success
					if(response.success == true) {
						
						var share_count = parseInt($('#comment_share_count_'+post_id).text()) - 1;
						
						$('#comment_share_btn_'+post_id).remove();
						var share_btn=$("<li id='comment_share_btn_"+post_id+"' onclick='comment_paginate_share("+post_id+");'><img src='"+_url+"img/icons/share.png'><span id='comment_share_count_"+post_id+"'>"+share_count+"</span><span id='comment_share_loading_"+post_id+"'></span></li>");
						$("#comment_share_body_"+post_id).append(share_btn);
						
						$('#share_btn_'+post_id).remove();
						var share_btn=$("<li id='share_btn_"+post_id+"' onclick='paginate_share("+post_id+");'><img src='"+_url+"img/icons/share.png'><span id='share_count_"+post_id+"'>"+share_count+"</span><span id='share_loading_"+post_id+"'></span></li>");
						$("#share_body_"+post_id).append(share_btn);
						
						if( response.message ) {
							show_error_msg(response.message);
						} 	
					}
					else 
					 {
						if( response.message ) {
							show_error_msg(response.message);
						}  
					 }
					$("#comment_share_loading_"+post_id).empty();	
				}
			});
	};



function comment_delete_post_confirm(id,parent_id_arr) {
			
  var confirm = new LightFace({
		width: 200,
		title:_warning,
		url: _url,
		draggable: true,
		
		keys: {
			esc: function() { this.close(); box.unfade(); }
		},
		content: _are_you_sure,
		buttons: [
			{ title: _yes, event: function() { this.close(); comment_delete_post(id,parent_id_arr); }, color: 'green' },
			{ title: _no, event: function() { this.close();  }, color: 'blue' }
		]
	});
	confirm.open();								
}


function comment_delete_post(id,parent_id_arr)
{
	$("#comment_delete_post_loading_"+id).html('<img src="'+_url+'/img/loader/waiting24.gif" >');
  $.ajax({
		type: "POST",
		url: _url+'posts/post_delete',
		data: 'post_id='+id,
		dataType: "json",
		success: function(response)
		{
		 $("#comment_delete_post_loading_"+id).empty();
		 if(response.success == true) {			
			$('#message_content').html(''); 
			$('#post_'+id).hide();
			$('#comment_post_'+id).hide();
			/* delete child post*/
			var sucess_ids=[];		
					for(var i = 0; i < parent_id_arr.length ; i++){
						if(parent_id_arr[i]!=0){
							var cid=parent_id_arr[i];
							sucess_ids.push(cid);
							$("#comment_delete_post_loading_"+cid).html('<img src="'+_url+'/img/loader/waiting24.gif" >');
							$.ajax({
								type: "POST",
								url: _url+'posts/post_delete',
								data: 'post_id='+cid,
								dataType: "json",
								success: function(response)
								{	 		
									if(response.success == true) {	
										$("#comment_delete_post_loading_"+cid).empty();
									}
									else 
									 {
										if( response.message ) {
											//showmsg(response.message);
										}  
										$("#comment_delete_post_loading_"+cid).empty();
									 }	
								}						
							  });
						}
					} 
				if(sucess_ids.length>0){
					for(var j = 0; j < sucess_ids.length ; j++){
						$('#post_'+sucess_ids[j]).hide();
						$('#comment_post_'+sucess_ids[j]).hide();
					}
				}	 
			
			/* delete child post*/
			if( response.message ) {
				//showmsg(response.message);		
			} 
			 	
		}
		else 
		 {
			if( response.message ) {
				showmsg(response.message);
			}  
		 }
		 //$("#inbox_message_loading").empty();
		 
		}
	
	  });

}


function set_comment(user_name,post_id,current_post_id,user_id)
{
	$('#commnet_post_id_'+current_post_id).val(post_id);
	$('#commnet_post_user_id_'+current_post_id).val(user_id);
	$('#newcomment_input_'+current_post_id).attr('placeholder',_answer_to +' @ '+user_name);
	//$("html, body").animate({ scrollTop: 0 }, "fast");
	var pos = $('#commnet_post_id_'+current_post_id).offset();
	$("html, body").animate({ scrollTop: pos.top/2 }, "fast");
 
}
	
	 
</script>	