

<div class="content">
	<div class="content">
			<div class="login_container">
                <div id="login" class="tile" style="transform: rotate(0deg); border-spacing: 0px;">
                   <div class='centered'><h1 style="margin-bottom: 20px;">
				   	<?php echo __('login') ?>
				   </h1></div>
					 <?php echo $this->Form->create('User', array('class' => 'LoginForm')); ?>
					<table border="0" cellpadding="2">
	                  <tr>
					  	<td colspan="2" style="color:#e9523e">
							<?php echo $this->Session->flash(); ?>
						</td>
					  </tr>
					  <tr>
	                    <td><label><?php echo __('email'); ?></label></td>
	                    <td>
							<?php echo $this->Form->input('login_email',array('label'=>'','id'=>'login_email')); ?>
						</td>
	                  </tr>
					  <tr>
					  	<td><label><?php echo __('password'); ?></label></td>
	                    <td>
							<?php echo $this->Form->input('login_password',array('label'=>'','type'=>'password','id'=>'login_password')); ?>
						</td>
					  </tr>
					  <tr>
					  	<td align="center">
							 
							<img id="captcha" src="<?php echo $this->Html->url(__SITE_URL.'users/captcha_image');?>" alt="" />
						</td>
					  </tr>
					  <tr>
	                    <td><label><?php echo __('captcha'); ?></label></td>
	                    <td>
							<?php echo $this->Form->input('captcha',array('label'=>'','id'=>'captcha')); ?>
						</td>
	                  </tr>
					  <tr>
					  	<td><input name="" type="submit" value="<?php echo __('login'); ?>" /></td>
	                    <td> 
							<div id="register_ajax_loader"></div>
							<div id="register_ajax_msg"></div>
						</td>
					  </tr>
					  <tr>
					  	<td>&nbsp;</td>
	                    <td>
						  <?php echo $this->Form->input('remember_me',array('label'=>__('remember_me'),'type'=>'checkbox','id'=>'remember_me','value'=>1)); ?>
						   
						</td>
					  </tr>
	                  <tr>
	                   <td></td>
	                    <td><label><a href="JavaScript:void(0);" onclick="show_forget_pass();"> <?php echo __('forget_password'); ?></a></label></td>
	                  </tr>
                  </table>
				</form>	
					
			    </div>
            </div>
	</div>			
			
</div>



