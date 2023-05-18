<?php
	echo $this->Html->script('jquery.form');
	//print_r($categoryposts);
 
?>

<div class="bg modalCloser"></div>

<?php if(empty($in_home)){ ?>
<div class="dataBox modalMain col-md-6 col-md-offset-3">
<?php }else{ ?>
<div class="dataBox modalMain " style="margin-bottom: 20px;">
<?php } ?>

<header class="modalHeader">
<?php if(empty($in_home)){ ?>
<div class="closer modalCloser icon-cancel"></div>
<?php } ?>
<?php echo __('send_post'); ?>
</header>
 
   <?php echo $this->Form->create('Post', array('id'=>'AddPostForm','name'=>'AddPostForm','enctype'=>'multipart/form-data','action'=>'/add_post','class'=>'myForm')); ?>
        <section class="modalContent">
            <div class="insertNewPost" >
                    <div class="col-sm-12">
                        <div class="textBoxCounter">
                            <textarea maxlength="500" rows="5" class="myFormComponent notTrans fixHeight" placeholder="<?php echo __('type_a_text'); ?>" id="newpost_input" name="data[Post][newpost_input]"></textarea>
                            <span class="counter" id="newpost_charnumber">500</span>
                        </div>
                    </div>
					
                    <div class="col-sm-12" style="display: none" id="newpost_link_box">
                        <div class="insertLinkBox">
                            <input  type="text" id='newpost_link' name="data[Post][newpost_link]" 
							placeholder="<?php echo __('insert_link_in_box') ?>" class="myFormComponent ltr">
                            <div class="tile box33x33 trans clearInput" id="newpost_link_clear">
                                <span class="icon icon-cancel"></span>
                            </div>
                        </div>
                    </div>
					
					<div class="col-sm-12" style="display: none" id="newpost_video_box">
                        <div class="insertLinkBox">
                            <input  type="text" id='newpost_video' name="data[Post][newpost_video]" 
							placeholder="<?php echo __('insert_video_link_in_box') ?>" class="myFormComponent ltr">
                            <div class="tile box33x33 trans clearInput" id="newpost_video_clear">
                                <span class="icon icon-cancel"></span>
                            </div>
                        </div>
                    </div>
							<input type="hidden" id="url_title" name="data[Post][url_title]" />
							<input type="hidden" id="url_content" name="data[Post][url_content]" />
							<input type="hidden" id="url_image" name="data[Post][url_image]" />
                    
					<div class="clear"></div>
					<div class='col-sm-12' id="NewPost">

					</div>
					<div class="url_loading"></div>
            </div>
			<div class="preview_bord">
				<img  class="add_image" id="add_image" />
				<div id="video_preview"></div>
				<div class="extract" style="display: none;">
	   			 <div class=""><a class="delete float-right" href="#"></a></div>
				 <div class="float-left extract-thumb">
				 </div>
				 <div class="float-left extract-info">
					<span id="title"></span>
					<span id="url"></span>
					<span id="desc"></span>
					<div class="nav">
						<img id="prev" src="<?php echo __SITE_URL.'img/icons/prev.gif'; ?>">
						<img id="next" src="<?php echo __SITE_URL.'img/icons/next.gif'; ?>">
						<span id="navount"></span>
					</div>
				 </div>
	   		   </div>
		   </div>
		   
        </section>
		<div id="newpost_loading" style="float:left;margin-left: 5px"> </div>
		<div id="post_result" style="float:right"></div>
        <footer>
                    
					<div class="tile box33x33 trans clearInput" id="new_post_link_btn">
                        <span class="icon icon-link"></span>
                    </div>
					<div class="tile box33x33 trans clearInput" id="new_post_image_btn">
                        <span class="icon icon-camera-1"></span>
						
                    </div>
					<input type="file" style="position:absolute;top:-2000px;" id='newpost_image' name="data[Post][newpost_image]">
					<div class="tile box33x33 trans clearInput" id="new_post_video_btn">
                        <span class="icon icon-video"><img src="<?php echo __SITE_URL.'img/icons/video.png' ?>" /></span>
                    </div>
										
					<div class="col-sm-9 upload_location" >
                        <div class="fileUpload">
                            <div class="btn green">                               
                                <span id="newpost_submit" class="text"><?php echo __('send_post'); ?></span>
								<span class="icon icon-left-open"></span>
                            </div>
                        </div>
                    </div>
					 
        </footer>
		<div class="clear"></div>
		<?php 
		if(!empty($categoryposts)){
			$i=0;
			foreach($categoryposts as $categorypost){
				if($i==0){
					echo "<input type='radio' checked value='".$categorypost['Categorypost']['id']."'  name='data[Post][categorypost_id]' >".$categorypost['Categorypost']['title'];
				}else{
					echo "<input type='radio' value='".$categorypost['Categorypost']['id']."'  name='data[Post][categorypost_id]' >".$categorypost['Categorypost']['title'];
				}
				$i++;
			}
		}
		?>
		<div class="clear"></div>
    </form>
</div>
<script>
	
	$('#newpost_input').on('paste', function () {
	  var element = this;
	  setTimeout(function () {	  	 
	    var text = $(element).val();	
			
		urls = text.match(/(http|ftp|https):\/\/[\w-]+(\.[\w-]+)+([\w.,@?^=%&amp;:\/~+#-]*[\w@?^=%&amp;\/~+#-])?/);
		if(urls!=null){
			for (var i = 0, il = urls.length; i < il; i++) {
			    var url=urls[i];
												
				if($('#newpost_link').val()==''){
					$('#newpost_link').val(url);
					$('#newpost_link_box').fadeIn(400);	
					var str = text.replace(url, "");
					$('#newpost_input').val(str);
					load_link_preview();
				}				
				break;
			 }
		}
		
	  }, 100);
	});
	
	
	$('#new_post_link_btn').click(function(){
		$('#newpost_link_box').fadeIn(400);	
	});	 
	$('#new_post_video_btn').click(function(){
		$('#newpost_video_box').fadeIn(400);	
	}); 
	
	$('#newpost_link_clear').click(function(){
		clear_link();
	});	 
	
	$('#newpost_video_clear').click(function(){
		clear_video();	
	});
	
	$('#newpost_video').keyup(function(){
		clear_link();
		clear_image();
		$('#video_preview').html($(this).val());
	})
	 
	
	function isUrl(s) {
	    var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/
	    return regexp.test(s);
	}
	
	$('#newpost_submit').click(function(){
		$('#AddPostForm').submit();
	})
	
	modalCloser();
	makeCenterVer($("#modal .modalMain"));
	$('input, textarea').keyup(function() {
	    $(this).val().charAt(0).charCodeAt(0) < 200 ? $(this).css('direction','ltr') : $(this).css('direction','rtl');
	});
	
	textBoxCounter(500);
	$('.addLink').click(function(e) {
        $('.insertLinkBox').fadeIn();
    });
	$('.clearInput','#modal').click(function(e) {
        var parent = $(this).parent();
		$('input',parent).val('');
    });
	
	function clear_video(){
		$('#video_preview').html("");
		$('#newpost_video_box').fadeOut(400);
		$('#newpost_video').val("");
	}
	
	function clear_link(){
		$('#newpost_link_box').fadeOut(400);
		$('#newpost_link').val("");	
		$(".extract").fadeOut("slow");
	}
	
	function clear_image(){
		$("#add_image").fadeOut("slow");
		$("#newpost_image").val("");
		$('#newpost_image_attachment').parent().fadeOut(200);
		$('#newpost_image_attachment').parent().remove();
	}
	
	function getBase64FromImageUrl(e) {		
		clear_link();
		clear_video();
		var img = new Image();
		var url = URL.createObjectURL(e.target.files[0]);
	    img.src = url;
	    img.onload = function () {
	    	var canvas = document.createElement("canvas");
		    canvas.width =this.width;
		    canvas.height =this.height;
		    var ctx = canvas.getContext("2d");
		    ctx.drawImage(this, 0, 0);
		    var dataURL = canvas.toDataURL("image/png").replace("image/png", "image/octet-stream");
		    //alert(  dataURL.replace(/^data:image\/(png|jpg);base64,/, ""));
			$('#add_image').attr('src',dataURL.replace(/^data:image\/(png|jpg);base64,/, ""));
	    }
	  }
	 
	  
		
	 var imgArray;
	 var title;
	 var desc;
	 var index = 0;
	
	function load_link_preview(){
		
	   
		 var link = $("#newpost_link").val();
		  
		 if(/^(http|https|ftp):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/i.test($("#newpost_link").val()))
		 {
			 clear_image();
			 clear_video();
			 
			 imgArray = new Array();
			 index = 0;
			 title = "";
			 desc = "";
			 $(".url_loading").css("display","block");
			 if(link.length){
				 // Encode url so we can have single line url instead of different parts
				 elink = encodeURIComponent(link);
				 $.ajax({
				   type: "POST",
				   url: _url+'posts/extract_url',
				   data: "link="+elink,
				   success: function(responce){	
					if(responce != "0")
					{
					  var json = $.parseJSON(responce);
					  $.each(json, function(key, val) {
						//alert(val.src);
						
						if(val.src != null){
							imgArray.push(val.src);
							$("#trick").attr("src",val.src);
							//$(".array").append("<br>"+val.src);
						}
						
						//console.log(val);
						if(val.title != null)
							title = val.title;
						if(val.url != null)
							link = val.url;
						if(val.desc != null)
							desc = val.desc;
						
					  });
						//alert(title);
						if(imgArray.length > 0){
							// if images found then show nav icons
							//$(".nav").show();
							$(".nav").hide();
							// also hide image holder
							$(".extract-thumb").css("visibility","visible");

							if($(".extract-thumb").html() == "")
							   $(".extract-thumb").append('<img src="'+imgArray[0]+'" >');
							else
							   $(".extract-thumb").html('<img src="'+imgArray[0]+'" >');
						}else{
							// if images not found then hide nav icons
							$(".nav").hide();
							// also hide image holder
							$(".extract-thumb").css("visibility","hidden");
						}
						//console.log(title);
						$(".extract-info #title").html(title);
						$(".extract-info #url").html(link);
						$(".extract-info #desc").html(desc);
						
						$("#url_title").val(title);
						$("#url_content").val(desc);
						$("#url_image").val(imgArray[0]);
						
						showcount(index);
						$(".extract").slideDown("slow");
					 }else{
						show_warning_msg('<?php echo __("this_url_doesnt_exists")?>');
						
					 }
					 $(".url_loading").css("display","none");
				   }	
				});

			 }else{
				show_warning_msg('<?php echo __("please_enter_link")?>');
			 }
		 }else{
			show_warning_msg('<?php echo __("enter_correct_link")?>');
			$(".extract").fadeOut("slow");
	   }
	}
	
	
	$("#newpost_link").keyup(function(){
		load_link_preview();
	});
	
	jQuery(document).ready(function(){
	  
		 $("#next").click(function(){
			 if(index < imgArray.length){
				 index++;
				 $(".extract-thumb").find("img").attr("src",imgArray[index]);
				 showcount(index);
				 $("#url_image").val(imgArray[index]);
			 }
		 });
		 $("#prev").click(function(){
			 if(index > 0){
				 index--;
				 $(".extract-thumb").find("img").attr("src",imgArray[index]);
				 showcount(index);
				 $("#url_image").val(imgArray[index]);
			 }
		 });
		 $(".delete").click(function(){
			$(".extract").fadeOut("slow");
		 });
	 
	 showcount = function(index){
   	   index = index + 1;
	   if(index <= imgArray.length && index > 0)	
		$("#navount").html("Showing "+index+" of "+imgArray.length);
	 };
	 
	 
     $('#AddPostForm').on('submit', function(e) {                         
                var newpost_str =$('#newpost_input').val(); 
				if(newpost_str.trim()==''){
					show_warning_msg(_enter_text);
					e.preventDefault();
					return false;
				}
				
				var newpost_video =$('#newpost_video').val(); 
				if(newpost_video.trim()!=''){
					
					if(!newpost_video.endsWith('</iframe>') ) {
					 	show_warning_msg("<?php echo __('enter_valid_iframe') ?>");
						e.preventDefault();
						return false;  
					}
				}
				
				e.preventDefault();
               // $("#newpost_loading").html('<img width="24" src="'+_url+'/img/loader/5.gif" >');
			   $('body').prepend("<div id='modal'></div>");
			   $("#modal").html('<div class="loadingPage"><div class="loaderCycle"></div><span>'+_loading+'</span></div>' );
                $(this).ajaxSubmit({
                    target: '#post_result',
                    success:  afterPostSuccess , //call function after success
					error  :  afterPostError
                });
            });
			
			function afterPostSuccess()  {
	            $('#AddPostForm').resetForm();  // reset form
				//$("#newpost_loading").empty();
				remove_modal();  
	            $('#newpost_link').val('');
				$('.preview_bord').fadeOut(200);
				
				$('#newpost_image_attachment').parent().fadeOut(200);
			    $('#newpost_image_attachment').parent().remove();
				
			    $('#newpost_input').val('');
			    $('.newpost_charnumber').text(500);
				remove_modal();
			    show_success_msg(_save_post_success);
			    new_refresh_home();	
       	  }
		  
		  function afterPostError()  {
			show_error_msg(_save_post_notsuccess);
       	  }
	 
 		$('#newpost_image').change(function(){
			$("#newpost_image_attachment").remove();
			var count = 0;
			var arr = $("#newpost_image_attachment").map(function() {
				  count+=1;
			  });
			if(count>=1){
				show_warning_msg(_exist_image);return;
			}
			
			var attach="<span class='imageStatus' id='newpost_image_attachment'><span style='cursor:pointer' class='icon icon-cancel clearInput' id='closethis'></span>"+_image_added+"</span>";
			$('#NewPost').append(attach);
			$("#closethis").click(function(e) {
		       $(this).parent().fadeOut(200,function() { $(this).remove(); });
			   $('#newpost_image').clearInputs();
			   $("#add_image").fadeOut("slow");
			   $("#newpost_image").val("");
		    });
			$("#add_image").slideDown("slow");
		});
		var input = document.getElementById('newpost_image');
     		input.addEventListener('change', getBase64FromImageUrl);
		
	 $('#newpost_add_image').click(function(){
		 $('#newpost_image').trigger('click');
        //$(this).parent().find('input').click();
		$("#closethis").click(function(e) {
	        $(this).parent().fadeOut(200);
			$(this).parent().remove();
			$("#newpost_image").val("");
			
	    });
		
    });
	
	
	/*
	 $('#newpost_input').keyup(function(e){
	 	$('.newpost_charnumber').text(500-$(this).val().length); 
	 });*/
 
 
 	$("#closethis").click(function(e) {
        $(this).parent().fadeOut(200);
		$("#newpost_image").val(""); 
    });
	
	$('#new_post_image_btn').click(function(){
		$('#newpost_image').trigger('click');
	})
	
});	
	
</script>

