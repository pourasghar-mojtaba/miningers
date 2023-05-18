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
		_delete_loading= "<?php echo __('delete_loading') ?>";
		_favorite= "<?php echo __('favorite') ?>";
		_notfavorite= "<?php echo __('notfavorite') ?>";
		_share= "<?php echo __('share') ?>";
		_notshare= "<?php echo __('notshare') ?>";
		_enter_user_type= "<?php echo __('enter_user_type') ?>";
	</script>
	
	<?php
		echo $this->Html->meta('icon');	
		echo $this->Html->css('/js/Zebra_Dialog-master/public/css/zebra_dialog.css');   	
		echo $this->Html->script('jquery-1.7.2.min');
        echo $this->Html->script('function');
        echo $this->Html->script('component');
		echo $this->Html->script('global');
        
        /* foundation */
       /* echo $this->Html->css('/js/foundation/css/normalize'); 
        echo $this->Html->css('/js/foundation/css/foundation_'.$locale); 
        echo $this->Html->script('/js/foundation/js/vendor/modernizr');*/
        
		
        echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
        // debug($locale) ;
		$User_Info= $this->Session->read('User_Info');
		
	?>
</head>
<body>
    <?php echo $this->element('header'); ?>  
	
	<?php echo $this->Session->flash(); ?>
	<?php echo $this->fetch('content'); ?>
      <br>
     <?php  /* echo $this->element('sql_dump');*/  ?>
	 
	 
	<?php echo $this->element('footer'); ?>  
</body>
</html>
<?php
 		
 		//echo $this->Html->script('jquery-ui-1.10.4.custom.min');
	    
	    echo $this->Html->script('/js/Zebra_Dialog-master/public/javascript/zebra_dialog');
		echo $this->Html->script('fix');
		echo $this->Html->css('/js/slimScroll/prettify');   	
		echo $this->Html->script('/js/slimScroll/prettify');
		echo $this->Html->script('/js/slimScroll/jquery.slimscroll');
		
 ?> 
 <style>
	.tooltip {
	display:none;
	position:absolute;
	/*border:1px solid #333;*/
	background-color:#161616;
	border-radius:5px;
	padding:8px;
	color:#fff;
	font-size:12px Arial;
}
</style>
<script>

	$(document).ready(function(e) {
        init();
		//must run after any post load//
			/*postsImage();
			textBoxCounter();
			fileUpload();*/
     });
	
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
	
	
</script>

