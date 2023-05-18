
<?php 
echo $this->Html->css('profile_'.$locale); 
 // pr($users);
?>
 <?php echo $this->element('right_panel'); ?>        

	  <div class="mainPanel">
            
            <div class="profileContent">
            	<ul class="posts">
                	<?php
						if(!empty($users))
						{
							foreach($users as $user)
							{
								
							
					 ?>
					<li>
                    	<div class="profileImage">
							<?php  
						  
						if(fileExistsInPath(__USER_IMAGE_PATH.$user['User']['image'] ) && $user['User']['image']!='' ) 
						{
							echo $this->Html->image('/'.__USER_IMAGE_PATH.$user['User']['image'],array('id'=>'image_img'));
						}
						else{
							if($user['User']['sex']==0)
							  echo $this->Html->image('profile_women.png',array('id'=>'image_img')); else echo $this->Html->image('profile_men.png',array('id'=>'image_img'));
						}
						
					 ?>
						</div>
                    	<h2>
                        	<span class="name">
							<a href="<?php echo __SITE_URL.'users/profile/'.$user['User']['id'] ?>">
								<?php echo $user['User']['name']; ?>
							</a>
							</span>
                            <span class="AtAddress"> <?php if(!empty($user['User']['user_name'])) echo '@'.$user['User']['user_name']; ?></span>
                            <span class="icon">
								<img src="images/icons/image.png" width="20" height="20" />
							</span>
                        </h2>
                        <div class="contex">
                             
                        </div>
                        <div class="extraBtn" id="extraBtn_<?php echo $user['User']['id']; ?>">
                        	<?php
							if($user['User']['id']==$user['Follow']['to_user_id']){
								echo"<div class='btn cancel' onclick='not_follow(".$user['User']['id'].");' id='not_follow_btn_".$user['User']['id']."'>
							 ".__('not_follow')."
								
							</div>";
							}
							else
							echo"<div class='btn ok' onclick='follow(".$user['User']['id'].");' id='follow_btn_".$user['User']['id']."'>
							 ".__('follow')."
								
							</div>";
							?>
                        </div>
                    </li>
                    <div class="seperator_Hor"></div>
                	<?php
							}
						}
					?>
                </ul>
          </div>
        </div>

