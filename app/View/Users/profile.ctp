<?php 

	$this->Gilace->autoCompileLess(ROOT.DS.APP_DIR.DS.'webroot'.DS.'less_'.$locale. DS .'profile.less', ROOT.DS.APP_DIR.DS.'webroot'.DS.'css'.DS.'profile_'.$locale.'.css');

	echo $this->Html->css('profile_'.$locale); 
	echo $this->Html->css('userlist_'.$locale);
	//echo $this->Html->script('jquery-ui-1.10.3.custom.min');
	echo $this->Html->script('profile');
	
	echo $this->Html->css('/css/ListSelector/autocomplete.css'); 
	echo $this->Html->css('/css/ListSelector/ui-lightness/jquery-ui-1.8.custom'); 
	echo $this->Html->script('/js/ListSelector/jquery-ui-custom.min');
    
    echo $this->Html->script('jquery.cropit_profile');
	echo $this->Html->script('jquery.form');
	
	echo $this->Html->script('/js/mosaic/js/jquery.jMosaic');
	echo $this->Html->css('/js/mosaic/css/jquery.jMosaic'); 

	$User_Info= $this->Session->read('User_Info');
 
     if(fileExistsInPath(__USER_IMAGE_PATH.$user['User']['cover_image'] ) && $user['User']['cover_image']!='' ) {
	$backimg =__SITE_URL.__USER_IMAGE_PATH.$user['User']['cover_image']; 
	}
	else{
		if($user['User']['sex']==0)
		 $backimg = __SITE_URL."img/cover_women.jpg" ; 
		 else $backimg = __SITE_URL."img/cover_men.jpg" ; 
		}
?>
	<div class="profileCover">
        	<div class="image-editor"> 
        	 <div class="cropit-image-preview-container">
        		<div class="cropit-image-preview"  >		
        		</div>
        	 </div>       
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
                        <a class="atSign" href="<?php echo __SITE_URL.$user['User']['user_name'] ?>">@<?php echo $user['User']['user_name']; ?></a>
                    </div>
                </div>
            </div>
        </div>
		 
		<div class="col-md-4">
            <div class="profileData">
                <div class="otherData">
                    <h1 class="naem"><?php echo $user['User']['name']; ?></h1>
                    <div class="detail"><?php echo $user['User']['headline']; ?></div>
                    <div class="location"><span class="icon icon-location-1">
					</span> <?php echo $user['Industry']['title']; ?>  
					<?php if(!empty($user['Country']['country_name'])) echo ' - '.$user['Country']['country_name']; ?>   
					<?php if(!empty($user['User']['location'])) echo ' - '.$user['User']['location']; ?>   </div>
                    <?php if(!empty($user['User']['site'])){ ?>
					<div class="web">
					<span class="icon icon-link"></span>
					<a href="<?php echo $user['User']['site']; ?>" >
					<?php echo $this->Gilace->filter_url($user['User']['site']); ?></a>
					</div>
					<?php } ?>
                </div>
            </div>
        </div>
		<div class="col-md-5 embedEdit">
			<?php if(!empty($User_Info)){ ?>
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
		<?php } ?>
            <br>
			<?php
				if($user['User']['id']!=$User_Info['id'])
					{
						if($is_follow>0){
							echo"
							<div class='col-sm-5' id ='follow_btns'>
				                <div class='btn green' onclick='profile_not_follow(".$user['User']['id'].");' id='not_follow_btn_".$user['User']['id']."'>
				                    <span class=''>".__('not_follow')."</span>
				                </div>
				            </div>
							";
						}
						else
						if(isset($User_Info)){
						echo"
						<div class='col-sm-5' id ='follow_btns'>
			                <div class='btn blue' onclick='profile_follow(".$user['User']['id'].");' id='follow_btn_".$user['User']['id']."'>
			                    <span class=''>".__('follow')."</span>
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
					                <div class='btn dark' onclick=window.location.href='".__SITE_URL."'>
					                    <span  style='float:right;margin-right: 10px'></span>
					                    <span class=''>".__('send_message')."</span>
					                </div>
					            </div>
							";
						}else
						{
						?>
						  
								<div class='col-sm-5' onClick="popUp('chats/send_friend_message','action=load_page&name=<?php echo $user['User']['name']?>&id=<?php echo $user['User']['id'] ?>')">
					                <div class='btn dark' >
					                    <span  style='float:right;margin-right: 10px'></span>
					                    <span class=''><?php echo __('send_message')?></span>
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
							<div class='col-sm-5' onClick=popUp('".__SITE_URL."chats/message_box')>
				                <div class='btn dark' >
				                    <span class='icon icon-mail-alt' style='float:right;margin-right: 10px'></span>";
									if($new_message_count>0){
                                        echo "<span class='counter' style='background: none repeat scroll 0 0 red;border: 1px solid red;border-radius: 24px;float: left;margin: 2px;width: 28px;margin-left: 10px'>".$new_message_count."</span>";
                                    }
                                    
                                    echo"
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
            <?php echo $this->element('user_box',array('user_id'=>$user_id,'is_profile'=>TRUE)); ?>
        </aside>
		<?php		   
		  if(!isset($_REQUEST['chaser']) && !isset($_REQUEST['follow_payee']))
		  {
		  ?>
         <section class="postsPlace col-md-6">
			<div id="home_body">
				
			</div>
			
			<div id="home_loading"></div>
			<div class="col-xs-12" id="new_post_profile" style="display:none">
				<?php
					 if($User_Info['id']!=$user['User']['id']){
					 	echo $user['User']['name'].' '.__('not_send_posts').'.';
					 }
					 else{
					 	echo '<label>'.__('not_send_post_send_new_post').'</label>';
						echo "<div class='btn classic' onClick=popUp('".__SITE_URL."posts/post_window')>
				                <span class='text'>".__('send_post')."</span>
				                <span class='icon icon-left-open'></span>
				            </div>";
					 }
					 
				?> 
				</div>
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
		<?php
		  }		 
		?>
		<?php		   
		  if(isset($_REQUEST['chaser']))
		  {
		  	
		  ?>
		  		
				<section class="mainSection col-md-6 col-sm-9">        
			        <div class="dataBox userListBox" >
			            <div id="profile_result_body"></div>
			            <div id="profile_result_loading"></div>
			        </div>
			    </section>
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
<?php
		  }		 
		?>
		
		<?php		   
		  if(isset($_REQUEST['follow_payee']))
		  {
		  ?>
				<section class="mainSection col-md-6 col-sm-9">        
			        <div class="dataBox userListBox" >
			            <div id="profile_result_body"></div>
			            <div id="profile_result_loading"></div>
			        </div>
			    </section>
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
		<?php
		  }		 
		?>
		
		
		
        <aside class="col-md-3">
            <?php
				if(!empty($post_images)){			
			?>
			<aside class="dataBox big_box">
			<p><?php echo __('image_posts'); ?></p>
			 	<section id="invitation">
					 <div class="pictures">
						<?php
							foreach($post_images as $post_image){
								//echo"<img src='".__SITE_URL.__POST_IMAGE_PATH.$post_image['Post']['image']."'/>";
								echo"<a class='date' href='".__SITE_URL."posts/view/".$post_image['Post']['id']."'>";
								echo $this->Html->image('/'.__POST_IMAGE_PATH.$post_image['Post']['image'],array('width'=>'70px','height'=>'70px','class'=>'post_image')); 
								echo "</a>";
							}
						?>
					 </div>
					<p><a href="<?php echo __SITE_URL.'posts/post_image/'.$user['User']['id'] ?>">
					<?php echo __('all_image_posts'); ?></a></p>
			    </section>
			</aside>
			<?php } ?>
			<?php echo $this->element('left_profile'); ?>
        </aside>
        <div class="clear"></div>	
        
        <script>
            $('.image-editor').cropit({
              exportZoom: 0.1,      
              imageState: {
                src: '<?php echo $backimg; ?>' ,
    			zoom:'<?php echo $user["User"]["cover_zoom"]; ?>',
    			offset:{x:'<?php echo $user["User"]["cover_x"]; ?>',y:'<?php echo $user["User"]["cover_y"]; ?>'} 
              }
            });	
            refresh_notification(0);
			// $('.pictures').jMosaic({min_row_height: 50, margin: 3, is_first_big: true});
			//$('.pictures').jMosaic({items_type: "img", margin: 3, is_first_big: true,min_row_height: 50});
        </script>