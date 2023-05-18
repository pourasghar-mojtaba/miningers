
<li>
    <div title="0"  class="nav <?php if($blog_id==0) echo "open"; ?>" >
	 <span class="bl_ax">
	 	<img src="<?php echo __SITE_URL.'img/new_blog.png' ?>" />
	 </span>
	 <span class="blog_title">
	 	<?php echo __('add_new_blog'); ?>
	 </span>
	 <span class="blog_date">
	 	<?php 
		if($locale =='per')
            echo $this->Gilace->show_persian_date("Y/m/d - H:i",time());  
        if($locale =='eng')
            echo date("Y/m/d - H:i",time());   
		
		 ?>
	 </span>
	</div>
  </li>

<?php
	//print_r($blogs);
	if(!empty($blogs)){
		foreach($blogs as $blog){
										
			echo "<li>";
		        if($blog['Blog']['id']==$blog_id) 
					echo "<div title='".$blog['Blog']['id']."' class='nav open'>"; else
					echo "<div title='".$blog['Blog']['id']."' class='nav'>";
			echo"		
				 <span class='bl_ax'>";
					$user_image='';
					$width=70;
					$height=70;
					$image=$blog['Blog']['image'];
				    if(fileExistsInPath(__USER_BLOG_PATH.$image )&& $image!='' ) 
					{
						$user_image = $this->Html->image('/'.__USER_BLOG_PATH.__UPLOAD_THUMB.'/'.$image,array('width'=>$width,'height'=>$height,'alt'=>$alt,'id'=>'blog_thumb_image_'.$blog['Blog']['id']));
					}
					else{		 
						$user_image = $this->Html->image('new_blog.png',array('width'=>$width,'height'=>$height,'alt'=>$alt,'id'=>$id));
					}
					echo $user_image;
				echo"	
				 </span>
				 <span class='blog_title'>";
				 	if(mb_strlen($blog['0']['title'])>50){
				 	echo mb_substr($blog['0']['title'],0,45).'...';
				 }else echo $blog['0']['title'];
				 echo"</span>
				 <span class='blog_date'>";
				 	
					if($locale =='per')
                        echo $this->Gilace->show_persian_date("Y/m/d - H:i",strtotime($blog['Blog']['created']));  
                    if($locale =='eng')
                        echo date("Y/m/d - H:i",strtotime($blog['Blog']['created']));   
				 echo"</span>
				</div>
		      </li>";
		}
	}
?>

<script>
$(function(){
  $('#sidemenu .nav').on('click', function(e){
    e.preventDefault();

    if($(this).hasClass('open')) {
      // do nothing because the link is already open
    } else {
		
		load_blog($(this).attr('title'));
		
		/*
      var oldcontent = $('#sidemenu .nav.open').attr('title');
      var newcontent = $(this).attr('title');
      
      $(oldcontent).fadeOut('fast', function(){
        $(newcontent).fadeIn().removeClass('hidden');
        $(oldcontent).addClass('hidden');
      });*/
      
     
      $('#sidemenu .nav').removeClass('open');
      $(this).addClass('open');
    }
  });
  
 }); 
</script>
