
<?php 

	$this->Gilace->autoCompileLess(ROOT.DS.APP_DIR.DS.'webroot'.DS.'profile_'.$locale. DS .'index.less', ROOT.DS.APP_DIR.DS.'webroot'.DS.'css'.DS.'profile_'.$locale.'.css');

	echo $this->Html->css('profile_'.$locale); 
	echo $this->Html->script('jquery-ui-1.10.3.custom.min');
	echo $this->Html->script('profile');
	
	echo $this->Html->css('/css/ListSelector/autocomplete.css'); 
	echo $this->Html->css('/css/ListSelector/ui-lightness/jquery-ui-1.8.custom'); 
	echo $this->Html->script('/js/ListSelector/jquery-ui-custom.min');

	$User_Info= $this->Session->read('User_Info');
   // pr($user);
   
?>
 
	
<script type="text/javascript">
	_selected_max_user= "<?php echo __('selected_max_user') ?>";
</script>	
<style>
#content .profileHeader .coverImage .item1
{
	background:url(<?php 
	
	if(fileExistsInPath(__USER_IMAGE_PATH.$user['User']['cover_image'] ) && $user['User']['cover_image']!='' ) {
			echo __SITE_URL.__USER_IMAGE_PATH.$user['User']['cover_image']; 
		}
	else{
			if($user['User']['sex']==0)
			 echo __SITE_URL.'img/cover_women.jpg'; 
			 else echo __SITE_URL.'img/cover_men.jpg';  
		}
	
	?>);
}
	
</style>
 <?php echo $this->element('right_panel'); ?>        
    
	 <?php   
	   	if(!empty($user)){
	   ?>
	 <section id="mainPanel">
       
		    <div class="profileHeader">
                <div class="coverImage">
                	<div class="item1">
					   
                        <div class="middleContent">
                            <div class="profileImage"  >
                                <?php  
						  
							if(fileExistsInPath(__USER_IMAGE_PATH.$user['User']['image'] )&& $user['User']['image']!='' ) 
							{
								echo $this->Html->image('/'.__USER_IMAGE_PATH.__UPLOAD_THUMB.'/'.$user['User']['image'],array('id'=>'image_img'));
							}
							else{
								if($user['User']['sex']==0)
								  echo $this->Html->image('profile_women.png',array('id'=>'image_img')); 
								elseif($user['User']['sex']==1) echo $this->Html->image('profile_men.png',array('id'=>'image_img'));
								elseif($user['User']['sex']==2) echo $this->Html->image('company.png',array('id'=>'image_img'));
							}
							
						 ?>
                            </div>
                            <span><?php echo $user['User']['name']; ?></span>
                            <div class="AtAddress">@<?php echo $user['User']['user_name']; ?></div>
                        </div>
						
                    </div>
                	<div class="item2">
                        <div class="middleContent">
                            <div class="profileImage">
                                <?php  
						  
								if(fileExistsInPath(__USER_IMAGE_PATH.$user['User']['image'] ) && $user['User']['image']!='' ) 
								{
									echo $this->Html->image('/'.__USER_IMAGE_PATH.$user['User']['image'],array('id'=>'image_img'));
								}
								else{
									if($user['User']['sex']==0)
									  echo $this->Html->image('profile_women.png',array('id'=>'image_img')); 
									elseif($user['User']['sex']==1) echo $this->Html->image('profile_men.png',array('id'=>'image_img'));
									elseif($user['User']['sex']==2) echo $this->Html->image('company.png',array('id'=>'image_img'));
								}
								
							 ?>
                            </div>
                            <h1 class="name"><?php echo $user['User']['name']; ?></h1>
                            <h2 class="AtAddress">@<?php echo $user['User']['user_name']; ?></h2>
                            <h2 class="location"><?php echo $user['User']['location']; ?> 
							| <?php echo $user['Industry']['title']; ?> 
							  
							 </h2>
							 <?php 
							 	if(isset($user['User']['job_status'])&&!empty($user['User']['job_status'])&&$user['User']['job_status']!=0)
								{
									echo "
										<h2 class='location'>";
										if($user['User']['job_status']==1 || $user['User']['job_status']==2){
											if( isset($user['User']['degree'])){
												switch($user['User']['degree']){
													case 1:
														echo __('phd');
														break;
													case 2:
														echo __('ma');
														break;
													case 3:
														echo __('bachelor');
														break;	
													case 4:
														echo __('diploma');
														break;
													case 5:
														echo __('diplom');
														break;			
												}
											}
											if( isset($user['User']['university_name'])){
												echo" | ".$user['User']['university_name'];
											}
										}Elseif($user['User']['job_status']==3)
										{
											if( isset($user['User']['job_title'])){
												echo $user['User']['job_title'];
											}
											if( isset($user['User']['company_name'])){
												echo" | ".$user['User']['company_name'];
											}
										}
									echo"</h2>"; 
								}
							 ?>
							 
                            <h2 class="website"><a href="<?php echo $user['User']['site']; ?>" target="_blank">
							<?php echo $this->Gilace->filter_url($user['User']['site']); ?></a></h2>
                            <h3 class="more">
							<div id='view_tags' > 
								<?php
									 if(!empty($tags)){
										foreach($tags as $tag){
											echo"#<a href='".__SITE_URL."users/search?tag=".$tag['Usertag']['title']."' >".$tag['Usertag']['title']."</a>";
										}
									}
								 ?>	
							  </div>
                            </h3>
                        </div>
                    </div>
                </div>
                <ul class="amars withSeperator">
                    <li>
                        <span><?php echo $post_count; ?></span>
                        <span><a href="<?php echo __SITE_URL.$user['User']['user_name']; ?>"><?php echo __('my_posts') ?></a></span>
                    </li>
                    <li>
                        <span><a href="<?php echo "http://".$_SERVER['HTTP_HOST'].$this->here.'?follow_payee'; ?>">
						<?php echo $follow_me; ?></a></span>
                        <span><a href="<?php echo "http://".$_SERVER['HTTP_HOST'].$this->here.'?follow_payee'; ?>"> 
						 <?php echo __('follow_payee') ?></a></span>
                    </li>
                    <li>
                        <span><a href="<?php echo "http://".$_SERVER['HTTP_HOST'].$this->here.'?chaser'; ?>"><?php echo $me_follow; ?></a></span>
                        <span>
							<a href="<?php echo "http://".$_SERVER['HTTP_HOST'].$this->here.'?chaser'; ?>">  
							<?php echo __('follower') ?></a>
						</span> 
						 
                    </li>
					<li>
					    <span>&nbsp;</span>
						<span>
                          <?php
                              if(!empty($user['User']['pdf']))
                              {
                                  echo "<a target='_blank' href='". __SITE_URL.__USER_FILE_PATH.$user['User']['pdf'] ."'>  
							". __('my_resume')."</a>";
                              }else
                              {
                              ?>
                               
                           <?php      
                              }
                          ?>
                        
                        
  
						</span>
					</li>
                </ul>
                 <div id="follow_btns" style="float: left;margin-left: 10px;" class="extraBtn">
				<?php

					if($user['User']['id']!=$this->Session->read('Auth.User.id'))
					{
						if($is_follow>0){
							echo"<div class='btn cancel' onclick='profile_not_follow(".$user['User']['id'].");' id='not_follow_btn_".$user['User']['id']."' style='padding: 6px;'>
						 ".__('not_follow')."
							
						</div>";
						}
						else
						if(isset($User_Info)){
						echo"<div class='tile btn blue1' onclick='profile_follow(".$user['User']['id'].");' id='follow_btn_".$user['User']['id']."'>
						 ".__('follow')."
							
						</div>";
						}else{
								echo"<div class='tile btn blue1' onclick=window.location.href='".__SITE_URL."' >
									 ".__('follow')."
									</div>";
						}
					}else
					{
						
						echo"
						 <div id='messageBtn' class='tile btn blue1'>
		                  <a href='JavaScript:void(0);' onclick='message_box();'>
		                    <header>".__('send_message')." </header>
		                    <div class='symbol'>
		                      <div class='topMenuIcons MenuMessage'></div> 
		                    </div>   
		                  </a>
		                   <div class='notifications' id='newprofile_message_count'><span id='newprofile_message_loading'></span></div>
		                </div>
							
						 
						<a href='".__SITE_URL."users/edit_profile'>
						<div   id='follow_btn_".$user['User']['id']."' class='tile btn blue1'>
						 ".__('edit_info')."  
							
						</div></a>
						
						 
						";
					}	 
						
				?>
				</div>
				<?php if(isset($User_Info)){ ?>
                 <div class="profileMenu">
                    <?php if($user['User']['id']!=$this->Session->read('Auth.User.id'))
					{
						?>
					<div class="menuBtn" id="profile_menu">
                    </div>
					
                    <div class="submenu">
                        <div class="trail"></div>
                        <ul class="menuItem">
                             <li onclick="send_friend_message_form(<?php echo $user['User']['id']; ?>,'<?php echo $user['User']['name'] ?>',0);"><?php echo __('send_message') ?> </li>
							 <div class="seperator_Hor"></div>
                            <li>
								<?php
								 if($is_block>=1){
								?>
									<div  id="block_btn" onclick="unblock_user(<?php echo $user['User']['id']; ?>)">
										<?php echo __('unblock'); ?>
									</div>	
								<?php } else { ?>	
								<div  id="block_btn" onclick="block_user(<?php echo $user['User']['id']; ?>)">
									<?php echo __('block'); ?>
								</div>	
								<?php } ?>							
								<span id="block_loading"></span>
							</li> 
                            <div class="seperator_Hor"></div>
							 <li onclick="send_infraction_report_form(<?php echo $user['User']['id']; ?>);">
							 <?php echo __('send_infraction_report') ?> </li>
                             
                        </ul>
                    </div>
					<?php } ?>
               </div> 
			   <?php } ?>
            </div>
            <div class="profileContent">
             
                
				<?php
				   
				  if(!empty($chaser)&&isset($chaser))
				  {
				  	?>
					<div class="searchResult">
					<ul class="result" id="profile_result_body">
						 <script>
							var count=0;
							profile_chaser(count,<?php echo $user_id; ?>);	
								
							$(window).scroll(function(){  
								if  ($(window).scrollTop() == $(document).height() - $(window).height()){    
								   count++; 
								   profile_chaser(count,<?php echo $user_id; ?>);
								}  
							});
						 </script>  
					 </ul>
					 <div id="profile_result_loading"></div> 
				   </div>
				<?php
				  }
				 
				?>
				
				<?php
				   
				  if(!empty($follow_payee)&&isset($follow_payee))
				  {
				  	?>
					<div class="searchResult">
					<ul class="result" id="profile_result_body">
						 <script>
							var count=0;
							profile_follow_payee(count,<?php echo $user_id; ?>);	
								
							$(window).scroll(function(){  
								if  ($(window).scrollTop() == $(document).height() - $(window).height()){    
								   count++; 
								   profile_follow_payee(count,<?php echo $user_id; ?>);
								}  
							});
						 </script>  
					 </ul>
					 <div id="profile_result_loading"></div> 
				   </div>
				<?php
				  }
				 
				?>
				
				<section id="mainPanel">
				  <div id="home_body"></div>
				  <div id="home_loading"></div>
				<?php
				  
				  
				  if(!empty($user_post)&&isset($user_post))
				  {	
				?>
				 <script>
					var count=0;
					refresh_profile_post(count,<?php echo $user_id; ?>);	
						
					$(window).scroll(function(){  
						if  ($(window).scrollTop() == $(document).height() - $(window).height()){    
						   count++; 
						   refresh_profile_post(count,<?php echo $user_id; ?>);
						}  
					}); 
				 </script>  
				<?php

				  }
				
				?>
				</section>   

             
          </div>
        </section>
		
		<?php }
		 else{
		 	echo " <div class='mainPanel'>". $this->Session->flash()."</div>";
			
		 }
		?>






<?php
  $User_Info= $this->Session->read('User_Info');
  if(isset($User_Info) && ($User_Info['sex']!=-1 || $User_Info['industry_id']!=0 || $User_Info['user_type']!=1) && $User_Info['follow_count']>=5){
	  if($user_id!=$User_Info['id']){
		  $LearnObjectInfos= $this->Session->read('LearnObjectInfo');
		 
		  if(!empty($LearnObjectInfos)){
		  	foreach ($LearnObjectInfos as $key=>$LearnObjectInfo  )
			{
				if($LearnObjectInfo['learn_object_id']==2)
				{
					if($LearnObjectInfo['count']==0){
						echo "<script> show_learn(".$User_Info['id'].",2,".$LearnObjectInfo['parent_id'].",'".$LearnObjectInfo['object_created']."','".$LearnObjectInfo['user_created']."'); </script>";
					}				
				}
			}
		  } 
	  }
  }
 ?>

<!-- Tip Content -->
    <ol id="ProfilePageTipContent">
	
      <li data-id="profile_menu" data-text="<?php echo __('next') ?>" >
        <h2><?php echo __('learn_profile_menu') ?></h2>
        <p><?php echo __('learn_profile_menu_detail') ?></p>
      </li>
	  <li data-id="follow_btns" data-text="<?php echo __('close') ?>" >
        <h2><?php echo __('learn_profile_follow_btn') ?></h2>
        <p><?php echo __('learn_profile_follow_btn_detail') ?></p>
      </li>
    </ol>
  







<script>
		


_send_infraction_report="<?php echo __('send_infraction_report') ?>"; 
_enter_infraction_report="<?php echo __('enter_infraction_report') ?>"; 
function send_infraction_report_form(id) {
	
	TINY.box.show({url:_url+'infractionreports/send_infraction_report',
				   post:'action=load_page&id='+id,
				   opacity:50,
				   topsplit:2}
				   );					
} 

</script>
 