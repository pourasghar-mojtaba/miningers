// JavaScript Document

/*
*   New post
*/
jQuery(document).ready(function(){

	 /*
	 	change cover image
	 */
	 $('#ChangeCoverImage').on('submit', function(e) {
                e.preventDefault();
                $("#cover_image_loading").html('<img src="'+_url+'/img/loader/328.gif" >');
                $(this).ajaxSubmit({
                    target: '#ajax_result',
                    success:  afterCoverImageSuccess , //call function after success
					error  :  afterCoverImageError
                });
            });
			
			function afterCoverImageSuccess()  {
	             
				$('#ChangeCoverImage').resetForm();  // reset form
				$("#cover_image_loading").empty();
				
			    $('#cover_image').val('');
			    //show_success_msg(_save_cover_image_success);	
       	  }
		  
		  function afterCoverImageError()  {
			//showmsg(_save_cover_image_notsuccess);
            $("#cover_image_loading").empty();
       	  }
	 /*
	 	end change cover image
	 */
	 
	 /*
	 	change image
	 */
	 $('#ChangeImage').on('submit', function(e) {
                e.preventDefault();
                $("#image_loading").html('<img src="'+_url+'/img/loader/328.gif" >');
                $(this).ajaxSubmit({
                    target: '#ajax_result',
                    success:  afterImageSuccess , //call function after success
					error  :  afterImageError
                    
                });
            });
			
		function afterImageSuccess()  {
             
			$('#ChangeImage').resetForm();  // reset form
			$("#image_loading").empty();
			
		    $('#image').val('');
		    //show_success_msg(_save_image_success);	
   	   }
	  
	  function afterImageError()  {
		//showmsg(_save_image_notsuccess);
        $("#image_loading").empty();
   	  }
	 /*
	 	end change image
	 */
	 
	$("#single").change(function(){
		$('tr#sex_box').show();
	}); 
	
	$("#company").change(function(){
		$('tr#sex_box').hide();
	}); 
 		
		
 });
	
	
 
 /*
   follow and not_follow
*/ 
 function profile_follow(id)
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
						var not_follow_btn=$("<div class='btn green' onclick='profile_not_follow("+id+");' id='not_follow_btn_"+id+"'><span class=''>"+_not_follow+"</span></div>");
					    $("#follow_btns").append(not_follow_btn);
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
 
 function profile_not_follow(id)
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
						var follow_btn=$("<div class='btn blue' onclick='profile_follow("+id+");' id='follow_btn_"+id+"'><span class=''>"+_follow+"</span></div>");
					    $("#follow_btns").append(follow_btn);
						 
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
   block user
*/ 
 
  function block_user(id)
 {
 	$("#block_loading").html('<img src="'+_url+'/img/loader/328.gif" >');
	$.ajax({
			type:"POST",
			url:_url+'blockusers/block',
			data:'id='+id,
			dataType: "json",
			success:function(response){
				// hide table row on success
				
				$("#block_loading").empty();
				
				if(response.success == true) {
						
					$('#block_btn').text(_unblock);
					$('#block_btn').removeAttr("onclick");
					
					$('#block_btn').unbind();
					$('#block_btn').bind('click',function()
					{
						unblock_user(id);
					});
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
			}
		});
 }
 
 function unblock_user(id)
 {
 	$("#block_loading").html('<img src="'+_url+'/img/loader/328.gif" >');
	$.ajax({
			type:"POST",
			url:_url+'blockusers/unblock',
			data:'id='+id,
			dataType: "json",
			success:function(response){
				// hide table row on success
				
				$("#block_loading").empty();
				
				if(response.success == true) {
						
					$('#block_btn').text(_block);
					$('#block_btn').removeAttr("onclick");
					
					$('#block_btn').unbind();
					$('#block_btn').bind('click',function()
					{
						 block_user(id);
					});
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
			}
		});
 }
 /* profile request */
function refresh_profile_post(count,id){
	$("#home_loading").html('<img src="'+_url+'/img/loader/big_loader.gif" >');

	var first =  count * 10; 
	$.ajax({
			type:"POST",
			url:_url+'posts/refresh_profile_post/'+id,
			data:'first='+first,
	
			success:function(response){
				$('#home_body').append(response);
				$("#home_loading").empty(); 
				
				if($.trim($('#home_body').text())==''){
					$('#new_post_profile').show();
				}
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
			}
		}) ;	
		  
}
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 