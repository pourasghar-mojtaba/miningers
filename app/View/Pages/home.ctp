<?php
	$this->Gilace->autoCompileLess(ROOT.DS.APP_DIR.DS.'webroot'.DS.'less_'.$locale. DS .'index.less', ROOT.DS.APP_DIR.DS.'webroot'.DS.'css'.DS.'index_'.$locale.'.css');
	
	$this->Gilace->autoCompileLess(ROOT.DS.APP_DIR.DS.'webroot'.DS.'less_'.$locale. DS .'components.less', ROOT.DS.APP_DIR.DS.'webroot'.DS.'css'.DS.'components_'.$locale.'.css');
		
	echo $this->Html->css('index_'.$locale);
    
      
	
	echo $this->Html->script('jquery.form');
	$User_Info= $this->Session->read('User_Info');
?>

<script>
 
var count=0;
;	
	
 



</script>
<section id="homePage">
	<div class="col-md-3 col-md-offset-0 col-sm-3 ">
        <?php echo $this->element('user_box'); ?>
        <?php echo $this->element('right'); ?>				
    </div>
    <div class="col-md-6 col-sm-6" >
        <div class="clear"></div>  
		<?php 
		   $categoryposts=$this->requestAction(__SITE_URL.'posts/new_post_data/');
		   echo $this->element('Posts/Ajax/post_window',array('in_home'=>TRUE,'categoryposts'=>$categoryposts));
		?>	
		<ul class="options" id="categoryposts" style="display:none">
			<?php 
			if(!empty($categoryposts)){
				$i=0;
				foreach($categoryposts as $categorypost){
					if($i==0){
						echo "<li check_value='1' title='".$categorypost['Categorypost']['title']."' value='".$categorypost['Categorypost']['id']."'  ></li>";
					}else{
						echo "<li check_value='0' title='".$categorypost['Categorypost']['title']."' value='".$categorypost['Categorypost']['id']."'  ></li>";
					}
					$i++;
				}
			}
		  ?>
		</ul>
		<div class="clear"></div> 
		<?php echo __('minits'); ?>:
		<select id="categorypost_element" style="margin-bottom:10px;" >
			<?php 
			if(!empty($categoryposts)){
				$i=0;
				foreach($categoryposts as $categorypost){
					if($i==0){
						echo "<option selected value='0'  >
							".__('all_minit')."
						</option>";
					}
						echo "<option value='".$categorypost['Categorypost']['id']."'  >
							".$categorypost['Categorypost']['title']."
						</option>";
					
					$i++;
				}
			}
		  ?>
		</select>
		<br />
		<div class="clear"></div> 
		<div id="home_body">
		
		</div>
		<span class="home_loading" style="margin: 40%">
            <?php echo "<img src ='".__SITE_URL."img/loader/big_loader.gif' />"; ?> 
        </span>  
		<div class="col-xs-12" id="new_follow" style="display:none">
			<label><?php echo __('follow_dtl1'); ?> </label>
			<a href="<?php echo __SITE_URL.'users/search'; ?>" style="color:#1766e8" ><?php echo __('here'); ?> </a>
			<label><?php echo __('follow_dtl2'); ?></label>
            <a href="<?php echo __SITE_URL.'users/search'; ?>" style="text-decoration: none">
				<div class="btn classic" >
	                <span class="text"><?php echo __('follow_more_users'); ?></span>
	                <span class="icon icon-left-open"></span>
	            </div>
			</a>
        </div>          
    </div>
	
 <aside class="col-md-3 col-md-offset-0 col-sm-3 big_box" >
 	<?php echo $this->element('left'); ?>	
 </aside>
</section>
 
<script>
$(window).scroll(function(){  
		if  ($(window).scrollTop() == $(document).height() - $(window).height()){    
		   count++; 
		   refresh_home(count,$("#categorypost_element option").filter(":selected").val());
		}  
});

$('#categorypost_element').change(function(){
	$('#home_body').html("");
	refresh_home(count,$("#categorypost_element option").filter(":selected").val());
});

refresh_home(count,$("#categorypost_element option").filter(":selected").val());

select_notification();
refresh_notification(0);
//refresh_postads();
refresh_new_post();
</script>