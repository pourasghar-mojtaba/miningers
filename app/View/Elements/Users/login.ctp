<div class="login">
                    <div class="col-md-4 col-md-offset-4">
                        <div class="dataBox green">
                        <header><h2><?php echo __('madaner'); ?></h2><br>
                        <h3><?php echo __('intro_detail1'); ?></h3></header>
						
						<?php echo $this->Form->create('User', array('class' => 'myForm','id'=>'UserLoginAjaxForm')); ?>
                            <div class="col-md-12">
                                <input class="myFormComponent" type="text" id="login_email" placeholder="email" name="data[User][login_email]" required style="color:#ffffff">
                            </div>
                            <div class="col-md-12">
                                <input class="myFormComponent" id="login_password" type="password" placeholder="password" name="data[User][login_password]" required style="color:#ffffff">
                            </div>
							<div class="col-md-12">
                                 <span style="display:none" id="visible_captcha_box">
									  <input type="text" id="login_captcha" placeholder="<?php echo __('captcha'); ?>" name="data[User][login_captcha]">
										<div id="captch_box">
										<a href="javascript:void(0)" 
										onclick="document.getElementById('captcha').src='<?php echo  __SITE_URL.'users/captcha_image?' ;?>'+Math.random()" id="change-image"><?php  echo $this->Html->image('/img/icons/refresh.gif',array('boder'=>0));  ?>
										</a>
										<img id="captcha" src="<?php echo $this->Html->url(__SITE_URL.'users/captcha_image');?>" alt="" />	
										</div>
										 
				                    </span>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" role="button" class="trans myFormComponent" >
                                    <span class="icon"></span>
                                    <span class="text"> <?php echo __('login_in_site'); ?></span>
                                </button>
								<div id="register_ajax_loader"></div>
								<div id="register_ajax_msg"></div>
                            </div>
                            <div class="col-sm-6">
                                <label>
                                	<input type="checkbox" id="remember_me">
									<?php echo __('remember_me'); ?>
                                </label>
                            </div>
                            <div class="col-sm-6">
                                <a href="JavaScript:void(0);" onclick="show_forget_pass();">
								<?php echo __('forget_password'); ?></a>
                            </div>
                            <div class="clear"></div>
                        </form>
						
						
                        <div><?php echo __('register_with_invitation'); ?><br>
 							<?php echo __('intro_detail2'); ?>
							<a href="#">
								<?php echo __('tell_with_us'); ?>
							</a>. </div>
                        <div class="clear"></div>
                      </div>
                    </div>
                </div>