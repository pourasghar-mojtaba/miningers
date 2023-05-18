<?php
 
if (!empty($users))
{
	 
	foreach($users as $user)
	{
		echo " <div>
			   <a href='".__SITE_URL.$user['User']['user_name']."'> ";
			   if(fileExistsInPath(__USER_IMAGE_PATH.$user['User']['image'] ) && $user['User']['image']!='' ) 
				{
					echo $this->Html->image('/'.__USER_IMAGE_PATH.__UPLOAD_THUMB.'/'.$user['User']['image'],array('id'=>'image_img','class'=>'profile'));
				}
				else{
					if($user['User']['sex']==0)
					  echo $this->Html->image('profile_women.png',array('id'=>'image_img','class'=>'profile')); 
					elseif($user['User']['sex']==1) echo $this->Html->image('profile_men.png',array('id'=>'image_img','class'=>'profile'));
					elseif($user['User']['sex']==2) echo $this->Html->image('company.png',array('id'=>'image_img','class'=>'profile'));
				}
			   
			   echo	$user['User']['name']." &nbsp; ".$user['User']['user_name']."@<br/>
			   </a>	 
			   </div>
			    
			  ";	 
	}
	
	
}else if(!empty($posts)){
 	foreach($posts as $post)
	{
		//print_r($post);
		echo " <div>
			   <a href='".__SITE_URL."posts/view/".$post['Post']['id']."'> ";
			   if(fileExistsInPath(__USER_IMAGE_PATH.$post['User']['image'] ) && $post['User']['image']!='' ) 
				{
					echo $this->Html->image('/'.__USER_IMAGE_PATH.__UPLOAD_THUMB.'/'.$post['User']['image'],array('id'=>'image_img','class'=>'profile'));
				}
				else{
					if($post['User']['sex']==0)
					  echo $this->Html->image('profile_women.png',array('id'=>'image_img','class'=>'profile')); 
					elseif($post['User']['sex']==1) echo $this->Html->image('profile_men.png',array('id'=>'image_img','class'=>'profile'));
					elseif($post['User']['sex']==2) echo $this->Html->image('company.png',array('id'=>'image_img','class'=>'profile'));
				}
			   
			   echo	$post['User']['name']." &nbsp; ".$post['User']['user_name']."@ : ".$post['0']['body']."<br/>
			   </a>	 
			   </div>
			    
			  ";	 
	}
 }else if(!empty($post_tags)){
 	foreach($post_tags as $post)
	{
		//print_r($post);
		echo " <div>
			   <a href='".__SITE_URL."posts/view/".$post['Post']['id']."'> ";
			   if(fileExistsInPath(__USER_IMAGE_PATH.$post['User']['image'] ) && $post['User']['image']!='' ) 
				{
					echo $this->Html->image('/'.__USER_IMAGE_PATH.__UPLOAD_THUMB.'/'.$post['User']['image'],array('id'=>'image_img','class'=>'profile'));
				}
				else{
					if($post['User']['sex']==0)
					  echo $this->Html->image('profile_women.png',array('id'=>'image_img','class'=>'profile')); 
					elseif($post['User']['sex']==1) echo $this->Html->image('profile_men.png',array('id'=>'image_img','class'=>'profile'));
					elseif($post['User']['sex']==2) echo $this->Html->image('company.png',array('id'=>'image_img','class'=>'profile'));
				}
			   
			   echo	$post['User']['name']." &nbsp; ".$post['User']['user_name']."@ : ".$post['0']['body']."<br/>
			   </a>	 
			   </div>
			    
			  ";	 
	}
 }
else

	echo"
			  <div >
				".__('not_exist')."
			  </div>";	
	
 
?>

 