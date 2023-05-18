<?php
  $User_Info= $this->Session->read('User_Info');
 ?>
 
 <section class="userData " id="profilePanel">
    	<div class="ax">
			<?php  
						  
			if(fileExistsInPath(__USER_IMAGE_PATH.__UPLOAD_THUMB.'/'.$User_Info['image'] ) && $User_Info['image']!='' ) 
			{
				echo $this->Html->image('/'.__USER_IMAGE_PATH.__UPLOAD_THUMB.'/'.$User_Info['image'],array('width'=>160,'height'=>160));
			}
			else
			{				 
			  if($User_Info['sex']==0)     echo $this->Html->image('profile_women.png',array('width'=>160,'height'=>160));
			  elseif($User_Info['sex']==1) echo $this->Html->image('profile_men.png',array('width'=>160,'height'=>160));
			  elseif($User_Info['sex']==2) echo $this->Html->image('company.png',array('id'=>'image_img','class'=>'profile'));
			}
			
			
		   ?>
        </div>
        <div class="data">
        	<span class="userName"><?php echo $User_Info['name']; ?></span>
			<a class="userAtSign" id="userbox_username" href="<?php echo __SITE_URL.$User_Info['user_name']; ?>">@<?php echo $User_Info['user_name']; ?></a>
			<br>

            <span class="minidata">  <?php if(!empty($User_Info['industry_name'])){
										   	 echo $User_Info['industry_name'].'|';
										   }  ?></span>
            <span class="minidata">  <?php if(!empty($User_Info['location'])){
										   	echo $User_Info['location'];
										   }  ?></span>
            <span class="editProfile"><a class="userAtSign" id="userbox_useredit" href="<?php echo __SITE_URL.'users/edit_profile' ?>">
			  <?php echo __('edit_profile') ?>
			 </a>
			 </span>
        </div>
        <ul class="otherBtn">
        	
				<li class="tile size0 skyBlue" onclick="message_box()" id="learn_userbox_message_box">
	                <div class="symbol">
					  <?php  echo $this->Html->image('/img/metroIcons/Blog_2.png',array('height'=>50,'width'=>50,'class'=>'icon'));  ?>
	                </div>
	                <header id="new_message_count"><span id="new_message_loading"></span></header>
	            </li>
			<?php if(isset($User_Info))
				echo "<a href='".__SITE_URL.$User_Info['user_name']."'>";
				else echo "<a href='". __SITE_URL."' > ";
			?> 	
        	<li class="tile size0 lajani" id="learn_userbox_my_post">
                <div class="symbol">
                <span class="data"><span  id="post_count">0</span></span>
                </div>
                <header><?php echo __('my_post') ?></header>
				 
            </li>
			<?php if(isset($User_Info))
						echo "</a>";
			?>
			<?php if(isset($User_Info))
				echo "<a href='".__SITE_URL.$User_Info['user_name']."?follow_payee'>";
				else echo "<a href='". __SITE_URL."' > ";
			?>
	        	<li class="tile size0 blue3">
	                <div class="symbol">
	                <span class="data"><span  id="new_tofollow_count">0</span></span>
	                </div>
	                <header><?php echo __('follow_payees') ?></header>
	            </li>
			<?php if(isset($User_Info))
						echo "</a>";
			?>	
			<?php if(isset($User_Info))
				echo "<a href='".__SITE_URL.$User_Info['user_name']."?chaser'>";
				else echo "<a href='". __SITE_URL."' > ";
			?>
	        	<li class="tile size0 gray2">
	                <div class="symbol">
	                <span class="data" id="new_follow_count"><span id="new_follow_loading"></span></span>
	                </div>
	                <header><?php echo __('chasers') ?> </header>
	            </li>
			<?php if(isset($User_Info))
						echo "</a>";
			?>
			 
        </ul>
        
	        <div class="notification tile size_1 red" style="display:none">
				<div class="symbol">
					<?php  echo $this->Html->image('/img/icons/notification2.png');  ?>
				</div>
	            <header><span></span></header>
	        </div>
		
    </section>
	<?php
		echo $this->Html->css('/js/tab/css/styles');
		echo $this->Html->script('/js/tab/src/js/mtabs.2.2.1.min');
	?>
	<div class="set invitation_tab">
		<div class="panel panel-1">
		<h2><?php echo __('send_invitation_email_title'); ?></h2>
		 	<section id="invitation">
				<form id="invitation_form">
					<label>  <?php echo __('invitation_detail1'); ?></label>
					<div>
						<input type="text" placeholder="<?php echo __('email'); ?>" id="invitation_email" name="invitation_email" /> 
						<input type="submit" value="<?php echo __('send'); ?>" id="invitation_btn" />
					</div>
					<label>  <?php echo __('invitation_detail2'); ?></label> 
					<span id="loading"> </span>
				</form>
		    </section>
		</div>
		<div class="panel panel-2">
		<h2><?php echo __('send_invitation_sms_title'); ?></h2>
		 	<section id="invitation" class="invitation_sms">
				<form id="invitation_sms_form">
					<label>  <?php echo __('invitation_sms_detail1'); ?></label>
					<div>
						<input type="text" placeholder="<?php echo __('sms'); ?>" id="invitation_sms" name="invitation_sms" /> 
						<input type="submit" value="<?php echo __('send'); ?>" id="invitation_sms_btn" />
					</div>
					<label>  <?php echo __('invitation_sms_detail2'); ?></label> 
					<span id="loading"> </span>
				</form>
		    </section>
		</div>
    </div>
		<script>
			$(function() {
				$(".invitation_tab").mtabs();
			});
		</script>
			
	<section id="new_notification" >
    	<header><?php echo __('users_for_follow') ?> <a  href="<?php echo __SITE_URL.'users/search?user_type=0'; ?>"> 
		<?php echo __('all_users'); ?> </a></header> 
		<div id="body"></div>
    </section>
	
	 