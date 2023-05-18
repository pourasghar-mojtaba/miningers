<?php
 echo $this->Html->css('index_'.$locale);
 echo $this->Html->css('error'); 

?>
 
<p class="error-code">
	441
</p>
<div class="clear"></div>
<div class="error_content">
	<?php	echo __('not_found_address'); ?>	
	<br/><a href="<?php echo __SITE_URL; ?>"><?php	echo __('go_home'); ?></a>
</div>


<script>
  setTimeout("location.href = '<?php echo __SITE_URL ?>';", 10000);
</script>