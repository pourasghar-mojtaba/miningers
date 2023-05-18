<?php 
	echo $this->Html->css('static_'.$locale); 
 
	$pages=$this->requestAction(__SITE_URL.'pages/get_child_pages/'.$this->request->params['pass'][0]);
 ?>
 
 
 <?php 
	$this->Gilace->autoCompileLess(ROOT.DS.APP_DIR.DS.'webroot'.DS.'less_'.$locale. DS .'userlist.less', ROOT.DS.APP_DIR.DS.'webroot'.DS.'css'.DS.'userlist_'.$locale.'.css');
		
	echo $this->Html->css('userlist_'.$locale);

	//echo $this->Html->script('jquery-ui-1.10.3.custom.min');
	$User_Info= $this->Session->read('User_Info');
	
?>
<!--
	<aside class="col-sm-3">
    	<div class="dataBox">
        	<ul class="helpIcon">
	        	<li>
				<?php
			 
					
					if(!empty($pages)){
						foreach($pages as $page)
						{
							echo"<li>".
								$this->Html->image('/img/icons/label_162.png') ."
								<a href=". __SITE_URL."pages/view/".$page['Page']['id']."> ".$page['Page']['title']."</a>
								</li>";
						}
					}
					else{
						echo"<li>".
								$this->Html->image('/img/icons/label_162.png') .__('not_exist_child_page')."
								 
								</li>";
					}
				?>
	        </ul>
        </div>
    </aside>-->
    
    <section class="mainSection col-md-12 col-sm-12">
         
        <div class="dataBox userListBox" >
	         <h1>	
				<?php
				   echo $page['Page']['title'];
				?>   
			 </h1>  
			 <article> 
			<?php 
			  echo $this->Gilace->convert_character_editor($page['Page']['body']); 
			?>
			</article>
			<div class="clear"></div>
        </div>
    </section>
 
 
 