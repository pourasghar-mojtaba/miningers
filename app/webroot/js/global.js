// JavaScript Document


function showmsg(msg) {
	 		return new LightFace({
	 			title: _message,
	 			content: msg,
				class_icon:'error_icon2' ,
				buttons: [
						{ title:  _close , event: function() { this.close(); } }
					],
				draggable: true
	 		}).open();
	 	}
		
function show_success_msg(msg,size) {
	$.Zebra_Dialog(msg, {
				    'type':     'confirmation',
				    'title':    _message,
					'auto_close': 1500 ,
					'modal': false ,
					'width':size ,
				    'buttons':  [
				                    {caption: _close, callback: function() { }}
				                ]
	});
}

function show_error_msg(msg,size) {
 		$.Zebra_Dialog(msg, {
				    'type':     'error',
				    'title':    _warning,
					/*'auto_close': 1500 ,*/
					'modal': false ,
					'width':size ,
				    'buttons':  [
				                    {caption: _close, callback: function() { }}
				                ]
	    });
}

function show_warning_msg(msg,size) {
 		$.Zebra_Dialog(msg, {
				    'type':     'warning',
				    'title':    _warning,
					/*'auto_close': 1500 ,*/
					'modal': false ,
					'width':size ,
				    'buttons':  [
				                    {caption: _close, callback: function() { }}
				                ]
	    });
}	

function send_privacy_email(user_id,location,text,post_id){
	
$.ajax({
		type: "POST",
		url: _url+'sendemails/send_email',
		data: 'user_id='+user_id+'&location='+location+'&text='+text+'&post_id='+post_id,
		cache: false,
		success: function(html)
		{
		 	$('#ajax_result').html(html);
		}
	
	  });
		
}

//====================================== posts ============================
 function refresh_home(count,categorypost_id){
	if(count > 0) $(".home_loading").html('<img src="'+_url+'/img/loader/big_loader.gif" >');	 
	var first =  count * 10; 
	$.ajax({
			type:"POST",
			url:_url+'posts/refresh_home',
			data:'first='+first+'&categorypost_id='+categorypost_id,	
			success:function(response){
				//var div= "<div></div>";
				//$(div).appendTo($('#home_body')).html(response).fadeOut(100).fadeIn(1000);
				$('#new_follow').hide();
				if(count<=0){
					$('#home_body').append(response);
				}else $('#home_body').append(response);
				$(".home_loading").empty();	
				
				if($.trim($('#home_body').text())==''){
					$('#new_follow').show();
				} 			  
			}
		}) ;	
}

function new_refresh_home(){
	$("#home_loading").html('<img src="'+_url+'/img/loader/big_loader.gif" >');
	$.ajax({
			type:"POST",
			url:_url+'posts/refresh_new_home',
			data:'first=0',
	
			success:function(response){
				$('#home_body').html('');
				$('#home_body').html(response);
				$("#home_loading").empty(); 
			}
		}) ;	
} 
function refresh_comment(post_id){
	$("#view_loading").html('<img src="'+_url+'/img/loader/big_loader.gif" >');
	$('#view_body').html('')
	$.ajax({
			type:"POST",
			url:_url+'posts/refresh_view/'+post_id,
			data:'',
	
			success:function(response){
				$('#view_body').html(response);
				$("#view_loading").empty(); 
			}
		}) ;	
}
function refresh_new_post()
{	  		
		$.ajax({
		type: "POST",
		url: _url+'posts/refresh_new_post',
		data: '',
		cache: false,
		success: function(response)
		{
			$('#home_body').prepend(response);
		}
	
	  });
	setTimeout('refresh_new_post();',40000);
}
function refresh_postads()
{	  		
		$.ajax({
		type: "POST",
		url: _url+'posts/refresh_postads',
		data: '',
		cache: false,
		success: function(response)
		{
		 	//var div= "<div></div>";
			//$(div).prependTo($('#home_body')).html(response).fadeOut(100).fadeIn(1000).fadeOut(100).fadeIn(1000);
            $('#home_body').prepend(response);
		}
	
	  });
}

function refresh_notification(count){
	if(count > 0) $("#notification_loading").html('<img src="'+_url+'/img/loader/big_loader.gif" >');	 	
	var first =  count * 10; 
	$.ajax({
			type:"POST",
			url:_url+'users/get_notofication_list',
			data:'first='+first,	
			success:function(response){
				if(count<=0){
					$('#notification_body').html(response);
				}else $('#notification_body').append(response);
				if(count > 0) $("#notification_loading").empty();				  
			}
		}) ;	
}

/* add posts */
jQuery(document).ready(function(){
	
	$('input, textarea').keyup(function() {
	    $(this).val().charAt(0).charCodeAt(0) < 200 ? $(this).css('direction','ltr') : $(this).css('direction','rtl');
	});
	/*  suto suggest search box */	
	$("#search_box").keyup(function() 
	{
	  var searchbox = $(this).val();
	  var value=$('#search_type').val();   
	  var dataString = 'search_word='+ searchbox+'&search_type='+value;
	  if(searchbox=='')
		{
			$("#search_result").slideUp(400);
		}
		else
		{
		    $("#search_loading").fadeOut();
		    $("#search_loading").fadeIn(400).html('<img width="100" src="'+_url+'/img/loader/metro/preloader-w8-line-white.gif" >');
			$.ajax({
				type: "POST",
				url: _url+"users/search_suggest",
				data: dataString,
				cache: false,
				success: function(html)
				{
					$("#search_display").slideDown(400);
					$("#search_result").html(html).slideDown(400);	
					$("#search_loading").fadeOut();
				}
		  });
		}return false;   
				 
	});
	
	
	 
});/* end of documrnt */

function delete_post_confirm(id,parent_id_arr) {
	$.Zebra_Dialog(_are_you_sure, {
				    'type':     'warning',
				    'title':    _warning,
					'modal': true ,
				    'buttons':  [
				                    {caption: _yes, callback: function() {delete_post(id,parent_id_arr);}},
									{caption: _no, callback: function() { }}
				                ]
	});						
}
function delete_post(id,parent_id_arr)
{
	//$("#delete_post_loading_"+id).html('<img src="'+_url+'/img/loader/waiting24.gif" >');
	$('body').prepend("<div id='modal'></div>");
	$("#modal").html('<div class="loadingPage"><div class="loaderCycle"></div><span>'+_delete_loading+'</span></div>' );
  $.ajax({
		type: "POST",
		url: _url+'posts/post_delete',
		data: 'post_id='+id,
		dataType: "json",
		success: function(response)
		{
		 //$("#delete_post_loading_"+id).empty();
		 remove_modal();
		 if(response.success == true) {			
			$('#message_content').html(''); 
			$('#post_'+id).slideUp('slow').hide();
			/* delete child post*/
			var sucess_ids=[];		
					for(var i = 0; i < parent_id_arr.length ; i++){
						if(parent_id_arr[i]!=0){
							var cid=parent_id_arr[i];
							sucess_ids.push(cid);
							//$("#delete_post_loading_"+cid).html('<img src="'+_url+'/img/loader/waiting24.gif" >');
							remove_modal();
							$.ajax({
								type: "POST",
								url: _url+'posts/post_delete',
								data: 'post_id='+cid,
								dataType: "json",
								success: function(response)
								{	 		
									if(response.success == true) {	
										//$("#delete_post_loading_"+cid).empty();
										remove_modal();
									}
									else 
									 {
										if( response.message ) {
											//showmsg(response.message);
										}  
										//$("#delete_post_loading_"+cid).empty();
										remove_modal();
									 }	
								}						
							  });
						}
					} 
				if(sucess_ids.length>0){
					for(var j = 0; j < sucess_ids.length ; j++){
						$('#post_'+sucess_ids[j]).slideUp(function(){$(this).remove()});
					}
				}	 
			
			/* delete child post*/
			
			
			if( response.message ) {	
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
/*
	favorite function
*/
function paginate_favorite(post_id){
		//$("#favorite_loading_"+post_id).html('<img src="'+_url+'/img/loader/ui-anim_basic_16x16.gif" >');
		$('#favorite_btn_'+post_id).text(_notfavorite); 
        $('#favorite_btn_'+post_id).removeAttr('onclick'); 
		$.ajax({
				type:"POST",
				url:_url+'favoriteposts/favorite',
				data:'post_id='+post_id,
				dataType: "json",
				success:function(response){
					// on success
					if(response.success == true) {
						$('#favorite_btn_'+post_id).remove();					
						var favorite_btn=$("<span  onclick='paginate_unfavorite("+post_id+")' id='favorite_btn_"+post_id+"' >"+_notfavorite+"</span>");					
						$("#favorite_body_"+post_id).append(favorite_btn);
					}
					else 
					 {
						if( response.message ) {
							show_error_msg(response.message);
						}  
						
						$('#favorite_btn_'+post_id).remove();		
						var favorite_btn=$("<span  onclick='paginate_favorite("+post_id+")' id='favorite_btn_"+post_id+"' >"+_favorite+"</span>");
						$("#favorite_body_"+post_id).append(favorite_btn);
					 }
					//$("#favorite_loading_"+post_id).empty();	
			 		 
				}
			});
	};
 
 /* unfavorite */
 function paginate_unfavorite(post_id){
		//$("#favorite_loading_"+post_id).html('<img src="'+_url+'/img/loader/ui-anim_basic_16x16.gif" >');	
		$('#favorite_btn_'+post_id).text(_favorite); 		
		$('#favorite_btn_'+post_id).removeAttr('onclick'); 		
		$.ajax({
				type:"POST",
				url:_url+'favoriteposts/unfavorite',
				data:'post_id='+post_id,
				dataType: "json",
				success:function(response){
					// hide table row on success
					if(response.success == true) {
						$('#favorite_btn_'+post_id).remove();		
						var favorite_btn=$("<span  onclick='paginate_favorite("+post_id+")' id='favorite_btn_"+post_id+"' >"+_favorite+"</span>");
						$("#favorite_body_"+post_id).append(favorite_btn);
					}
					else 
					 {
						if( response.message ) {
							show_error_msg(response.message);
						}  
						$('#favorite_btn_'+post_id).remove();					
						var favorite_btn=$("<span  onclick='paginate_unfavorite("+post_id+")' id='favorite_btn_"+post_id+"' >"+_notfavorite+"</span>");					
						$("#favorite_body_"+post_id).append(favorite_btn);
					 }
					//$("#favorite_loading_"+post_id).empty();	
				}
			});
};
	
	
function refresh_favorite(count){
	$("#favorite_loading").html('<img src="'+_url+'/img/loader/big_loader.gif" >');

	var first =  count * 10; 
	$.ajax({
			type:"POST",
			url:_url+'posts/refresh_favorite',
			data:'first='+first,	
			success:function(response)
			{
	            $('#favorite_body').prepend(response);
				$("#favorite_loading").html('');
			}			
		}) ;	
}

function refresh_discover(count){
	$("#discover_loading").html('<img src="'+_url+'/img/loader/big_loader.gif" >');

	var first =  count * 10; 
	$.ajax({
			type:"POST",
			url:_url+'posts/refresh_discover',
			data:'first='+first,	
			success:function(response)
			{
	            $('#discover_body').prepend(response);
				$("#discover_loading").html('');
			}			
		}) ;	
}

function refresh_tag(count,tag){
	$("#tag_loading").html('<img src="'+_url+'/img/loader/big_loader.gif" >');	 
	if(tag=='undefined'){
		tag='';
	}	
	var first =  count * 5; 
	$.ajax({
			type:"POST",
			url:_url+'posts/refresh_tag',
			data:'first='+first+'&tag='+tag,	
			success:function(response){
				$('#tag_body').prepend(response);
				$("#tag_loading").html('');			  
			}
		}) ;	
}

/*
	favorite function
*/


function paginate_share(post_id,post_user_id){
		//$("#share_loading_"+post_id).html('<img width="24" src="'+_url+'/img/loader/metro/preloader-w8-cycle-white.gif" >');
		$('#share_btn_'+post_id).text(_notshare); 	
		$('#share_btn_'+post_id).removeAttr('onclick'); 	
		$.ajax({
				type:"POST",
				url:_url+'allposts/share',
				data:'post_id='+post_id+'&post_user_id='+post_user_id,
				dataType: "json",
				success:function(response){
					// hide table row on success
					if(response.success == true) {
						$('#share_btn_'+post_id).remove();					
						var share_btn=$("<span  onclick='paginate_unshare("+post_id+","+post_user_id+")' id='share_btn_"+post_id+"' >"+_notshare+"</span>");					
						$("#share_body_"+post_id).append(share_btn); 
						/*if( response.message ) {
							 show_success_msg(response.message);
						} */
						send_privacy_email(post_user_id,'onsharing','',post_id);
					}
					else 
					 {
						if( response.message ) {
							show_error_msg(response.message);
						}  
						$('#share_btn_'+post_id).remove();		
						var share_btn=$("<span  onclick='paginate_share("+post_id+","+post_user_id+")' id='share_btn_"+post_id+"' >"+_share+"</span>");
						$("#share_body_"+post_id).append(share_btn);
					 }
					//$("#share_loading_"+post_id).empty();	
			 		 
				}
			});
	};
 
 /* unlike */
 function paginate_unshare(post_id,post_user_id){
		//$("#share_loading_"+post_id).html('<img width="24" src="'+_url+'/img/loader/metro/preloader-w8-cycle-white.gif" >');
		$('#share_btn_'+post_id).text(_share); 
		$('#share_btn_'+post_id).removeAttr('onclick'); 
		$.ajax({
				type:"POST",
				url:_url+'allposts/unshare',
				data:'post_id='+post_id,
				dataType: "json",
				success:function(response){
					// hide table row on success
					if(response.success == true) {
						$('#share_btn_'+post_id).remove();		
						var share_btn=$("<span  onclick='paginate_share("+post_id+","+post_user_id+")' id='share_btn_"+post_id+"' >"+_share+"</span>");
						$("#share_body_"+post_id).append(share_btn);						
						/*if( response.message ) {
							show_success_msg(response.message);
						} */	
					}
					else 
					 {
						if( response.message ) {
							show_error_msg(response.message);
						} 
						$('#share_btn_'+post_id).remove();					
						var share_btn=$("<span  onclick='paginate_unshare("+post_id+","+post_user_id+")' id='share_btn_"+post_id+"' >"+_notshare+"</span>");					
						$("#share_body_"+post_id).append(share_btn);  
					 }
					//$("#share_loading_"+post_id).empty();	
				}
			});
	};

function show_answer_expand(post_id,show_comment){
		var answer_place = $("#answer_place_"+post_id);
		$(answer_place).show();
		$(answer_place).html('<img width="130" src="'+_url+'/img/loader/metro/preloader-w8-line-black.gif" >');
		$.ajax({
			type:"POST",
			url:_url+'posts/load_answer',
			data:'id='+post_id+'&show_comment='+show_comment,
			success:function(response){				 
				$(answer_place).html(response);  
                $('#replay_post_'+post_id).slideDown(1000);
			}
		}) ;
};
function clear_reply_box(post_id){
	$('#newcomment_input_'+post_id).val('');
	$('#newcomment_link_'+post_id).val('');
	$('#newcomment_image_'+post_id).val('');
	$('#newpost_image_attachment_'+post_id).remove();
	$('#post_'+post_id).attr('class','post');
}
function replay_post(sender,post_id,parent_id,old_parent_id){
    var container = $('.postReply');
    var before_post = $('.before_post');
    var after_post = $('.after_post');
	container.slideUp(600,function(){clear_reply_box(post_id)});
	var thisParent = $(sender).closest('.post_body');
		
	if(parent_id==0){
        if(!$('.postReply',thisParent).is(':visible'))
		{
			container.slideUp(600,function(){clear_reply_box(post_id)});
			
			$('.postReply',thisParent).slideDown(600,function(){
				$('textarea',this).focus();
				});	
			
			
				
			/*alert($(sender).closest('.answer').length);
			alert($('.postReply',thisParent).closest('.after_post').length);*/
						 
			if($(sender).closest('.answer').length==0){
				before_post.slideUp(600,function(){$(this).remove()});
				after_post.slideUp(600,function(){$(this).remove()});
			}/*
			else{
				before_post.slideUp(600,function(){$(this).remove()});
				after_post.slideUp(600,function(){$(this).remove()});
			}*/
		}
    }else
	{
		before_post.slideUp(600,function(){$(this).remove()});
		after_post.slideUp(600,function(){$(this).remove()});
		container.slideUp(600,function(){clear_reply_box(post_id)});
		if(!before_post.is(':visible'))
		{			
			before_post.slideUp(600,function(){$(this).remove()});
			after_post.slideUp(600,function(){$(this).remove()});		
			container.slideUp(600,function(){clear_reply_box(post_id)});
			$('#post_'+post_id).before('<div class="before_post"></div>');
			$('#answer_place_'+post_id).after('<div class="after_post"></div>');
			show_inline_answer_expand(post_id,old_parent_id,$('.before_post'),'before');
			show_inline_answer_expand(post_id,old_parent_id,$('.after_post'),'after');
		}			
	}
}
function show_inline_answer_expand(post_id,parent_id,object_id,status){
	 
	$(object_id).html('<img width="130" src="'+_url+'/img/loader/metro/preloader-w8-line-black.gif" >');
	$.ajax({
		type:"POST",
		url:_url+'posts/load_inline_posts/'+status,
		data:'post_id='+post_id+'&parent_id='+parent_id,
		success:function(response){				 
			$(object_id).html(response).fadeOut(100).fadeIn(1000);   
			$('#replay_post_'+post_id).slideDown(600);
			$('#newcomment_input_'+post_id).focus();
			$('#post_'+post_id).attr('class','post answer');
			//$('.postReply:visible');
			//$('.postReply').set;
			$('html, body').animate({ scrollTop: $('#newcomment_input_'+post_id).offset().top-100 }, 2000);
		}
	}) ;
};

function industry_notification_multi()
{
	var new_notification = $("#new_notification #body");
		$(new_notification).html('<img width="24" src="'+_url+'/img/loader/metro/preloader-w8-cycle-white.gif" >');
	$.ajax({
		type: "POST",
		url: _url+'follows/industry_notification_multi',
		data: '',
		success: function(response)
		{	 		
			$(new_notification).html(response);
		}
	  });
}

function max_follow_notification_multi()
{
	var new_notification = $("#new_notification #body");
		$(new_notification).html('<img width="24" src="'+_url+'/img/loader/metro/preloader-w8-cycle-white.gif" >');
	$.ajax({
		type: "POST",
		url: _url+'follows/max_follow_notification_multi',
		data: '',
		success: function(response)
		{	 		
			$(new_notification).html(response);
		}
	  });
}	

function student_notification_multi()
{
	var new_notification = $("#new_notification #body");
		$(new_notification).html('<img width="24" src="'+_url+'/img/loader/metro/preloader-w8-cycle-white.gif" >');
	$.ajax({
		type: "POST",
		url: _url+'follows/student_notification_multi',
		data: '',
		success: function(response)
		{	 		
			$(new_notification).html(response);
		}
	  });
}

function job_notification_multi()
{
	var new_notification = $("#new_notification #body");
		$(new_notification).html('<img width="24" src="'+_url+'/img/loader/metro/preloader-w8-cycle-white.gif" >');
	$.ajax({
		type: "POST",
		url: _url+'follows/job_notification_multi',
		data: '',
		success: function(response)
		{	 		
			$(new_notification).html(response);
		}
	  });
}	

function post_notification_multi()
{
	var new_notification = $("#new_notification #body");
		$(new_notification).html('<img width="24" src="'+_url+'/img/loader/metro/preloader-w8-cycle-white.gif" >');
	$.ajax({
		type: "POST",
		url: _url+'follows/post_notification_multi',
		data: '',
		success: function(response)
		{	 		
			$(new_notification).html(response);
		}
	  });
}

function follow_follower_notification_multi()
{
	var new_notification = $("#new_notification #body");
		$(new_notification).html('<img width="24" src="'+_url+'/img/loader/metro/preloader-w8-cycle-white.gif" >');
	$.ajax({
		type: "POST",
		url: _url+'follows/follow_follower_notification_multi',
		data: '',
		success: function(response)
		{	 		
			$(new_notification).html(response);
		}
	  });
}

function select_notification()
{
	 var nofication_array = Array(1,2,5,6);
	 rand = nofication_array[Math.floor(Math.random() * nofication_array.length)];
	 //rand=1;
	 switch(rand){
	 	case 1:
			industry_notification_multi();
	 		break;
		case 2:
			max_follow_notification_multi();
	 		break;	
		case 3:
			student_notification_multi();
	 		break;	
		case 4:
			job_notification_multi();
	 		break;	
		case 5:
			follow_follower_notification_multi();
	 		break;
		case 6:
			post_notification_multi();
	 		break;		
	 }
}

function new_follow(id,type)
 {
 	$.ajax({
			type:"POST",
			url:_url+'follows/follow',
			data:'id='+id,
			dataType: "json",
			success:function(response){
				// hide table row on success
				if(response.success == true) {
					 
					var new_notification = $("#new_notification_"+id);
						$(new_notification).fadeOut('slow');
						$(new_notification).html('<img width="24" src="'+_url+'/img/loader/metro/preloader-w8-cycle-white.gif" >');
						
						switch(type){
						 	case 1:
								var first=$('#last_notification_first').val();
								$('#last_notification_first').remove();
								$.ajax({
									type: "POST",
									url: _url+'follows/industry_notification_one',
									data: 'first='+first,
									success: function(response)
									{	 		
										$(new_notification).html(response).fadeOut(100).fadeIn('slow');
									}
								  });
						 		break;
							case 2:
								var first=$('#last_notification_first').val();
								$('#last_notification_first').remove();
								$.ajax({
									type: "POST",
									url: _url+'follows/max_follow_notification_one',
									data: 'first='+first,
									success: function(response)
									{	 		
										$(new_notification).html(response).fadeOut(100).fadeIn('slow');
									}
								  });
						 		break;	
							case 3:
								var first=$('#last_notification_first').val();
								$('#last_notification_first').remove();
								$.ajax({
									type: "POST",
									url: _url+'follows/student_notification_one',
									data: 'first='+first,
									success: function(response)
									{	 		
										$(new_notification).html(response).fadeOut(100).fadeIn('slow');
									}
								  });	
						 		break;	
							case 4:
								var first=$('#last_notification_first').val();
								$('#last_notification_first').remove();
								$.ajax({
									type: "POST",
									url: _url+'follows/job_notification_one',
									data: 'first='+first,
									success: function(response)
									{	 		
										$(new_notification).html(response).fadeOut(100).fadeIn('slow');
									}
								  });
						 		break;	
							case 5:
								var first=$('#last_notification_first').val();
								$('#last_notification_first').remove();
								$.ajax({
									type: "POST",
									url: _url+'follows/follow_follower_notification_one',
									data: 'first='+first,
									success: function(response)
									{	 		
										$(new_notification).html(response).fadeOut(100).fadeIn('slow');
									}
								  });
						 		break;
							case 6:
								var first=$('#last_notification_first').val();
								$('#last_notification_first').remove();
								$.ajax({
									type: "POST",
									url: _url+'follows/post_notification_one',
									data: 'first='+first,
									success: function(response)
									{	 		
										$(new_notification).html(response).fadeOut(100).fadeIn('slow');
									}
								  });
						 		break;		
						 }
						
						 
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
 function search_users(count){
	//alert(count);
	var first =  count * 5;
	$("#result_search_loading").html('<img src="'+_url+'/img/loader/big_loader.gif" >'); 
	var action_type = $('#action_type').val();
	var action_type_value = $('#action_type_value').val();
	var user_type = $('#user_type').val();
	$.ajax({
			type:"POST",
			url:_url+'users/ajax_search',
			data:'first='+first+'&action_type='+action_type+'&action_type_value='+action_type_value+'&user_type='+user_type,	
			success:function(response){				 
				$('#search_result_body').append(response);  
				$("#result_search_loading").empty();  
			}
		}) ;			  
}
/*
   follow and not_follow
*/ 
 function follow(id)
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
						var not_follow_btn=$("<div class='btn green' onclick='not_follow("+id+");' id='not_follow_btn_"+id+"'><span class='text'>"+_not_follow+"</span><span class='icon icon-left-open'></span></div>");
						/*var not_follow_btn=$("<div class='btn cancel' onclick='not_follow("+id+");' id='not_follow_btn_"+id+"'>"+_not_follow+"</div>");*/
					    $("#extraBtn_"+id).append(not_follow_btn);
						send_privacy_email(id,'onfollow','',0);
						
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
 function not_follow(id)
 {
 	$.ajax({
			type:"POST",
			url:_url+'follows/not_follow',
			data:'id='+id,
			dataType: "json",
			success:function(response){
				// hide table row on success
				if(response.success == true) {
					$('#not_follow_btn_'+id).remove();
						/*var follow_btn=$("<div class='btn ok' onclick='follow("+id+");' id='follow_btn_"+id+"'>"+_follow+"</div>");*/
						var follow_btn=$("<div class='btn blue' onclick='follow("+id+");' id='follow_btn_"+id+"'><span class='text'>"+_follow+"</span><span class='icon icon-left-open'></span></div>");
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
 function search_post(count,search_word){
	$("#search_result_loading").html('<img src="'+_url+'/img/loader/big_loader.gif" >');

	if(search_word=='undefined'){
		search_word='';
	}	
	var first =  count * 5; 
	$.ajax({
			type:"POST",
			url:_url+'posts/search_post',
			data:'first='+first+'&search_word='+search_word,	
			success:function(response){
 	    		$('#search_result_body').append(response);
				$("#search_result_loading").empty();				  
			}
	}) ;	
} 
//=========================== add_to_friend =================================================
function send_friend_message_form(id,name,is_multi) {
    
     TINY.box.show({url:_url+'chats/send_friend_message',
				   post:'action=load_page&name='+name+'&is_multi='+is_multi+'&id='+id,
				   opacity:50,
				   topsplit:2}
				   );				
	/* load auto complete*/
	if(is_multi==1)
	{
		$.ajax({
			type: "POST",
			url: _url+'chats/friend_autocomplete',
			data: 'id='+id,
			cache: false,
			success: function(html)
			{
			 $('#ajax_result').html(html);
			}
		
		  });
	}	
} 
function read_message(id,obj)
{
	//obj.addClass('active');
	$(obj).find('.active').removeClass();
	$("#read_message_loading").html('<img width="24" src="'+_url+'/img/loader/metro/preloader-w8-cycle-black.gif" >');
	$(obj).find('span #new_message_count').remove();
	
	$('#profile_notification_icon').remove();
	$('.counter').remove();
	 
	$.ajax({
			type:"POST",
			url:_url+'chats/read_message',
			data:'id='+id,
	
			success:function(response){
				 
				$('#message_content').html(response);  
				$("#read_message_loading").empty();
				  
			}
		}) ;
	
}

function send_message(id)
{  
	$("#inbox_message_loading").html('<img width="24" src="'+_url+'/img/loader/metro/preloader-w8-cycle-black.gif" >');	
	var message = $("textarea#inbox_message_content").val();
	if(message.trim()==''){
		show_warning_msg(_enter_text);
		$("#inbox_message_loading").empty();
		return;
	}
	$.ajax({
		type: "POST",
		url: _url+'chats/send_friend_message',
		data: 'id='+id+'&message='+message+'&action=send',
		dataType: "json",
		success: function(response)
		{	 
		 if(response.success == true) {			
			if( response.message ) {
				show_success_msg(response.message);		
			} 
			read_message(id,null);	
			send_privacy_email(id,'onmessage',message,0);
		}
		else 
		 {
			if( response.message ) {
				show_error_msg(response.message);
			}  
		 }
		 $("#inbox_message_loading").empty();		 
		}	
	  });
}

function send_one_message()
{  
	$("#newMSG_loading").html('<img width="24" src="'+_url+'/img/loader/metro/preloader-w8-cycle-black.gif" >');		
	var message = $("textarea#newMSG_text").val();
	if(message.trim()==''){
		show_warning_msg(_enter_text);
		$("#newMSG_loading").empty();
		return;
	}
	var user_name = $("#newMSG_user_name").val();
	 
	$.ajax({
		type: "POST",
		url: _url+'chats/send_one_message',
		data: 'user_name='+user_name+'&message='+message,
		dataType: "json",
		success: function(response)
		{		 
		 if(response.success == true) {			
			if( response.message ) {
				$("textarea#newMSG_text").val('');
				$("#newMSG_user_name").val('');
				show_success_msg(response.message);		
			} 	
				send_privacy_email(user_name,'onmessage',message,0);
			}
			else 
			 {
				if( response.message ) {
					show_error_msg(response.message);
				}  
			 }		 
		 $("#newMSG_loading").empty();
		}	
	  });
}

function view_message(id,name,chat_id,obj)
{     
	$.ajax({
			type: "POST",
			url: _url+'/ajax/view_message.php',
			data: 'id='+chat_id,
			cache: false,
			success: function(html)
			{
			 $('#result_windows').html(html);
			 $(obj).parent().hide(400);
			 send_friend_message_form(id,name,0);
			}
		
		  })		
}
function delete_message_confirm(id,obj) {	
	$.Zebra_Dialog(_are_you_sure, {
				    'type':     'warning',
				    'title':    _warning,
					'modal': true ,
				    'buttons':  [
				                    {caption: _yes, callback: function() {delete_message(id,obj);}},
									{caption: _no, callback: function() { }}
				                ]
	});									
}
function delete_message(id,obj)
{
  $.ajax({
		type: "POST",
		url: _url+'chats/delete_message',
		data: 'id='+id,
		dataType: "json",
		success: function(response)
		{
		 
		 if(response.success == true) {			
			$('#message_content').html(''); 
			$(obj).parent().hide();
			if( response.message ) {
				show_success_msg(response.message);		
			} 			 	
		}
		else 
		 {
			if( response.message ) {
				show_error_msg(response.message);
			}  
		 }
		 //$("#inbox_message_loading").empty();		 
		}	
	  });
}
function refresh_alltag(count){
	$("#tag_loading").html('<img src="'+_url+'/img/loader/big_loader.gif" >');	 
	var first =  count * 50; 
	$.ajax({
			type:"POST",
			url:_url+'posts/get_all_tags',
			data:'first='+first,	
			success:function(response){
				var div= "<div></div>";
				$(div).appendTo($('#tag_body')).html(response).fadeOut(100).fadeIn(1000);
				$("#tag_loading").empty();
				$("#tag_loading1").empty();				  
			}
		}) ;	
}
//=========================== add_to_friend =================================================
function edit_email(email)
{  
	$("#verify_email_loading").html('<img width="24" src="'+_url+'/img/loader/metro/preloader-w8-cycle-black.gif" >');	
	$.ajax({
		type: "POST",
		url: _url+'users/verify_email/'+email,
		data: '',
		dataType: "json",
		success: function(response)
		{	 
			 if(response.success == true) {			
				if( response.message ) {
					show_success_msg(response.message);	
					$('#email_verify').remove();	
				} 
			}
			else 
			 {
				if( response.message ) {
					show_error_msg(response.message);
				}  
			 }
			 $("#verify_email_loading").empty();		 
		}	
	  });
}

function refresh_post_image(id){
	$("#post_image_loading").html('<img src="'+_url+'/img/loader/big_loader.gif" >');

	var first =  count * 10;
	$.ajax({
			type:"POST",
			url:_url+'posts/refresh_post_image',
			data:'first='+first+'&id='+id,	
			success:function(response){
				if(count<=0){
					$('#post_image_body').append(response);
				}else $('#post_image_body').append(response);
				$(".post_image_loading").empty();	
				
				if($.trim($('#post_image_body').text())==''){
					$('#post_image_loading').show();
				} 			  
			}
		}) ; 
 	
} 	

function refresh_last_blog(count){
	if(count > 0) $("#lastblog_loading").html('<img src="'+_url+'/img/loader/big_loader.gif" >');	 	
	var first =  count * 10; 
	$.ajax({
			type:"POST",
			url:_url+'blogs/get_last_blog',
			data:'first='+first,	
			success:function(response){
				if(count<=0){
					$('#lastblog_body').html(response);
				}else $('#lastblog_body').append(response);
				if(count > 0) $("#lastblog_loading").empty();				  
			}
		}) ;	
}

function refresh_my_blog(count,user_id,blog_id){
	if(count > 0) $("#myblog_loading").html('<img src="'+_url+'/img/loader/big_loader.gif" >');	 	
	var first =  count * 10; 
	$.ajax({
			type:"POST",
			url:_url+'blogs/get_my_blog/'+user_id+'/'+blog_id,
			data:'first='+first,	
			success:function(response){
				if(count<=0){
					$('#myblog_body').html(response);
				}else $('#myblog_body').append(response);
				if(count > 0) $("#myblog_loading").empty();				  
			}
		}) ;	
}
/*=========================================================================================*/

/*=========================================================================================*/
