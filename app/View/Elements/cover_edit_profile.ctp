<?php
	echo $this->Html->script('jquery.cropit');
    echo $this->Html->script('jquery.form');
  	
	if(fileExistsInPath(__USER_IMAGE_PATH.$user['User']['cover_image'] ) && $user['User']['cover_image']!='' ) {
	$backimg =__SITE_URL.__USER_IMAGE_PATH.$user['User']['cover_image']; 
	}
	else{
		if($user['User']['sex']==0)
		 $backimg = __SITE_URL."img/cover_women.jpg" ; 
		 else $backimg = __SITE_URL."img/cover_men.jpg" ; 
		}
	
     
?>

    <div class="image-editor">
    
    	 <div class="cropit-image-preview-container">
    		<div class="cropit-image-preview"  >		
    		</div>
    	 </div>
    	<div id="zoom_tools"  class="col-md-12"  > 
            <?php  echo $this->Form->create('User', array('id'=>'ChangeImage','name'=>'ChangeImage','enctype'=>'multipart/form-data','action'=>'/edit_cover_image'));  ?>
    		<label><?php echo __('zoom') ?>:</label> 
    		<input type="range" class="cropit-image-zoom-input" >
            <span id="cover_loading"></span>
    		<span class="icon icon-ok" id="ok_cover_crop" style="cursor:pointer;"></span>
    		<!--<span class="icon icon-reply" id="cancle_cover_crop" style="cursor:pointer;"></span> -->
    		<input type="hidden" id="old_cover_image" value="<?php echo $backimg; ?>"/>		   
    		<input type="hidden" id="cover_x" value="<?php echo $user['User']['cover_x']; ?>" name="data[User][cover_x]"/>		   
    		<input type="hidden" id="cover_y" value="<?php echo $user['User']['cover_y']; ?>" name="data[User][cover_y]"/>		   
    		<input type="hidden" id="cover_zoom" value="<?php echo $user['User']['cover_zoom']; ?>" name="data[User][cover_zoom]"/>		   
            <div id="post_result" style="float:right"></div>	    
    	    <input type="file" class="cropit-image-input" style="display:none" name="data[User][cover_image]">
            </form> 
        </div>
		
        
    </div>
 
	
<div class="col-md-12">
	<div role="menu" class="edit_image_highlight dropdown">
		<div class="dropdownBtn">
		    <span class="icon icon-camera"  style="margin-top: 4px;"></span>
		</div>
		<ul>
	    	<li>
				<span id="change_cover_btn"><?php echo __('upload_new_image') ?></span>
			</li>
			<li><a class="delete_cover_image" href="javascript:void(0)"><?php echo __('delete_image') ?></a></li> 
	    </ul>
	</div>
</div>

<script>
	
	 $('#change_cover_btn').click(function(){
		 $('.cropit-image-input').trigger('click');		 
	 })	;
	 
	 $('.cropit-image-input').change(function(){
	 	$('#zoom_tools').show();
	 });
	 //ok_cover_crop
	 $('#cancle_cover_crop').click(function(){
	 	$('#zoom_tools').hide();
		 
		$('.cropit-image-preview').css("background-image", ""); 
		//$('.cropit-image-preview').css("background-image", "url("+$('#old_cover_image').val()+")");
		//$('.image-editor').cropit({ imageState: { src:  $('#old_cover_image').val() } });
		$('.image-editor').cropit({
          exportZoom: 0.1,      
          imageState: {
            src: $('#old_cover_image').val() /*,
			zoom:0,
			offset:{x:0,y:0} */
          }
        });	
	 });
     $('#ok_cover_crop').click(function(){
        $('#ChangeImage').submit(); 
     })	
     $('#ChangeImage').on('submit', function(e) {                         
		e.preventDefault();
        var offset = $('.image-editor').cropit('offset');
        $('#cover_x').val(offset['x']);
        $('#cover_y').val(offset['y']);
        $('#cover_zoom').val($('.image-editor').cropit('zoom'));
        $("#cover_loading").html('<img width="24" src="'+_url+'/img/loader/5.gif" >');
		
        $(this).ajaxSubmit({
            target: '#post_result',
            success:  afterUserSuccess , //call function after success
			error  :  afterUserError
        });
    });
		
	function afterUserSuccess()  {
       // $('#zoom_tools').hide();
        //$('#ChangeImage').resetForm();  // reset form
		$("#cover_loading").empty();			
    }
	  
  	function afterUserError()  {
		$('#ChangeImage').resetForm();
		show_error_msg(_save_image_notsuccess);
  	}
	 	
	 
	 
       
        $('.image-editor').cropit({
          exportZoom: 0.1,      
          imageState: {
            src: $('#old_cover_image').val() ,
			zoom:'<?php echo $user["User"]["cover_zoom"]; ?>',
			offset:{x:'<?php echo $user["User"]["cover_x"]; ?>',y:'<?php echo $user["User"]["cover_y"]; ?>'} 
          }
        });	
		
      
	 
	    
	   
    </script>