<?php
	$User_Info= $this->Session->read('User_Info');
?>
<div class="bg modalCloser"></div>
<div class="dataBox modalMain col-md-8 col-md-offset-2">
<header class="modalHeader">
<div class="closer modalCloser icon-cancel"></div>
<?php echo __('messages') ?>
</header>
<!--
<form class="myForm">
	<div class="col-md-12">
   	  <input type="text" readonly="" class="myFormComponent notTrans" value="@hoceyn">
    </div>
	<div class="col-md-12">
    	<textarea placeholder="متن پیام" class="myFormComponent notTrans" rows="5"></textarea>
  </div>
  <div class="col-xs-6">
    	<button class="green" type="submit">
            <span class="icon icon-mail-alt"></span>
            <span class="text">ارسال پیام</span>
        </button>
    </div>
</form>-->
 
		<div id="MessageBox" class="modalContent">
		        	
		        	<div  >
		              <ul class="icons">
		                  <li class="newMSG_btn">
						  <div class="newMessage"  >
						  	
						  </div><?php echo __('new_message') ?></li>
		              </ul>
		            </div>
		            <div class="contentTag">
		                <ul class="SenderName">
		                	<?php
							pr($chat_list);
							 if(!empty($chat_list))
							 {
							 	foreach($chat_list as $chat)
								{
									if($chat['User']['count']>0){
										echo"<li class='unreaded'>";
									}else echo"<li>";
									echo"
									<div onclick='read_message(".$chat['User']['id'].",this)'>
									";
									if(fileExistsInPath(__USER_IMAGE_PATH.$chat['User']['image'] ) && $chat['User']['image']!='' ) 
										{
											echo $this->Html->image('/'.__USER_IMAGE_PATH.__UPLOAD_THUMB.'/'.$chat['User']['image'],array('id'=>'image_img','class'=>'profile'));
										}
										else{
											if($chat['User']['sex']==0)
											  echo $this->Html->image('profile_women.png',array('id'=>'image_img','class'=>'profile')); else echo $this->Html->image('profile_men.png',array('id'=>'image_img','class'=>'profile'));
										}
									if($chat['User']['count']>0){
										echo $chat['User']['name'].'<span>('.$chat['User']['count'].')</span>';
									}else
										echo $chat['User']['name'];
									echo "
									 </div>
									<div class='delete' onclick='delete_message_confirm(".$chat['User']['id'].",this)'></div>
									</li>
									";
								}
							 }
							?>

		                </ul>
		                <ul class="MessageList">
		                    <div id="read_message_loading"></div>
							<div id="message_content">
								
							</div> 
		                    
							
		                </ul>
						
						<div class="newMSG">
		                	<ul class="friendsList"> 
		                        <span>روی گیرنده مورد نظر کلیک کنید</span>
								<?php
									
									if(!empty($friends)){
										foreach($friends as $friend)
										{
											echo"<li title='@".$friend['User']['user_name']."'>";
											if(fileExistsInPath(__USER_IMAGE_PATH.$friend['User']['image'] ) && $friend['User']['image']!='' ) 
												{
													echo $this->Html->image('/'.__USER_IMAGE_PATH.__UPLOAD_THUMB.'/'.$friend['User']['image'],array('id'=>'image_img','class'=>'profile'));
												}
												else{
													if($friend['User']['sex']==0)
													  echo $this->Html->image('profile_women.png',array('id'=>'image_img','class'=>'profile')); else echo $this->Html->image('profile_men.png',array('id'=>'image_img','class'=>'profile'));
												}
										    echo"<div class='add'></div></li>";	
										}
									
									}
								?>
		                    </ul>
		                    <div class="newComnt">
		                        <form action="" method="get">
		                            <label class="to">به</label>
		                            <input type="text" class="receiver" id="newMSG_user_name" disabled="disabled"/>
		                            <div role="button" class="receiver_show btn" title="افزودن مخاطب">
									<?php   echo $this->Html->image('/img/icons/add2.png');  ?>
									</div>
		                            <input type="button" class="btn cancel" value="لغو" />
		                            <input type="button" onclick="send_one_message()" class="btn ok" value="ارسال" />
									<span id="newMSG_loading"></span>
		                            <div class="space_warper"></div>
		                          <textarea  class="" maxlength="200" id="newMSG_text"></textarea>
		                        </form>
		                    </div>
		              </div>
						
		          </div>
		        </div>
 		
	<footer>
	    <div class="col-sm-12">
	        <button role="button" type="button" id="save_forget_pass" class="myFormComponent green">
	            <span class="text"><?php echo __('send') ?></span>
	            <span class="icon icon-left-open"></span>
	        </button>
	    </div>
	</footer>
  </form>
</div>		
<script>

	//new message :add to message list
	$("#MessageBox .contentTag .newMSG ul.friendsList li .add").click(function(e) {
		var titles = $(this).parent().prop("title");
		$("#MessageBox .contentTag .newMSG form input[type='text']").val(titles);
    });
	
	$(".newMSG_btn").click(function(e) {
        $("#MessageBox .contentTag ul.MessageList").fadeOut(200);
		$("#MessageBox .contentTag .newMSG").delay(200).fadeIn(200);
    });
	
	//new message :show contacts 
	$("#MessageBox .contentTag .newMSG form .receiver_show").click(function(e) {
        $("#MessageBox .contentTag .newMSG .space_warper").slideDown(200);
		$("#MessageBox .contentTag .newMSG ul.friendsList").delay(200).fadeIn(200);
    });
	//new message :cancel contacts 
	$("#MessageBox .contentTag .newMSG form .cancel").click(function(e) {
        $("#MessageBox .contentTag .newMSG").slideUp(200);
		$("#MessageBox .contentTag ul.MessageList").delay(200).fadeIn(200);
	});
		
</script>		
<style>
	#MessageBox .contentTag ul.MessageList .newComnt form
{
	float:right;
	margin:0;
	padding:0;
	position:relative;
}
#MessageBox .contentTag ul.MessageList .newComnt form .commentCloud
{
	position:absolute;
	right:55px;
	top:-20px;
}
#MessageBox .contentTag ul.MessageList .newComnt form input[type="button"]
{
	position:absolute;
	left:0px;
	top:-30px;
}
#MessageBox .contentTag ul.MessageList .newComnt form .commentTXB2
{
	float:right;
	margin:10px 5px 0 0 ;
	width:405px;
	height:30px;
	padding:5px;
	border:none;
	box-shadow:0 0 1px rgba(0,0,0,0.5);
	color:#30709E;
	font-size:12px;
	border-radius:5px;
}
#MessageBox #message_content
{
	height: 350px;
	overflow-y: auto;
}
.message_icon
{
	background-image:url(../img/icons/Mail.png);
}
#MessageBox
{
	width:710px;
	height:545px;
}
#MessageBox .close
{
	position:absolute;
	top:5px;
	left:5px;
	z-index:26;
}
#MessageBox .topTag
{
	width:100%;
	height:70px;
	background:rgba(0,0,0,0.1);
	float:right;
	border-radius:10px 10px 0 0;
	border-bottom:1px solid rgba(0,0,0,0.2);
}
#MessageBox .topTag h1
{
	font-size:14px;
	width:100%;
	height:35px;
	margin:0;
	float:right;
	text-shadow:-1px 1px 1px rgba(255,255,255,1);
	padding:10px 5px 0 0px;
	box-sizing:border-box;
	-moz-box-sizing:border-box;
	-webkit-box-sizing:border-box;
	text-align:center;
}
#MessageBox .topTag ul.icons
{
	float:right;
	margin:5px;
}
#MessageBox .topTag ul.icons li
{
	float:right;
	display:inline-block;
	margin:0px 10px 0 5px;
	color:rgba(153,153,153,1);
	cursor:pointer;
}
#MessageBox .topTag ul.icons li:hover
{
	color:rgba(102,102,102,1);
}
#MessageBox .topTag ul.icons li:hover > div
{
	background-position:right;
}
#MessageBox .topTag ul.icons li .newMessage
{
	float:right;
	margin-top:-5px;
}
#MessageBox .contentTag
{
	width:100%;
	height:100%;
	border-radius:10px 10px 0 0;
	padding-top:70px;
	box-sizing:border-box;
	-moz-box-sizing:border-box;
	-webkit-box-sizing:border-box;
	background: #ffffff; /* Old browsers */
	background: -moz-linear-gradient(top,  #ffffff 0%, #e3f1fc 100%); /* FF3.6+ */
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffffff), color-stop(100%,#e3f1fc)); /* Chrome,Safari4+ */
	background: -webkit-linear-gradient(top,  #ffffff 0%,#e3f1fc 100%); /* Chrome10+,Safari5.1+ */
	background: -o-linear-gradient(top,  #ffffff 0%,#e3f1fc 100%); /* Opera 11.10+ */
	background: -ms-linear-gradient(top,  #ffffff 0%,#e3f1fc 100%); /* IE10+ */
	background: linear-gradient(to bottom,  #ffffff 0%,#e3f1fc 100%); /* W3C */
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#e3f1fc',GradientType=0 ); /* IE6-9 */
}
#MessageBox .contentTag ul.SenderName
{
	width:250px;
	height:100%;
	float:right;
	box-sizing:border-box;
	-moz-box-sizing:border-box;
	-webkit-box-sizing:border-box;
	/*border-bottom:2px rgba(153,153,153,1) solid;*/
	overflow-y: scroll;
	overflow-x: hidden;
}
#MessageBox .contentTag ul.SenderName li
{
	padding:5px;
	/*border-bottom:1px solid rgba(0,0,0,0.1);*/
	color:rgba(102,102,102,1);
	cursor:pointer;
	position:relative;
}
#MessageBox .contentTag ul.SenderName li:hover,
#MessageBox .contentTag ul.SenderName li.active
{
	background-color:rgba(0,0,0,0.1);
}
#MessageBox .contentTag ul.SenderName li.unreaded
{
	font-weight:bold;
	background:none repeat scroll 0 0 rgba(0, 0, 0, 0.1)
}
#MessageBox .contentTag ul.SenderName li .date
{
	color:rgba(153,153,153,1);
	padding-right:5px;
}
#MessageBox .contentTag ul.SenderName li .delete
{
	position:absolute;
	top:2px;
	left:2px;
	width:16px;
	height:16px;
	background:url(../img/icons/delete.png) left;
	opacity:0.2;
}
#MessageBox .contentTag ul.SenderName li .delete:hover
{
	opacity:1;
	background-position:right;
}
#MessageBox .contentTag ul.SenderName li img
{
	margin-left:5px;
	width:40px;
	height:40px;
}
#MessageBox .contentTag ul.MessageList
{
	float:right;
	width:459px;
	height:100%;
	box-sizing:border-box;
	-moz-box-sizing:border-box;
	-webkit-box-sizing:border-box;
	overflow-y: scroll;
	overflow-x: hidden;
	/*border-bottom:2px rgba(153,153,153,1) solid;*/
	position:relative;
}
#MessageBox .contentTag ul.MessageList li
{
	padding:5px;
	line-height:25px;
}
#MessageBox .contentTag ul.MessageList li.sender .name
{
	color:rgba(0,153,102,1);
	font-weight:bold;
}
#MessageBox .contentTag ul.MessageList li.sender .text
{
	color:rgba(153,153,153,1);
}
#MessageBox .contentTag ul.MessageList li.receiver .name
{
	color:rgba(0,102,153,1);
	font-weight:bold;
}
#MessageBox .contentTag ul.MessageList li.receiver .text
{
	color:rgba(103,103,103,1);
}

/*answer*/
#MessageBox .contentTag ul.MessageList .newComnt
{
	background:rgba(204,204,204,0.2);
	position:absolute;
	bottom:0;
	left:0;
	padding:5px;
	height:110px;
	opacity:0.1;
}
#MessageBox .contentTag ul.MessageList .newComnt:hover
{
	opacity:0.8;
}
#MessageBox .contentTag ul.MessageList .newComnt img.profile
{
	float:right;
	width:50px;
	height:50px;
	margin:0 5px -5px 5px;
}
#MessageBox .contentTag ul.MessageList .newComnt form
{
	float:right;
	margin:0;
	padding:0;
	position:relative;
}
#MessageBox .contentTag ul.MessageList .newComnt form .commentCloud
{
	position:absolute;
	right:55px;
	top:-20px;
}
#MessageBox .contentTag ul.MessageList .newComnt form input[type="submit"]
{
	position:absolute;
	left:0px;
	top:-30px;
}
#MessageBox .contentTag ul.MessageList .newComnt form .commentTXB2
{
	float:right;
	margin:10px 5px 0 0 ;
	width:405px;
	height:50px;
	padding:5px;
	border:none;
	box-shadow:0 0 1px rgba(0,0,0,0.5);
	color:#30709E;
	font-size:12px;
	border-radius:5px;
}

/*new message*/
#MessageBox .contentTag .newMSG
{
	float:right;
	width:450px;
	height:100%;
	box-sizing:border-box;
	-moz-box-sizing:border-box;
	-webkit-box-sizing:border-box;
	/*border-bottom:2px rgba(153,153,153,1) solid;*/
	position:relative;
	display:none;
}
#MessageBox .contentTag .newMSG ul.friendsList
{
	height:300px;
	overflow-y: scroll;
	overflow-x: hidden;
	border-bottom:1px solid rgba(204,204,204,1);
	position:absolute;
	top:45px;
	z-index:2;
	display:none;
}
#MessageBox .contentTag .newMSG ul.friendsList span
{
	width:98%;
	margin-right:2%;
	float:right;
}
#MessageBox .contentTag .newMSG ul.friendsList li
{
	width:80px;
	height:80px;
	float:right;
	margin:10px;
	background:rgba(153,153,153,1);
	border-radius:10px;
	overflow:hidden;
	position:relative;
}
#MessageBox .contentTag .newMSG ul.friendsList li img
{
	width:inherit;
	height:auto;
}
#MessageBox .contentTag .newMSG ul.friendsList li .add
{
	position:absolute;
	bottom:0;
	left:0;
	width:100%;
	height:20px;
	background:rgba(255,255,255,0.4) url(../img/icons/addReciever.png) no-repeat left bottom;
	z-index:27;
}
#MessageBox .contentTag .newMSG ul.friendsList li .add:hover
{
	background-position:right;
	background-color:rgba(255,255,255,0.6);
	cursor:pointer;
}
#MessageBox .contentTag .newMSG form
{
	margin:0;
	padding:0;
	height:95%;
	width:450px;
	position:relative;
}
#MessageBox .contentTag .newMSG form label
{
	float:right;
	margin:5px;
}

#MessageBox .contentTag .newMSG form input.btn,
#MessageBox .contentTag .newMSG form .receiver_show
{
	float:right;
	margin:5px;
}
#MessageBox .contentTag .newMSG form .receiver_show
{
	padding:5px;
}
#MessageBox .contentTag .newMSG form .receiver_show img
{
	margin:-0px 0;
	width:20px;
	height:auto;
}
#MessageBox .contentTag .newMSG form input[type="submit"]
{
	float:right;
	margin:5px;
}
#MessageBox .contentTag .newMSG form input[type="text"]
{
	float:right;
	height:27px;
	padding:5px;
	margin:5px;
	width:190px;
}
#MessageBox .contentTag .newMSG form .space_warper
{
	float:right;
	height:300px;
	width:100%;
	display:none;
}
#MessageBox .contentTag .newMSG form textarea
{
	float:right;
	margin:10px 5px 0 0 ;
	width:425px;
	height:70px;
	padding:5px;
	border:none;
	box-shadow:0 0 1px rgba(0,0,0,0.5);
	color:#30709E;
	font-size:12px;
	border-radius:5px;
}
</style>			  