<?php
	
	if($locale =='per'){
		$default_back = __SITE_URL."img/add_user_article.jpg";
	}
	else $default_back = __SITE_URL."img/blog_add_image.png";
  	$check_image = "";
	
	
	if(fileExistsInPath(__USER_BLOG_PATH.$blog['Blog']['image'] ) && $blog['Blog']['image']!='' ) {
	$backimg =__SITE_URL.__USER_BLOG_PATH.$blog['Blog']['image']; 
	$check_image =$backimg;
	}
	else{
		 $backimg = $default_back ;  
		  
		}
	
  if(!empty($blog['Blog']['id'])){
  	$action = '/edit_blog_save/'.$blog['Blog']['id'];
  }else	$action = '/add_blog_save/';	
  
  if(!empty($blog['Blog']['id'])){
  	$blog_id = $blog['Blog']['id'];
  }else $blog_id=0;
 
?>


<div class="closer modalCloser icon-cancel"  style="cursor:pointer;<?php if(empty($blog["Blog"]["image"] ) )  echo "display:none";?>"></div>
<div class="image-editor">
    
    	 <div class="cropit-image-preview-container">
    		<div class="cropit-image-preview"  >		
    		</div>
    	 </div>
    	<div id="zoom_tools"  class="col-md-12"  > 
            <?php  echo $this->Form->create('Blog', array('id'=>'BlogForm','name'=>'BlogForm','enctype'=>'multipart/form-data','action'=>$action,'class'=>'myForm'));  ?>
			
			<input type="hidden" id="set_publish" name="data[Blog][set_publish]" value="0"/>	
			
    		<span id="zoom_range" style='display:none' <?php /*if(empty($blog["Blog"]["image_zoom"] ) )  echo "style='display:none'";*/ ?> >
				<label><?php echo __('zoom') ?>:</label> 
	    		<input type="range" class="cropit-image-zoom-input" >
			</span>
			<!--
			<span class="icon icon-camera"  style="margin-top: 4px;"></span>
            <span class="icon icon-ok" id="ok_cover_crop" style="cursor:pointer;"></span>
			-->
			<span id="cover_loading"></span>
    		
			
    		<!--<span class="icon icon-reply" id="cancle_cover_crop" style="cursor:pointer;"></span> -->
    		<input type="hidden" id="old_image" value="<?php echo $backimg; ?>"/>		   
    		<input type="hidden" id="check_image" value="<?php echo $check_image; ?>"/>		   
    		<input type="hidden" id="blog_id" value="<?php echo $blog_id; ?>"/>		   
    			   
    		<input type="hidden" id="image_x" value="<?php echo $blog['Blog']['image_x']; ?>" name="data[Blog][image_x]"/>		   
    		<input type="hidden" id="image_y" value="<?php echo $blog['Blog']['image_y']; ?>" name="data[Blog][image_y]"/>		   
    		<input type="hidden" id="image_zoom" value="<?php echo $blog['Blog']['image_zoom']; ?>" name="data[Blog][image_zoom]"/>		   
    		<input type="hidden" id="blog_body" value="<?php echo $blog['Blog']['body']; ?>" name="data[Blog][body]"/>		   
            <div id="blog_result" style="float:right;"></div>	    
    	    <input type="file" class="cropit-image-input" style="display:block" id="blog_image" name="data[Blog][image]">
			<div class="col-md-12"  > 
				<input type="text" placeholder="<?php echo __('blog_title') ?>" class="myFormComponent notTrans" style="border: 1px solid #cfcfcf" value="<?php if(!empty($blog["Blog"]["title"] ) )  echo $blog["Blog"]["title"] ;?>" id="title" name="data[Blog][title]">
			</div>
			<div class="col-md-12"  > 
				<textarea  id="blog_content"  class="myFormComponent notTrans fixHeight" rows="20" maxlength="500">				
					<?php if(!empty($blog["Blog"]["body"] ) )  echo $this->Gilace->convert_character_editor($blog["Blog"]["body"]) ;?>
				</textarea>
			</div>
			<div class="col-md-12">
				<?php
					//print_r($cur_channels);
					$channel_arr = array();
					if(!empty($cur_channels)){
						foreach($cur_channels as $cur_channel){
							$channel_arr[] = $cur_channel['Channel']['id'];
						}
					}
					 
					if(!empty($channels)){
						echo "<select name='data[Blog][channel][]' multiple='multiple' placeholder='".__('select_max_3_channel')."' class='SlectBox'>";
						foreach($channels as $channel){
							if(in_array($channel['Channel']['id'],$channel_arr)){
								echo "<option selected value='".$channel['Channel']['id']."'>".$channel['Channel']['title']."</option>";
							}else
							echo "<option value='".$channel['Channel']['id']."'>".$channel['Channel']['title']."</option>";
						}
						echo " </select>";
					}
				?>
				<!--<select multiple="multiple" placeholder="Hello  im from placeholder" class="SlectBox">
			       <option selected value="volvo">Volvo</option>
			       <option value="saab">Saab</option>
			       <option disabled="disabled" value="mercedes">Mercedes</option>
			       <option value="audi">Audi</option>
			       <option value="bmw">BMW</option>
			       <option value="bmw">BMW</option>
			       <option value="bmw">BMW</option>
           		 </select>-->
			</div>
			
			
            </form> 
        </div>
		<div class="clear"></div>
		<br>
		<footer>
				
                <button role="button" style="float: right;" type="button" id="blog_publish_btn" class="myFormComponent blue">
                    <?php if(empty($blog_id)){ ?>
					<span class="text"> <?php echo __('published'); ?></span>
					<?php }else{ ?>
					<span class="text"> <?php echo __('Updates'); ?></span>
					<?php } ?>
                </button>
				
				<?php if(empty($blog_id)){ ?>
                <button role="button" style="float: right;margin-right: 10px" id="blog_save_btn" type="button" class="myFormComponent green">
                    <span class="text"> <?php echo __('save'); ?></span>
                </button>
				<?php } ?>
				
				
				<?php if($blog_id!=0) { ?>
                <button role="button" style="float: right;margin-right: 10px" id="blog_delete_btn" type="button" class="myFormComponent red">
                    <span class="text"> <?php echo __('delete'); ?></span>
                </button>
				<?php } ?>
                  
        </footer>
		<br>
		<div class="clear"></div>
        <br>
 </div>
 
	


<div class="clear"></div>


<script>
	  var blog_id = $("#blog_id").val();
	  window.asd = $('.SlectBox').SumoSelect({ csvDispCount: 3,triggerChangeCombined: true });
		
	  var nicE = new nicEditor({buttonList : ['fontSize','bold','italic','underline','strikeThrough','link','unlink','html','image','left','center','right','justify','ol','ul','hr'],iconsPath : _url+'js/niceditor/nicEditorIcons.gif'}).panelInstance('blog_content');	
		
	 
	 $('#change_cover_btn').click(function(){
		 $('.cropit-image-input').trigger('click');		 
	 })	;
	 
	 $('.cropit-image-input').change(function(){
	 	//$('#zoom_range').show();
	 	$('.closer').show();
	 });
	 
	 
	 if(blog_id==0){
	 	$('.closer').click(function(){ 	
			$(".cropit-image-input").val("");
			$('.cropit-image-preview').css("background-image", ""); 
			$('.cropit-image-preview').css("background-image", "url("+$('#old_image').val()+")");
			$('#zoom_range').fadeOut();
		 	$('.closer').fadeOut();		
		 })	;
	 }
	 
	if(blog_id!=0){
		$('.closer').click(function(){ 			
			delete_image_confirm(blog_id);
				
		 })	;
	}	 
	 
	 //ok_cover_crop
	 $('#cancle_cover_crop').click(function(){
	 	$('#zoom_tools').hide();
		 
		$('.cropit-image-preview').css("background-image", ""); 
		//$('.cropit-image-preview').css("background-image", "url("+$('#old_image').val()+")");
		//$('.image-editor').cropit({ imageState: { src:  $('#old_image').val() } });
		$('.image-editor').cropit({
          exportZoom: 0.1,      
          imageState: {
            src: $('#old_image').val() /*,
			zoom:0,
			offset:{x:0,y:0} */
          }
        });	
	 });
     $('#blog_save_btn').click(function(){
        $('#BlogForm').submit(); 
     });
	 
	 var error_status = 0;
	 var counter=0;
	 
	 function check_value(){
	 	if(blog_id==0){
			if($(".cropit-image-input").val()==""){
				show_warning_msg("<?php echo __('set_image_to_blog') ?>");	
				error_status = 1;		
				return error_status;
			}
		}else{
			if($('#check_image').val()==""){
				if($(".cropit-image-input").val()==""){
					show_warning_msg("<?php echo __('set_image_to_blog') ?>");	
					error_status = 1;		
					return error_status;
				}
			}
		}
		
		
		if($.trim($("#title").val())==""){
			show_warning_msg("<?php echo __('enter_title_for_blog') ?>");	
			error_status = 1;		
			return error_status;
		}
		
		var nicInstance = nicEditors.findEditor('blog_content');
		var blog_content = nicInstance.getContent();
		if($.trim(blog_content).length<100){
			show_warning_msg("<?php echo __('enter_body_for_blog') ?>");	
			error_status = 1;		
			return error_status;
		}
		
		counter = 0;
		
		$( "select option:selected" ).each(function() {
			counter+=1;
		});
		if(counter>3){
			show_warning_msg("<?php echo __('select_max_3_channel') ?>");	
			error_status = 1;		
			return error_status;
		}
		if(counter==0){
			show_warning_msg("<?php echo __('select_channel') ?>");	
			error_status = 1;		
			return error_status;
		}
		
		error_status = 0;
		return error_status;
	 }
	 
	 	
     $('#BlogForm').on('submit', function(e) {                         
		e.preventDefault();
        var offset = $('.image-editor').cropit('offset');
        $('#image_x').val(offset['x']);
        $('#image_y').val(offset['y']);
        $('#image_zoom').val($('.image-editor').cropit('zoom'));
       // $("#cover_loading").html('<img width="24" src="'+_url+'/img/loader/5.gif" >');
		
		
		var nicInstance = nicEditors.findEditor('blog_content');
		var blog_content = nicInstance.getContent();
		$('#blog_body').val(blog_content);
		
		
		//e.preventDefault();
		error_status = check_value();
		 
		
		if(error_status==0){
			$('body').prepend("<div id='modal'></div>");
			$("#modal").html('<div class="loadingPage"><div class="loaderCycle"></div><span>'+_loading+'</span></div>' );
	        $(this).ajaxSubmit({
	            target: '#blog_result'/*,
	            success:  afterBlogSuccess , //call function after success
				error  :  afterBlogError*/
	        });
		}
		
    });
	 
        $('.image-editor').cropit({
          exportZoom: 0.1,      
          imageState: {
            src: $('#old_image').val() ,
			zoom:'<?php echo $blog["Blog"]["image_zoom"]; ?>',
			offset:{x:'<?php echo $blog["Blog"]["image_x"]; ?>',y:'<?php echo $blog["Blog"]["image_y"]; ?>'} 
          }
        });
	 	
function delete_image_confirm(id) {
	$.Zebra_Dialog("<?php echo __('are_you_sure_delete_image') ?>", {
    'type':     'warning',
    'title':    _warning,
	'modal': true ,
    'buttons':  [
                    {caption: _yes, callback: function() {delete_image(id);}},
					{caption: _no, callback: function() { }}
                ]
	});						
}
function delete_image(id)
{
  $.ajax({
		type: "POST",
		url: _url+'blogs/delete_image',
		data: 'blog_id='+id,
		dataType: "json",
		success: function(response){
		if(response.success == true) {	
			$(".cropit-image-input").val("");
			$('.cropit-image-preview').css("background-image", ""); 
			$('.cropit-image-preview').css("background-image", "url(<?php echo $default_back; ?>)");
			$('.cropit-image-zoom-input').val(0);
			$('#zoom_range').fadeOut();
		 	$('.closer').fadeOut();	
			$('#blog_thumb_image_'+id).attr("src", _url+'img/new_blog.png');
			$('#check_image').val("");	
			refresh_last_blog(0);		
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

$('#blog_delete_btn').click(function(){
	delete_blog_confirm(blog_id);
});

function delete_blog_confirm(id) {
	
	$.Zebra_Dialog("<?php echo __('are_you_sure_delete_blog') ?>", {
    'type':     'warning',
    'title':    _warning,
	'modal': true ,
    'buttons':  [
                    {caption: _yes, callback: function() {delete_blog(id);}},
					{caption: _no, callback: function() { }}
                ]
	});						
}
function delete_blog(id)
{
  $.ajax({
		type: "POST",
		url: _url+'blogs/delete_blog',
		data: 'blog_id='+id,
		dataType: "json",
		success: function(response){
		if(response.success == true) {	
			load_blog(0);	
			load_blog_tab(0);	
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

$('#blog_publish_btn').click(function(e){
	
	if(check_value()==0){
		$.Zebra_Dialog("<?php echo __('are_you_sure_publish_blog') ?>", {
	    'type':     'warning',
	    'title':    _warning,
		'modal': true ,
		//'auto_close': 1000,
	    'buttons':  [
	               {caption: _yes, callback: function() {$('#set_publish').val(1);e.preventDefault();$('#BlogForm').submit(); }},
				    {caption: _no, callback: function() { }}
	                ]
		});
	}
	
	
	
	
	/*
	$('body').prepend("<div id='modal'></div>");
	$("#modal").html('<div class="loadingPage"><div class="loaderCycle"></div><span>'+_loading+'</span></div>' );
	
	$.ajax({
		type: "POST",
		url: _url+'blogs/publish_blog',
		data: 'blog_id='+blog_id,
		dataType: "json",
		success: function(response){
		if(response.success == true) {	
			if( response.message ) {
				show_success_msg(response.message);
				$('#lastblog_body').html("");
				refresh_last_blog(0);
			}	
		}
		else 
		 {
			if( response.message ) {
				show_error_msg(response.message);
			}  
		 }
		 remove_modal();
		} 
		
	});	*/
})

function clear_box(){
	 $('#zoom_range').fadeOut();
	 $('.closer').fadeOut();
	 $('#title').val('');
}
	  
	  
	  	 
</script>

