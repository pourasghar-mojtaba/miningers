<?php
	echo $this->Html->script('jquery.form');
?>


<div class="bg modalCloser"></div>
<div class="dataBox modalMain col-md-6 col-md-offset-3" style="hieght:500px">
<header class="modalHeader">
<div class="closer modalCloser icon-cancel"></div>
<?php echo __('add_image'); ?>
</header>
 
   <?php echo $this->Form->create('User', array('id'=>'ChangeImage','name'=>'ChangeImage','enctype'=>'multipart/form-data','action'=>'/edit_image','class'=>'myForm')); ?>
        <section class="modalContent">
            <div class="insertNewUser" >                
                    <div class="col-sm-12" style="text-align: center;margin: auto;">  
							
                             
							<div class="col-sm-12" style="text-align: center;margin: auto;">								
                                <div class="pane clearfix">
                                  <img style="cursor: pointer;" id="add_image" src="<?php echo __SITE_URL.'img/add_user.png' ?>" />
                                  <div class="coords">
                                    <input  name="x" type="hidden" />
                                    <input name="y" type="hidden" />
                                    <input name="w" type="hidden" />
                                    <input  name="h" type="hidden" />
                                    <input name="image_width" type="hidden" />
                                    <input name="image_height" type="hidden" />
                                    <!--<input type="checkbox" checked="checked" />-->
                                  </div>
                                </div>
							</div>                    
							  
                            
                            <div class="clear"></div>
							<div id="newpost_loading" style="float:left;margin:auto "> </div>
 
                            <input type="file" id='image' style="display:none" name="data[User][image]">  
                            <input name='new_image' id="new_image" type='hidden' value='' > 
                    </div>
                    
					<div class="clear"></div>
                    
					<div class='col-sm-12' id="NewUser">

					</div>
                    <div class="col-sm-12" style="text-align: center">                      
						<?php echo __('add_image_hint'); ?>
                    </div>
            </div>
        </section>
		
		<div id="post_result" style="float:right"></div>
        <footer>
                    <div class="col-sm-12">
                        <button role="button" type="submit" class="myFormComponent green">
                            <span class="text"> <?php echo __('save_changed'); ?></span>
                            <span class="icon icon-left-open"></span>
                        </button>
                    </div>
        </footer>
    </form>
</div>

<script>
	modalCloser();
	makeCenterVer($("#modal .modalMain"));
		
	$('.clearInput','#modal').click(function(e) {
        var parent = $(this).parent();
		$('input',parent).val('');
    });
	
	jQuery(document).ready(function(){
	/* 
	$('#image').change(function(){
	 	$('#ChangeImage').submit();
	 });*/
	 
	 var canvas = document.getElementById("add_image");
	 var img    = canvas.toDataURL("image/png");
	 document.write('<img src="'+img+'"/>');
	 
     $('#ChangeImage').on('submit', function(e) {                         
			e.preventDefault();
			//$('#add_image').removeAttr('src');
            $("#newpost_loading").html('<img width="24" src="'+_url+'/img/loader/5.gif" >');
			
            $(this).ajaxSubmit({
                target: '#post_result',
                success:  afterUserSuccess , //call function after success
				error  :  afterUserError
            });
        });
			
		function afterUserSuccess()  {
            $('#ChangeImage').resetForm();  // reset form
			$("#newpost_loading").empty();			
			$('#image_attachment').parent().fadeOut(200);
		    $('#image_attachment').parent().remove();
			
			$(document).ready(function(){
		        // Apply jrac on some image.
		        $('.pane img').jrac({
		          'crop_width': 160,
		          'crop_height': 160,
		          'crop_x': 100,
		          'crop_y': 100,
		          'image_width': -1,
				  'crop_resize': false,
		          'viewport_onload': function() {
		            var viewport = this;
		            var inputs = viewport.$container.parent('.pane').find('.coords input:hidden');
		            var events = ['jrac_crop_x','jrac_crop_y','jrac_crop_width','jrac_crop_height','jrac_image_width','jrac_image_height'];
		            for (var i = 0; i < events.length; i++) {
		              var event_name = events[i];
		              // Register an event with an element.
		              viewport.observator.register(event_name, inputs.eq(i));
		              // Attach a handler to that event for the element.
		              inputs.eq(i).bind(event_name, function(event, viewport, value) {
		                $(this).val(value);
						console.log('name='+event_name+' '+'value='+value);
		              })
		              // Attach a handler for the built-in jQuery change event, handler
		              // which read user input and apply it to relevent viewport object.
		              .change(event_name, function(event) {
		                var event_name = event.data;
		                viewport.$image.scale_proportion_locked = viewport.$container.parent('.pane').find('.coords input:checkbox').is(':checked');
		                viewport.observator.set_property(event_name,$(this).val());
		              });
		            }
		            viewport.$container.append('<div>'
		              +viewport.$image.originalWidth+' x '
		              +viewport.$image.originalHeight+'</div>')
		          }
		        })
		        // React on all viewport events.
		        .bind('jrac_events', function(event, viewport) {
		          var inputs = $(this).parents('.paneitems').find('.coords input');
		          inputs.css('background-color',(viewport.observator.crop_consistent())?'chartreuse':'salmon');
		        });
		      });
			
			//$('#add_image').attr('src','".__SITE_URL.__USER_IMAGE_PATH.$image."' );
			//remove_modal();
		    //show_success_msg(_save_post_success);
   	    }
		  
	  	function afterUserError()  {
			$('#ChangeImage').resetForm();
			show_error_msg(_save_image_notsuccess);
   	  	}
	 
 		$('#image').change(function(){
			
			var count = 0;
			var arr = $("#image_attachment").map(function() {
				  count+=1;
			  });
			if(count>=1){
				show_warning_msg(_exist_image);return;
			}
			
			var attach="<span class='imageStatus' id='image_attachment'><span style='cursor:pointer' class='icon icon-cancel clearInput' id='closethis'></span>"+_image_added+"</span>";
			$('#NewUser').append(attach);
			$("#closethis").click(function(e) {
		       $(this).parent().fadeOut(200);
		    });
		});
		
	 $('#add_image').click(function(){
		 $('#image').trigger('click');
		$("#closethis").click(function(e) {
        $(this).parent().fadeOut(200);
		$(this).parent().remove();
    });
		
    });
 
 	$("#closethis").click(function(e) {
        $(this).parent().fadeOut(200);
    });
	
});	

</script>

