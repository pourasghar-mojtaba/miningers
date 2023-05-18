<?php 
	 
 ?>

<script>
 
var count=0;
;	
	
$(window).scroll(function(){  
		if  ($(window).scrollTop() == $(document).height() - $(window).height()){    
		   count++; 
		   refresh_home(count);
		}  
}); 

var is_up=0;
$(window).scroll(function(){  
	if($(window).scrollTop()<=200 && is_up==1){
		is_up=0;
		refresh_new_post();
	}
	if($(window).scrollTop()>200){
		is_up=1;
	}	
		
});
 
 
 
     // jQuery on an empty object, we are going to use this as our Queue
    var ajaxQueue = $({});
    var currAjaxCalls = [refresh_home(count),refresh_postads(),select_notification(),select_newtag(),show_post_count(),show_tofollow_notification(),show_follow_notification(),show_message_notification(),refresh_new_post()];
    $.ajaxQueue = function(ajaxOpts) {
        
        if(ajaxOpts == "dequeue") {
            if(currAjaxCalls.length > 0) {
                for(var i=0; i<currAjaxCalls.length; i++) {
                    currAjaxCalls[i].abort();    
                }    
            }
            ajaxQueue.dequeue();
        } else {    
            // hold the original complete function
            var oldComplete = ajaxOpts.complete;
            
            // queue our ajax request
            ajaxQueue.queue(function(next) {
            
                // create a complete callback to fire the next event in the queue
                ajaxOpts.complete = function() {
                // fire the original complete if it was there
                if (oldComplete) {
                    currAjaxCalls.slice(1);
                    oldComplete.apply(this, arguments);
                }
                
                next(); // run the next query in the queue
                };
                
                // run the query
                currAjaxCalls[currAjaxCalls.length] = $.ajax(ajaxOpts);
            });
        }
    };
    $.clearAjaxQueue = function() {
        $.ajaxQueue("dequeue");
    };
 
 
</script>
<script type="text/javascript">
	_are_you_have_learn_profile= "<?php echo __('are_you_have_learn_profile') ?>";
</script>
<?php
     
	$this->requestAction(__SITE_URL.'userpostviews/updatetime/');
?>
 		
		
<?php echo $this->element('right_panel'); ?>		




 
<section id="mainPanel">
<div id="home_loading1">
<?php echo $this->Html->image('/img/loader/big_loader.gif'); ?>
</div>	 
<div id="home_body"></div>
             
<div id="home_loading"></div>
	
</section>

<?php
  $User_Info= $this->Session->read('User_Info');
  if(isset($User_Info) && ($User_Info['sex']!=-1 || $User_Info['industry_id']!=0 || $User_Info['user_type']!=1) && $User_Info['follow_count']>=5 ){
       
      $LearnObjectInfos= $this->Session->read('LearnObjectInfo');
       
      if(!empty($LearnObjectInfos)){
      	foreach ($LearnObjectInfos as $key=>$LearnObjectInfo  )
    	{
    		if($LearnObjectInfo['learn_object_id']==1)
    		{
    			if($LearnObjectInfo['count']==0){
    				echo "<script> show_learn(".$User_Info['id'].",1,".$LearnObjectInfo['parent_id'].",'".$LearnObjectInfo['object_created']."','".$LearnObjectInfo['user_created']."'); </script>";
    			}
    			
    		}
    	}
      } 
  }
 ?>

<!-- Tip Content -->
    <ol id="HomePageTipContent">
	
      <li data-id="topmenu_home" data-text="<?php echo __('next') ?>" data-options="tipLocation:left;" >
        <h2><?php echo __('home_menu') ?></h2>
        <p><?php echo __('learn_site_top_menu_home') ?></p>
      </li>
	  <li data-id="topmenu_users" data-text="<?php echo __('next') ?>" data-options="tipLocation:left;">
        <h2><?php echo  __('users_menu') ?></h2>
        <p><?php echo __('learn_site_top_menu_users') ?></p>
      </li>
	  <li data-id="topmenu_favorite" data-text="<?php echo __('next') ?>" >
        <h2><?php echo  __('favorite_menu') ?></h2>
        <p><?php echo __('learn_site_top_menu_favorite') ?></p>
      </li>
	  <li data-id="topmenu_newNews" data-text="<?php echo __('next') ?>" >
        <h2><?php echo  __('newNews_menu') ?></h2>
        <p><?php echo __('learn_site_top_menu_newNews') ?></p>
      </li>
      <li data-id="newpost_input" data-text="<?php echo __('next') ?>" >
        <h2><?php echo __('learn_addpost') ?></h2>
        <p><?php echo __('learn_addpost_newpost_input') ?></p>
      </li>
      <li data-id="learn_addlink" data-text="<?php echo __('next') ?>" >
        <h2><?php echo __('addlink') ?></h2>
        <p><?php echo __('learn_addpost_addlink') ?></p>
      </li>
      <li data-id="learn_addimage" data-text="<?php echo __('next') ?>" >
        <h2><?php echo __('addimage') ?></h2>
        <p><?php echo __('learn_addpost_addimage') ?></p>
      </li>
      <li data-class="learn_search_box" data-text="<?php echo __('next') ?>" >
        <h2><?php echo __('learn_search_box')  ?></h2>
        <p><?php echo __('learn_search_box_detail') ?></p>
      </li>
      <li data-id="learn_top_help_menu" data-text="<?php echo __('next') ?>" >
        <h2><?php echo __('learn_top_help_menu')  ?></h2>
        <p><?php echo __('learn_top_help_menu_detail') ?></p>
      </li>
	  <li data-id="userbox_username" data-text="<?php echo __('next') ?>"  >
        <h2><?php echo __('user_name');  ?></h2>
        <p><?php echo __('learn_user_box_user_name') ?></p>
      </li>
	  <li data-id="userbox_useredit" data-text="<?php echo __('next') ?>"  >
        <h2><?php echo __('edit_profile');  ?></h2>
        <p><?php echo __('learn_user_box_edit_profile') ?></p>
      </li>
	  <li data-id="learn_userbox_message_box" data-text="<?php echo __('next') ?>"  data-options="tipLocation:left;">
        <h2><?php echo __('message_box');  ?></h2>
        <p><?php echo __('learn_user_box_edit_message_box') ?></p>
      </li>
	  <li data-id="learn_userbox_my_post" data-text="<?php echo __('next') ?>"  data-options="tipLocation:left;">
        <h2><?php echo __('my_post');  ?></h2>
        <p><?php echo __('learn_user_box_edit_my_post') ?></p>
      </li>
	  <li data-id="new_tofollow_count" data-text="<?php echo __('next') ?>"  data-options="tipLocation:left;">
        <h2><?php echo __('follow_payees');  ?></h2>
        <p><?php echo __('learn_user_box_edit_follow_payees') ?></p>
      </li>
	  <li data-id="new_follow_count" data-text="<?php echo __('next') ?>"  data-options="tipLocation:left;">
        <h2><?php echo __('chasers');  ?></h2>
        <p><?php echo __('learn_user_box_edit_chasers') ?></p>
      </li>
	  <!-- post -->
      <li data-id="learn_post_date" data-text="<?php echo __('next') ?>" >
        <h2><?php echo __('learn_post');  ?></h2>
        <p><?php echo __('learn_post_date') ?></p>
      </li>
      <li data-class="learn_post_favorite" data-text="<?php echo __('next') ?>" >
        <h2><?php echo __('learn_post');  ?></h2>
        <p><?php echo __('learn_post_favorite') ?></p>
      </li>
      <li data-class="learn_post_facebook" data-text="<?php echo __('next') ?>" >
        <h2><?php echo __('learn_post');  ?></h2>
        <p><?php echo __('learn_post_facebook') ?></p>
      </li>
      <li data-id="learn_post_comment" data-text="<?php echo __('next') ?>" >
        <h2><?php echo __('learn_post');  ?></h2>
        <p><?php echo __('learn_post_comment') ?></p>
      </li>
      <li data-class="learn_post_share" data-text="<?php echo __('next') ?>" >
        <h2><?php echo __('learn_post');  ?></h2>
        <p><?php echo __('learn_post_share') ?></p>
      </li>
      <li data-class="learn_post_report" data-text="<?php echo __('next') ?>" >
        <h2><?php echo __('learn_post');  ?></h2>
        <p><?php echo __('learn_post_report') ?></p>
      </li>
      
      
	  <li data-class="tab-1" data-text="<?php echo __('next') ?>"  data-options="tipLocation:left;">
        <h2><?php echo __('send_invitation_email');  ?></h2>
        <p><?php echo __('learn_invitation_email') ?></p>
      </li>
	  <li data-class="tab-2" data-text="<?php echo __('next') ?>"  data-options="tipLocation:left;">
        <h2><?php echo __('send_invitation_sms');  ?></h2>
        <p><?php echo __('learn_invitation_sms') ?></p>
      </li>
	  <li data-id="new_notification" data-text="<?php echo __('next') ?>"  data-options="tipLocation:left;">
        <p><?php echo __('learn_new_notification') ?></p>
      </li>
	  <li data-id="notificationBox" data-text="<?php echo __('next') ?>"   data-options="tipLocation:left;">
        <p><?php echo __('learn_notificationBox') ?></p>
      </li>
      
      
      
      
    </ol>
 <script>/*show_home_learn();*/</script>
  
