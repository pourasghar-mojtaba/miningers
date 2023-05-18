<?php
  $User_Info= $this->Session->read('User_Info');
  if(empty($user_id)){
  	$user_id = 0;
  }
  
  
 ?> 

 <aside class="dataBox">
	<p><?php echo __('last_blog') ?> </p>
	<section class="nano has-scrollbar" id="lastblogBox">
      <ul id="lastblog_body">  
	  	<?php echo "<img src ='".__SITE_URL."img/loader/big_loader.gif' />"; ?> 	    
	  </ul>
	  <span id="lastblog_loading"></span>
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
	    
	    $('#lastblog_body').bind('scroll', function()
	      {
	        if($(this).scrollTop() + $(this).innerHeight()>=$(this)[0].scrollHeight)
	        {
	           lastblog_count++;
			   refresh_last_blog(lastblog_count);
	        }
	      })
  }
);	
refresh_last_blog(0);
</script>
