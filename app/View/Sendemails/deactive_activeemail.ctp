
<?php echo $this->Html->css('edit_profile_'.$locale); 
	  echo $this->Html->script('profile');
	 
     //pr($sendemail);
?>
	
   

<section id="mainPanel">
    <div class="mainBox">			    
        	<table border="0" cellpadding="2">                  
			   <tr> 
                   <td align="center">
                       <?php  echo $this->Session->flash(); ?>
                   </td>
               </tr>
                    
            </table>            
    </div>
</section>

