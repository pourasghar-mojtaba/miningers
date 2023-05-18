<?php
$User_Info= $this->Session->read('User_Info');
echo $this->Html->css('/css/ListSelector/autocomplete.css'); 
echo"	
	<div class='userList'>
        <div class='userItem'>
            <div class='profImage'>
                <div class='ax'>";
				if(fileExistsInPath(__USER_IMAGE_PATH.$value['User']['image'] ) && $value['User']['image']!='' ) 
				{
					echo $this->Html->image('/'.__USER_IMAGE_PATH.__UPLOAD_THUMB.'/'.$value['User']['image'],array('height'=>'160','width'=>'160'));
				}
				else{
					if($value['User']['sex']==0)
					  echo $this->Html->image('profile_women.png',array('height'=>'160','width'=>'160')); 
					elseif($value['User']['sex']==1) echo $this->Html->image('profile_men.png',array('height'=>'160','width'=>'160'));	
					
					elseif($value['User']['sex']==2) echo $this->Html->image('company.png',array('height'=>'160','width'=>'160'));	
				}
		   echo"</div>
            </div>
            <div class='profText'>
                <div><strong>".$value['User']['name']."</strong>
					<a href='". __SITE_URL.$value['User']['user_name']."' class='atSign'>@".$value['User']['user_name']."</a>
				</div>";
                if(!empty($value['User']['location']) || !empty($value['Industry']['title'])){
					echo "
						<div class='location'><span class='icon icon-location-1'></span>";
						if(!empty($value['User']['location'])) echo $value['User']['location'];
						if(!empty($value['User']['location']) && !empty($value['Industry']['title'])) echo " - ";
						if(!empty($value['Industry']['title'] )) echo $value['Industry']['title'] ;
				 echo" </div>";
				}
				if($value['User']['site']!=''){
					echo"<div class='web'><span class='icon icon-link'></span>
						<a href='".$value['User']['site']."'>".$this->Gilace->filter_url($value['User']['site'])."</a>
					</div>";
				}  
            echo"</div>
        </div>
        <div class='describe'>";
        $tags=$this->requestAction(__SITE_URL.'usertags/get_user_tag/'.$value['User']['id']);
		 /*
		 if(!empty($tags)){
			foreach($tags as $tag){
				echo"#<a href='".__SITE_URL."users/search?tag=".$tag['Usertag']['title']."' >".$tag['Usertag']['title']."</a> ";
			}
		}*/
		echo $this->Gilace->filter_editor($value['User']['details']);
   echo"</div>
        <div class='row'>
            <div class='col-xs-12'>";
              if($value['User']['id']!=$this->Session->read('Auth.User.id'))	 
			  {  
				echo "<div class='extraBtn' id='extraBtn_".$value['User']['id']."'>";
					if(!empty($User_Info)){
						if($value[0]['count']>=1){
							echo "
							<div class='btn green' onclick='not_follow(".$value['User']['id'].");' id='not_follow_btn_".$value['User']['id']."'>
			                    <span>".__('not_follow')."</span>
			                    <!--<span class='icon icon-left-open'></span>-->
			                </div>
						  ";
						}else{
							echo "
							<div class='btn blue' onclick='follow(".$value['User']['id'].");' id='follow_btn_".$value['User']['id']."'>
			                    <span class=''>".__('follow')."</span>
			                    <!--<span class='icon icon-left-open'></span>-->
			                </div>
						  ";
						}	
					}
					else{
						echo "
							<div class='btn blue' onclick=window.location.href='".__SITE_URL."'>
			                    <span class=''>".__('follow')."</span>
			                    <!--<span class='icon icon-left-open'></span>-->
			                </div>
						";
					}
					
				echo "</div>";
			  }
       echo"</div>
        </div>
        <div class='clear'></div>
    </div>";


?>