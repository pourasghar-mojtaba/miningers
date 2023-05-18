
<footer id="siteFooter">
		<div class="externalLink">
        	<div class="col-sm-2 col-xs-4">
            	<header> <?php echo __('madaner'); ?></header>
                <ul>
                	<li><a href="<?php echo __SITE_URL.'News' ?>"> <?php echo __('news'); ?></a></li>
                	<li><a href="<?php echo __SITE_URL.'Events' ?>"> <?php echo __('madaner_events'); ?></a></li>
                	<li><a href="<?php echo __SITE_URL.'Jobs' ?>"> <?php echo __('madaner_jobs'); ?></a></li>
                	<li><a href="<?php echo __SITE_URL.'CapitalMarket' ?>"> <?php echo __('capital_markets'); ?></a></li>
                	<li>&nbsp;</li>
                </ul>
            </div>
        	<div class="col-sm-2  col-xs-4">
            	<header> <?php echo __('main_section'); ?></header>
                <ul>
                	<li><a href="#"> <?php echo __('social_network'); ?></a></li>
                	<li><a href="#"> <?php echo __('shop'); ?></a></li>
                	<li><a href="#"> <?php echo __('library'); ?></a></li>
                	<li><a href="<?php echo __SITE_URL."users/search"; ?>"> <?php echo __('peoples'); ?></a></li>
                	<li><a href="#"> <?php echo __('companies'); ?></a></li>
                </ul>
            </div>
        	<div class="col-sm-2  col-xs-4">
            	<header> <?php echo __('solutions'); ?></header>
                <ul>
                	<li><a href="<?php echo __SITE_URL.'pages/view/24' ?>"> <?php echo __('advertise_whit_us'); ?></a></li>
                	<li><a href="#"> <?php echo __('premium_membership'); ?></a></li>
                	<li><a href="<?php echo __SITE_URL.'pages/view/23' ?>"> <?php echo __('mobile'); ?></a></li>
                	<li>&nbsp;</li>
                	<li>&nbsp;</li>
                </ul>
            </div>
        	<div class="col-sm-2  col-xs-4">
            	<header> <?php echo __('help_center'); ?></header>
                <ul>
                	<li><a href="<?php echo __SITE_URL.'pages/view/1' ?>"> <?php echo __('about'); ?></a></li>
                	<li><a href="#"> <?php echo __('faq'); ?></a></li>
                	<li><a href="#"> <?php echo __('blog'); ?></a></li>
                	<li><a href="<?php echo __SITE_URL.'pages/view/4' ?>"> <?php echo __('terms'); ?></a></li>
                	<li><a href="<?php echo __SITE_URL.'pages/view/5' ?>"> <?php echo __('contact'); ?></a></li>
                </ul>
            </div>
        	<div class="col-sm-2  col-xs-4">
            	<header> <?php echo __('follow_us'); ?>!</header>
                <ul>
                	<li><a href="http://www.avands.com/Miningers" target="_blank">Avands </a></li>
                	<li><a href="https://www.facebook.com/miningers" target="_blank"><span class="icon-facebook"></span>Facebook</a></li>
                	<li><a href="https://www.twitter.com/Miningers" target="_blank"><span class="icon-twitter"></span>Twitter</a></li>
                	<li><a href="https://plus.google.com/u/0/b/116937396125958801977/116937396125958801977/about/p/pub?hl=en&service=PLUS" target="_blank"><span class="icon-gplus-squared"></span>+Google</a></li>
                	<li>&nbsp;</li>
                </ul>
            </div>
			 
        	<div class="col-sm-2  col-xs-4">
            	<header>Language <?php echo __('madaner'); ?></header>
                <ul>
					<?php
					//__SITE_URL.$this->request->params['controller'].'/'.$this->request->params['action']
					$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
					
					if($this->request->params['controller']=='pages' && $this->request->params['action']=='display'){
						$actual_link = __SITE_URL.'pages/display';
						
					}
					
					/*if($this->request->params['controller']=='users' && $this->request->params['action']=='profile'){
						$actual_link = __SITE_URL.'users/display';
						
					}*/
					
					
					
					$actual_link = str_replace(__SITE_URL,'',$actual_link);
					$actual_link = str_replace('per/','',$actual_link);
					$actual_link = str_replace('eng/','',$actual_link);
											
					$per_link = __SITE_URL.'per/'.$actual_link;						
					$eng_link = __SITE_URL.'eng/'.$actual_link;
							
							// http://localhost/madaner/per/pages/display
						  				 
					?>
					
					
					<!--<li><?php echo $this->Html->link('Persian', array('language'=>'per')); ?></li>
                	<li><?php echo $this->Html->link('English', array('language'=>'eng')); ?></li>-->
                	<li><a href="<?php echo $per_link;  ?>">Persian</a></li>
					<li><a href="<?php echo $eng_link;  ?>">English</a></li>
                	<li>&nbsp;</li>
					<li>&nbsp;</li>
                	<li>&nbsp;</li>
                	<li>&nbsp;</li>
                </ul>
            </div>
			
            <div class="clear"></div>
        </div>
        <div class="rights">all rights reserved for <a href="http://www.madaner.ir">www.madaner.ir</a> 2014
            <div class="clear"></div>
        </div>
        <div class="developer">
            <a id="gilace" href="http://springdesigng.com" target="_blank" >
				<?php if($locale !='eng'){ echo $this->Html->image('gilaceLogo.png'); } ?>
            </a>
			<?php  if($locale !='eng'){echo $this->Html->image('/img/icons/BPMLogo1.png');}  ?>
        </div>
</footer>
<div id="ajax_result"></div>
<!--
<div id="footer">
    <div id="gilace">
        <div class="text1">Powered By</div>
        <div class="text2"><a href="http://www.Gilace.com" target="_blank">Gilace.com</a></div>
    </div>
	<ul class="links"> 
		<li><a href="<?php echo __SITE_URL.'MadanerNews'; ?>"><?php echo __('madaner_news'); ?></a></li>
		<li><a href="<?php echo __SITE_URL.'MadanerJobs'; ?>"><?php echo __('madaner_jobs'); ?></a></li>
		<li><a href="<?php echo __SITE_URL.'MadanerEvents'; ?>"><?php echo __('madaner_events'); ?></a></li>
		<li><a href="<?php echo __SITE_URL.'CapitalMarkets'; ?>"><?php echo __('capital_markets'); ?></a></li>
		
		<li><a href="<?php echo __SITE_URL.'users/search?user_type=0'; ?>"> <?php echo __('users'); ?></a></li> 
		<li><a href="<?php echo __SITE_URL.'blogs/index'; ?>"><?php echo __('blog'); ?></a></li>
		<li><a href="<?php echo __SITE_URL.'users/edit_profile' ?>"><?php echo __('privacy'); ?></a></li> 
		<?php
			$pages=$this->requestAction(__SITE_URL.'pages/get_main_pages/');
			if(!empty($pages)){
				foreach($pages as $page)
				{
					echo"<li>
						<a href=". __SITE_URL."pages/view/".$page['Page']['id']."> ".$page['Page']['title']."</a>
						</li>";
				}
			}
		?> 
    </ul>
     <div id="ajax_result"></div>
</div>
-->
<?php
    //echo $this->Html->script('/js/foundation/js/vendor/jquery');
    //echo $this->Html->script('/js/foundation/js/foundation.min');
    if($this->layout=='default'){
        
    
?>
<script>
//$(document).foundation();
jQuery(
  function($)
  {
  		var notificaion_count = 0;
	    $('#notification_body').slimScroll({
	          height: 'auto'
	      });
	    
	    $('#notification_body').bind('scroll', function()
	      {
	        if($(this).scrollTop() + $(this).innerHeight()>=$(this)[0].scrollHeight)
	        {
	           notificaion_count++;
			   refresh_notification(notificaion_count);
	        }
	      })
  }
);
</script>
<?php
}
?>