 
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
        <span id="add_comment_link_loading"></span>  
        <input type="button" value="<?php echo __('add') ?>" id="add_comment_link_btn">
        </td>
      </tr>
	 </table> 

	</form>
</div>

<script>
	
	 $('#add_comment_link_btn').click(function(){
	  	add_comment_link();
	  });
	  
	  
	function add_comment_link(){
		
		var count = 0;
		var arr = $("#newcomment_link_attachment_"+<?php echo $_REQUEST['post_id'] ?>).map(function() {
			  count+=1;
		  });
		if(count>=1){
			show_warning_msg(_existlink);return;
		}
		var url = $('#newpost_link').val();
		if(url!='')
		{
			var attach="<div class='attachment' id='newcomment_link_attachment_"+<?php echo $_REQUEST['post_id'] ?>+"'><input type='hidden' name=data[Post][newcomment_link] id='newcomment_link_'"+<?php echo $_REQUEST['post_id'] ?>+" value='"+url+"' ><div class='close closethis'></div>"+_link_added+"</div>";
		$('#attachment_place_'+<?php echo $_REQUEST['post_id'] ?>).append(attach);
		}
		
		
		$(".closethis").click(function(e) {
	        $(this).parent().fadeOut(200);
			$(this).parent().remove();
	    });
		
		TINY.box.hide();
	}  
	
</script>

 