<?php
$User_Info= $this->Session->read('User_Info');
echo $this->Html->css('/css/ListSelector/autocomplete.css'); 
echo"
	<li>
	<a href='#'>
			<div class='ax'>";
	 
		if(fileExistsInPath(__USER_IMAGE_PATH.$value['User']['image'] ) && $value['User']['image']!='' ) 
			{
				echo $this->Html->image('/'.__USER_IMAGE_PATH.__UPLOAD_THUMB.'/'.$value['User']['image'],array('id'=>'image_img','class'=>'profile'));
			}
			else{
				if($value['User']['sex']==0)
				  echo $this->Html->image('profile_women.png',array('id'=>'image_img','class'=>'profile')); 
				elseif($value['User']['sex']==1) echo $this->Html->image('profile_men.png',array('id'=>'image_img','class'=>'profile'));	
				
				elseif($value['User']['sex']==2) echo $this->Html->image('company.png',array('id'=>'image_img','class'=>'profile'));
				
				
			}
	    echo"
		   </div></a>
		   <div class='mein'>";
		   
	        if($value['User']['id']!=$this->Session->read('Auth.User.id'))	 
			{
				
			  echo "<div class='extraBtn' id='extraBtn_".$value['User']['id']."'>";
				if($value[0]['count']>=1){
					echo"<div class='btn cancel' onclick='not_follow(".$value['User']['id'].");' id='not_follow_btn_".$value['User']['id']."'>
				 ".__('not_follow')."
					
				</div>";
				}
				else
				if(isset($User_Info)){
				echo"<div class='btn ok' onclick='follow(".$value['User']['id'].");' id='follow_btn_".$value['User']['id']."'>
				 ".__('follow')."
					
		 	 </div>
			 </div>
			  ";
			  }else{
					echo"<div class='btn ok' onclick=window.location.href='".__SITE_URL."' >
						 ".__('follow')."
						</div>";
				}
		    }
			
	    	echo"<h1 class='name'><a href='". __SITE_URL.$value['User']['user_name']."'>
			".$value['User']['name']."</a></h1>
	    	<h1 class='atAddress'><a href='". __SITE_URL.$value['User']['user_name']."'>
			@".$value['User']['user_name']."</a></h1>
	        <br />
	    	<h2 class='location'>".$value['User']['location']."</h2>
			<h2 class='location'>".$value['Industry']['title']."</h2>
	    	<h2 class='website'><a href='".$value['User']['site']."'>".$this->Gilace->filter_url($value['User']['site'])."</a></h2>
	        <div class='details'>
			   <div id='view_tags'>";
				$tags=$this->requestAction(__SITE_URL.'usertags/get_user_tag/'.$value['User']['id']);
				 if(!empty($tags)){
					foreach($tags as $tag){
						echo"#<a href='".__SITE_URL."users/search?tag=".$tag['Usertag']['title']."' >".$tag['Usertag']['title']."</a> ";
					}
				}
				//$this->Gilace->filter_editor($value['User']['details']).
			echo"</div></div>
	    </div>
	</li>
	<div class='seperator_Hor'></div>
	";

?>