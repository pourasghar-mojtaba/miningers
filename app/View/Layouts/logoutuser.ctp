<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

//$cakeDescription = __d('cake_dev', __('site_title'));
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php if(isset($title_for_layout)) echo  $title_for_layout ?> 
	</title>
	
	
	<meta name="keywords" content="<?php if(isset($keywords_for_layout)) echo $keywords_for_layout ?>"/>
    <meta name="description" content="<?php if(isset($description_for_layout)) echo $description_for_layout; ?>">   

	<meta name="copyright" content="madaner.ir" />
	<META NAME="ROBOTS" CONTENT="INDEX, FOLLOW">
	<script type="text/javascript">
		_url = '<?php echo __SITE_URL  ?>';
		_loading="<?php echo __('loading') ?>"; 
		_close= "<?php echo __('close') ?>";
		_message= "<?php echo __('message') ?>";
		_cancel= "<?php echo __('cancel') ?>";
		_warning= "<?php echo __('warning') ?>";
	</script>
	
	<?php
		echo $this->Html->meta('icon');	

		$this->Gilace->autoCompileLess(ROOT.DS.APP_DIR.DS.'webroot'.DS.'less_'.$locale. DS .'home.less', ROOT.DS.APP_DIR.DS.'webroot'.DS.'css'.DS.'home_'.$locale.'.css');
		
		echo $this->Html->css('home_'.$locale);	
		echo $this->Html->css('/js/Zebra_Dialog-master/public/css/zebra_dialog.css');
        echo $this->Html->script('jquery-1.7.2.min');   	
		
        echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
        
	?>
</head>
<body>
    <?php echo $this->element('header'); ?> 
	<section id="apps">
        <a class="tile green size100" href="#">
            <span class="icon icon-madaner-shop"></span>
            <span class="text"> <?php echo __('shop'); ?></span>
        </a>
        <a class="tile dark size100" href="#">
            <span class="icon icon-madaner-library"></span>
            <span class="text"> <?php echo __('library'); ?></span>
        </a>
    </section>
	<!--
	<div class="pageContainer container-fluid page0" style="height:533px">
        <div class="homeShow row">
        	<div class="container">
                <a href="http://miningram.miningers.com/">
				<div class="BG">
					<?php
						 $image = '/img/background/minigram1.jpg';		 
					?>
					<?php  echo $this->Html->image($image,array('width'=>1920,'height'=>540));  ?>
					<map name="imgmap">
	                    <area shape="rect" coords="48,341,294,275" href="http://www.lego.com/en-us/">
	                </map>
                </div>
				</a>
            </div>
        </div>
    </div>-->
	<div class="pageContainer container-fluid page1">
        <div class="homeShow row">
        	<div class="container">
            	<!--<header id="homeHeader">
                    <div class="tile blue size100 free">
                        <h1>
                            <a href="http://madaner.ir">
                                <div class="icon icon-madaner-main"></div>
                                 
								<div id="logo"> 
								<?php  echo $this->Html->image('/img/madaner-logo-name.png',array('width'=>114,'height'=>40,'alt'=>'madaner'));  ?>
                                </div> 
                               <span> <?php echo __('site_title'); ?></span>
                            </a>
                        </h1>
                         <div class="allApps icon icon-down-open"></div> 
                    </div>
                </header>-->
                <?php
					echo $this->fetch('content');
				?>
                <div class="BG">
					<?php
						$home_image=$this->requestAction(__SITE_URL.'homeimages/get_home_image/');
						//pr($home_image);
						if(!empty($home_image) && isset($home_image)){
							$home_image = $home_image[0];
							$image = '/'.__HOME_IMAGE_PATH.$home_image['Homeimage']['image'];
						}else
						 $image = '/img/background/3.jpg';		 
					?>
					<?php  echo $this->Html->image($image,array('width'=>1920,'height'=>1080));  ?>
                </div>
            </div>
        </div>
    </div>
     
	<div class="container-fluid page3">
    	<div class="col-sm-8 col-sm-offset-2">
        	<header><?php echo __('register_comment2'); ?></header>
            <p><?php echo __('register_comment4'); ?> </p>
        </div>
        <div class="clear"></div>
    </div>
	 
    <div class="pageContainer container-fluid page2">
    	<div class="col-md-6">
        	<div class="dataBox">
            	<div class="obj2">
					<?php  echo $this->Html->image('/img/miningers-app1.png',array('width'=>'480','height'=>'571'));  ?>
				</div>           
            </div>
        </div>
    	<div class="col-md-6">
        	<div class="dataBox obj1">
            	<h3><?php echo __('intro_detail4'); ?> </h3>
                <p><?php echo __('intro_detail5'); ?> </p>
				<p>
					<a target="_blank" href="http://cafebazaar.ir/app/Miningers.Miningers/">
					<?php  
						if($locale =='per')
							echo $this->Html->image('/img/badge-g.png');  
						else if($locale =='eng') echo $this->Html->image('/img/badge-g.png');  
					?>
					</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<img  src="<?php echo __SITE_URL.'img/barcode.jpg' ?>" />
				</p>
            </div>
        </div>
    </div>
	 
   <!--
   
   <div class="pageContainer container-fluid page2">
    	<div class="col-md-6">
        	<div class="dataBox">
            	<div class="obj2">
					<?php  echo $this->Html->image('/img/world.png',array('width'=>'516','height'=>'316'));  ?>
				</div>
            	<div class="obj3">
					<?php  echo $this->Html->image('/img/statistics.png',array('width'=>'593','height'=>'316'));  ?>
				</div>
            
            </div>
        </div>
    	<div class="col-md-6">
        	<div class="dataBox obj1">
            	<h3><?php echo __('relationship_with_us'); ?> ...</h3>
                <p><?php echo __('intro_detail3'); ?>... </p>
            </div>
        </div>
        <div class="col-md-7 col-md-offset-5 obj1">
        	<div class="searchBox">
            	<form class="myForm">
                    <input type="hidden" value="0">
                	<input type="search" class="myFormComponent" placeholder="<?php echo __('search_all_madaner_event'); ?>">
                    <button type="submit" role="button"><span class="icon icon-search"></span></button>
                </form>
				<div class="col-sm-6 col-sm-offset-3">
                    <a href="#" class="tile blockTile btn green">
                    	<span class="icon"></span>
                    	<span class="text center"><?php echo __('follow_madanr_news'); ?></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
   
    <div class="pageContainer container-fluid page4">
        <div class="homeShow">
        	<div class="container">
                <div class="col-sm-9 col-sm-offset-1 col-md-8 col-md-offset-2 overBox">
                    <div class="darkBox someText">
                        <header><?php echo __('join_to_madaner'); ?></header>
                        <p><?php echo __('to_be_informed_of_news_mining_and_related_industries'); ?></p>
                        <div class="col-xs-10 col-xs-offset-1">
                            <div class="searchBox">
                                <?php echo $this->Form->create('Post', array('id'=>'SearchForm','name'=>'SearchForm','class'=>'myForm','action'=>'/search','autocomplete'=>'off')); ?>
                                    <input type="hidden" value="0">
                                    <input type="search" id="search_box" name="data[User][search_word]" class="myFormComponent" placeholder="<?php echo __('search_all_events_Mine'); ?>">
                                    <button type="submit" role="button"><span class="icon icon-search"></span></button>
									<input type="hidden" value="2" id="search_type">
									<div id="search_display" class="col-md-12">
											 <div id="search_result"></div>
											 <div id="search_loading"></div>
								    </div>
                                </form>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <p class="OR"><?php echo __('or'); ?></p>
                    <div class="col-sm-6 col-sm-offset-3">
                        <a href="#" class="tile blockTile btn green">
                            <span class="icon"></span>
                            <span class="text center"> <?php echo __('follow_madanr_news'); ?></span>
                        </a>
                    </div>
                </div>
				<?php
					$home_image=$this->requestAction(__SITE_URL.'homeimages/get_home_image/');
					//pr($home_image);
					if(!empty($home_image) && isset($home_image)){
						$home_image = $home_image[0];
						$image = '/'.__HOME_IMAGE_PATH.$home_image['Homeimage']['image'];
					}else
					 $image = '/img/background/7.jpg';		 
				?>
                <div class="BG">
					<?php  echo $this->Html->image($image,array('width'=>1800,'height'=>1200));  ?>
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </div>-->
	   
   <?php echo $this->element('footer'); ?>  
   
 <?php
 		
	    echo $this->Html->script('component');
	    echo $this->Html->script('/js/Zebra_Dialog-master/public/javascript/zebra_dialog');
		echo $this->Html->script('fix');
		echo $this->Html->script('function');
		echo $this->Html->script('register');
		echo $this->Html->script('global');
 ?>  
   
   
    <script>
        $(document).ready(function(e) {
            init();
			
			var page2FirstTime = 1;
			$( window ).scroll(function()
			{
			if(HasBeenScrolledTo($('.page2')) && page2FirstTime )
				{
					//animationPage2();
					page2FirstTime=0;
				}
			});
        });
    </script>

</body>
</html>
