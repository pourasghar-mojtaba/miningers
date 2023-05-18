<div class="bg modalCloser"></div>
<div class="dataBox modalMain col-md-6 col-md-offset-3">
<header class="modalHeader">
<div class="closer modalCloser icon-cancel"></div>
<?php echo __('forget_password') ?>
</header>
 <form class="myForm">
	<section class="modalContent">
		 <div class="col-md-12">
	       <input type="text" required="" placeholder="<?php echo __('enter_a_email') ?>" name="data[User][fgemail]" 
		   id="fgemail" class="myFormComponent" dir="ltr">
	     </div>
	</section>
	<footer>
	    <div class="col-sm-12">
	        <button role="button" type="button" id="save_forget_pass" class="myFormComponent green">
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
	
  $('.myForm #save_forget_pass').click(function(){
  	send_email();
  });
  
  function send_email(){
 	var fgemail = $('#fgemail').val();
	if(fgemail==''){
		show_warning_msg('<?php echo __("enter_a_email") ?>');
		return false;
	} 
	$("#forget_pass_loading").html('<img width="24" src="'+_url+'/img/loader/metro/preloader-w8-cycle-white.gif" >');	
	$.ajax({
			type: "POST",
			url: _url+'users/forget_pass',
			data: 'fgemail='+fgemail+'&action=send',
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
              
             remove_modal();
			 //showmsg(html);
			 $('#ajax_result').html(response);
             $("#forget_pass_loading").empty();
			}
		
		  });
			
 }
	 
</script>
 