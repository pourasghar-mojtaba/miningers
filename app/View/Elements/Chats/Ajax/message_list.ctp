<?php
	$User_Info= $this->Session->read('User_Info');
 
	 
	if(!empty($message_list)){
		foreach($message_list as $message)
		{
			if($User_Info['id']!=$message['User']['id']){
				echo "
					<div class='recieverName'>
		                <div class='text'>
		                    <span>".$message['User']['name']."</span>
		                    <a href='".__SITE_URL.$message['User']['user_name']."' class='atSign'>@".$message['User']['user_name']."</a>
		                </div>
		            </div>
		            <div class='reciever'>
		            	<div class='trail'></div>
		                <div class='time'>".$this->Gilace->show_persian_date(" l ، j F Y - H:m   ",strtotime($message['Chat']['sent']))."</div>
		                <div class='text'>".$this->Gilace->filter_editor($message['Chat']['message'])."</div>
		            </div>
				";
			}
			else{
				echo "
					<div class='sender'>
		            	<div class='trail'></div>
		                <div class='time'>".$this->Gilace->show_persian_date(" l ، j F Y - H:m   ",strtotime($message['Chat']['sent']))."</div>
		                <div class='text'>".$this->Gilace->filter_editor($message['Chat']['message'])."</div>
		            </div>
				";
			}
		}
	} 
	 
?>

<div class="sender">
	<div class="trail"></div>            	
	<form class="myForm">
    	<textarea class="messageTextarea" id="inbox_message_content" rows="4" placeholder="<?php echo __('enter_your_message_here'); ?>"></textarea>
		<span id="inbox_message_loading"></span>
    </form>
    <Script>
		$('.messageTextarea').on('keyup', function(e) {
			if (e.which == 13 && ! e.shiftKey) {
				//$(this).parent().submit();
				send_message(<?php echo $user_id ?>);
			}
		});
    </Script>
</div>
<div class="clear"></div>

<!--<div class="newComnt">
					
    <?php echo $this->Html->image('/'.__USER_IMAGE_PATH.__UPLOAD_THUMB.'/'.$User_Info['image'],array('class'=>'profile')); ?>
    <form action="" method="get">
        <?php  echo $this->Html->image('/img/icons/comment_cloud.png',array('class'=>'commentCloud')); ?>
        <input type="button" onclick="send_message(<?php echo $user_id ?>);" class="btn ok" value=" <?php echo __('send') ?>" />
		<span id="inbox_message_loading"></span>
        <textarea  class="commentTXB2" maxlength="200" id="inbox_message_content"></textarea>
    </form>
</div>-->