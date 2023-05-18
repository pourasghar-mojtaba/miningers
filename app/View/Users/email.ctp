<div class="users form">
	<?php echo $this->AlaxosForm->create('User');?>
 	
 	<table border="0" cellpadding="5" cellspacing="0" class="edit">
	<tr><td>&nbsp;</td></tr>
	<tr>
		<td>
			<?php echo ___('email') ?>
		</td>
		<td>:</td>
		<td>
			<?php echo $this->AlaxosForm->input('test_email', array('id'=>'test_email','label' => false)); ?>
		</td>
	</tr>

	<tr>
 		<td></td>
 		<td></td>
 		<td>
			<?php echo $this->AlaxosForm->end(___('submit')); ?> 		
			<input type="button" id="submit_login" value="send ajax"/>
			<div id="register_ajax_msg"></div>
		</td>
 	</tr>
	</table>
	
</div>

<script type="text/javascript">
	$(document).ready(		
		function()
		{
			
			$("#submit_login").click(function() {

				var email = $('#test_email').val();
					
					$.ajax({
						type: "POST",
						url: _url+'users/email',
						data: 'email='+email,
						cache: false,
						success: function(html)
						{
						 $('#ajax_result').html(html);
						}
					
					  });
		  
		 
		
		return false;
	});
			
			
		});
		
		
    </script>
