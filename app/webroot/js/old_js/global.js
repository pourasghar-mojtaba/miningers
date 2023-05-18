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

function generate(type,text,layout) {
  	var n = noty({
  		text: text,
  		type: type,
      dismissQueue: false,
  		layout: layout,
  		theme: 'defaultTheme'
  	});
    console.log(type + ' - ' + n.options.id);
    return n;
  }


 //=========================== add_to_friend =================================================
function send_friend_request_profile(id)
{
  $("#friendLoader").html('<img width="24" src="'+_url+'/img/loader/metro/preloader-w8-cycle-white.gif" >');
	$.ajax({
		type: "POST",
		url: _url+'/ajax/tomb_user/add_to_friend.php',
		data: 'id='+id+'&action=profile' ,
		cache: false,
		success: function(html)
		{  
		  $('#result_windows').html(html);
		  $("#friendLoader").empty();
		}
	
	  });
}




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

function send_friend_request(id)
{
  $("#friendLoader_"+id).html('<img src="'+_IMAGE_PATH+'/loader.gif">');
	$.ajax({
		type: "POST",
		url: _url+'/ajax/tomb_user/add_to_friend.php',
		data: 'id='+id ,
		cache: false,
		success: function(html)
		{  
		  $('#friendResult_'+id).html(html);
		  $("#friendLoader_"+id).empty();
		}
	
	  });
}


function message_box(){
	TINY.box.show({url:_url+'chats/message_box',
				  // post:'action=load_page',
				   opacity:50,
				   topsplit:2}
				   );
};	

function read_message(id,obj)
{
	//obj.addClass('active');
	$(obj).parent().parent().find('.unreaded').removeClass();
	$("#read_message_loading").html('<img width="24" src="'+_url+'/img/loader/metro/preloader-w8-cycle-black.gif" >');
	$(obj).find('span').remove();
	 
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
			
  /*var confirm = new LightFace({
		width: 200,
		title:_warning,
		url: _url,
		draggable: true,
		
		keys: {
			esc: function() { this.close(); box.unfade(); }
		},
		content: _are_you_sure,
		buttons: [
			{ title: _yes, event: function() { this.close(); delete_message(id,obj); }, color: 'green' },
			{ title: _no, event: function() { this.close();  }, color: 'blue' }
		]
	});
	confirm.open();	*/
	
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
//=========================== add_to_friend =================================================
/*
*   New post
*/
jQuery(document).ready(function(){


		
	 $('#AddPostForm').on('submit', function(e) {
                            
                var newpost_str =$('#newpost_input').val(); 
				if(newpost_str.trim()==''){
					show_warning_msg(_enter_text);
					e.preventDefault();
					return false;
				}
				e.preventDefault();
                $("#newpost_loading").html('<img width="24" src="'+_url+'/img/loader/metro/preloader-w8-cycle-white.gif" >');
                $(this).ajaxSubmit({
                    target: '#post_result',
                    success:  afterPostSuccess , //call function after success
					error  :  afterPostError
                });
            });
			
			function afterPostSuccess()  {
	            $('#AddPostForm').resetForm();  // reset form
				$("#newpost_loading").empty();
	            $('#newpost_link').parent().fadeOut(200);
			    $('#newpost_link').parent().remove();
				
				$('#newpost_image_attachment').parent().fadeOut(200);
			    $('#newpost_image_attachment').parent().remove();
				
			    $('#newpost_input').val('');
			    $('.newpost_charnumber').text(200);
				fadeOutNewPost();
			    show_success_msg(_save_post_success);
			    new_refresh_home();	
       	  }
		  
		  function afterPostError()  {
			show_error_msg(_save_post_notsuccess);
       	  }
	 
 		$('#newpost_image').change(function(){
			
			var count = 0;
			var arr = $("#newpost_image_attachment").map(function() {
				  count+=1;
			  });
			if(count>=1){
				show_warning_msg(_exist_image);return;
			}
			
			var attach="<div class='attachment' id='newpost_image_attachment'><div class='close closethis'></div>"+_image_added+"</div>";
			$('#NewPost .newComnt').append(attach);
			$(".closethis").click(function(e) {
		       $(this).parent().fadeOut(200);
		    });
		});
		
	 $('#newpost_add_image').click(function(){
		 $('#newpost_image').trigger('click');
        //$(this).parent().find('input').click();
		$(".closethis").click(function(e) {
        $(this).parent().fadeOut(200);
		$(this).parent().remove();
    });
		
    });
	
	
	
	 $('#newpost_input').keyup(function(e){
	 	$('.newpost_charnumber').text(500-$(this).val().length); 
	 });
 
 
 	$(".closethis").click(function(e) {
        $(this).parent().fadeOut(200);
    });
 
 
	/*  suto suggest search box */
	
	$("#search_box").keyup(function() 
	{
	  var searchbox = $(this).val();
	  var selectOption = "ul.SelectOption";
	  var value=$("li.header span",selectOption).attr("title");
	   
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
 
 function show_add_link_form() {
	
	TINY.box.show({url:_url+'posts/add_link',
				   post:'action=load_page',
				   opacity:50,
				   topsplit:2}
				   );
}
 

 
function refresh_home(count,tag,search_word){
	$("#home_loading").html('<img src="'+_url+'/img/loader/big_loader.gif" >');
	//$("#home_loading1").html('<img src="'+_url+'/img/loader/big_loader.gif" >');	 
	if(tag=='undefined'){
		tag='';
	}
	if(search_word=='undefined'){
		search_word='';
	}	
	var first =  count * 5; 
	$.ajax({
			type:"POST",
			url:_url+'posts/refresh_home',
			data:'first='+first+'&tag='+tag+'&search_word='+search_word,	
			success:function(response){
				var div= "<div></div>";
				$(div).appendTo($('#home_body')).html(response).fadeOut(100).fadeIn(1000);
				$("#home_loading").empty();
				$("#home_loading1").empty();				  
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
				var div= "<div></div>";
				$(div).appendTo($('#tag_body')).html(response).fadeOut(100).fadeIn(1000);
				$("#tag_loading").empty();
				$("#tag_loading1").empty();				  
			}
		}) ;	
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

function refresh_allusertag(count){
	$("#tag_loading").html('<img src="'+_url+'/img/loader/big_loader.gif" >');	 
	var first =  count * 50; 
	$.ajax({
			type:"POST",
			url:_url+'users/get_all_tags',
			data:'first='+first,	
			success:function(response){
				var div= "<div></div>";
				$(div).appendTo($('#tag_body')).html(response).fadeOut(100).fadeIn(1000);
				$("#tag_loading").empty();
				$("#tag_loading1").empty();				  
			}
		}) ;	
}

function search_post(count,search_word){
	$("#home_loading").html('<img src="'+_url+'/img/loader/big_loader.gif" >');

	if(search_word=='undefined'){
		search_word='';
	}	
	var first =  count * 5; 
	$.ajax({
			type:"POST",
			url:_url+'posts/search_post',
			data:'first='+first+'&search_word='+search_word,	
			success:function(response){
				var div= "<div></div>";
				$(div).appendTo($('#home_body')).html(response).fadeOut(100).fadeIn(1000);
				$("#home_loading").empty();
				$("#home_loading1").empty();				  
			}
	}) ;	
} 

/*function new_refresh_home()
{
	$("#home_loading").html('<img src="'+_url+'/img/loader/big_loader.gif" >');
	$.ajax({
			type:"POST",
			url:_url+'posts/refresh_new_home',
			data:'first=0',	
			success:function(response){				 
				$('#home_body').html(response).fadeOut(100).fadeIn(1000);  
				$("#home_loading").empty();			  
			}
		}) ;	
}*/
function new_refresh_home(){
	$("#home_loading").html('<img src="'+_url+'/img/loader/big_loader.gif" >');
	$.ajax({
			type:"POST",
			url:_url+'posts/refresh_new_home',
			data:'first=0',
	
			success:function(response){
				$('#home_body').html('');
				var div= "<div></div>";
				$(div).appendTo($('#home_body')).html(response).fadeOut(100).fadeIn(1000);
				$("#home_loading").empty(); 
			}
		}) ;	
} 
 
 /*
   expand post
 
 */

function expand_post(s,post_id,show_comment){
	 
	if($(s).hasClass("minimize"))
	{
		expand_minimize($(s),post_id);
	}
	else
		expand_maximize($(s),post_id,show_comment);
}
function  expand_maximize(s,post_id,show_comment)
{
	var sender =s;
	//sender.removeClass("maximize");
	sender.addClass("minimize");
	
	rotate180(sender);
	//show_image_expand(post_id);
	show_answer_expand(post_id,show_comment);
}
function  expand_minimize(s,post_id)
{
	var sender =s;
	sender.removeClass("minimize");
	//sender.addClass("maximize");
	rotate360(sender);
	//hide_image_expand(post_id);
	hide_answer_expand(post_id);
}
function show_answer_expand(post_id,show_comment){
		var answer_place = $("#answer_place_"+post_id);
		$(answer_place).show();
		$(answer_place).html('<img width="130" src="'+_url+'/img/loader/metro/preloader-w8-line-white.gif" >');
		$.ajax({
			type:"POST",
			url:_url+'posts/load_answer',
			data:'id='+post_id+'&show_comment='+show_comment,
			success:function(response){				 
				$(answer_place).html(response).fadeOut(100).fadeIn(1000);  
			}
		}) ;
};
function hide_answer_expand(post_id)
{
	var answer_place = $("#answer_place_"+post_id);
	$(answer_place).slideUp('slow').empty();
}
 function show_image_expand(post_id){
		var imagePlace = $("#imagePlace_"+post_id);
		$(imagePlace).show();
		$(imagePlace).html('<img width="100" src="'+_url+'/img/loader/metro/preloader-w8-line-black.gif" >');
		$.ajax({
			type:"POST",
			url:_url+'posts/load_image_link',
			data:'id='+post_id,
			success:function(response){				 
				$(imagePlace).html(response).fadeOut(100).fadeIn(1000);  
			}
		}) ;
};
function hide_image_expand(post_id)
{
	var imagePlace = $("#imagePlace_"+post_id);
	$(imagePlace).slideUp('slow').empty();
}	
function emptyExtend()
{
	/*var BGtag = "<div class='BG'></div>";
	var Loadingtag = "<div class='loading'>درحال بارگذاری. شکیبا باشید</div>";
	$("#extendPost").empty();
	$("#extendPost").html(BGtag+Loadingtag);*/
	$("#extendPost").fadeOut(400);
	$("#extendPost").remove();
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
						var not_follow_btn=$("<div class='btn cancel' onclick='not_follow("+id+");' id='not_follow_btn_"+id+"'>"+_not_follow+"</div>");
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
						var follow_btn=$("<div class='btn ok' onclick='follow("+id+");' id='follow_btn_"+id+"'>"+_follow+"</div>");
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
 
 
function insert_comment(){
	 
	var post_id = $('#commnet_post_id').val();
	var post_user_id = $('#commnet_post_user_id').val();
	var body = $('#comment_input').val();
	if(body==''){
		return;
	}
	
	$("#comment_loading").html('<img width="24" src="'+_url+'/img/loader/metro/preloader-w8-cycle-white.gif" >');
	$.ajax({
			type:"POST",
			url:_url+'comments/add_comment',
			data:'post_id='+post_id+'&body='+body+'&post_user_id='+post_user_id,
			dataType: "json",
			success:function(response){
				$("#comment_loading").empty();	
				// hide table row on success
				if(response.success == true) {

					if( response.message ) {
						show_success_msg(response.message);
						$('#comment_input').val('');
						//refresh_comment(post_id);
						
					} 
					send_privacy_email(post_user_id,'oncomment',body,post_id);
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
   	like function
   */
 	function like(post_id){
		$("#like_loading").html('<img src="'+_url+'/img/loader/waiting24.gif" >');
		var post_user_id = $('#share_post_user_id_'+post_id).val();
		$.ajax({
				type:"POST",
				url:_url+'likeunlikes/like',
				data:'post_id='+post_id+'&post_user_id='+post_user_id,
				dataType: "json",
				success:function(response){
					// hide table row on success
					if(response.success == true) {
						$('#like_count').text(parseInt($('#like_count').text())+response.like);
						$('#unlike_count').text(parseInt($('#unlike_count').text())+response.unlike);
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
					$("#like_loading").empty();	
				}
			});
	};
 
 /* unlike */
 function unlike(post_id){
		$("#unlike_loading").html('<img src="'+_url+'/img/loader/waiting24.gif" >');
		var post_user_id = $('#share_post_user_id_'+post_id).val();
		$.ajax({
				type:"POST",
				url:_url+'likeunlikes/unlike',
				data:'post_id='+post_id+'&post_user_id='+post_user_id,
				dataType: "json",
				success:function(response){
					// hide table row on success
					if(response.success == true) {
						$('#like_count').text(parseInt($('#like_count').text())+response.like);
						$('#unlike_count').text(parseInt($('#unlike_count').text())+response.unlike);
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
					$("#unlike_loading").empty();	
				}
			});
	};
 
 
 /* 
   	like function
   */
 	function paginate_like(post_id){
		$("#like_loading_"+post_id).html('<img src="'+_url+'/img/loader/waiting24.gif" >');
		var post_user_id = $('#share_post_user_id_'+post_id).val();
		$.ajax({
				type:"POST",
				url:_url+'likeunlikes/like',
				data:'post_id='+post_id+'&post_user_id='+post_user_id,
				dataType: "json",
				success:function(response){
					// hide table row on success
					if(response.success == true) {
						$('#like_count_'+post_id).text(parseInt($('#like_count_'+post_id).text())+response.like);
						$('#unlike_count_'+post_id).text(parseInt($('#unlike_count_'+post_id).text())+response.unlike);
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
					$("#like_loading_"+post_id).empty();	
				}
			});
	};
 
 /* unlike */
 function paginate_unlike(post_id){
		$("#unlike_loading_"+post_id).html('<img src="'+_url+'/img/loader/waiting24.gif" >');
		var post_user_id = $('#share_post_user_id_'+post_id).val();
		$.ajax({
				type:"POST",
				url:_url+'likeunlikes/unlike',
				data:'post_id='+post_id+'&post_user_id='+post_user_id,
				dataType: "json",
				success:function(response){
					// hide table row on success
					if(response.success == true) {
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
					$("#unlike_loading_"+post_id).empty();	
				}
			});
	};
 
/*function refresh_comment(id){
	$("#comment_body_loading").html('<img src="'+_url+'/img/loader/5.gif" >');
	 
	$.ajax({
			type:"POST",
			url:_url+'comments/refresh_comment',
			data:'post_id='+id,
	
			success:function(response){
				 
				$('#float_comment_place').html(response);  
				$("#comment_body_loading").empty();
				  
			}
		}) ;	
}  */
 
 
 
 /*
    ajax search result
 */
 
 function search_home(count){
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
				
				//searchresult show follow btn
				$(".searchResult ul.result li").mouseenter(function(e) {
			        $(".btn",this).animate(
				{
					top:"5"
				},500,"easeOutElastic");
			    });
				$(".searchResult ul.result li").mouseleave(function(e) {
			        $(".btn",this).animate(
				{
					//opacity:1,
					top:"-40"
				},500,"easeOutElastic");
			    });
				  
			}
		}) ;	
		  
} 
 
 
/*
*  share function
*/
/* 
   	share function
   */
 	function share(post_id){
		$("#share_loading").html('<img src="'+_url+'/img/loader/waiting24.gif" >');
		var post_user_id = $('#share_post_user_id').val();
		$.ajax({
				type:"POST",
				url:_url+'shareposts/share',
				data:'post_id='+post_id+'&post_user_id='+post_user_id,
				dataType: "json",
				success:function(response){
					// hide table row on success
					if(response.success == true) {
						var share_count = parseInt($('#share_count').text())+ 1;
						$('#share_btn').remove();
						var share_btn=$("<li id='share_btn' onclick='unshare("+post_id+");'><img src='"+_url+"img/icons/share_b.png'><span id='share_count'>"+share_count+"</span><span id='share_loading'></span></li>");
						$("#share_body").append(share_btn);
						if( response.message ) {
							 show_success_msg(response.message);
						} 	
						send_privacy_email(post_user_id,'onsharing','',post_id);
					}
					else 
					 {
						if( response.message ) {
							show_error_msg(response.message);
						}  
					 }
					$("#share_loading").empty();	
				}
			});
	};
 
 /* unlike */
 function unshare(post_id){
		$("#share_loading").html('<img src="'+_url+'/img/loader/waiting24.gif" >');
		$.ajax({
				type:"POST",
				url:_url+'shareposts/unshare',
				data:'post_id='+post_id,
				dataType: "json",
				success:function(response){
					// hide table row on success
					if(response.success == true) {
						var share_count = parseInt($('#share_count').text()) - 1;
						$('#share_btn').remove();
						var share_btn=$("<li id='share_btn' onclick='share("+post_id+");'><img src='"+_url+"img/icons/share_b.png'><span id='share_count'>"+share_count+"</span><span id='share_loading'></span></li>");
						$("#share_body").append(share_btn);
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
					$("#share_loading").empty();	
				}
			});
	};
 
 
 /* 
   	like function
   */
 	function paginate_share(post_id){
		$("#share_loading_"+post_id).html('<img width="24" src="'+_url+'/img/loader/metro/preloader-w8-cycle-white.gif" >');
		var post_user_id = $('#share_post_user_id_'+post_id).val();
		$.ajax({
				type:"POST",
				url:_url+'shareposts/share',
				data:'post_id='+post_id+'&post_user_id='+post_user_id,
				dataType: "json",
				success:function(response){
					// hide table row on success
					if(response.success == true) {
						var share_count = parseInt($('#share_count_'+post_id).text())+ 1;
						$('#share_btn_'+post_id).remove();
						
						/*var share_btn=$("<div onclick='paginate_unshare("+post_id+")' id='share_btn_"+post_id+"' class='tile unshare lajani'><span class='numb'><div style='float:right'><span id='share_count_"+post_id+"'>"+share_count+"</span><span id='share_loading_"+post_id+"'></span><div class='symbol'></div><input type='hidden' value='"+post_user_id+"' id='share_post_user_id_"+post_id+"></div></span></div>");*/
						
						var share_btn=$("<div onclick='paginate_unshare("+post_id+")' id='share_btn_"+post_id+"' class='tile unshare lajani'><span class='more'></span><span class='numb'><div style='float:right' id='share_body_"+post_id+"'><span id='share_count_"+post_id+"'>"+share_count+"</span><span id='share_loading_"+post_id+"'></span><div class='symbol'></div><input type='hidden' value='"+post_user_id+"' id='share_post_user_id_"+post_id+"' ></div></span></div>");
						
						$("#share_body_"+post_id).append(share_btn);
						if( response.message ) {
							 show_success_msg(response.message);
						} 
						send_privacy_email(post_user_id,'onsharing','',post_id);
					}
					else 
					 {
						if( response.message ) {
							show_error_msg(response.message);
						}  
					 }
					$("#share_loading_"+post_id).empty();	
			 		 
				}
			});
	};
 
 /* unlike */
 function paginate_unshare(post_id){
		$("#share_loading_"+post_id).html('<img width="24" src="'+_url+'/img/loader/metro/preloader-w8-cycle-white.gif" >');
		var post_user_id = $('#share_post_user_id_'+post_id).val();
		$.ajax({
				type:"POST",
				url:_url+'shareposts/unshare',
				data:'post_id='+post_id,
				dataType: "json",
				success:function(response){
					// hide table row on success
					if(response.success == true) {
						var share_count = parseInt($('#share_count_'+post_id).text()) - 1;
						$('#share_btn_'+post_id).remove();
						var share_btn=$("<div onclick='paginate_share("+post_id+")' id='share_btn_"+post_id+"' class='tile share lajani'><span class='more'></span><span class='numb'><div style='float:right' id='share_body_"+post_id+"'><span id='share_count_"+post_id+"'>"+share_count+"</span><span id='share_loading_"+post_id+"'></span><div class='symbol'></div><input type='hidden' value='"+post_user_id+"' id='share_post_user_id_"+post_id+"' ></div></span></div>");
						
						$("#share_body_"+post_id).append(share_btn);
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
					$("#share_loading_"+post_id).empty();	
				}
			});
	};
	
 /* unlike */
 function profile_unshare(post_id){
		$("#share_loading_"+post_id).html('<img src="'+_url+'/img/loader/waiting24.gif" >');
		$.ajax({
				type:"POST",
				url:_url+'shareposts/unshare',
				data:'post_id='+post_id,
				dataType: "json",
				success:function(response){
					// hide table row on success
					if(response.success == true) {
						 $("#share_body_"+post_id).parent().remove();
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
					$("#share_loading_"+post_id).empty();	
				}
			});
	};	
	
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
	$("#delete_post_loading_"+id).html('<img src="'+_url+'/img/loader/waiting24.gif" >');
	
  $.ajax({
		type: "POST",
		url: _url+'posts/post_delete',
		data: 'post_id='+id,
		dataType: "json",
		success: function(response)
		{
		 $("#delete_post_loading_"+id).empty();
		 if(response.success == true) {			
			$('#message_content').html(''); 
			$('#post_'+id).slideUp('slow').hide();
			/* delete child post*/
			var sucess_ids=[];		
					for(var i = 0; i < parent_id_arr.length ; i++){
						if(parent_id_arr[i]!=0){
							var cid=parent_id_arr[i];
							sucess_ids.push(cid);
							$("#delete_post_loading_"+cid).html('<img src="'+_url+'/img/loader/waiting24.gif" >');
							$.ajax({
								type: "POST",
								url: _url+'posts/post_delete',
								data: 'post_id='+cid,
								dataType: "json",
								success: function(response)
								{	 		
									if(response.success == true) {	
										$("#delete_post_loading_"+cid).empty();
									}
									else 
									 {
										if( response.message ) {
											//showmsg(response.message);
										}  
										$("#delete_post_loading_"+cid).empty();
									 }	
								}						
							  });
						}
					} 
				if(sucess_ids.length>0){
					for(var j = 0; j < sucess_ids.length ; j++){
						$('#post_'+sucess_ids[j]).hide();
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


function show_message_notification()
{
	//$("#new_message_loading").html('<img src="'+_url+'/img/loader/ui-anim_basic_16x16.gif" >');
	
	$.ajax({
		type: "POST",
		url: _url+'chats/new_message_info',
		data: '',
		dataType: "json",
		success: function(response)
		{	 		
			//if(response.count==0) $('#new_message_count').remove();
			//else
			$('#new_message_count').text(response.count);
			$("#new_message_loading").empty(); 
		}
	
	  });
	  show_profile_message_notification();
}

function show_profile_message_notification()
{
	$("#newprofile_message_loading").html('<img src="'+_url+'/img/loader/ui-anim_basic_16x16.gif" >');
	
	$.ajax({
		type: "POST",
		url: _url+'chats/new_message_info',
		data: '',
		dataType: "json",
		success: function(response)
		{	 		
			if(response.count==0) $('#newprofile_message_count').remove();
			else
			$('#newprofile_message_count').text(response.count);
			$("#newprofile_message_loading").empty(); 
		}
	
	  });
}

function show_follow_notification()
{
	//$("#new_follow_loading").html("<img src='"+_url+"/img/loader/metro/preloader-w8-cycle-white.gif' >");
	
	$.ajax({
		type: "POST",
		url: _url+'follows/new_follow_info',
		data: '',
		dataType: "json",
		success: function(response)
		{	 		
			//if(response.count==0) $('#new_follow_count').remove();else
			$('#new_follow_count').text(response.count);
			$("#new_follow_loading").empty(); 
		}
	
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

function refresh_new_post()
{	  		
		$.ajax({
		type: "POST",
		url: _url+'posts/refresh_new_post',
		data: '',
		cache: false,
		success: function(response)
		{
		 	var div= "<div></div>";
			$(div).prependTo($('#home_body')).html(response).fadeOut(100).fadeIn(1000).fadeOut(100).fadeIn(1000);
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
		 	var div= "<div></div>";
			$(div).prependTo($('#home_body')).html(response).fadeOut(100).fadeIn(1000).fadeOut(100).fadeIn(1000);
		}
	
	  });
	//setTimeout('refresh_new_post();',40000);
}


function show_follow_notification()
{
	//$("#new_follow_loading").html("<img  width='24' src='"+_url+"/img/loader/metro/preloader-w8-cycle-white.gif' >");
	
	$.ajax({
		type: "POST",
		url: _url+'follows/new_follow_info',
		data: '',
		dataType: "json",
		success: function(response)
		{	 		
			//if(response.count==0) $('#new_follow_count').remove();else
			$('#new_follow_count').text(response.count);
			$("#new_follow_loading").empty(); 
		}
	
	  });
}

function show_tofollow_notification()
{
	var new_tofollow_count = $("#new_tofollow_count");
		$(new_tofollow_count).show();
		//$(new_tofollow_count).html('<img width="24" src="'+_url+'/img/loader/metro/preloader-w8-cycle-white.gif" >');
	$.ajax({
		type: "POST",
		url: _url+'follows/new_tofollow_info',
		data: '',
		dataType: "json",
		success: function(response)
		{	 		
			$(new_tofollow_count).text(response.count);
		}
	
	  });
}
function show_post_count()
{
	var post_count = $("#post_count");
		$(post_count).show();
		//$(post_count).html('<img width="24" src="'+_url+'/img/loader/metro/preloader-w8-cycle-white.gif" >');
	$.ajax({
		type: "POST",
		url: _url+'posts/post_count',
		data: '',
		dataType: "json",
		success: function(response)
		{	 		
			$(post_count).text(response.count);
		}
	
	  });
}

function show_blog_count()
{
	var blog_count = $("#blog_count");
		$(blog_count).show();
		$(blog_count).html('<img width="24" src="'+_url+'/img/loader/metro/preloader-w8-cycle-white.gif" >');
	$.ajax({
		type: "POST",
		url: _url+'blogs/blog_count',
		data: '',
		dataType: "json",
		success: function(response)
		{	 		
			$(blog_count).text(response.count);
		}
	  });
}

/*
*  Follow Alert Nofification
*
*/


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
	// rand=2;
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

/* 
    load follow box
*/ 


 function follow_box()
 {
 	TINY.box.show({url:_url+'users/follow_box',
				   post:'action=load_page',
				   opacity:50,
				   closejs:function(){document.location.href = _url} ,
				   topsplit:2}
				   );
 }		


/* statusBar */
 function statusBar(colo)
 {
 	TINY.box.show({url:_url+'users/industry_box',
				   post:'action=load_page',
				   /*width:200,height:100,*/
				   opacity:50,
				   topsplit:2}
				   );
 }		

function save_industry()
{  
	$("#industry_loading").html('<img width="24" src="'+_url+'/img/loader/metro/preloader-w8-cycle-white.gif" >');
	
	var sex = $('#float_sex').val();
	var industry_id = $('#float_industry_id').val();
	var details = $('#details').val();
	var location = $('#float_location').val();
	var tag_array=[];
    $('#tags input#users_tag').each(function() {
       tag_array.push($(this).attr('title'));
    });
 
	$.ajax({
		type: "POST",
		url: _url+'users/industry_box',
		data: $("#first_industry_info").serialize()+'&sex='+sex+'&action=save'+'&industry_id='+industry_id+'&details='+details+'&location='+location+'&tags='+tag_array,
		dataType: "json",
		success: function(response)
		{
		 
		 if(response.success == true) {			
			if( response.message ) {
				show_success_msg(response.message);	
				document.location.href = _url;	
			} 	
		}
		else 
		 {
			if( response.message ) {
				show_error_msg(response.message);
			}  
		 }
		 $("#industry_loading").empty();
		 
		}
	
	  });
}

/* statusBar */

/* profile request */
function refresh_profile_post(count,id){
	$("#home_loading").html('<img src="'+_url+'/img/loader/big_loader.gif" >');

	var first =  count * 5; 
	$.ajax({
			type:"POST",
			url:_url+'posts/refresh_profile_post',
			data:'first='+first+'&id='+id,
	
			success:function(response){
				var div= "<div></div>";
				$(div).appendTo($('#home_body')).html(response).fadeOut(100).fadeIn(1000);
				$("#home_loading").empty(); 
			}
		}) ;	
} 
/*
**
*/
 function profile_follow_payee(count,id){
	//alert(count);
	var first =  count * 5;
	$("#profile_result_loading").html('<img src="'+_url+'/img/loader/big_loader.gif" >'); 
	$.ajax({
			type:"POST",
			url:_url+'users/profile_follow_payee',
			data:'first='+first+'&id='+id,
	
			success:function(response){
				 
				$('#profile_result_body').append(response);  
				$("#profile_result_loading").empty();
				
				//searchresult show follow btn
				$(".searchResult ul.result li").mouseenter(function(e) {
			        $(".btn",this).animate(
				{
					top:"5"
				},500,"easeOutElastic");
			    });
				$(".searchResult ul.result li").mouseleave(function(e) {
			        $(".btn",this).animate(
				{
					//opacity:1,
					top:"-40"
				},500,"easeOutElastic");
			    });
				  
			}
		}) ;	
		  
}
/*
**
*/
 function profile_chaser(count,id){
	//alert(count);
	var first =  count * 5;
	$("#profile_result_loading").html('<img src="'+_url+'/img/loader/big_loader.gif" >'); 
	$.ajax({
			type:"POST",
			url:_url+'users/profile_chaser',
			data:'first='+first+'&id='+id,
	
			success:function(response){
				 
				$('#profile_result_body').append(response);  
				$("#profile_result_loading").empty();
				
				//searchresult show follow btn
				$(".searchResult ul.result li").mouseenter(function(e) {
			        $(".btn",this).animate(
				{
					top:"5"
				},500,"easeOutElastic");
			    });
				$(".searchResult ul.result li").mouseleave(function(e) {
			        $(".btn",this).animate(
				{
					//opacity:1,
					top:"-40"
				},500,"easeOutElastic");
			    });
				  
			}
		}) ;	
		  
}
/*
	favorite function
*/
function paginate_favorite(post_id){
		$("#favorite_loading_"+post_id).html('<img src="'+_url+'/img/loader/ui-anim_basic_16x16.gif" >');
		$.ajax({
				type:"POST",
				url:_url+'favoriteposts/favorite',
				data:'post_id='+post_id,
				dataType: "json",
				success:function(response){
					// on success
					if(response.success == true) {
						var favorite_count = parseInt($('#favorite_count_'+post_id).text())+ 1;
						$('#favorite_btn_'+post_id).remove();					
						var favorite_btn=$("<div class='favorite tile size_2 outOfBox green' style='display: block;' onclick='paginate_unfavorite("+post_id+")' id='favorite_btn_"+post_id+"' ><span id='favorite_loading_"+post_id+"'></span><div class='symbol'></div></div>");
						
						$("#favorite_body_"+post_id).append(favorite_btn);
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
					$("#favorite_loading_"+post_id).empty();	
			 		 
				}
			});
	};
 
 /* unfavorite */
 function paginate_unfavorite(post_id){
		$("#favorite_loading_"+post_id).html('<img src="'+_url+'/img/loader/ui-anim_basic_16x16.gif" >');
		$.ajax({
				type:"POST",
				url:_url+'favoriteposts/unfavorite',
				data:'post_id='+post_id,
				dataType: "json",
				success:function(response){
					// hide table row on success
					if(response.success == true) {
						$('#favorite_btn_'+post_id).remove();
						
						var favorite_btn=$("<div class='favorite tile size_2 outOfBox atashi' style='display: block;' onclick='paginate_favorite("+post_id+")' id='favorite_btn_"+post_id+"' ><span id='favorite_loading_"+post_id+"'></span><div class='symbol'></div></div>");
						
						$("#favorite_body_"+post_id).append(favorite_btn);
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
					$("#favorite_loading_"+post_id).empty();	
				}
			});
};
	
	
function refresh_favorite(){
	$("#favorite_loading").html('<img src="'+_url+'/img/loader/big_loader.gif" >');

	var first =  count * 5; 
	$.ajax({
			type:"POST",
			url:_url+'posts/refresh_favorite',
			data:'first='+first,
	
			success:function(response){
				var div= "<div></div>";
				$(div).appendTo($('#favorite_body')).html(response).fadeOut(100).fadeIn(1000);
				$("#favorite_loading").empty();	
				$("#favorite_loading1").empty();	  
			}
		}) ;	
} 	
	
/*
*	Infractionreport_post
*/
function send_infraction_report_post_form(id) {
	
	TINY.box.show({url:_url+'infractionreportposts/send_infraction_report_post',
				   post:'action=load_page&id='+id,
				   opacity:50,
				   topsplit:2}
				   );		
} 



function refresh_post_image(id){
	$("#post_image_loading").html('<img src="'+_url+'/img/loader/big_loader.gif" >');

	var first =  count * 5; 
	$.ajax({
			type:"POST",
			url:_url+'posts/refresh_post_image',
			data:'first='+first+'&id='+id,
	
			success:function(response){
				var div= "<div></div>";
				$(div).appendTo($('#post_image_body')).html(response).fadeOut(100).fadeIn(1000);
				$("#post_image_loading").empty();	
				$("#post_image_loading1").empty();	  
			}
		}) ;	
} 	

/* show learn */
function show_home_learn()
{
    $('#HomePageTipContent').joyride({
      autoStart : true,
      timer:10000,
      startTimerOnClick:true,
      postStepCallback : function (index, tip) {
	  	console.log(index);
      if (index == 2) {
			if($("#NewPost").css("display")=="none")
			{
				fadeInNewPost();
			}
			else
			{
				fadeOutNewPost();
			}
      }
	  if (index == 6) {
			if($("#NewPost").css("display")=="none")
			{
				fadeInNewPost();
			}
			else
			{
				fadeOutNewPost();
			}
      }
      if (index == 14) 
          $(".post .outOfBox").fadeIn(400);
      if (index == 17) 
          $(".post .outOfBox").fadeOut(400);  
	  /*if (index == 18) {
	  		$.Zebra_Dialog(_are_you_have_learn_profile, {
				    'type': 'question',
				    'title': _warning,
					'modal': true ,
				    'buttons':  [
				                    {caption: _yes, callback: function() {
											document.getElementById('userbox_username').click();
										}},
									{caption: _no, callback: function() { }}
				                ]
			});	
	  }*/
    },
    modal:true,
    expose: false
    });    
}

function show_profile_learn()
{
    $('#ProfilePageTipContent').joyride({
      timer:10000,
      startTimerOnClick:true,  
      autoStart : true,
      postStepCallback : function (index, tip) {
	  	//console.log(index);
    },
    modal:true,
    expose: false
    });    
}

function show_learn(user_id,learn_object_id,parent_id,object_created,user_created)
{ 
	  $.ajax({
		type: "POST",
		url: _url+'learnobjectusers/show_learn',
		data: 'user_id='+user_id+'&learn_object_id='+learn_object_id+'&parent_id='+parent_id+'&object_created='+object_created+'&user_created='+user_created,
		dataType: "json",
		success: function(response)
		{		 
		 if(response.success == true) {	
		 			
			/* if( response.message ) {
				show_success_msg(response.message);		
			} */
			 
			if(response.show_tour == true) {
					switch(learn_object_id){
					 	case 1:
							show_home_learn();
					 		break;
						case 2:
							show_profile_learn();
					 		break;	
						/*case 3:
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
					 		break;	*/	
					 }
			}
		}
		else 
		 {
			/* if( response.message ) {
				show_error_msg(response.message);
			} */ 
		 }	 
		}
		// $('#result_windows').html(html);
	
	  });
}
/* show learn */


$(document).ready(		
	function()
	{
          $('#invitation_form').on('submit', function(e) {    

				e.preventDefault();
                $("#invitation #loading").html('<img src="'+_url+'/img/loader/ui-anim_basic_16x16.gif" >');
                $(".invitation_sms #loading").empty();
				$('#invitation_btn').attr('disabled','disabled');
				var email = $('#invitation_email').val();
				$.ajax({
						type: "POST",
						url: _url+'invitations/send_email',
						data: 'email='+email,
						dataType: "json",
						success: function(response)
						{
						 	if(response.success == true) {
								if( response.message )
								{
									show_success_msg(response.message);
								}	
							}
							else
							{
								if( response.message )
								{
									show_error_msg(response.message);
								}	
							}	
							
							$('#ajax_result').html(response);
							$('#invitation_btn').removeAttr('disabled');
							$("#invitation #loading").empty();
							$('#invitation_email').val(''); 	
						}
					
					  });
		  });
          
          $('#invitation_sms_form').on('submit', function(e) {    

				e.preventDefault();
                $(".invitation_sms #loading").html('<img src="'+_url+'/img/loader/ui-anim_basic_16x16.gif" >');
				$('#invitation_sms_btn').attr('disabled','disabled');
				var sms = $('#invitation_sms').val();
				$.ajax({
						type: "POST",
						url: _url+'invitations/send_invitation_sms',
						data: 'sms='+sms,
						dataType: "json",
						success: function(response)
						{
						 	if(response.success == true) {
								if( response.message )
								{
									show_success_msg(response.message);
								}	
							}
							else
							{
								if( response.message )
								{
									show_error_msg(response.message);
								}	
							}	
							
							$('#ajax_result').html(response);
							$('#invitation_sms_btn').removeAttr('disabled');
							$(".invitation_sms #loading").empty();
							$('#invitation_sms').val(''); 	
						}
					
					  });
		  });
	}
);				

/**

*/				
function select_newtag()
{
	 var new_tag = $("#new_tag #body");
	 $(new_tag).html('<img width="24" src="'+_url+'/img/loader/metro/preloader-w8-cycle-white.gif" >');
	  $.ajax({
		type: "POST",
		url: _url+'posts/new_tag',
		data: '',
		success: function(response)
		{	 		
			$(new_tag).html(response);
		}
	  });
}  

/*
  ads function
*/
function ads_post_form(id) {
	
	TINY.box.show({url:_url+'postads/ads_post_form',
				   post:'id='+id,
				   opacity:50,
				   topsplit:2}
			);		
} 
 
	
	
	
	
	
	
	
	
	
	
	