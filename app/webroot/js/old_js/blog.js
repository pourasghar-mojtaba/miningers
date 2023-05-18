// JavaScript Document

/*
*   New post
*/
jQuery(document).ready(function(){

	/*$("#add_blog").click(function(){
		add_blog();
	}); */
	
	$('#attach_file').click(function(){
		 $('#blog_file').trigger('click');
	});	
	
	
	$('#blog_file').change(function(){
			
			var count = 0;
			var arr = $("#new_attachment").map(function() {
				  count+=1;
			  });
			if(count>=1){
				return;
			}
			
			var attach="<div class='attachment' id='new_attachment'><div class='close closethis'></div>"+_file_added+"</div>";
			$('#blog_attach_place').append(attach);
			$(".closethis").click(function(e) {
		       $(this).parent().remove();
			   $('#blog_file').attr({ value: '' }); 
		    });
		});
	
 });
	
 /*
   add blog
*/ 
 function add_blog()
 {
 	$("#add_blog_loading").html('<img src="'+_url+'/img/loader/3.gif" >');
	$("#add_blog").attr('disabled','disabled');
	var blog_text = $('#blog_text').val();
	$.ajax({
			type:"POST",
			url:_url+'blogs/add_blog',
			data:'blog_text='+blog_text,
			dataType: "json",
			success:function(response){
				//   on success
				if(response.success == true) {
			
					if( response.message ) {
						 show_success_msg(response.message);					
					} 	
					$('#blog_text').val('');
				}
				else 
				 {
					if( response.message ) {
						show_error_msg(response.message);
					}  
				 }
				$("#add_blog_loading").empty();
				$("#add_blog").removeAttr('disabled');
			}
		});
 }
 
 function refresh_blog(count){
	$("#blog_loading").html('<img src="'+_url+'/img/loader/big_loader.gif" >');
	var first =  count * 5; 
	$.ajax({
			type:"POST",
			url:_url+'blogs/refresh_blog',
			data:'first='+first,
	
			success:function(response){
				$('#blog_body').append(response);  
				$("#blog_loading").empty();	  
			}
		}) ;	
} 

function refresh_tag(count,tag_id){
	$("#blog_tagloading").html('<img src="'+_url+'/img/loader/big_loader.gif" >');
	var first =  count * 5; 
	$.ajax({
			type:"POST",
			url:_url+'blogs/refresh_tag',
			data:'first='+first+'&tag_id='+tag_id,
	
			success:function(response){
				$('#blog_tagbody').append(response);  
				$("#blog_tagloading").empty();	  
			}
		}) ;	
} 

function add_search(count,year,month,writer,search_text,tag){
	$("#blog_searchloading").html('<img src="'+_url+'/img/loader/big_loader.gif" >');
	var first =  count * 5; 
	$.ajax({
			type:"POST",
			url:_url+'blogs/add_search',
			data:'first='+first+'&year='+year+'&month='+month+'&writer='+writer+'&search_text='+search_text+'&tag='+tag,
	
			success:function(response){
				$('#blog_searchbody').append(response);  
				$("#blog_searchloading").empty();	  
			}
		}) ;	
} 


