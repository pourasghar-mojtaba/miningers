<?php
 echo $this->Html->css('index_'.$locale);
 echo $this->Html->css('error'); 

?>

<?php
if (Configure::read('debug') > 0){
?>
<h2><?php echo $name; ?></h2>
<p class="error">
	<strong><?php echo __d('cake', 'Error'); ?>: </strong>
	<?php echo __d('cake', 'An Internal Error Has Occurred.'); ?>
</p>
<?php	
	echo $this->element('exception_stack_trace');
}
elseif(Configure::read('debug') == 0){
?>
<p class="error-code">
	440
</p>
<div class="clear"></div>
<div class="error_content">
	<?php	echo __('not_found_address'); ?>	
	<br/><a href="<?php echo __SITE_URL; ?>"><?php	echo __('go_home'); ?></a>
</div>


<script>
  setTimeout("location.href = '<?php echo __SITE_URL ?>';", 10000);
</script>
<?php
}
?>