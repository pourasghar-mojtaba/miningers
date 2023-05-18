<?php
  $User_Info= $this->Session->read('User_Info');
 ?>
 <script>
 	_file_added = "<?php echo __('file_added') ?>";
 </script>
<header class="reporterInfo">
<?php if($this->Session->check('User_Info')){ ?>
	<?php  echo $this->Session->flash(); ?>
		<span class="titr">  <?php echo __('blog_dt1') ?></span>
		<span class="text">  <?php echo __('blog_dt2') ?></span>
	    <?php echo $this->Form->create('Blog', array('id'=>'AddBlogForm','name'=>'AddBlogForm','enctype'=>'multipart/form-data','action'=>'/index')); ?>
			<?php echo $this->Form->input('blog_file',array('label'=>'','type'=>'file','id'=>'blog_file','style'=>'position:absolute;top:-1000px;' )); ?>
			<?php
			   echo $this->Html->image('/'.__USER_IMAGE_PATH.$User_Info['image'],array('width'=>160,'height'=>160,'class'=>'profile_img'));
			?>
	        <textarea placeholder="<?php echo __('enter_text_for_blog'); ?>" name="data[Blog][little_body_<?php echo $this->Session->read('Config.language'); ?>]" id="blog_text"></textarea>
	        <input type="submit" id="add_blog" class="btn ok" value="<?php echo __('send'); ?>">
			<!--<input type="button" id="attach_file" class="btn attach" value="" title="<?php echo __('ext_is_doc'); ?>">-->
			<div id="blog_attach_place"></div>
			<span id="add_blog_loading"></span>
	    </form>
<?php }else 
	{
		echo "<span class='titr'>". __('blog_dt1')."</span>"; 
		echo"<span class='text'>". __('with')." <a href='".__SITE_URL."'>". __('register')."</a> ". __('blog_dt3')."</span>";
	}
	
?>	
</header> 