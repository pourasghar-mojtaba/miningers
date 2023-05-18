 <?php
 	$User_Info= $this->Session->read('User_Info');
    echo $this->Html->css('/css/ListSelector/autocomplete.css'); 
    echo $this->Html->css('/css/ListSelector/ui-lightness/jquery-ui-1.8.custom'); 
    echo $this->Html->script('/js/ListSelector/admin-jquery-ui-custom.min');
 ?>
<script>
_not_repeated_select_tag= "<?php echo __('not_repeated_select_tag') ?>";
_your_tag_is_maxtag= "<?php echo __('your_tag_is_maxtag') ?>";
</script>
<style>
	#adsForm{
		border: 1px solid #eeeeee;
		height: 100px;
		width: 485px;
		overflow: auto;
	}
	#adsForm input{
		background: none repeat scroll 0 0 #333333;
        border: medium none #FFFFFF;
        width: 100px;
        color: #ffffff;
	}
	 
	#adsForm #tags span { 
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
	#adsForm #tags span a { 
		position:absolute; 
		right:8px; 
		top:2px; 
		color:#666; 
		font:bold 12px Verdana, Sans-serif; text-decoration:none; }
	#adsForm #tags span a:hover { color:#ff0000; }
	  
</style> 
 <div class="myForm" style="width:500px">
<header class="ajaxheader">
	<strong>به معدنر خوش آمدید</strong><br>

	ما با اطلاعات زیر پروفایل شما را ایجاد خواهیم کرد.
</header>
<form id="first_industry_info">
<table >
	<tr>
    	<td>
        	<label>*<?php echo __('sex') ?></label>
            <select id="float_sex">
            <?php 
				if($User_Info['sex']==-1) echo "<option value='-1' selected> ----------- </option>";
				if($User_Info['sex']==1) echo "<option value='1' selected>".__('man')."</option>";
									else echo "<option value='1'>".__('man')."</option>";
				if($User_Info['sex']==0) echo "<option value='0' selected>".__('woman')."</option>";
									else echo "<option value='0'>".__('woman')."</option>";
				if($User_Info['sex']==2) echo "<option value='2' selected>".__('company')."</option>";
									else echo "<option value='2'>".__('company')."</option>";					
			?>
            </select>
        </td>
    	<td>
        	<label>*<?php echo __('industry') ?></label>
            <select name="float_industry_id" id="float_industry_id">
							 
			<?php
			   if($User_Info['industry_id']==0){
				  	echo"<option value='0' selected >--------</option>";
				  } else echo"<option value='0'>--------</option>";
				  
				if(!empty($industries)){
					foreach($industries as $industry){
					  if($User_Info['industry_id']==$industry['Industry']['id']){
					  echo"<option  selected   value='".$industry['Industry']['id']."'>";
						echo $industry['Industry']['title'];
					  echo "</option>";	
					  }else
					  {
					  	echo"<option  value='".$industry['Industry']['id']."'>";
						echo $industry['Industry']['title'];
						echo "</option>";	 
					  }
					  
					  
					}
				}
			
			?>
			</select>
        </td>
    </tr>
	<tr>
    	<td>
  			<label> <?php echo __('location') ?></label>
            <input id="float_location" placeholder="<?php echo __('location_detail') ?>" type="text" value="<?php echo $User_Info['location']; ?>">
        </td>
    	<td>
        	 &nbsp;
        </td>	
    </tr>
	<tr>
    	<td colspan="2">
        	<label><?php echo __('letter_of_introduction') ?></label>
            <div id="adsForm">
				  <div id="tags"  >
                     <?php
                     
					    if(!empty($tags)){
							foreach($tags as $tag){
								echo"<span>".$tag['Usertag']['title']." <a class='remove' href='javascript:' title='Remove کوشا' id='".$tag['Usertag']['id']."'>x</a><input type='hidden' value='".$tag['Usertag']['id']."' name='data[Userrelatetag][usertag_id][]'></span>";
							}
						}
					  	
					  ?>
					#<input id='tag_input' type='text' dir='rtl' size='30' >								  
				  </div>
			</div>
			<!--
            <?php echo $this->Form->textarea('details',array('label'=>'','type'=>'text','id'=>'details','value'=>$User_Info['details'],'class'=>'commentTXB3','maxlength'=>200,'rows'=>3,'placeholder'=>__('letter_of_introduction_detail'))); ?>
        	<label><span class="commentCounter3">200</span> شناسه</label>-->
        </td>
    </tr>
	<tr>
    	<td colspan="2">
        <span id="industry_loading"></span>  
        <input type="button" value="به روز رسانی" id="save_industry">
        </td>
    </tr>
</table>
</form>
<div class="extraComments">* موارد ستاره دار الزامیست</div>
</div>
<script>
 
  $('.myForm #save_industry').click(function(){
  	save_industry();
  });
	
 $(".commentTXB3").keydown(function(e) {
		var numb = 200 - $(this).val().length;
	    $(".commentCounter3").text(numb);
 });
 
 
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
							 						
								suggestions.push({'title':val.title,'id':val.id});
								 
								//suggestions.push(val.name+'<input type=hidden id='+val.user_id+'>');
							 
							});
							
							//pass array to callback
							
							add(suggestions);
							 
							 
						});
					},
					select: function(e, ui) {
						
						//create formatted tag
						var tag = ui.item.value;
                        
                        if(tarr.length>10){                          
                            show_warning_msg(_your_tag_is_maxtag);
							return false;
                        }
                        
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
						var hide_input= "<input type='hidden' id='users_tag' name='data[Userrelatetag][usertag_id][]' value='"+id+"' title='"+tag+"' >";
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
 
				

 