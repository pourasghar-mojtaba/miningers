
 <?php
  $User_Info= $this->Session->read('User_Info');
	echo $this->Html->script('jquery.form');
 ?>
 
 
 		<div class="post postReply" style="display:none" id="replay_post_<?php echo $post['Post']['id']; ?>">
            <?php echo $this->Form->create('Post', array('id'=>'AddCommentForm_'.$post['Post']['id'],'name'=>'AddCommentForm','class'=>'myForm','enctype'=>'multipart/form-data','action'=>'/add_comment')); ?>
                <div class="insertNewPost">
                        <div class="col-sm-12">
                            <div class="textBoxCounter">
                                <?php echo $this->Form->textarea('newcomment_input',array('label'=>'','type'=>'text','id'=>'newcomment_input_'.$post['Post']['id'],'rows'=>5,'maxlength'=>500,'placeholder'=>__('answer_to').' @'.$post['User']['user_name'],'class'=>'myFormComponent notTrans fixHeight')); ?>
                                <input type="hidden"  name="data[Post][parent_id]" id="commnet_post_id_<?php echo $post['Post']['id'] ?>" 
                                value="<?php echo $post['Post']['id'] ?>"/>
		                        <input type="hidden" id="parent_commnet_post_id_<?php echo $post['Post']['id'] ?>" 
                                value="<?php echo $post['Post']['id'] ?>" />
		                        <input type="hidden"  id="commnet_post_user_id_<?php echo $post['Post']['id'] ?>" 
                                value="<?php echo $post['User']['id']; ?>" name="data[Post][commnet_post_user_id]" />
                                <span class="counter">500</span>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="insertLinkBox">
                                <input name="data[Post][newcomment_link]" id="newcomment_link_<?php echo $post['Post']['id'] ?>" type="text" placeholder="http://" class="myFormComponent ltr">
                                <div class="tile box33x33 trans clearInput">
                                    <span class="icon icon-cancel"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div id="attachment_place_<?php echo $post['Post']['id'] ?>"></div>    
    		                <div id="comment_result_<?php echo $post['Post']['id'] ?>" style="float:right"></div>
                        </div>
                        <div class="col-sm-6">
                            <div class="fileUpload myFormComponent">
                                <div class="btn red uploadBtn" id="newcomment_add_image_<?php echo $post['Post']['id'] ?>">
                                    <span class="icon icon-camera-1"></span>
                                    <span class="text"> افزودن تصویر</span>
                                </div>
                                <?php echo $this->Form->input('newcomment_image',array('label'=>'','type'=>'file','id'=>'newcomment_image_'.$post['Post']['id'])); ?>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <button role="button" type="submit" class="myFormComponent green">
                                <span class="text">ارسال خبر</span>
                                <span class="icon icon-left-open"></span>
                            </button>
                            <div id="comment_loading_<?php echo $post['Post']['id'] ?>" style="float:left;margin-left: 5px"> </div>
                        </div>
                </div>
                <div class="clear"></div>
            </form>
        </div>

<div id="comment_place_<?php echo $post['Post']['id'] ?>"></div>
</div>
<script>
     
	_answer_to = '<?php echo __('answer_to'); ?>';
	_show_comment = '<?php echo $show_comment; ?>';
    
    $('.clearInput').click(function(e) {
            var parent = $(this).parent();
    	    $('input',parent).val('');
        });
       textBoxCounter(500);
    
	jQuery(document).ready(function(){
		
		var commnet_post_id = $('#commnet_post_id_'+<?php echo $post['Post']['id'] ?>).val();
		//refresh_comment(commnet_post_id);
		
		$('#AddCommentForm_'+<?php echo $post['Post']['id'] ?>).on('submit', function(e) {
				if($('#newcomment_input_'+<?php echo $post['Post']['id'] ?>).val()==''){
					show_warning_msg(_enter_text);
					e.preventDefault();
					return false;
				}
				e.preventDefault();
                $("#comment_loading_"+<?php echo $post['Post']['id'] ?>).html('<img width="24" src="'+_url+'/img/loader/metro/preloader-w8-cycle-black.gif" >');
                $(this).ajaxSubmit({
                    target: '#comment_result_'+<?php echo $post['Post']['id'] ?>,
                    success:  afterPostSuccess , //call function after success
					error  :  afterPostError
                });
            });
			
			function afterPostSuccess()  {
				$("#comment_loading_"+<?php echo $post['Post']['id'] ?>).empty();
				var post_user_id = $('#commnet_post_user_id_'+<?php echo $post['Post']['id'] ?>).val();
				var body = $('#newcomment_input_'+<?php echo $post['Post']['id'] ?>).val();				
				$('#AddCommentForm_'+<?php echo $post['Post']['id'] ?>).resetForm();  // reset form
	            $('#newcomment_link_'+<?php echo $post['Post']['id'] ?>).val('');
				
				$('#newcomment_image_attachment_'+<?php echo $post['Post']['id'] ?>).parent().fadeOut(200);
			    $('#newcomment_image_attachment_'+<?php echo $post['Post']['id'] ?>).parent().remove();
				
			    $('#newcomment_input_'+<?php echo $post['Post']['id'] ?>).val('');
				$("#comment_counter_"+<?php echo $post['Post']['id']; ?>).val(500);
				var commnet_post_id = $('#parent_commnet_post_id_'+<?php echo $post['Post']['id'] ?>).val();
			    show_success_msg(_save_post_success);
                $('.postReply').slideUp(1000);
                $('.postReply').remove();
				if(_show_comment==1){
					refresh_comment(commnet_post_id);
				}else   new_refresh_home();	
				send_privacy_email(post_user_id,'oncomment',body,<?php echo $post['Post']['id'] ?>);
       	  	}
		  
		  function afterPostError()  {
			show_error_msg(_save_post_notsuccess);
       	  }
		
		
		$('#newcomment_image_'+<?php echo $post['Post']['id'] ?>).change(function(){

			var count = 0;
			var arr = $("#newcomment_image_attachment_"+<?php echo $post['Post']['id'] ?>).map(function() {
				  count+=1;
			  });
			if(count>=1){
				show_warning_msg(_exist_image);return;
			}
           
            var attach="<span class='imageStatus' id='newpost_image_attachment_"+<?php echo $post['Post']['id'] ?>+"'><span style='cursor:pointer' class='icon icon-cancel clearInput' id='closethis'></span>"+_image_added+"</span>";
            
			$('#attachment_place_'+<?php echo $post['Post']['id'] ?>).append(attach);
			$("#closethis").click(function(e) {
		       $(this).parent().fadeOut(200);
		    });
		});
		
		
		
		 $('#newcomment_add_image_'+<?php echo $post['Post']['id'] ?>).click(function(){
			 $('#newcomment_image_'+<?php echo $post['Post']['id'] ?>).trigger('click');
	        //$(this).parent().find('input').click();
			$("#closethis").click(function(e) {
		        $(this).parent().fadeOut(200);
				$(this).parent().remove();
	    	});
			
	      });	
	});
	
function show_add_comment_link_form() {
	
	TINY.box.show({url:_url+'posts/add_comment_link',
			   post:'action=load_page&post_id='+<?php echo $post['Post']['id'] ?>,
			   opacity:50,
			   topsplit:2}
			   );
}
 
	

function set_comment(user_name,post_id,current_post_id,user_id)
{
	$('#commnet_post_id_'+current_post_id).val(post_id);
	$('#commnet_post_user_id_'+current_post_id).val(user_id);
	$('#newcomment_input_'+current_post_id).attr('placeholder',_answer_to +' @ '+user_name);
	//$("html, body").animate({ scrollTop: 0 }, "fast");
	var pos = $('#commnet_post_id_'+current_post_id).offset();
	$("html, body").animate({ scrollTop: pos.top/2 }, "fast");
 
}
	
	 
</script>	