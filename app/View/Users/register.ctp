<?php
	$this->Gilace->autoCompileLess(ROOT.DS.APP_DIR.DS.'webroot'.DS.'less_'.$locale. DS .'other.less', ROOT.DS.APP_DIR.DS.'webroot'.DS.'css'.DS.'other_'.$locale.'.css');
		
	echo $this->Html->css('other_'.$locale);
	echo $this->Html->script('register');
	
?>

<section id="homePage">
	<div class="col-md-3 col-md-offset-0 col-sm-3">
			
    </div>
	
    <div class="col-sm-6 registerForm">
    	<header>
        	<h3><?php echo __('register') ?>:</h3>
        </header>
		<h8>
			<?php  echo $this->Session->flash(); ?>
		</h8>
		<?php if($invite){ 
			//echo "<a href='".__SITE_URL.$user_name."'>@".$user_name."</a> ".__('invite_you');
		?>
		<?php echo $this->Form->create('User', array('class' => 'RegisterForm','id'=>'RegisterForm','class'=>'myForm','action'=>'/register?ragid='.$_REQUEST['ragid'],'onsubmit'=>'return check_field()')); ?>
        	<label style="display: inline;margin-left: 5px"><?php echo __('iam'); ?></label>
            <input style="width:20px" type="radio" value="0" class="myFormComponent" id="user_type" name="data[User][user_type]">
            <?php echo __('user'); ?>
            <input style="width:20px" type="radio" value="1"  class="myFormComponent" id="user_type1" name="data[User][user_type]">
            <?php echo __('company'); ?>
                  
            <br>
            
        	<label><?php echo __('name'); ?></label>
			<input type="text" class="myFormComponent" id="register_name" placeholder="<?php echo __('name'); ?>" name="data[User][name]"><br>
        	<label><?php echo __('email'); ?></label>
			<input type="email" class="myFormComponent" id="register_email" placeholder="<?php echo __('email'); ?>" name="data[User][email]"><br>
        	<label><?php echo __('user_name'); ?></label>
			<input type="text" class="myFormComponent" id="user_name" placeholder="<?php echo __('user_name'); ?>" name="data[User][user_name]"><br>
        	<label><?php echo __('password'); ?></label>
			<input type="password" class="myFormComponent" id="password" placeholder="<?php echo __('password'); ?>" name="data[User][password]"> <br>
        	<label><?php echo __('industry') ?></label>
        	<select class="myFormComponent" name="data[User][industry_id]">
			<?php
				if(!empty($industries)){
					foreach($industries as $industry){
						echo "<option value='".$industry['Industry']['id']."'>".$industry['Industry']['title']."</option>";
					}
				}
			?>
            </select><br>
        	<label><?php echo __('country') ?></label>
        	<select class="myFormComponent" name="data[User][country_id]">
            <?php
				if(!empty($countries)){
					foreach($countries as $country){
						echo "<option value='".$country['Country']['id']."'>".$country['Country']['name']."</option>";
					}
				}
			?>
            </select><br>
            <div class="myFormComponent captcha">
				<span id="visible_captcha_box">
					<div id="captch_box">
					<a href="javascript:void(0)" 
					onclick="document.getElementById('captcha').src='<?php echo  __SITE_URL.'users/captcha_image?' ;?>'+Math.random()" id="change-image"><?php  echo $this->Html->image('/img/icons/refresh.gif',array('boder'=>0));  ?>
					</a>
					<img id="captcha" src="<?php echo $this->Html->url(__SITE_URL.'users/captcha_image');?>" alt="" />	
					</div>					 
                </span>
			</div>
            <input type="text" class="myFormComponent" name="data[User][captcha]" placeholder="<?php echo __('insert_captcha'); ?>"><br>
            <label class="myFormComponent" >  
					<?php echo __('regdt1') ?> (<a target="_blank" href="<?php echo __SITE_URL.'pages/view/4' ?>"><?php echo __('regdt2') ?></a>).
                 	
			</label>
			<br>
            <button type="submit" class="myFormComponent orange">
          		<span class="icon icon-user-add-1"></span>
                <span class="text"><?php echo __('register') ?></span>
            </button>
        </form>
		<?php } ?>
    </div>
	
 <aside class="col-md-3">
 	
 </aside>
</section>