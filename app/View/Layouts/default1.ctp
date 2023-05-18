<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

//$cakeDescription = __d('cake_dev', __('site_title'));
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php if(isset($title_for_layout)) echo  $title_for_layout ?> 
	</title>
	
	
	<meta name="keywords" content="<?php if(isset($keywords_for_layout)) echo $keywords_for_layout ?>"/>
    <meta name="description" content="<?php if(isset($description_for_layout)) echo $description_for_layout; ?>">   

	<meta name="copyright" content="madaner.ir" />
	<META NAME="ROBOTS" CONTENT="INDEX, FOLLOW">
	<script type="text/javascript">
		_url = '<?php echo __SITE_URL  ?>';
		_loading="<?php echo __('loading') ?>"; 
		_close= "<?php echo __('close') ?>";
		_message= "<?php echo __('message') ?>";
		_cancel= "<?php echo __('cancel') ?>";
		_warning= "<?php echo __('warning') ?>";
	</script>
	
	<?php
		echo $this->Html->meta('icon');
		//echo $this->Html->css('/css/LightFace/LightFace_'.$locale);		
		echo $this->Html->css('global_per');
		echo $this->Html->css('tiles_'.$locale);		
		echo $this->Html->css('index_'.$locale);
		echo $this->Html->css('forms');
		echo $this->Html->css('/js/tinybox2/style');	
		echo $this->Html->css('/js/Zebra_Dialog-master/public/css/zebra_dialog.css');
        echo $this->Html->css('/js/joyride/joyride'); 
        
		echo $this->Html->script('jquery-1.7.2.min');
	    echo $this->Html->script('jquery-ui-1.10.3.custom.min');
		echo $this->Html->script('/js/tinybox2/tinybox');
	    echo $this->Html->script('/js/Zebra_Dialog-master/public/javascript/zebra_dialog');
		echo $this->Html->script('function');
		echo $this->Html->script('global');
		
		
		
        echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
        // debug($locale) ;
		$User_Info= $this->Session->read('User_Info');
	?>
</head>
<body>
    
<?php 	
 
 
 if($this->Session->check('User_Info')  ) {
    
	echo $this->Html->css('nanoscroller'); 
	echo $this->Html->script('jquery.form');
	$User_Info= $this->Session->read('User_Info');
	$tag_count= $this->Session->read('tag_count');
	 
	if($this->Session->check('User_Info')){ 
	 
		if($User_Info['sex']==-1 || $User_Info['industry_id']==0 || $User_Info['user_type']==1 || $tag_count <=0){
		 	 echo"<script>statusBar();</script>";
		 }
         elseif($User_Info['follow_count']<5 ){
		 	 echo"<script>follow_box();</script>";
		 }
		 
	 }
  echo $this->Html->script('/js/jquery.nanoscroller.min');	
  
  echo $this->Html->script('/js/joyride/jquery.joyride');
  
  
?>
	       
 	
		
			<div id="header">
		        <div class="content">
		            <div id="logo"> 
                     <a href="http://madaner.ir/">
						<?php  echo $this->Html->image('/img/icons/logo.png',array('width'=>275,'height'=>68));  ?>
                     </a>   
		            </div>
					<?php if($this->Session->check('User_Info')){ ?>
		            <ul class="topMenu">
				  
					  <li class="tile btn blue1" id="topmenu_home">
		                  <a href="<?php echo __SITE_URL; ?>">
		                    <header><?php echo __('home'); ?></header>
		                    <div class="symbol">
		                      <div class="topMenuIcons MenuHome"></div> 
		                    </div>   
		                  </a>
	                  </li>
					  
		               
					  
					  <li class="tile btn blue1" id="topmenu_users">
		                  <a href="<?php echo __SITE_URL.'users/search?user_type=0'; ?>">
		                    <header><?php echo __('people') ?></header>
		                    <div class="symbol">
		                      <div class="topMenuIcons MenuPeople"></div> 
		                    </div>   
		                  </a>
	                  </li>
					  
					   <li class="tile btn blue1" id="topmenu_favorite">
		                  <a href="<?php echo __SITE_URL.'posts/favorite'; ?>">
		                    <header><?php echo __('my_favorite_post') ?></header>
		                    <!--<div class="symbol">
		                      <div class="topMenuIcons MenuPeople"></div> 
		                    </div>   -->
		                  </a>
	                  </li>
					  
					    
					  
					   <li class="tile btn green2 newNews" id="topmenu_newNews">
		                  <a href="JavaScript:void(0);">
		                    <header><?php echo __('new_post'); ?></header>
		                    <div class="symbol">
		                      <div class="topMenuIcons MenuCompose"></div> 
		                    </div>   
		                  </a>
		               </li>
					  
		              <!--<a href="<?php echo __SITE_URL.'users/search?user_type=1'; ?>"><li> 
						  <?php  echo $this->Html->image('/img/icons/companies.png',array('width'=>35,'height'=>35));  ?>
		                  <span><?php echo __('companies') ?></span>
		              </li></a> -->
					   
		            </ul>
					<div id="NewPost" style="display: none;">
		            	<div class="toptrail"></div>	
		                <div class="newComnt" style="display: block;">
		                    <?php echo $this->Form->create('Post', array('id'=>'AddPostForm','name'=>'AddPostForm','enctype'=>'multipart/form-data','action'=>'/add_post')); ?>
		                       
								<?php echo $this->Form->textarea('newpost_input',array('label'=>'','type'=>'text','id'=>'newpost_input','cols'=>'100','rows'=>2,'class'=>'commnetTXB','maxlength'=>500,'style'=>'height: 45px;overflow: auto;','placeholder'=>__('write_new_post'))); ?>
		                        <input type="submit" class="tile btn blue1" name=""   value="<?php echo __('send'); ?>">
		                    
		                    <ul class="icons" style="display: block;">
		                        <li class="tile size_2 white1" id="learn_addimage">
		                        	<?php  echo $this->Html->image('/img/icons/image.png',array('border'=>'0','id'=>'newpost_add_image')); ?>
									<?php echo $this->Form->input('newpost_image',array('label'=>'','type'=>'file','id'=>'newpost_image','style'=>'position:absolute;top:-1000px;' )); ?>
		                        </li>
		                        <li class="tile size_2 white1" onclick="show_add_link_form();" id="learn_addlink">
		                            <?php  echo $this->Html->image('/img/icons/link.png'); ?>
								</li>
		                        
		                    </ul>
							<div id="newpost_loading" style="float:left;margin-left: 5px"> </div>
							<div id="post_result" style="float:right"></div>
		                    <span class="characterCounter">
		                    <div id="charNumber" class="newpost_charnumber">500</div></span>
							</form>
		                </div>
		            </div>
					<?php } ?>
					<?php if($this->Session->check('User_Info')){ ?>
					
                    <?php echo $this->element('search_box'); ?> 
					
					 
					<?php } ?>
					<?php if($this->Session->check('User_Info')){ ?>
					<div class="tile size_2 blue1 help" id="learn_top_help_menu"><div class="symbol"></div></div>
		            <div class="submenu helpSubmenu">
		                <div class="trail"></div>
		                <ul class="menuItem">
                            <li> <a href="<?php echo __SITE_URL.'users/edit_profile' ?>"> <?php echo __('edit_info'); ?> </a></li>
                            <li><a href="<?php echo __SITE_URL.'privacies/edit' ?>"><?php echo __('privacy'); ?> </a></li>
                            <?php
                            
                    		  $LearnObjectInfos= $this->Session->read('LearnObjectInfo');                    		 
                    		  if(!empty($LearnObjectInfos)){
                    		  	foreach ($LearnObjectInfos as $key=>$LearnObjectInfo  )
                    			{
                    				if($LearnObjectInfo['learn_object_id']==1){
                                        if($this->request->params['controller']=='pages' && $this->request->params['action']=='display') 
                                        {
                                           echo "<div class='seperator_Hor'></div>";
                                           echo "<li><a href='javascript:void(0)' onclick='show_home_learn();' >". __('learn_this_page')."</a></li>"; 
                                        }                                           
                                    }
                                    if($LearnObjectInfo['learn_object_id']==2){
                                        if($this->request->params['controller']=='users' && $this->request->params['action']=='profile') 
                                        {
                                           if($user_id!=$User_Info['id']){
                                               echo "<div class='seperator_Hor'></div>";
                                               echo "<li><a href='javascript:void(0)' onclick='show_profile_learn();'>".__('learn_this_page')."</a></li>"; 
                                           }
                                        }                                           
                                    }
                    			}
                    		  } 
                            ?>
                            
                            <div class="seperator_Hor"></div>
                            <li><a href="<?php echo __SITE_URL.'pages/view/2'; ?>"><?php echo __('help'); ?></a></li>
                            <div class="seperator_Hor"></div>
                            <li><a href="<?php echo __SITE_URL.'users/logout' ?>"><?php echo __('logout'); ?></a></li>
                        </ul>
		            </div>
					 
				  <?php } ?>
		        </div>
		    </div>
			<div id="content">
			 
			<!--paste content-->
			
			<?php echo $this->Session->flash(); ?>
			<?php echo $this->fetch('content'); ?>
	          <br>
	         <?php   echo $this->element('sql_dump');  ?>
		   </div>

		   <div class="clear"></div>
		    <?php echo $this->element('footer'); ?>       
		   <div id="goToTop"> </div>	
		</body>
		</html>
<?php
   }
   
      /* echo $this->Html->link('English', array('language'=>'eng'))."<br>"; 
      echo $this->Html->link('Persian', array('language'=>'per'));  */
  else
  {
  	 echo $this->Html->css('login_'.$locale);	
	 echo $this->Html->css('boilerplate_'.$locale);
	// echo $this->Html->css('tiles_'.$locale);
	 
	 /* noty */
		echo $this->Html->script('/js/noty/jquery.noty');
	  /* layouts */
	   /* echo $this->Html->script('/js/noty/layouts/bottom');
		echo $this->Html->script('/js/noty/layouts/bottomCenter');
	    echo $this->Html->script('/js/noty/layouts/bottomLeft');
		echo $this->Html->script('/js/noty/layouts/bottomRight');*/
	    echo $this->Html->script('/js/noty/layouts/center');
		/*echo $this->Html->script('/js/noty/layouts/centerLeft');
		echo $this->Html->script('/js/noty/layouts/centerRight');
		echo $this->Html->script('/js/noty/layouts/inline');*/
		echo $this->Html->script('/js/noty/layouts/top');
		echo $this->Html->script('/js/noty/layouts/topCenter');
		echo $this->Html->script('/js/noty/layouts/topLeft');
		echo $this->Html->script('/js/noty/layouts/topRight');
	   /* themes */
	    echo $this->Html->script('/js/noty/themes/default_'.$locale);
		//echo $this->Html->script('/js/bpopup-master/jquery.bpopup');
		 
		  
 ?>
		 

	   <header id="header">
        <div class="content">
            <div id="logo">
				<a href="http://madaner.ir/">
					<?php  echo $this->Html->image('/img/icons/logo.png',array('width'=>275,'height'=>68));  ?>
                 </a> 
            </div>
            <div id="logInForm">
            	<?php echo $this->Form->create('User', array('class' => 'LoginForm','id'=>'UserLoginAjaxForm')); ?>
		
					<input type="text" id="login_email" placeholder="<?php echo __('email').'/'.__('user_name'); ?>" name="data[User][login_email]" style="width:190px">
					<input type="password" id="login_password" placeholder="<?php echo __('password'); ?>" name="data[User][login_password]">
					
					<span style="display:none" id="visible_captcha_box">
					  <input type="text" id="login_captcha" placeholder="<?php echo __('captcha'); ?>" name="data[User][login_captcha]">
						<div id="captch_box">
						<a href="javascript:void(0)" 
						onclick="document.getElementById('captcha').src='<?php echo  __SITE_URL.'users/captcha_image?' ;?>'+Math.random()" id="change-image"><?php  echo $this->Html->image('/img/icons/refresh.gif',array('boder'=>0));  ?>
						</a>
						<img id="captcha" src="<?php echo $this->Html->url(__SITE_URL.'users/captcha_image');?>" alt="" />	
						</div>
						 
                    </span>
					
                     
					<input type="submit" value="<?php echo __('login'); ?>" class="tile btn green1 login" style="width:55px">
					<div id="register_ajax_loader"></div>
					<div id="register_ajax_msg"></div>
					<br>
                    <input name="remember_me" id="remember_me" type="checkbox" />
                    <label><?php echo __('remember_me'); ?></label>
                    <a href="JavaScript:void(0);" onclick="show_forget_pass();"> <?php echo __('forget_password'); ?></a>
                </form>
            </div>
        </div>
    </header>
    
	 
    	<?php
           if($this->request->params['controller']!='pages'){
               echo"<div id='content'>";
           }
    		echo $this->fetch('content'); 
           if($this->request->params['controller']!='pages'){
               echo"</div>";
              } 
			  
    	?>
     <?php   echo $this->element('sql_dump');  ?>
	
    <?php echo $this->element('footer'); ?>  
    <div id="goToTop">
    </div>
  
</body>
</html>

<?php

}

	
	/*echo $this->Html->script('/js/LightFace/mootools.js');
	echo $this->Html->script('/js/LightFace/mootools-more-drag.js');
	echo $this->Html->script('/js/LightFace/LightFace.js');
	echo $this->Html->script('/js/LightFace/LightFace.Request.js');*/
	
	echo $this->Html->script('register');
	

?>


<script>
	
 	_send = "<?php echo __('send') ?>";
	_send_email = "<?php echo __('send_email') ?>";
	_send_message = "<?php echo __('send_message') ?>";
	_add_link = "<?php echo __('add_link') ?>";
	_add = "<?php echo __('add') ?>";
	_existlink = "<?php echo __('exist_link') ?>";
	_enter_post = "<?php echo __('enter_post') ?>";
	_save_post_success = "<?php echo __('save_post_success') ?>";
	_save_post_notsuccess =  "<?php echo __('save_post_notsuccess') ?>";
	_exist_image =  "<?php echo __('exist_image') ?>";
	_follow =  "<?php echo __('follow') ?>";
	_not_follow =  "<?php echo __('not_follow') ?>";
	_setting =  "<?php echo __('setting') ?>";
	_save =  "<?php echo __('save') ?>";
	_yes =  "<?php echo __('yes') ?>";
	_no =  "<?php echo __('no') ?>";
	_are_you_sure =  "<?php echo __('are_you_sure') ?>";
	_warning = "<?php echo __('warning') ?>";
	_enter_name = "<?php echo __('enter_name') ?>"; 
	_enter_user_name = "<?php echo __('enter_user_name') ?>"; 
	_enter_valid_user_name = "<?php echo __('enter_valid_user_name') ?>"; 
	_enter_email = "<?php echo __('enter_email') ?>"; 
	_enter_valid_email = "<?php echo __('enter_valid_email') ?>"; 
	_enter_password = "<?php echo __('enter_password') ?>"; 
	_enter_valid_length = "<?php echo __('enter_valid_length') ?>"; 
	_not_valid_repassword = "<?php echo __('not_valid_repassword') ?>";
	_not_repeated_user_to_send_message = "<?php echo __('not_repeated_user_to_send_message') ?>";
	_block = "<?php echo __('block') ?>";
	_unblock = "<?php echo __('unblock') ?>";
	_enter_text = "<?php echo __('enter_text') ?>";
	_image_added = "<?php echo __('image_added') ?>";
	_link_added = "<?php echo __('link_added') ?>"; 
	_select_job_status="<?php echo __('select_job_status') ?>"; 
	_send_infraction_report_post="<?php echo __('send_infraction_report_post') ?>"; 
	_enter_infraction_report="<?php echo __('enter_infraction_report') ?>"; 
	
	$(document).ready(		
		function()
		{
			initial();
		});
	
</script>

