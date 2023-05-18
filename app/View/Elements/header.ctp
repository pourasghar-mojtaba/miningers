<?php
	$User_Info= $this->Session->read('User_Info');
	if(!empty($User_Info)){
		$user_box=$this->requestAction(__SITE_URL.'users/get_user_info/'.$User_Info['id']);
		$new_message_count = $user_box['new_message_count']; 
	}
    else $new_message_count = 0;
	
?>
    <section id="apps">
        <a class="tile green size100" href="#">
            <span class="icon icon-madaner-shop"></span>
            <span class="text"><?php echo __('shop'); ?></span>
        </a>
        <a class="tile dark size100" href="#">
            <span class="icon icon-madaner-library"></span>
            <span class="text"><?php echo __('library'); ?></span>
        </a>
    </section>
	<div class="pageContainer container-fluid">
        <header id="siteHeader" >
            <nav class="navbar">
            	
            	<div class="col-md-7">
                	<ul class="topMenu" role="menubar">
                    	<li>
                        	<a href="<?php echo __SITE_URL; ?>">
                            <!--<span class="icon icon-home"></span>--><span class="text"><?php echo __('home'); ?></span>
                            </a>
                        </li>
						<?php if(!empty($User_Info)){ ?>
						<li>
                            <a href="<?php echo __SITE_URL.$User_Info['user_name']; ?>" style="position:relative">
								<span class="text"><?php echo __('profile') ?></span>
								<?php if($new_message_count>0){ ?>
								<span id="profile_notification_icon" class="profile_notification_icon">
								<?php echo $new_message_count; ?>
								</span>
								<?php } ?>
							</a>
                        </li>
						<li>
                            <a href="<?php echo __SITE_URL.'users/search'; ?>">
								<!--<span class="icon icon-users-1"></span>--><span class="text"><?php echo __('users') ?></span>
							</a>
                        </li>
						<li>
                        	<a href="<?php echo __SITE_URL.'News'; ?>">
                            	<span class="text"><?php echo __('news') ?></span>
                            </a>
                        </li>
						<!--<li>
                        	<a href="http://miningram.miningers.com/">
                            	<span class="text"><?php echo __('miningram_festival') ?></span>
                            </a>
                        </li>-->
						<li>
                        	<a href="<?php echo __SITE_URL.'/blogs/add_blog'; ?>">
                            	<span class="text"><?php echo __('blog') ?></span>
                            </a>
                        </li>
                    	<li onClick="popUp('<?php echo  __SITE_URL.'posts/post_window' ?>')">
                            <span class="icon icon-doc-new"></span><span class="text"><?php echo __('post_new') ?></span>
                        </li>

						<!--
						<li>
                        	<a href="<?php echo __SITE_URL.'posts/discover' ?>">
                            <span class="icon icon-globe"></span><span class="text"><?php echo __('discover'); ?></span>
                            </a>
                        </li>-->
						<?php }else{ ?>
                        <li>
                        	<a href="<?php echo __SITE_URL.'News'; ?>">
                            	<span class="text"><?php echo __('news') ?></span>
                            </a>
                        </li>
						<li>
                        	<a href="<?php echo __SITE_URL.'/blogs/add_blog'; ?>">
                            	<span class="text"><?php echo __('blog') ?></span>
                            </a>
                        </li>
						<!--<li>
                        	<a href="http://miningram.miningers.com/">
                            	<span class="text"><?php echo __('miningram_festival') ?></span>
                            </a>
                        </li>-->
						<?php } ?>
						
                    	
                    </ul>
                </div>
				
				<div class="col-md-1">
                    <h1 id="logo">
                        <a href="http://madaner.ir">
                            <div class="icon icon-madaner-main"></div>
                        <div class="logoImage">
							<?php /* echo $this->Html->image('/img/madaner-logo-name.png',array('width'=>114,'height'=>40,'alt'=>'madaner')); */ ?>
						</div>
                            <!--<span><?php echo __('site_title'); ?></span>-->
                        </a>
                    </h1>
                    <!--<div class="allApps icon icon-down-open"></div>-->
                </div>
				
                <div class="col-md-3">
                	<div class="searchBox" style="position:relative">
						<?php echo $this->Form->create('User', array('id'=>'SearchForm','name'=>'SearchForm','class'=>'myForm','action'=>'/search','autocomplete'=>'off')); ?>
                            <div class="dropdown" role="menu">
                                <div class="icon icon-down-dir dropdownBtn"></div>
                                <ul class="left">
                                    <li  onClick="setSearchOption(1,'<?php echo __('users') ?>','<?php echo __SITE_URL; ?>users/search')"><?php echo __('users') ?></li>
                                    <li onClick="setSearchOption(2,'<?php echo __('news_content') ?>','<?php echo __SITE_URL; ?>posts/search')"><?php echo __('news_content') ?></li>
                                    <li onClick="setSearchOption(3,'<?php echo __('tag') ?>','<?php echo __SITE_URL; ?>posts/search_tag')"><?php echo __('tag') ?></li>
                                </ul>
                            </div> 
                            <input type="hidden" value="1" id="search_type">
                        	<input type="search" id="search_box" name="data[User][search_word]" class="myFormComponent" 
							placeholder="<?php echo __('search'); ?>">
                            <button type="submit" role="button"><span class="icon icon-search"></span></button>
							<div id="search_display" class="col-md-12">
									 <div id="search_result"></div>
									 <div id="search_loading"></div>
						    </div>
                        </form>
                    </div>
                </div>
				<?php if(!empty($User_Info)){ ?>
                <div class="col-md-1">
                	<div class="reportMenu">
                    	<div class="dropdown" role="menu">
                            <div class="icon icon-menu2 dropdownBtn"></div>
                            <ul>
                            	<li><a href="<?php echo __SITE_URL.'users/edit_profile' ?>">
										<?php echo __('edit_info'); ?>
									</a>
								</li>
								<li><a href="<?php echo __SITE_URL.'privacies/edit' ?>"><?php echo __('privacy'); ?> </a></li>
                            	<?php
                            
                    		  $LearnObjectInfos= $this->Session->read('LearnObjectInfo');                    		 
                    		  if(!empty($LearnObjectInfos)){
                    		  	foreach ($LearnObjectInfos as $key=>$LearnObjectInfo  )
                    			{
                    				if($LearnObjectInfo['learn_object_id']==1){
                                        if($this->request->params['controller']=='pages' && $this->request->params['action']=='display') 
                                        {
                                           echo "<div class='seperator_Hor'></div>";
                                           echo "<li><a href='javascript:void(0)' onclick='show_home_learn();' >". __('learn_this_page')."</a></li>"; 
                                        }                                           
                                    }
                                    if($LearnObjectInfo['learn_object_id']==2){
                                        if($this->request->params['controller']=='users' && $this->request->params['action']=='profile') 
                                        {
                                           if($user_id!=$User_Info['id']){
                                               echo "<div class='seperator_Hor'></div>";
                                               echo "<li><a href='javascript:void(0)' onclick='show_profile_learn();'>".__('learn_this_page')."</a></li>"; 
                                           }
                                        }                                           
                                    }
                    			}
                    		  } 
                            ?>
							<li><a href="<?php echo __SITE_URL.'pages/view/2'; ?>"><?php echo __('help'); ?></a></li>
							<li><a href="<?php echo __SITE_URL.'users/logout' ?>"><?php echo __('logout'); ?></a></li> 
                            </ul>
                        </div>
                    </div>
                </div>
				<?php } ?>
            </nav>
        </header>
      <div class="header_fix"></div>	  
       <?php if(!empty($User_Info) && ($this->request->params['controller']=='pages' && $this->request->params['action']=='display') ){ ?>  
        <div class="col-md-12" id="alert-notification">
            <div class="col-md-3">
             
            </div>
            <div class="col-md-6"> 
				<?php if($this->Session->read('register_key')!=-1){ ?> 
	             <div data-alert class="alert-box secondary" id="email_verify">
	              <label><?php echo __('by_this_email_address_you_have_registered_on_MADANER_Please_edit_or_verify_it') ?>
				  </label>
                  <form class="myForm">
	              <input type="text" value="<?php echo $User_Info['email'];?>" dir="ltr" class="myFormComponent ltr" style='border:1px solid #000000' />
                  </form>
				    <div class="col-md-10 embedEdit">			
						<a href="<?php echo __SITE_URL.'users/edit_email' ?>">
						<div class="col-sm-5">
			                <div class="tile blockTile orange btn">
								<span class="icon icon-reply"></span>
			                    <span class="text"><?php echo __('edit_profile') ?></span>
			                </div>
			            </div>					
						</a>
						<div  class="col-sm-5">
			                <div class="tile blockTile green btn" onclick="edit_email('<?php echo $User_Info['email'] ?>');">
			                    <span class="icon icon-ok"></span>
								<span class="text"><?php echo __('verify') ?></span>
			                </div>
			            </div>	
						<span id="verify_email_loading"></span>	 
        			</div>
					<div class='clear'></div>

	               
				  <label><?php echo __('verify_note_email') ?>
				  </label>
	            </div>
				<?php } ?> 
            </div> 
            <div class="col-md-3">
             
            </div>
        </div>
		<?php } ?> 
        
        <?php if(!empty($User_Info) && ($this->request->params['controller']=='pages' && $this->request->params['action']=='display') && ($locale !='eng')){ ?>  
        <!--<div class="col-md-12" id="alert-notification">
            <div class="col-md-3">
             
            </div>
            <div class="col-md-6"> 
				<?php if($this->Session->read('mobile')=='' && $this->Session->read('register_key')==-1){ ?> 
	             <div data-alert class="alert-box secondary" id="email_verify">
	              <label><?php echo __('for_send_post_in_madaner_enter_your_mobile_number') ?>
				  </label>
	              
				    <div class="col-md-10 embedEdit">			
						<a href="<?php echo __SITE_URL.'sms/verify' ?>">
						<div class="col-sm-5">
			                <div class="tile blockTile orange btn">
								<span class="icon icon-ok"></span>
			                    <span class="text"><?php echo __('edit_mobile') ?></span>
			                </div>
			            </div>					
						</a>	
						<span id="verify_email_loading"></span>	 
        			</div>
					<div class='clear'></div>

	               
				   
				  </label>
	            </div>
				<?php } ?> 
            </div> 
            <div class="col-md-3">
             
            </div>
        </div>-->
		<?php } ?> 
	
 