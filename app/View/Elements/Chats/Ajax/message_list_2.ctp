<?php
	$User_Info= $this->Session->read('User_Info');
 
	 
	if(!empty($message_list)){
		foreach($message_list as $message)
		{
			echo"
				<li>
		        	<span class='name'>".$message['User']['name']." ".$message[0]['sent']." :</span>
		            <span class='text'>".$this->Gilace->filter_editor($message['Chat']['message'])."</span>
		        </li>
			";
		}
	} 
	 
?>

<div class="newComnt">
					
    <?php echo $this->Html->image('/'.__USER_IMAGE_PATH.__UPLOAD_THUMB.'/'.$User_Info['image'],array('class'=>'profile')); ?>
    <form action="" method="get">
        <?php  echo $this->Html->image('/img/icons/comment_cloud.png',array('class'=>'commentCloud')); ?>
        <input type="button" onclick="send_message(<?php echo $user_id ?>);" class="btn ok" value=" <?php echo __('send') ?>" />
		<span id="inbox_message_loading"></span>
        <textarea  class="commentTXB2" maxlength="200" id="inbox_message_content"></textarea>
    </form>
</div>