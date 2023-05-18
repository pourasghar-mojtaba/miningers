<?php
	$User_Info= $this->Session->read('User_Info');
	//echo $this->Html->css('/css/ListSelector/autocomplete.css'); 
	echo $this->Html->css('/css/ListSelector/ui-lightness/jquery-ui-1.8.custom'); 
	echo $this->Html->script('/js/ListSelector/jquery-ui-custom.min');
?>
<script>
_enter_text= "<?php echo __('enter_text') ?>";
_selected_max_user= "<?php echo __('selected_max_user') ?>";
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
<div class="dataBox modalMain col-md-7 col-md-offset-3">
<header class="modalHeader">
<div class="closer modalCloser icon-cancel"></div>
<?php echo __('messages') ?>

<div class="col-xs-3">
    <div class="tile btn blue newMsgBtn">
        <span class="icon icon-plus"></span>
        <span class="text"><?php echo __('new_message') ?></span>
    </div>
</div>
</header>
<section class="modalContent" id="msg">
	<div class="col-sm-5">
    	<div class="dataBox usersList">
        	<ul>
            	<?php
					 if(!empty($chat_list))
					 {
					 	foreach($chat_list as $chat)
						{
							if($chat['User']['count']>0){
								echo"<li class='active' onclick='read_message(".$chat['User']['id'].",this)'>";
							}else echo"<li onclick='read_message(".$chat['User']['id'].",this)'>";
							echo"<div class='ax'>";
							echo $this->Gilace->user_image($chat['User']['image'],$chat['User']['sex'],$chat['User']['user_name'],'');
							echo"</div> <div class='text'>";
							echo "<span>";
								if($chat['User']['count']>0){
								echo $chat['User']['name'].'<span id="new_message_count">('.$chat['User']['count'].')</span>';
								}else
									echo $chat['User']['name'];	
							echo "</span>";
							
							echo "
							 </div>
							<div class='delete icon-cancel' onclick='delete_message_confirm(".$chat['User']['id'].",this)'></div>
							</li>
							";
						}
					 }
					?>
				<!--
            	<li class="active">
                	<div class="ax"><img src="images/profile.jpg" width="160" height="160"  alt=""/></div>
                    <div class="text">
                    	<span>ali samadi</span>
                    	<span class="atSign">@hoceyn</span>
                    </div>
                </li>-->
            	
            </ul>
            <div class="clear"></div>
        </div>
    </div>
	<div class="col-sm-7">
    	<div class="dataBox msgList">
			<div id="read_message_loading"></div>
			<div id="message_content"></div>       
        </div>
    </div>
	<div class="clear"></div>
</section>
<section id="newMsg">
    <form class="myForm">
        <div class="col-md-12">
			
			<input type="text" id="friend_input" class="myFormComponent notTrans" placeholder="<?php echo __('enter_the_recipient_name') ?>">
			<div id="tag_adsForm">
			  <div id="tags"  >
          		<div id="tag_place"></div>
				<div class="clear"></div>
				
		  	  </div>
			</div>
        </div>
        <div class="col-md-12">
            <textarea rows="5" id="new_message_content" class="myFormComponent notTrans" placeholder="<?php echo __('message_content') ?>"></textarea>
      </div>
      <div class="col-xs-6">
            <button class="myFormComponent red" id="cancelNewMsg" type="button">
                <span class="icon icon-cancel"></span>
                <span class="text"><?php echo __('cancel') ?></span>
            </button>
      </div>
      <div class="col-xs-6">
            <button class="myFormComponent green" type="button" id="send_message_btn">
                <span class="icon icon-mail-alt"></span>
                <span class="text"><?php echo __('send') ?></span>
            </button>
      </div>
    </form>
</section>
<footer class="modalFooter">
            <div class="btn modalCloser">
                <span class="icon icon-globe-1"></span>
                <span class="text"><?php echo __('close') ?></span>
            </div>
</footer>
</div>
<script>
	modalCloser();
	makeCenterVer($("#modal .modalMain"));
	$('.newMsgBtn').click(function(e) {
        $('#msg').slideUp(200,function()
		{
			$('#newMsg').slideDown(200);
		});
    });
	$('#cancelNewMsg').click(function(e) {
        $('#newMsg').slideUp(200,function()
		{
			$('#msg').slideDown(200);
		});        
    });


$('.myForm #send_message_btn').click(function(){
  	send_friend_message();
  });

function send_friend_message()
{  
	$("#message_loading").html('<img width="24" src="'+_url+'/img/loader/metro/preloader-w8-cycle-white.gif"" >');
	var IDs = [];
    var arr = $("#tags [id]").map(function() {
	 var filter = /^([0-9])+$/;
	 if (filter.test(this.id))  
			IDs.push(this.id); 
	});
		 
	var message = $("textarea#new_message_content").val();
	
	if(String.trim(message) ==''){
		show_warning_msg(_enter_text);
		$("#message_loading").empty();
		return;
	}
	
	$.ajax({
		type: "POST",
		url: _url+'chats/send_friend_message',
		data: 'id='+IDs+'&message='+message+'&action=send',
		dataType: "json",
		success: function(response)
		{
		 
		 if(response.success == true) {			
			if( response.message ) {
				show_success_msg(response.message);	
                remove_modal();	
			} 	
			send_privacy_email(IDs,'onmessage',message);
		}
		else 
		 {
			if( response.message ) {
				show_error_msg(response.message);
			}  
		 }
		 $("#message_loading").empty();
		 
		}
	
	  });
}
	
/* search users */
var tarr =[];
var arr = [];	 
$("#friend_input").autocomplete({
	//define callback to format results
	
	source: function(req, add){
	
		//pass request to server
		$.getJSON(_url+"chats/friend_search?callback=?", req, function(data) {
			
			//create array for response objects
			var suggestions = [];
			//var suggestions1 = [];
			//process response
			$.each(data, function(i, val){								
				suggestions.push({'name':val.name,'id':val.user_id,'image':val.image});
				 
				//suggestions.push(val.name+'<input type=hidden id='+val.user_id+'>');
			 
			});
			
			//pass array to callback
			
			add(suggestions);
			 
			 
		});
	},
	select: function(e, ui) {
		
		//create formatted friend
		var friend = ui.item.value;
		var user_count= $("#tags span").length+1; 
		/*
		tarr.push(friend);
		 alert(tarr.length);
		for (var i=0;i<=tarr.length;i++)
		{
			 if(i>0){
			 	if(tarr[i]==friend){
				showmsg(_not_repeated_user_to_send_message);return false;
			 }else tarr=arr;
			 }  
		}
		arr.push(friend);
		tarr=arr;*/
		if(user_count>5){
			showmsg(_selected_max_user);return;
		}
		var id= ui.item.id;
			span = $("<span>").text(friend),
			a = $("<a>").addClass("remove").attr({
				href: "javascript:",
				title: "Remove " + friend,
				id   : id,
			}).text("x").appendTo(span);
		
		//add friend to friend div
		 
		$('#tag_place').append(span);
	},
	
	//define select handler
	change: function() {
		
		//prevent 'to' field being updated and correct position
		$("#friend_input").val("").css("top", 2);
	}
});

//add click handler to user_ids div
$("#tags").click(function(){
	
	//focus 'to' field
	$("#friend_input").focus();
});

//add live handler for clicks on remove links
$(".remove", document.getElementById("tags")).live("click", function(){

	//remove current friend
	$(this).parent().remove();
	
	//correct 'to' field position
	if($("#tags span").length === 0) {
		$("#friend_input").css("top", 0);
	}				
});


/**
* /
*/	
	
	
</script>

	