<?php
   if(!empty($blogs)){
   	 foreach($blogs as $blog)
	   {
	 		  	
?>
	<section>
        <header>
            <h1><a href="<?php echo __SITE_URL.'blogs/view/'.$blog['Blog']['id']; ?>"><?php echo $blog['Blog']['title'] ?></a></h1>
            <span class="date"><?php  echo $this->Gilace->show_persian_date('',$blog['Blog']['created']);?> </span>
            <span class="auther"><?php echo __('writer') ?> : 
			<?php if($blog['User']['role_id']==2){  ?>
				<a href="<?php echo __SITE_URL.$blog['User']['user_name'] ?>"><?php echo $blog['User']['name']; ?></a></span>
			<?php } else echo $blog['User']['name']."</span>" ?>
            <span class="visits"> <?php echo __('viewed') ?>  : <?php echo $blog['Blog']['num_viewed']; ?></span>
        </header> 
        <article>
       	  <?php echo $this->Gilace->convert_character_editor($blog['Blog']['little_body']); ?>
        </article>
        <footer>
        	<ul class="tags">
            	<?php   
				  $tags=$this->requestAction(__SITE_URL.'blogtags/get_blog_tag/'.$blog['Blog']['id']) ;
				  
				  if(!empty($tags))
				  {
					foreach($tags as $tag )
					{
						echo "<li><a href='".__SITE_URL."blogs/tag/".$tag['Blogtag']['id']."'>".$tag['Blogtag']['title']."</a></li>";
					}
				  }
				?>
            </ul>
        	<article>
			  <?php if(!empty($blog['Blog']['body'])){ ?>
				<a href="<?php echo __SITE_URL.'blogs/view/'.$blog['Blog']['id']; ?>"><?php echo __('blog_continuance') ?></a>
			  <?php } ?>
			</article>
			<ul class="share">
            	<li><a href="#" title="<?php echo __('share_in_madaner') ?>">
				<?php   echo $this->Html->image('/img/icons/madaner.png',array('width'=>33,'height'=>33));  ?>
				</a></li>
            	<li><a href="http://www.facebook.com/sharer.php?u='<?php echo __SITE_URL.'blogs/view/'.$blog['Blog']['id'] ?>?&t=<?php echo $blog['Blog']['title'] ?>" target="_blank" title="<?php echo __('share_in_facebook') ?>">
				<?php   echo $this->Html->image('/img/icons/fb.png',array('width'=>33,'height'=>33));  ?>	
				</a></li>
            	<li><a href="http://twitter.com/home/?status=<?php echo  $blog['Blog']['title'] ?>-<?php echo __SITE_URL.'blogs/view/'.$blog['Blog']['id'] ?>?" title="<?php echo __('share_in_twitter') ?>">
				<?php   echo $this->Html->image('/img/icons/twitter.png',array('width'=>33,'height'=>33));  ?>					
				</a></li>
            </ul>
			
        </footer>
    </section>
  <?php
		   }
   }