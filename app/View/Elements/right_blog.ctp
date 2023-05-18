<?php
  $User_Info= $this->Session->read('User_Info');
  if(empty($user_id)){
  	$user_id = 0;
  }
  
  
 ?> 

 <aside class="dataBox" style="margin-top: -15px;">
	<p><?php echo __('my_blog') ?> </p>
	<section class="nano has-scrollbar" id="lastblogBox">
      <ul id="myblog_body">  
	  	<?php echo "<img src ='".__SITE_URL."img/loader/big_loader.gif' />"; ?> 	    
	  </ul>
	  <span id="myblog_loading"></span>
	 </section> 
</aside>
 
<script>
jQuery(
  function($)
  {
  		var lastblog_count = 0;
	    $('#lastblog_body').slimScroll({
	          height: '700px'
	      });
	    
	    $('#myblog_body').bind('scroll', function()
	      {
	        if($(this).scrollTop() + $(this).innerHeight()>=$(this)[0].scrollHeight)
	        {
	           lastblog_count++;
			   refresh_my_blog(lastblog_count,'<?php echo $user_id; ?>','<?php echo $blog_id; ?>');
	        }
	      })
  }
);	
refresh_my_blog(0,'<?php echo $user_id; ?>','<?php echo $blog_id; ?>');
</script>
