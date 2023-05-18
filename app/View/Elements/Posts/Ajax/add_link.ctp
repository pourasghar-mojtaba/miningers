 
<div class="myForm">
    <header class="ajaxheader">
    	<strong><?php echo __('add_link') ?></strong><br>
    </header>
	<form action="#" method="get" name="sign_form">
	<?php echo $this->Form->create('User', array('class'=>'registerForm','id'=>'addlinkForm')); ?>
	<table width="350" border="0" cellspacing="1px" cellpadding="0" >
	  <tr>
	    <td ><label> <?php echo   __('add_link') ?> </label></td>
	    <td >
		   <?php echo $this->Form->input('newpost_link',array('label'=>'','id'=>'newpost_link','size'=>40,'dir'=>'ltr','value'=>'http://')); ?>
		</td>
	  </tr>
	  <tr>
    	<td colspan="2">
        <span id="add_link_loading"></span>  
        <input type="button" value="<?php echo __('add') ?>" id="add_link_btn">
        </td>
      </tr>
	 </table> 

	</form>
</div>

<script>
	
	 $('#add_link_btn').click(function(){
	  	add_link();
	  });
	
	function add_link(){
	
	var count = 0;
	var arr = $("#newpost_link_attachment").map(function() {
		  count+=1;
	  });
	if(count>=1){
		show_warning_msg(_existlink);return;
	}
	var url = $('#newpost_link').val();
	if(url!='')
	{
		var attach="<div class='attachment' id='newpost_link_attachment'><input type='hidden' name=data[Post][newpost_link] id='newpost_link' value='"+url+"' ><div class='close closethis'></div>"+_link_added+"</div>";
	$('#AddPostForm').append(attach);
	}
	
	
	$(".closethis").click(function(e) {
        $(this).parent().fadeOut(200);
		$(this).parent().remove();
    });
	TINY.box.hide();
  }
</script>

 