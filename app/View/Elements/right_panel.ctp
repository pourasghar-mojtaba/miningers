<?php
  $User_Info= $this->Session->read('User_Info');
  if(isset($User_Info)) $notifications=$this->requestAction(__SITE_URL.'users/get_notofication_list/');
  
 ?>
<aside id="rightpanel">
	<?php  if(isset($User_Info)) echo $this->element('user_box'); else echo '&nbsp';
		if(isset($User_Info)){
	?> 
	<?php if($this->request->params['controller']=='users'&&$this->request->params['action']=='profile'){ ?>
	<!--
    <section id="new_notification">
		<a href="<?php echo __SITE_URL.'posts/post_image/'.$user_id ?>"> Post image </a>
    </section> -->
	
	<?php } ?>
	
	
	<?php 
	if(!empty($notifications)){
	?>  
    <section class="nano has-scrollbar" id="notificationBox">
        <ul class="content" tabindex="0" style="right: -17px;">
		<?php
			if(!empty($notifications)){
				foreach($notifications as $notification)
				{
					echo "<li>";
					if(fileExistsInPath(__USER_IMAGE_PATH.__UPLOAD_THUMB.'/'.$notification['UserNotification']['image'] ) && $notification['UserNotification']['image']!='' ) 
						{
							echo $this->Html->image('/'.__USER_IMAGE_PATH.__UPLOAD_THUMB.'/'.$notification['UserNotification']['image'],array('width'=>160,'height'=>160));
						}
						else
						{
						  if($notification['UserNotification']['sex']==0)
							 echo $this->Html->image('profile_women.png',array('width'=>160,'height'=>160));
						  elseif ($notification['UserNotification']['sex']==1) echo $this->Html->image('profile_men.png',array('width'=>160,'height'=>160));
						  elseif ($notification['UserNotification']['sex']==2) echo $this->Html->image('company.png',array('id'=>'image_img','class'=>'profile'));	  
						}
						
						
					switch($notification['UserNotification']['notification_type']){
						case 0:
						echo $notification['UserNotification']['name']." <a class='userAtSign' href='".__SITE_URL.$notification['UserNotification']['user_name']."'>@".$notification['UserNotification']['user_name']."</a> ".__('follow_with_you');
						break;
						
						case 1:
                         if($notification['UserNotification']['type']==1){
                            echo "<div id='text'>".$notification['UserNotification']['name']."<a class='userAtSign' href='".__SITE_URL.$notification['UserNotification']['user_name']."'>@".$notification['UserNotification']['user_name']."</a>".__('post_for_you').": <a  href='".__SITE_URL.'posts/view/'.$notification['UserNotification']['notification_id']."'>".$this->Gilace->filter_editor($notification['UserNotification']['notification_body'])." ... </a></div>"; 
                         }elseif($notification['UserNotification']['type']==2){
                             echo "<div id='text'>".$notification['UserNotification']['name']."<a class='userAtSign' href='".__SITE_URL.$notification['UserNotification']['user_name']."'>@".$notification['UserNotification']['user_name']."</a>".__('used_your_user_name').": <a  href='".__SITE_URL.'posts/view/'.$notification['UserNotification']['notification_id']."'>".$this->Gilace->filter_editor($notification['UserNotification']['notification_body'])." ... </a></div>"; 
						}
						break;
						
						case 2:
						echo "<div id='text'>".$notification['UserNotification']['name']." <a class='userAtSign' href='".__SITE_URL.$notification['UserNotification']['user_name']."'>@".$notification['UserNotification']['user_name']."</a> ".__('share_your_post').": <a  href='".__SITE_URL.'posts/view/'.$notification['UserNotification']['notification_id']."'>".$this->Gilace->filter_editor($notification['UserNotification']['notification_body'])." ... </a></div>";
						break;
						
						case 3:
						echo "<div id='text'>".$notification['UserNotification']['name']." <a class='userAtSign' href='".__SITE_URL.$notification['UserNotification']['user_name']."'>@".$notification['UserNotification']['user_name']."</a> ";
						if($notification['UserNotification']['type']==0){
							echo __('unlike_your_post');
						}elseif($notification['UserNotification']['type']==1){
							echo __('like_your_post');
						}echo": <a  href='".__SITE_URL.'posts/view/'.$notification['UserNotification']['notification_id']."'>".$this->Gilace->filter_editor($notification['UserNotification']['notification_body'])." ... </a></div>";	
						break;
						case 4:
						echo "<div id='text'>".$notification['UserNotification']['name']." <a class='userAtSign' href='".__SITE_URL.$notification['UserNotification']['user_name']."'>@".$notification['UserNotification']['user_name']."</a> ".__('follow_you')." </div>";
						break;
					}	
					 echo "<span>".$this->Gilace->show_persian_date(" l ، j F Y    ",strtotime($notification['UserNotification']['created']))."</span>";
					
					echo "</li>";
				}
			}
			
		?>
        </ul>
		
        
    <div class="pane"><div class="slider" style="height: 103px; top: 0px;"></div></div>
	<?php
		}
		?>
	
	
	</section>
	<?php } ?>
	
	<?php if(isset($User_Info)){ ?>
	<section id="new_tag">
     	<header><?php echo __('hot_subject') ?> <a  href="<?php echo __SITE_URL.'posts/all_tags'; ?>"> <?php echo __('all_subject'); ?> </a></header> 
		<div id="body"></div>
    </section>
    <?php } ?>
	 
	
</aside>

<?php
	if(isset($User_Info)) echo "<script>
	$('#notificationBox').nanoScroller();
</script>";
?>
