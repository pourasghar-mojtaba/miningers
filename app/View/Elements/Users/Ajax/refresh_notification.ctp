 
<?php 
	//pr($notifications);
	if(!empty($notifications)){
	?>  
    
		<?php
			if(!empty($notifications)){
				foreach($notifications as $notification)
				{
					echo "<li>";
					if(fileExistsInPath(__USER_IMAGE_PATH.__UPLOAD_THUMB.'/'.$notification['User']['image'] ) && $notification['User']['image']!='' ) 
						{
							echo $this->Html->image('/'.__USER_IMAGE_PATH.__UPLOAD_THUMB.'/'.$notification['User']['image'],array('width'=>160,'height'=>160));
						}
						else
						{
						  if($notification['User']['sex']==0)
							 echo $this->Html->image('profile_women.png',array('width'=>160,'height'=>160));
						  elseif ($notification['User']['sex']==1) echo $this->Html->image('profile_men.png',array('width'=>160,'height'=>160));
						  elseif ($notification['User']['sex']==2) echo $this->Html->image('company.png',array('id'=>'image_img','class'=>'profile'));	  
						}
						
						
					switch($notification['Notification']['notification_type']){
						case 0:
						echo $notification['User']['name']." <a class='userAtSign' href='".__SITE_URL.$notification['User']['user_name']."'>@".$notification['User']['user_name']."</a> ".__('follow_with_you');
						break;
						
						case 1:
                         if($notification['Notification']['type']==1){
                            echo "<div id='text'>".$notification['User']['name']."<a class='userAtSign' href='".__SITE_URL.$notification['User']['user_name']."'>@".$notification['User']['user_name']."</a>".__('post_for_you').": <a  href='".__SITE_URL.'posts/view/'.$notification['Notification']['notification_id']."'>".$this->Gilace->filter_editor($notification['Notification']['notification_body'])." ... </a></div>"; 
                         }elseif($notification['Notification']['type']==2){
                             echo "<div id='text'>".$notification['User']['name']."<a class='userAtSign' href='".__SITE_URL.$notification['User']['user_name']."'>@".$notification['User']['user_name']."</a>".__('used_your_user_name').": <a  href='".__SITE_URL.'posts/view/'.$notification['Notification']['notification_id']."'>".$this->Gilace->filter_editor($notification['Notification']['notification_body'])." ... </a></div>"; 
						}elseif($notification['Notification']['type']==3){
                             echo "<div id='text'>".$notification['User']['name']."<a class='userAtSign' href='".__SITE_URL.$notification['User']['user_name']."'>@".$notification['User']['user_name']."</a>".__('answer_on_post_you_answerd').": <a  href='".__SITE_URL.'posts/view/'.$notification['Notification']['notification_id']."'>".$this->Gilace->filter_editor($notification['Notification']['notification_body'])." ... </a></div>"; 
						}
						break;
						
						case 2:
						echo "<div id='text'>".$notification['User']['name']." <a class='userAtSign' href='".__SITE_URL.$notification['User']['user_name']."'>@".$notification['User']['user_name']."</a> ".__('share_your_post').": <a  href='".__SITE_URL.'posts/view/'.$notification['Notification']['notification_id']."'>".$this->Gilace->filter_editor($notification['Notification']['notification_body'])." ... </a></div>";
						break;
						
						case 3:
						echo "<div id='text'>".$notification['User']['name']." <a class='userAtSign' href='".__SITE_URL.$notification['User']['user_name']."'>@".$notification['User']['user_name']."</a> ";
						if($notification['Notification']['type']==0){
							echo __('unlike_your_post');
						}elseif($notification['Notification']['type']==1){
							echo __('like_your_post');
						}echo": <a  href='".__SITE_URL.'posts/view/'.$notification['Notification']['notification_id']."'>".$this->Gilace->filter_editor($notification['Notification']['notification_body'])." ... </a></div>";	
						break;
						case 4:
						echo "<div id='text' >".$notification['User']['name']." <a class='userAtSign' href='".__SITE_URL.$notification['User']['user_name']."'>@".$notification['User']['user_name']."</a> ".__('follow_you')." </div>";
						break;
						case 5:
						if($notification['Notification']['type']==0){
							echo "<div id='text'>".$notification['User']['name']." <a class='userAtSign' href='".__SITE_URL.$notification['User']['user_name']."'>@".$notification['User']['user_name']."</a> ".__('published_blog').": <a  href='".__SITE_URL.'blogs/view/'.$notification['Notification']['notification_id']."'>".$this->Gilace->filter_editor($notification['Notification']['notification_body'])." ... </a></div>";
						}	
						
						if($notification['Notification']['type']==1){
							echo "<div id='text'>".$notification['User']['name']." <a class='userAtSign' href='".__SITE_URL.$notification['User']['user_name']."'>@".$notification['User']['user_name']."</a> ".__('edit_published_blog').": <a  href='".__SITE_URL.'blogs/view/'.$notification['Notification']['notification_id']."'>".$this->Gilace->filter_editor($notification['Notification']['notification_body'])." ... </a></div>";
						}
						
						break;
					}	
					 if($locale =='eng')
                         echo "<span>".date(" l ، j F Y    ",strtotime($notification['Notification']['created']))."</span>";
                     if($locale =='per')
                         echo "<span>".$this->Gilace->show_persian_date(" l ، j F Y    ",strtotime($notification['Notification']['created']))."</span>";
					
					echo "</li>";
				}
			}
		}
	?>
	
	
	 