


<div class="container-fluid">
		<div class="row-fluid">
		
			<div class="row-fluid">
				<div class="span12 center login-header">
					<h2>Welcome to Madaner Admin</h2>
				</div><!--/span-->
			</div><!--/row-->
			
			<div class="row-fluid">
				<div class="well span5 center login-box">
					<div >
						 <?php echo $this->Session->flash(); ?>
					</div>
					<?php echo $this->Form->create('User', array('class' => 'form-horizontal')); ?>
						<fieldset>
							<div class="input-prepend" title="<?php echo __('email'); ?>" data-rel="tooltip">
								<span class="add-on"><i class="icon-user"></i></span>
								<input autofocus class="input-large span10" name="data[User][login_email]" id="login_email" type="text"   />
							</div>
							<div class="clearfix"></div>

							<div class="input-prepend" title="<?php echo __('password'); ?>" data-rel="tooltip">
								<span class="add-on"><i class="icon-lock"></i></span>
								<input class="input-large span10" name="data[User][login_password]" id="login_password" type="password"  />
							</div>
							<div class="clearfix"></div>
							
							<div class="input-prepend">
								<img id="captcha" src="<?php echo $this->Html->url('/users/captcha_image');?>" alt="" />
							</div>
							<div class="clearfix"></div>
							<div data-rel="tooltip" class="input-prepend" data-original-title="<?php echo __('captcha'); ?>">
								 
								<input type="text"  id="captcha" name="data[User][captcha]" class="input-large span10">
							</div>
								<div class="clearfix"></div>
							
							<div class="clearfix"></div>

							<p class="center span5">
							<button type="submit" class="btn btn-primary"><?php echo __('login'); ?></button>
							</p>
						</fieldset>
					</form>
				</div><!--/span-->
			</div><!--/row-->
				</div><!--/fluid-row-->
		
	</div><!--/.fluid-container-->



