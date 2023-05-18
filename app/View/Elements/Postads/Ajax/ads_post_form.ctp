<?php
      echo $this->Html->css('/css/ListSelector/autocomplete.css'); 
	  echo $this->Html->css('/css/ListSelector/ui-lightness/jquery-ui-1.8.custom'); 
	  //echo $this->Html->script('/js/ListSelector/admin-jquery-ui-custom.min');
	   
	  echo $this->Html->script('/js/ListSelector/tagcount-jquery-ui-custom.min');
?>
<script>
_not_repeated_select_tag= "<?php echo __('not_repeated_select_tag') ?>";
</script>
<style>
	#tag_adsForm #tags span { 
		display:block; 
		width:auto; 
		margin: 3px; 
		padding:3px 20px 4px 8px; 
		position:relative;
	    float: right; 
		text-indent:0; 
		background-color:#eff2f7; 
		border:1px solid #ccd5e4; 
		color:#333; font:normal 11px tahoma, Sans-serif; 
		}
	#tag_adsForm #tags span a { 
		position:absolute; 
		right:8px; 
		top:2px; 
		color:#666; 
		font:bold 12px Verdana, Sans-serif; text-decoration:none; }
	#tag_adsForm #tags span a:hover { color:#ff0000; }
	  
</style>


<div class="bg modalCloser"></div>
<div class="dataBox modalMain col-md-6 col-md-offset-3">
<header class="modalHeader">
<div class="closer modalCloser icon-cancel"></div>
<?php echo __('promote_post') ?>
</header>
 <form class="myForm">
	<section class="modalContent">
		 <div class="col-md-12">
			<b><?php echo  __('ads_dtl1') ?>  </b>
	     </div>
		 <div class="col-md-12">
			<?php echo  __('ads_dtl2') ?><a href="#"><?php echo  __('ads_dtl3') ?></a>
	     </div>
		 <div class="col-md-12">
			<b><?php echo  __('ads_dtl4') ?>: </b>
	     </div>
		 <div class="col-md-12">
			<?php echo  __('ads_dtl5') ?> : <a href="<?php echo __SITE_URL.'users/all_tags' ?>" target="_blank">(<?php echo  __('ads_dtl51') ?>)</a>
	     </div>
		 <div class="col-md-12">
			<div id="tag_adsForm">
				  <div id="tags"  >
					#<input id='tag_input' type='text' dir='rtl' size='30' >								  
				  </div>
			</div>
	     </div>
		 <div class="col-md-12">
			<b><?php echo  __('ads_dtl6') ?> : <span id="current_member"><?php echo $user_count; ?></span> <?php echo  __('ads_dtl7') ?>
            <span id="total_member"><?php echo $user_count; ?></span> <?php echo  __('ads_dtl8') ?> </b>
	     </div>
		 <div class="col-md-12">
			<b><?php echo  __('ads_dtl9') ?> : </b> 
	     </div>
		 <div class="col-md-12">
			<ul>
				<li> <input type='radio' checked="checked" name='price_type' value="<?php echo $postads_level1_price; ?>" id="price_type" > <?php echo $postads_level1_price.' '.__('rial'); ?> - <?php echo  __('ads_dtl10') ?></li>
				<!--<li> <input type='radio' name='price_type' value="<?php echo $postads_level2_price; ?>" id="price_type1" checked="" > <?php echo $postads_level2_price.' '.__('rial'); ?> - <?php echo  __('ads_dtl11') ?></li>-->
			</ul>
	     </div>
		 <div class="col-md-12">
			<b><?php echo  __('ads_dtl12') ?> : <span id="total_amount">0</span> <?php echo  __('rial') ?></b>
	     </div>
		 <div class="col-md-12">
			<?php echo  __('ads_dtl13') ?><a href="#"><?php echo  __('ads_dtl14') ?></a>
            <?php echo  __('and') ?> <a href="#"><?php echo  __('ads_dtl15') ?></a><?php echo  __('ads_dtl16') ?>
	     </div>
	</section>
	<footer>
	    <div class="col-sm-12">
	        <button role="button" type="button" onclick="send_ads(<?php echo $_REQUEST['id'] ?>)" id="save_forget_pass" class="myFormComponent green">
	            <span class="text"><?php echo __('save_ads') ?></span>
	            <span class="icon icon-left-open"></span>
	        </button>
			<span id="ads_loading"></span>
	    </div>
	</footer>
  </form>
</div>


<script>
	 
     modalCloser();
	 makeCenterVer($("#modal .modalMain"));
     
     
     var current_member=0;
     var total_amount =0;
	 var industry_id=[];
     
	// muti select code
function MultiSelectFunc()
{
	$(".MultiSelect li:nth-child(1)").click(function(e) {
		if($(this).parent().find("li:nth-child(2)").css("display")=="none")
		{
			$(this).parent().find("li").fadeIn(100);
		}else
		{
			$(this).parent().find("li").each(function(index, element) {
                if(index>0)
				{
					$(this).fadeOut(100);
				}
            });
		}
    });
	$(".MultiSelect li input[type='checkbox']").change(function(e) {
        if( $(this).is(":checked") )
		{
			$(this).parent().css("margin-right","20px");
           // alert($(this).val());
            current_member = $(this).val();
			industry_id.push($(this).attr("title"));
			//alert(industry_id);
			
            var selected_price = $('input[name=price_type]:radio:checked');
            selected_priceVal = selected_price.val();
            
           $('#current_member').html(parseInt($('#current_member').text())+ parseInt(current_member));
           
           $('#total_amount').text(selected_priceVal*parseInt($('#current_member').text()));
		}
        else
		{
			$(this).parent().css("margin-right","0px");
            $('#current_member').html(parseInt($('#current_member').text())- parseInt(current_member));
            var selected_price = $('input[name=price_type]:radio:checked');
            selected_priceVal = selected_price.val();
            $('#total_amount').text(selected_priceVal*parseInt($('#current_member').text()));
			
			index=industry_id.indexOf($(this).attr("title"));
			industry_id.splice(index,1);
			//alert(industry_id);
		}
		/*
		var a = $(".MultiSelect li input[type='checkbox']:checked");
		if(a.length >3)
		{
			$(this).prop("checked","");
			$(this).parent().css("margin-right","0px");
		}*/
    });
	
}
// muti select code


$(document).ready(		
		function()
		{
			//MultiSelectFunc();
            
			$("#tag_input").focus();
            var selected_price = $('input[name=price_type]:radio:checked');
	            selected_priceVal = selected_price.val();
            $('#total_amount').text(selected_priceVal*parseInt(<?php echo $user_count; ?>));
			
			$('#price_type').change(function(e) {
                //alert('moj');
            	selected_priceVal = $(this).val();
	           $('#total_amount').text(selected_priceVal*parseInt($('#current_member').text()));
			 }
			);
			
			$('#price_type1').change(function(e) {
            	selected_priceVal = $(this).val();
	           $('#total_amount').text(selected_priceVal*parseInt($('#current_member').text()));
			 }
			);
			    
		});
	


function send_ads_detail(id)	{
    var selected_price = $('input[name=price_type]:radio:checked');
	    selected_priceVal = selected_price.val();
		
	var ads_type=$(selected_price).attr('id');	
	 
	var current_member = $("#current_member").text();
    var total_amount = $("#total_amount").text();
    
    var tag_array=[];
        $('#tags input#users_tag').each(function() {
           tag_array.push($(this).val());
        });
     
    $.ajax({
		type: "POST",
		url: _url+'postads/send_ads',
		data: 'id='+id+'&action=send'+'&total_amount='+total_amount+'&selected_priceVal='+selected_priceVal+'&current_member='+current_member+'&tag='+tag_array+'&ads_type='+ads_type,
		dataType: "json",
		success: function(response)
		{
			 if(response.success == true) {			
				if( response.message ) {
					show_success_msg(response.message);	
					remove_modal();	
					setTimeout("location.href = '"+_url+"getway/banks/pay/"+response.token+"?cn=postads&ac=postads_list'", 1500);
				} 
				$("#ads_loading").empty();	
			}
			else 
			 {
				if( response.message ) {
					show_error_msg(response.message);
				} 
				$("#ads_loading").empty();
			 }	 
		}

	  });
    
}  
function send_ads(id)
{  
	$("#ads_loading").html('<img width="22" src="'+_url+'/img/loader/metro/preloader-w8-cycle-black.gif" >');

	var total_amount = $("#total_amount").text();

	if(total_amount ==0){
		show_warning_msg("<?php echo __('select_member_group'); ?>");
		$("#ads_loading").empty();
		return;
	}
	
	$.ajax({
		type: "POST",
		url: _url+'postads/send_ads',
		data: 'id='+id+'&action=check_postads',
		dataType: "json",
		success: function(response)
		{ 
			 if(response.success != true) {			
				if( response.message ) {
					$.Zebra_Dialog(response.message, {
					    'type':     'question',
					    'title':    '<?php echo __("question"); ?>',
					    'buttons':  [
					                    {caption: _yes, callback: function() {send_ads_detail(id);}},
					                    {caption: _no, callback: function() { remove_modal();return; }},
					                    {caption: _cancel, callback: function() { return;}}
					                ]
					});
                     
				} 
				$("#ads_loading").empty();	
				 
			}
            else send_ads_detail(id); 
		}
        
	 });
	  
}

//===========================================================================================================	
	var tarr =[];
		var arr = [];
    
	$("#tag_input").autocomplete({
					//define callback to format results
				
					source: function(req, add){
					
						//pass request to server
						$.getJSON(_url+"usertags/tag_search?callback=?", req, function(data) {
							
							//create array for response objects
							var suggestions = [];
							//var suggestions1 = [];
							//process response
							$.each(data, function(i, val){	
							 						
								suggestions.push({'title':val.title,'id':val.id,'tag_count':val.tag_count});
								 
								//suggestions.push(val.name+'<input type=hidden id='+val.user_id+'>');
							 
							});
							
							//pass array to callback
							
							add(suggestions);
							 
							 
						});
					},
					select: function(e, ui) {
						
						//create formatted tag
						var tag = ui.item.value;

						for (var i=0;i<=tarr.length;i++)
						{
                                if(tarr[i]==tag){
								show_warning_msg(_not_repeated_select_tag);
                                //tarr.pop();
								return false;
							 } 
						}
						tarr.push(tag); 
						 
						var user_count= $("#tags span").length+1; 
						var id= ui.item.id;
							span = $("<span>").text(tag),
							a = $("<a>").addClass("remove").attr({
								href: "javascript:",
								//title: "Remove " + tag,
                                title: tag,
								id   : id,
							}).text("x").appendTo(span);
						var hide_input= "<input type='hidden' id='users_tag' name='data[Userrelatetag][usertag_id][]' value='"+id+"' >";
						//hide_input.appendTo(span);
						span.append(hide_input);
						//add tag to tag div
						 
						span.insertBefore("#tag_input");
                        var tag_array=[];
                        $('#tags input#users_tag').each(function() {
                           tag_array.push($(this).val());
                        });
                       // alert(tag_array);
                        
                        $.ajax({
                    		type: "POST",
                    		url: _url+'users/ads_count',
                    		data: 'tag='+tag_array,
                    		dataType: "json",
                    		success: function(response)
                    		{ 
                    			 if(response.success == true) {			
                    				$('#current_member').text(response.user_count);
                                    var selected_price = $('input[name=price_type]:radio:checked');
                                        selected_priceVal = selected_price.val();
                                    $('#total_amount').text(selected_priceVal*parseInt(response.user_count));
                    			}
                                
                    		}
                            
                    	 });
					},
					
					//define select handler
					change: function() {
						
						//prevent 'to' field being updated and correct position
						$("#tag_input").val("").css("top", 2);
					}
				});
				
				//add click handler to user_ids div
				$("#tags").click(function(){
					
					//focus 'to' field
					$("#tag_input").focus();
				});
				Array.prototype.remove = function(x) { 
                    var i;
                    for(i in this){
                        if(this[i].toString() == x.toString()){
                            this.splice(i,1)
                        }
                    }
                }
				//add live handler for clicks on remove links
				$(".remove", document.getElementById("tags")).live("click", function(){				
					//remove current tag
                    tarr.remove($(this).attr('title'));
					$(this).parent().remove();
                    var tag_array=[];
                        $('#tags input#users_tag').each(function() {
                           tag_array.push($(this).val());
                        });
                    $.ajax({
                    		type: "POST",
                    		url: _url+'users/ads_count',
                    		data: 'tag='+tag_array,
                    		dataType: "json",
                    		success: function(response)
                    		{ 
                    			 if(response.success == true) {			
                    				$('#current_member').text(response.user_count);
                                    var selected_price = $('input[name=price_type]:radio:checked');
                                        selected_priceVal = selected_price.val();
                                    $('#total_amount').text(selected_priceVal*parseInt(response.user_count));
                    			}                               
                    		}                           
                    	 });					
					//correct 'to' field position
					if($("#tags span").length === 0) {
						$("#tag_input").css("top", 0);
					}				
				});	
//===========================================================================================================	

</script>  
	  
<style>
  div .help{
  	color: #696969;
    line-height: 22px;
    margin-bottom: 5px;
  }
</style>