
<?php echo $this->Html->css('edit_profile_'.$locale); 
	  echo $this->Html->script('profile');
	  echo $this->Html->css('/reader/js/datetimepicker-master/jquery.datetimepicker');	
	  echo $this->Html->script('/reader/js/datetimepicker-master/jquery.datetimepicker');
  //pr($user);
?>
<script type="text/javascript">
	_save_cover_image_success = "<?php echo __('save_cover_image_success');  ?>";
	_save_cover_image_notsuccess = "<?php echo __('save_cover_image_notsuccess');  ?>";
	_save_image_success = "<?php echo __('save_image_success');  ?>";
	_save_image_notsuccess = "<?php echo __('save_image_notsuccess');  ?>";
</script>

 <?php echo $this->element('edit_right_panel',array('active'=>'edit_feed')); ?>        

	  <section id="mainPanel">
            <div class="mainBox">
               
			   <h1><div class="editProfile_icon"></div><?php echo __('edit_feed') ?></h1>
                <div class="seperator_Hor"></div>
            
				<?php echo $this->Form->create('User', array('id'=>'Feed','name'=>'Feed','enctype'=>'multipart/form-data','class'=>'edit_profile')); ?>
                	<table border="0" cellpadding="2">
                       <?php  echo $this->Session->flash(); ?>
					  <tr>
					  	<td>
							<?php echo __('rss_link'); ?> :
							<input type='text' name='data[Feedurl][url][]' style="width:400px" value="<?php if(!empty($feed['Feedurl']['url'])) echo $feed['Feedurl']['url']; ?>" dir="ltr" >
							<?php
								if(!empty($feed['Feedurl']['url'])){
									echo "<input type='hidden' value='".$feed['Feedurl']['id']."' 
									name='data[Feedurl][id][]'>";
								}
								
							?>
						</td>
					  </tr>	 
					  <tr>
					  	<td>
							<?php echo __('rss_count'); ?> :
							<input type='text' name='data[Feedurl][rss_count][]' value="<?php if(!empty($feed['Feedurl']['rss_count'])) echo $feed['Feedurl']['rss_count']; ?>" style="width:40px" maxlength="2" >
						</td>
					  </tr>
					  <tr>
					  	<td>
							<?php echo __('rss_time'); ?> :
							<input type="text" onmousedown='view_time(this)' value="<?php if(!empty($feed['Feedtime']['read_time'])) echo $feed['Feedtime']['read_time']; ?>" name="data[Feedtime][read_time][]" id="time_no_01" maxlength="255" required style="width:70px" />
							<?php
								if(!empty($feed['Feedtime']['read_time'])){
									echo "<input type='hidden' value='".$feed['Feedtime']['id']."' 
									name='data[Feedtime][id][]'>";
								}
								
							?>
						</td>
					  </tr>	
					  <tr>
					  	<td>
							<?php echo __('tag') ?> :
							<input class="input-xlarge focused" style="width:300px" name="data[Feed][tag]" value="<?php if(!empty($feed['Feed']['tag'])) echo $feed['Feed']['tag']; ?>" type="text" > <?php echo __('separate_with_sharp'); ?>
							<?php 
								 if(!empty($feed['Feed']['id'])){
								 	echo "<input type='hidden' name='feed_id' value='".$feed['Feed']['id']."' />";
								 }
							?>
						</td>
					  </tr>
                        <tr>
                            <td><input class="btn ok" name="" type="submit" value="<?php echo __('updates') ?>" /></td>
                        </tr>
                    </table>

                </form>
            </div>
        </section>

 <script>
	
	function view_time(obj){
		$(obj).datetimepicker({
			datepicker:false,
			format:'H:i',
			step:5
		});
	}
</script>	