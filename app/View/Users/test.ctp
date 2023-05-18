<?php 

	$this->Gilace->autoCompileLess(ROOT.DS.APP_DIR.DS.'webroot'.DS.'less_'.$locale. DS .'profile.less', ROOT.DS.APP_DIR.DS.'webroot'.DS.'css'.DS.'profile_'.$locale.'.css');

	echo $this->Html->css('profile_'.$locale); 
	echo $this->Html->script('jquery-ui-1.10.3.custom.min');
	echo $this->Html->script('profile');
	
	echo $this->Html->css('/css/ListSelector/autocomplete.css'); 
	echo $this->Html->css('/css/ListSelector/ui-lightness/jquery-ui-1.8.custom'); 
	echo $this->Html->script('/js/ListSelector/jquery-ui-custom.min');

	$User_Info= $this->Session->read('User_Info');
    
   pr($user);
?>

 		<div class="profileCover">
        	<div class="ax">
			   <?php 	
				if(fileExistsInPath(__USER_IMAGE_PATH.$user['User']['cover_image'] ) && $user['User']['cover_image']!='' ) {
				echo "<img src='".__SITE_URL.__USER_IMAGE_PATH.$user['User']['cover_image']."' width='1920' height='1080' >"; 
					}
				else{
					if($user['User']['sex']==0)
					 echo "<img src='".__SITE_URL."img/cover_women.jpg' width='1920' height='1080' >"; 
					 else echo "<img src='".__SITE_URL."img/cover_men.jpg' width='1920' height='1080' >"; 
					}
				
				?>
            </div>
        </div>
        <div class="col-md-3">
            <div class="profileData profileImageContainer">
                <div class="profileImage ">
                    <div class="ax">
					<?php
						echo $this->Gilace->user_image($user['User']['image'],$user['User']['sex'],$user['User']['user_name'],'');
					?>
                    </div>
                    <div class="Name">
                        <a class="atSign">@<?php echo $user['User']['user_name']; ?></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="profileData">
                <div class="otherData">
                    <span class="fontSize17"><?php echo $user['User']['name']; ?></span>
                    <span><?php echo $user['User']['location']; ?>  | <?php echo $user['Industry']['title']; ?> </span>
					<span><a href="<?php echo $user['User']['site']; ?>" target="_blank">
							<?php echo $this->Gilace->filter_url($user['User']['site']); ?></a></span>
                </div>
            </div>
        </div>
        <div class="col-md-5 embedEdit">
        	<?php if($user['User']['id']!=$User_Info['id']){ ?>
			<div class="dropdown editIcon" role="menu">
                <div class="icon icon-ellipsis dropdownBtn"></div>
                <ul>
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
                    <li onclick="popUp('<?php echo __SITE_URL; ?>infractionreports/send_infraction_report','action=load_page&id=<?php echo $user['User']['id'] ?>');">
						 <?php echo __('send_infraction_report') ?>
					</li>
                </ul>
            </div>
            <?php } ?>
            <br>
			
			<?php
				if($user['User']['id']!=$User_Info['id'])
					{
						if($is_follow>0){
							echo"
							<div class='col-sm-5' id ='follow_btns'>
				                <div class='tile blockTile orange btn' onclick='profile_not_follow(".$user['User']['id'].");' id='not_follow_btn_".$user['User']['id']."'>
				                    <span class='icon icon-user-add-1'></span>
				                    <span class='text'>".__('not_follow')."</span>
				                </div>
				            </div>
							";
						}
						else
						if(isset($User_Info)){
						echo"
						<div class='col-sm-5' id ='follow_btns'>
			                <div class='tile blockTile orange btn' onclick='profile_follow(".$user['User']['id'].");' id='follow_btn_".$user['User']['id']."'>
			                    <span class='icon icon-user-add-1'></span>
			                    <span class='text'>".__('follow')."</span>
			                </div>
			            </div>";
						}else{
								echo"
									<div class='col-sm-5'>
						                <div class='tile blockTile orange btn' onclick=window.location.href='".__SITE_URL."'>
						                    <span class='icon icon-user-add-1'></span>
						                    <span class='text'>".__('follow')."</span>
						                </div>
						            </div>";
						}
						if(!isset($User_Info)){
							echo "
								<div class='col-sm-5'>
					                <div class='tile blockTile dark btn' onclick=window.location.href='".__SITE_URL."'>
					                    <span class='icon icon-mail-alt' style='float:right;margin-right: 10px'></span>
					                    <span class='text'>".__('send_message')."</span>
					                </div>
					            </div>
							";
						}else
						{
						?>	 
								<div class='col-sm-5' onClick="popUp('chats/send_friend_message','action=load_page&name=<?php echo $user['User']['name']?>&id=<?php echo $user['User']['id'] ?>')">
					                <div class='tile blockTile dark btn' >
					                    <span class='icon icon-mail-alt' style='float:right;margin-right: 10px'></span>
					                    <span class='text'><?php echo __('send_message')?></span>
					                </div>
					            </div>
							
						<?php
						}
						
						
					}else
					{
						
						echo"
						<div class='col-sm-5'>
			                <div class='tile blockTile orange btn' onclick=window.location.href='".__SITE_URL."users/edit_profile'>
			                    <span class='icon icon-user-add-1'></span>
			                    <span class='text'>".__('edit_info')." </span>
			                </div>
			            </div>
						";
						
						echo "
							<div class='col-sm-5' onClick='popUp('header/modal.sendMsg.html')'>
				                <div class='tile blockTile dark btn' >
				                    <span class='icon icon-mail-alt' style='float:right;margin-right: 10px'></span>
									<span class='counter' style='background: none repeat scroll 0 0 red;border: 1px solid red;border-radius: 24px;float: left;margin: 2px;width: 28px;margin-left: 10px'>23</span>
				                    <span class='text'>".__('messages')."</span>
				                </div>
				            </div>
						";
					}
			?>
			
            <div class='col-md-2'></div>
        </div>
        <div class="clear"></div>
        <aside class="col-md-3 firstPanel">
            <?php echo $this->element('user_box'); ?>
        </aside>
        <section class="postsPlace col-md-6">
			<div id="profile_body"></div>
			<div id="profile_loading"></div>			
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
        </section>
        <aside class="col-md-3">
            <div class="dataBox"></div>
        </aside>
        <div class="clear"></div>
        
      