 <?php
 	echo $this->Html->css('/js/crop/jquery-ui/themes/base/jquery-ui');
	echo $this->Html->css('/css/ListSelector/ui-lightness/jquery-ui-1.8.custom'); 
 	echo $this->Html->css('/js/crop/sh_style');
 	echo $this->Html->css('/js/crop/style.jrac');
  
    
    
    
	echo $this->Html->script('/js/crop/jquery-ui');
	echo $this->Html->script('/js/ListSelector/tagcount-jquery-ui-custom.min');
	echo $this->Html->script('/js/crop/sh_main.min');
	echo $this->Html->script('/js/crop/sh_javascript.min');
	echo $this->Html->script('/js/crop/jquery.jrac');
	
	$User_Info= $this->Session->read('User_Info');
 ?>

    
 <div class="col-sm-3 settingOptions">
	<div class="profileData profileImageContainer">
	    <div class="profileImage ">
	        <div class="ax">
		<?php
			echo $this->Gilace->user_image($user['User']['image'],$user['User']['sex'],$user['User']['user_name'],'');
		?>                                     
	        </div>
	        
			<div role="menu" class="edit_image_highlight dropdown">
				<div class="dropdownBtn" >
		            <span class="icon icon-camera"  style="margin-top: 4px;"></span>
		        </div>
                <ul>
                	<li>
						<a  href="javascript:void(0)"
						onClick="popUp('<?php echo  __SITE_URL.'users/image_upload_window' ?>')"
						><?php echo __('upload_new_image') ?></a>
					</li>
				<li><a class="delete_image" href="javascript:void(0)"><?php echo __('delete_image') ?></a></li> 
                </ul>
            </div>
	        <div class="Name">
	            <a class="atSign" href="<?php echo __SITE_URL.$user['User']['user_name'] ?>">@<?php echo $user['User']['user_name']; ?></a>
	        </div>
	    </div>
	</div>
	
 	
	
    <ul>

 <?php
  $User_Info= $this->Session->read('User_Info');
 
	$edit_profile ='';
	$edit_account='';
	$edit_email='';
	$edit_password='';
	$edit_privacy='';
	$edit_send_email='';
	$disable_account='';
	$send_post_with_sms='';
    $post_ads='';
    $edit_feed='';
	$active_infomadan_app = '';
	 
	  switch ($active)
	  {
	  	case 'edit_profile':
			$edit_profile = 'active';					
		break;
		case 'edit_account':
			$edit_account = 'active';					
		break;
		case 'disable_account':
			$disable_account = 'active';					
		break;
		case 'edit_email':
			$edit_email = 'active';					
		break;
		case 'edit_password':
			$edit_password = 'active';					
		break;
		case 'edit_privacy':
			$edit_privacy = 'active';					
		break;
		case 'edit_send_email': 
			$edit_send_email = 'active';
		break;	
		case 'send_post_with_sms':
			$send_post_with_sms = 'active';
		break;
		case 'active_infomadan_app':
			$active_infomadan_app = 'active';
		break;                
        case 'post_ads':
			$post_ads = 'active';
		break;	
		case 'edit_feed':
			$edit_feed = 'active';
		break;
	  }
	?>
        <li class="<?php echo $edit_profile; ?>">
			<a href="<?php echo __SITE_URL.'users/edit_profile' ?>"><?php echo __('edit_all_info') ?></a>
		</li>
        <li class="<?php echo $edit_account; ?>">
			<a href="<?php echo __SITE_URL.'users/edit_account' ?>"><?php echo __('edit_account_info') ?></a>
		</li>
		<li class="<?php echo $disable_account; ?>">
			<a href="<?php echo __SITE_URL.'users/disable_account' ?>"><?php echo __('disable_account') ?></a>
		</li>
        <li class="<?php echo $edit_email; ?>">
			<a href="<?php echo __SITE_URL.'users/edit_email' ?>"><?php echo __('edit_email') ?></a>
		</li>
        <li class="<?php echo $edit_password; ?>">
			<a href="<?php echo __SITE_URL.'users/edit_password' ?>"><?php echo __('edit_pasword') ?></a></li>
        <li class="<?php echo $edit_privacy; ?>">
			<a href="<?php echo __SITE_URL.'privacies/edit' ?>"><?php echo __('privacy') ?></a></li>
        <li class="<?php echo $edit_send_email; ?>">
			<a href="<?php echo __SITE_URL.'sendemails/edit' ?>"><?php echo __('send_email_setting') ?></a>
		</li>
		<?php if($User_Info['country_id']==104){	 ?>
		<li class="<?php echo $send_post_with_sms; ?>">
			<a href="<?php echo __SITE_URL.'sms/verify' ?>"><?php echo __('send_post_with_sms') ?></a>
		</li>
		<?php } ?>
		<?php 
		  if($User_Info['country_id']==104){	
			if($User_Info['user_type']){ ?>
				<li class="<?php echo $active_infomadan_app; ?>">
					<a href="<?php echo __SITE_URL.'appusers/active_app' ?>"><?php echo __('active_infomadan_app') ?></a>
				</li>
		<?php }
			}
		 ?>
        <li class="<?php echo $post_ads; ?>">
			<a href="<?php echo __SITE_URL.'postads/postads_list' ?>"><?php echo __('postads_list') ?></a>
		</li>
		<!--<li class="<?php echo $edit_feed; ?>">
			<a href="<?php echo __SITE_URL.'reader/feeds/edit_feed' ?>"><?php echo __('edit_feed') ?></a>
		</li>-->
	</ul>
 </div>
	 
 
