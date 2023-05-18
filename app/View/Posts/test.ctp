<table>
  <tr>
  	<td>email: <input type="text" id="login_email" /></td>
	<td>pass: <input type="password" id="login_password" /></td>
	<td><input type="button" value="sign in" onclick="signin()" /></td>
  </tr>
  <tr><td colspan="3"> <div id='login_body'></div>  </td></tr>
</table>
<script>
	function signin()
	{
		var email = $('#login_email').val();
		var password=$('#login_password').val();
		$.ajax({
			type:"POST",
			url:_url+'users/app_login.json',
			data:'email='+email+'&password='+password,
			dataType: "json",
			success:function(response){
				$('#login_body').text(response.message);
			}
		});
	}
</script>
<br>