
<div class="LoginBox Register">
	<div class="titr">
	    <span>  <?php echo __('join_to_madaner'); ?></span>
	</div>
	<div class="main">
	    <form id='UserRegisterForm' name='UserRegisterForm' method="POST" action="">
	        <table border="0" cellpadding="2">
	            <tr>
	            	<td></td>
	                <td>
						<?php echo $this->Form->input('name',array('label'=>'','placeholder'=>__('name'),'id'=>'register_name','class'=>'validate[required] text-input')); ?>
					</td>
	            	<td></td>
	            </tr>
	            <tr>
	            	<td></td>
	                <td>
						<?php echo $this->Form->input('user_name',array('label'=>'','placeholder'=>__('user_name'),'id'=>'user_name','class'=>'validate[required] text-input')); ?>
					</td>
	            	<td></td>
	            </tr>
	            <tr>
	            	<td></td>
	                <td>
						<?php echo $this->Form->input('register_email',array('label'=>'','placeholder'=>__('email'),'id'=>'register_email','class'=>'validate[required,custom[email]] text-input')); ?>
					</td>
	            	<td></td>
	            </tr>
				<tr>
	            	<td></td>
	                <td>
						<?php echo $this->Form->input('password',array('label'=>'','placeholder'=>__('password'),'type'=>'password','id'=>'password','class'=>'validate[required] text-input')); ?> 
					</td>
	            	<td></td>
	            </tr>
	            <tr>
	            	<td></td>
	                <td>
						<?php echo $this->Form->input('confirm_password',array('label'=>'','placeholder'=>__('confirm_password'),'type'=>'password','id'=>'confirm_password','class'=>'validate[required,equals[password]] text-input')); ?>
					</td>
	            	<td></td>
	            </tr>
				<tr>
	            	<td></td>
	                <td>
						<input type="submit" class="login" value="<?php echo __('register'); ?>" />
						<input type="button" class="cancel" id="register_close" value="<?php echo __('close'); ?>" />
					</td>
	            	<td></td>
	            </tr>
	        </table>
	        
	    </form>
	</div>
	</div>        	
            
  <script>
  
    
	
	$('#user_name').keypress(function(e){
		
		if(e.which===32){
			return false;
		}
	})
	
	
	$('#register_close').click(function(e){
		 $('.register').remove();
		 $('#back_div').remove();
	})
	
	$("form#UserRegisterForm").submit(function(e) {
	  e.preventDefault();

		var name = $('#register_name').val();
		var user_name = $('#user_name').val();
		var register_email = $('#register_email').val();
		var password=$('#password').val();
		var confirm_password=$('#confirm_password').val();
		
		
		
		if (check_field()) {
			
		
		$('.ajax_loader').show();
		 	  
	      $.ajax({
				type:"POST",
				url:_url+'users/ajax_register',
				data:"name="+name+'&register_email='+register_email+'&password='+password+'&user_name='+user_name+'&confirm_password='+confirm_password ,
				dataType: "json",
				success:function(response){
					
					if(response.success == true) {
						if( response.message )
						{
							var success =generate('success',response.message,'top');
							setTimeout(function() {
						      $.noty.close(success.options.id);
						    }, 3000);
							$('.register').remove();
		 					$('#back_div').remove();
						}
						$('#ajax_result').html(response);
					}
					else 
					 {
						if( response.message )
						  {
						  	var error =generate('error',response.message,'topRight');
							setTimeout(function() {
						      $.noty.close(error.options.id);
						    }, 5000);
						  }
					 }
					 				
				}
			});
		  
		} 
		
		return false;
	});
	
	
	function check_email(){
 	 
	var email = $('#register_email').val();
	 if(email==''){
		return false;
	}  
 	$.ajax({
			type:"POST",
			url:_url+'users/check_email',
			data:'email='+email,
			dataType: "json",
			success:function(response){
				// hide table row on success
				if(response.success == true) {
					if( response.message )
					  //jQuery('#register_email').validationEngine('showPrompt',response.message, 'pass');
					  var success =generate('success',response.message,'topRight');
						setTimeout(function() {
					      $.noty.close(success.options.id);
					    }, 5000);
					error=true;	
				}
				else 
				 {
					if( response.message )
					 // jQuery('#register_email').validationEngine('showPrompt',response.message,false,'register_email');
					 var error =generate('error',response.message,'topRight');
						setTimeout(function() {
					      $.noty.close(error.options.id);
					    }, 5000);
					error=false; 
				 }
				 					
				// show respsonse message
				if( response.message ) {
					//$('#register_email_message').html( response.message ).show(); 
				} 
			}
		});
	 
	 	
 }
	
	
	 $('#register_email').focusout(function(){
		check_email();	
	 });
</script>