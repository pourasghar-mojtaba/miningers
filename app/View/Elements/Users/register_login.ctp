 
<div id="content">
    	<?php
			$home_image=$this->requestAction(__SITE_URL.'homeimages/get_home_image/');
			//pr($home_image);
			if(!empty($home_image) && isset($home_image)){
				$home_image = $home_image[0];
				$image = '/'.__HOME_IMAGE_PATH.$home_image['Homeimage']['image'];
			}else
			 $image = '/img/background/3.jpg';		 
		?>
		<div class="BG">
			<?php  echo $this->Html->image($image,array('width'=>1800,'height'=>1200));  ?>
		</div>
        <script type="text/javascript">
		$("#content .BG img").load(function(){
			var dist = (-1)* Math.abs(($("#content .BG").height() - $("#content .BG img").height())/2);
			$("#content .BG img").animate({top:dist},8000);
			})
        </script>
        <div class="registerForm" id="first_page">
            <form id='UserRegisterForm' name='UserRegisterForm' method="POST" action="">
            <div class="pages">
                <span><strong><?php echo __('not_member'); ?></strong><?php echo __('join_to_madaner'); ?></span>
                <input type="text"  id="register_name" placeholder="<?php echo __('name'); ?>" name="data[name]">
                <input type="text"  id="user_name" placeholder="<?php echo __('user_name'); ?>" name="data[user_name]">
                <input type="text" id="register_email" placeholder="<?php echo __('email'); ?>" name="data[register_email]">
                <input type="password"  id="password" placeholder="<?php echo __('password'); ?>" name="data[password]"> 
                <div class="tile btn orange1 next">
                    <header><?php echo __('join_to_madaner_on_few_minute') ?></header>
                    <div class="symbol">
                    </div>
                </div><br>
            </div>
            <div class="Pages" style="display:none" id="end_page">
                <span><strong> <?php echo __('check_security') ?></strong></span>
                <label><?php echo __('enter_captcha') ?></label>
                 
				<input type="text" class="captchaInput" id="register_captcha" placeholder="<?php echo __('captcha'); ?>" name="data[User][login_captcha]">
                <div id="register_captch_box">
				<a href="javascript:void(0)" 
				onclick="document.getElementById('regidter_captcha_img').src='<?php echo  __SITE_URL.'users/captcha_image?' ;?>'+Math.random()" id="change-image"><?php  echo $this->Html->image('/img/icons/refresh.gif',array('boder'=>0));  ?>
				</a>
				<img id="regidter_captcha_img" src="<?php echo $this->Html->url(__SITE_URL.'users/captcha_image');?>" alt="" />	
				</div>
                <span id="madaner_role">
                    <?php echo __('madaner_role1'); ?>
                    <a href="<?php echo __SITE_URL.'pages/view/4'; ?>" target="_blank"><?php echo __('madaner_role2'); ?></a>
                    <?php echo __('madaner_role3'); ?>
                </span>    
                <div class="tile btn orange1 submit" onClick="submitForm(this)">
                    <header> <?php echo __('register') ?></header>
                    <div class="symbol">
                    </div>
                </div><br>
                <div class="tile btn gray1 prev">
                    <header> <?php echo __('back') ?></header>
                    <div class="symbol">
                    </div>
                </div>
            </div>
            </form>
        </div>
        <div class="extraText">
        	<h1><?php echo __('register_comment1') ?></h1>
            <h2><?php echo __('register_comment2') ?></h2>
            <h3> <?php echo __('register_comment3') ?></h3>
        </div>
        <div class="searchPlace">
            <?php echo $this->element('search_box'); ?> 
        </div>
    </div>
    <div class="describtion">
    	<section class="columns">
        	<div class="boxes">
            	<div class="homeIcons">
                	<div class="news active"></div>
                	<div class="news"></div>
                </div>
                <section>
                    <header> <?php echo __('chat') ?></header>
                    <article>
                        <ul>
                        	<li><?php echo __('chat_comment1'); ?></li>
                        	<li><?php echo __('chat_comment2'); ?></li>
                        	 
                        </ul>
                    </article>
                </section>
            </div>
        	<div class="boxes">
            	<div class="homeIcons">
                	<div class="social active"></div>
                	<div class="social"></div>
                </div>
                <section>
                    <header><?php echo __('social_network'); ?></header>
                    <article>
                        <ul>
                        	<li><?php echo __('social_network_comment1'); ?></li>
                        	<li><?php echo __('social_network_comment2'); ?></li>
                        	<li><?php echo __('social_network_comment3'); ?></li>
                        	 
                        </ul>
                    </article>
                </section>
            </div>
        	<div class="boxes">
            	<div class="homeIcons">
                	<div class="blog active"></div>
                	<div class="blog"></div>
                </div>
                <section>
                    <header> <?php echo __('news'); ?></header>
                    <article>
                        <ul>
                        	<li><?php echo __('news_comment1'); ?></li>
                        	<li><?php echo __('news_comment2'); ?></li>
                        	<li><?php echo __('news_comment3'); ?></li>
                        	 
                        </ul>
                    </article>
                </section>
            </div>
        </section>
    	<section class="columns">
        	<div class="boxes">
            	<div class="homeIcons">
                	<div class="contact active"></div>
                	<div class="contact"></div>
                </div>
                <section>
                    <header><?php echo __('relation'); ?></header>
                    <article>
                        <ul>
                        	<li><?php echo __('relation_comment1'); ?></li>
                        	<li><?php echo __('relation_comment2'); ?></li>
                        	<li><?php echo __('relation_comment3'); ?></li>
                      
                        </ul>
                    </article>
                </section>
            </div>
        	<div class="boxes">
            	<div class="homeIcons">
                	<div class="vote active"></div>
                	<div class="vote"></div>
                </div>
                <section>
                    <header><?php echo __('regard'); ?></header>
                    <article>
                        <ul>
                        	<li><?php echo __('regard_comment1'); ?></li>
                        	<li><?php echo __('regard_comment2'); ?></li>
                        	  
                        </ul>
                    </article>
                </section>
            </div>
        	<div class="boxes">
            	<div class="homeIcons">
                	<div class="visit active"></div>
                	<div class="visit"></div>
                </div>
                <section>
                    <header> <?php echo __('profile'); ?></header>
                    <article>
                        <ul>
                        	<li><?php echo __('profile_comment1'); ?></li>
                        	<li><?php echo __('profile_comment2'); ?></li>
                        	<li><?php echo __('profile_comment3'); ?></li>
                        	
                        </ul>
                    </article>
                </section>
            </div>
        </section>
    </div>