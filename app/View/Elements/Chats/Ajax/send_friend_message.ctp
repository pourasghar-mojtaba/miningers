<script>
_enter_text= "<?php echo __('enter_text') ?>";
_selected_max_user= "<?php echo __('selected_max_user') ?>";
</script>
<div class="bg modalCloser"></div>
<div class="dataBox modalMain col-md-6 col-md-offset-3">
<header class="modalHeader">
<div class="closer modalCloser icon-cancel"></div>
<?php echo __('send_message') ?>
</header>
 <form class="myForm">
	<section class="modalContent">
		 <div class="col-md-12" id="friends">
		   <?php
		   	 echo "
			   <span>
				".$name."
				<a   href='javascript:' title='Remove ".$name."' id='".$id."'>x</a>
				</span>
			 ";
		   ?>
	     </div>
		 <div class="col-md-12">
		   <textarea  class="myFormComponent" id='message_content' name='message_content' cols='40' rows='7'></textarea>
	     </div>
	</section>
	<footer>
	    <div class="col-sm-12">
			<span id="message_loading"></span> 
	        <button role="button" type="button" id="send_message_btn" class="myFormComponent green">
	            <span class="text"><?php echo __('send') ?></span>
	            <span class="icon icon-left-open"></span>
	        </button>
	    </div>
	</footer>
  </form>
</div>

  

<script>

	modalCloser();
	makeCenterVer($("#modal .modalMain"));

$('.myForm #send_message_btn').click(function(){
  	send_friend_message();
  });

function send_friend_message()
{  
	$("#message_loading").html('<img width="24" src="'+_url+'/img/loader/metro/preloader-w8-cycle-white.gif"" >');
	var IDs = [];
    var arr = $("#friends [id]").map(function() {
	 var filter = /^([0-9])+$/;
	 if (filter.test(this.id))  
			IDs.push(this.id); 
	});
		 
	var message = $("textarea#message_content").val();
	
	if(String.trim(message) ==''){
		show_warning_msg(_enter_text);
		$("#message_loading").empty();
		return;
	}
	
	$.ajax({
		type: "POST",
		url: _url+'chats/send_friend_message',
		data: 'id='+IDs+'&message='+message+'&action=send',
		dataType: "json",
		success: function(response)
		{
		 
		 if(response.success == true) {			
			if( response.message ) {
				show_success_msg(response.message);	
                remove_modal();	
			} 	
			send_privacy_email(IDs,'onmessage',message);
		}
		else 
		 {
			if( response.message ) {
				show_error_msg(response.message);
			}  
		 }
		 $("#message_loading").empty();
		 
		}
	
	  });
}

</script>