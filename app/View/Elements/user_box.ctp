<?php
  $User_Info= $this->Session->read('User_Info');
  
  if(empty($user_id)){
  	$user_id = 0;
  }
  
  $user_box=$this->requestAction(__SITE_URL.'users/get_user_info/'.$user_id);

  	
 ?>
 <aside class="big_dataBox firstPanel" style="margin-top: 10px;">
    
	<?php if(isset($is_profile) && $is_profile=TRUE){ ?>
	<header>
        <h3><?php echo __('about_me'); ?></h3>
    </header>
    <article class="dataArticle">
         <?php
             
			 if(!empty($user_box['user_info']['User']['details'])){
			 	 echo $user_box['user_info']['User']['details']; 
			 }
			else 
			{
				if($User_Info['id']!=$user_id){
					echo $user_box['user_info']['User']['name'].' '.__('not_write_details').'.';
				}else echo __('you_not_write_details').'.';			 
			}
			
			 
			 
			 if(!empty($user_box['user_info']['User']['telephon'])){
			 	echo '<br><b>'.__('telephon').' : </b>'.$user_box['user_info']['User']['telephon'];
			 }
			 if(!empty($user_box['user_info']['User']['fax'])){
			 	echo '<br><b>'.__('fax').' : </b>'.$user_box['user_info']['User']['fax'];
			 }
			 /*if(!empty($user_box['user_info']['User']['location'])){
			 	echo '<br><b>'.__('location').' : </b>'.$user_box['user_info']['User']['location'];
			 }*/
			 if(!empty($user_box['user_info']['User']['address'])){
			 	echo '<br><b>'.__('address').' : </b>'.$user_box['user_info']['User']['address'];
			 }
			  
          /*   
		 if(!empty($user_box['tags'])){
			foreach($user_box['tags'] as $tag){
				echo"#<a href='".__SITE_URL."users/search?tag=".$tag['Usertag']['title']."' >".$tag['Usertag']['title']."</a>";
			}
		  }*/
								 
         ?>
         <?php
            if(!empty($user_box['user_info']['User']['pdf'])){
        ?>
        <!-- <div class="tile btn dark fullCol">
            <span class="icon icon-user-1"></span>
            <span class="text">
                <?php
                    echo "<a target='_blank' href='". __SITE_URL.__USER_FILE_PATH.$user_box['user_info']['User']['pdf']."'>  
							". __('my_resume')."</a>";
                ?>
            </span>
         </div>-->
        <?php
         }
        ?> 
    </article >
	<?php
         }
     ?>
	 <a href="<?php echo __SITE_URL.$user_box['user_info']['User']['user_name'].'?chaser'; ?>" style="text-decoration: none;">
	    <div class="tile size100 free blue_light fullCol">  
	        <span class="icon">
	         <?php
	            echo $user_box['follow_count']; 
	         ?> 
	        </span>
	        <span class="text"><?php echo __('chasers') ?></span>
	    </div>
	</a>
	<a href="<?php echo __SITE_URL.$user_box['user_info']['User']['user_name'].'?follow_payee'; ?>" style="text-decoration: none;">
	    <div class="tile size100 free blue fullCol">
	        <span class="icon">
	         <?php
	            echo $user_box['tofollow_count']; 
	         ?>
	        </span>
	        <span class="text"><?php echo __('follow_payees') ?></span>
	    </div>
	</a>
	<a href="<?php echo __SITE_URL.$user_box['user_info']['User']['user_name']; ?>" style="text-decoration: none;">
	    <div class="tile size100 free green fullCol">
	        <span class="icon">
	         <?php
	            echo $user_box['post_count']; 
	         ?>            
	         </span>
	        <span class="text"><?php echo __('my_post') ?></span>
	    </div>
	</a>
	<?php if(!empty($User_Info) ){ 
		if($User_Info['id'] == $user_id && $this->request->params['action']=='profile'){ 
	?>
	
	    <!--<div class="tile size100 free orange fullCol" onClick="popUp('<?php echo  __SITE_URL.'chats/message_box' ?>')">
	        <span class="icon">
	         <?php
	            echo $user_box['new_message_count']; 
	         ?>
	        </span>
	        <span class="text"><?php echo __('message') ?></span>
	    </div>-->
		<a href="<?php echo __SITE_URL.'posts/favorite'; ?>" style="text-decoration: none;">
			<div class="tile size100 free orange fullCol" >
		        <span class="icon">
		         <?php
		            echo $user_box['favorite_count']; 
		         ?>
		        </span>
		        <span class="text"><?php echo __('favorite_post') ?></span>
		    </div>
		</a>
		<?php
		if(!empty($user_box['tags'])){
		?>
		<aside class="dataBox big_box">		
			<div id="body">
				<?php
						foreach($user_box['tags'] as $tag){
							echo"#<a href='".__SITE_URL."users/search?tag=".$tag['Usertag']['title']."' >".$tag['Usertag']['title']."</a>";
						}
				?>
			</div>
		</aside>
		<?php } ?>
		
 
	<?php 
	 }elseif($this->request->params['action']!='profile')
		 {
		 	
	 ?>
	 			<!--<div class="tile size100 free orange fullCol" onClick="popUp('<?php echo  __SITE_URL.'chats/message_box' ?>')">
			        <span class="icon">
			         <?php
			            echo $user_box['new_message_count']; 
			         ?>
			        </span>
			        <span class="text"><?php echo __('message') ?></span>
			    </div>-->
				<a href="<?php echo __SITE_URL.'posts/favorite'; ?>" style="text-decoration: none;">
					<div class="tile size100 free orange fullCol" >
				        <span class="icon">
				         <?php
				            echo $user_box['favorite_count']; 
				         ?>
				        </span>
				        <span class="text"><?php echo __('favorite_post') ?></span>
				    </div>
				</a>
	 <?php		
			
		 }
	} ?>
</aside>
