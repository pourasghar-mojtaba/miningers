
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		پنل مدیریت سایت معدنر
	</title>
	
	
	<meta name="keywords" content="<?php if(isset($keywords_for_layout)) echo $keywords_for_layout ?>"/>
    <meta name="description" content="<?php if(isset($description_for_layout)) echo $description_for_layout; ?>">   

	<meta name="copyright" content="madaner.ir" />
	<META NAME="ROBOTS" CONTENT="INDEX, FOLLOW">
	<script>
		_url = "<?php echo __SITE_URL.'admin/'  ?>";
		_inactive = "<?php echo __('inactive') ?>";
		_active = "<?php echo __('active') ?>";
		_durl = "<?php echo __SITE_URL;  ?>";
	</script>
	<?php
		echo $this->Html->meta('icon');
		echo $this->Html->css('/css/admin/bootstrap-cerulean');
		//echo $this->Html->css('/css/admin/bootstrap-responsive');
		echo $this->Html->css('/css/admin/charisma-app.css');
		echo $this->Html->css('/css/admin/jquery-ui-1.8.21.custom');
		echo $this->Html->css('/css/admin/fullcalendar');
		echo $this->Html->css('/css/admin/chosen');
		echo $this->Html->css('/css/admin/uniform.default');
		echo $this->Html->css('/css/admin/colorbox');
		echo $this->Html->css('/css/admin/jquery.cleditor');
		echo $this->Html->css('/css/admin/jquery.noty');
		echo $this->Html->css('/css/admin/noty_theme_default');
		echo $this->Html->css('/css/admin/elfinder.min');
		echo $this->Html->css('/css/admin/elfinder.theme');
		echo $this->Html->css('/css/admin/jquery.iphone.toggle');
		echo $this->Html->css('/css/admin/opa-icons');
		echo $this->Html->css('/css/admin/uploadify');
		
		echo $this->Html->script('/js/admin/ckeditor/ckeditor');
		echo $this->Html->script('/js/admin/function');

echo $this->Html->script('/js/admin/jquery-1.7.2.min');
		echo $this->Html->script('/js/admin/jquery-ui-1.8.21.custom.min');
		
        echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script'); 
        // debug($locale) ;
		
		
		/**
		* admin information
		*/
		
		 
		$AdminUser_Info=$this->Session->read('AdminUser_Info');
		
		
		
	?>
	<style type="text/css">
	  body {
		padding-bottom: 40px;
	  }
	  .sidebar-nav {
		padding: 9px 0;
	  }
  </style>	  
</head>
<body>

<!-- topbar starts -->
	<div class="navbar">
		<div class="navbar-inner">
			<div class="container-fluid">
				<a class="btn btn-navbar" data-toggle="collapse" data-target=".top-nav.nav-collapse,.sidebar-nav.nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>
				<a class="brand" href="index.html"> 
				<?php  echo $this->Html->image('/img/admin/logo.png');  ?>
				 <span>Madaner</span></a>
				
				
				<!-- user dropdown starts -->
				<div class="btn-group pull-right" >
					<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
						<i class="icon-user"></i><span class="hidden-phone"> 
							<?php echo $AdminUser_Info['name']; ?>
						</span>
						<span class="caret"></span>
					</a>
					<ul class="dropdown-menu">
						<!--<li><a href="#">Profile</a></li>
						<li class="divider"></li>-->
						<li><a href="<?php echo __SITE_URL.'admin/users/logout' ?>"><?php echo __('logout') ?></a></li>
					</ul>
				</div>
				<!-- user dropdown ends -->
				
				<div class="top-nav nav-collapse">
					<ul class="nav">
						<li><a href="http://www.madaner.ir" target="_blank"><?php echo __('visit_site'); ?></a></li>
					</ul>
				</div><!--/.nav-collapse -->
			</div>
		</div>
	</div>
	<!-- topbar ends -->
	<div class="container-fluid">
	 <?php if($this->Session->check('Message.flash')) {?>
		<div >
			  <?php echo $this->Session->flash(); ?>
		</div>
	<?php } ?>
 	 <?php echo $this->fetch('content'); ?>
	  <?php echo $this->element('sql_dump'); ?>
	 
	<hr>

		<footer>
			<p class="pull-left">&copy; <a href="http://www.gilace.com" target="_blank">Gilace Group</a> 2013</p>
			<p class="pull-right">Powered by: <a href="http://www.gilace.com">Gilace</a></p>
		</footer>
		
	</div><!--/.fluid-container--> 
  <?php
  		
		/* transition / effect library */
		echo $this->Html->script('/js/admin/bootstrap-transition');
		/* alert enhancer library */
		echo $this->Html->script('/js/admin/bootstrap-alert');
		/* modal / dialog library */
		echo $this->Html->script('/js/admin/bootstrap-modal');
		/* custom dropdown library */
		echo $this->Html->script('/js/admin/bootstrap-dropdown');
		/* scrolspy library */
		echo $this->Html->script('/js/admin/bootstrap-scrollspy');
		/* library for creating tabs */
		echo $this->Html->script('/js/admin/bootstrap-tab');
		/* library for advanced tooltip */
		echo $this->Html->script('/js/admin/bootstrap-tooltip');
		/* popover effect library */
		echo $this->Html->script('/js/admin/bootstrap-popover');
		/* button enhancer library */
		echo $this->Html->script('/js/admin/bootstrap-button');
		/* accordion library (optional, not used in demo) */
		echo $this->Html->script('/js/admin/bootstrap-collapse');
		/* carousel slideshow library (optional, not used in demo) */
		echo $this->Html->script('/js/admin/bootstrap-carousel');
		/* autocomplete library */
		echo $this->Html->script('/js/admin/bootstrap-typeahead');
		/* tour library */
		 echo $this->Html->script('/js/admin/bootstrap-tour');
		/* library for cookie management */
		echo $this->Html->script('/js/admin/jquery.cookie');
		/* calander plugin */
		echo $this->Html->script('/js/admin/fullcalendar.min');
		/* data table plugin */
		echo $this->Html->script('/js/admin/jquery.dataTables.min');
		
		/* chart libraries start */
		echo $this->Html->script('/js/admin/excanvas');
		echo $this->Html->script('/js/admin/jquery.flot.min');
		echo $this->Html->script('/js/admin/jquery.flot.pie.min');
		echo $this->Html->script('/js/admin/jquery.flot.stack');
		echo $this->Html->script('/js/admin/jquery.flot.resize.min');
		/* chart libraries end */
		
		
		/* select or dropdown enhancer */
		echo $this->Html->script('/js/admin/jquery.chosen.min');
		/* checkbox, radio, and file input styler */
		echo $this->Html->script('/js/admin/jquery.uniform.min');
		/* plugin for gallery image view */
		echo $this->Html->script('/js/admin/jquery.colorbox.min');
		/* rich text editor library */
		echo $this->Html->script('/js/admin/jquery.cleditor.min');
		/* notification plugin */
		echo $this->Html->script('/js/admin/jquery.noty');
		/* file manager library */
		echo $this->Html->script('/js/admin/jquery.elfinder.min');
		/* star rating plugin */
		echo $this->Html->script('/js/admin/jquery.raty.min');
		/* for iOS style toggle switch */
		echo $this->Html->script('/js/admin/jquery.iphone.toggle');
		/* autogrowing textarea plugin */
		echo $this->Html->script('/js/admin/jquery.autogrow-textarea');
		/* multiple file upload plugin */
		echo $this->Html->script('/js/admin/jquery.uploadify-3.1.min');
		/* history.js for cross-browser state change on ajax */
		echo $this->Html->script('/js/admin/jquery.history');
		/* application script for Charisma demo */
		echo $this->Html->script('/js/admin/charisma');
		
		
		
		
	
		
	?>	
  
</body>
</html>
