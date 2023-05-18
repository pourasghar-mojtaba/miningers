 
<?php
  echo "<table id='user_tag' cellpadding='0' cellspacing='0'>";
  $i=0;
  $style='row2';
	 foreach($tags as $tag){
        $i++;
         
        if($i % 2 ==0){
            $style='row1';
        } else $style='row2';
	 	echo "<tr class='".$style."'> 
					<td width='50'>".$this->Gilace->farsidigit($tag['0']['count'])."</td>
                    <td> #".$tag['Usertag']['title']." </td> 
                    <td> <span id='tag_loading_".$tag['Usertag']['id']."'></span>
                        <input type='button' onclick='add_usertag(".$tag['Usertag']['id'].",this)' value='+ ".__('add_to_myprofile')."' ></td>
			  </tr>";
	 }
   echo "</table>";    
?>
  
<script>
  function add_usertag(id,obj)
  {
      $(obj).attr('disabled','disabled');
      $(obj).css('background-color','#d7d9d9');
      $(obj).css('color','#000000');
      $("#tag_loading_"+id).html('<img width="24" src="'+_url+'/img/loader/metro/preloader-w8-cycle-black.gif" >');
      
      $.ajax({
			type:"POST",
			url:_url+'users/add_usertag',
			data:'id='+id,
			dataType: "json",
			success:function(response){
				 
				if(response.success == true) {
					if( response.message ) {
						show_success_msg(response.message);					
					} 					
				}
				else 
				 {
					if( response.message ) {
						show_error_msg(response.message);
					}  
				 }
                $("#tag_loading_"+id).empty();
			}
		});
      
      
  }
</script> 