<?php
	$this->Gilace->autoCompileLess(ROOT.DS.APP_DIR.DS.'webroot'.DS.'less_'.$locale. DS .'other.less', ROOT.DS.APP_DIR.DS.'webroot'.DS.'css'.DS.'other_'.$locale.'.css');
		
	echo $this->Html->css('other_'.$locale);
    
	$User_Info= $this->Session->read('User_Info');
?>

<div class="col-md-10 col-md-offset-1">
	<div class="dataBox ">
	<div class="link">
	    	<span class="icon icon-link"></span>
	        <span><?php echo __SITE_URL.'users/register?ragid='.md5($User_Info['id']); ?></span>
	    </div>            
	</div>
	</div>
	<div class="row">
	<div class="col-md-10 col-md-offset-1">
	    <div class="col-sm-4 ">
	        <div class="dataBox">
	            <div class="tile size100 free red fullCol">  
	                <!--<span class="icon">27</span>
	                <span class="text">دوست دعوت شده</span>-->
	            </div>

	            <div class="tile size100 free dark fullCol">
	                <!--<span class="icon">133</span>
	                <span class="text">دوست عضو شده</span>-->
	            </div>

	            <div class="tile size100 free green fullCol">
	            	<ul class="List">
	                	<!--<li> شما هر کاری دوستداشتید می تونید بکنید</li>
	                	<li> شما هر کاری دوستداشتید می تونید بکنید</li>
	                	<li> شما هر کاری دوستداشتید می تونید بکنید</li>
	                	<li> شما هر کاری دوستداشتید می تونید بکنید</li>-->
	                </ul>
	            </div>
	        </div>
	    </div>
	    <div class="col-sm-8">
	        <div class="dataBox">
	            <header><h3><?php echo __('invite_from_friend'); ?></h3></header>
	            <div class="myForm">
	                <?php  echo $this->Session->flash(); ?>
                    <?php
                         
                         if(!empty($err_emails )){
                             echo"<div style='background-color: #ffb9b9;border-radius: 3px;margin-top: 5px;padding: 5px;'>";
                             echo "<b>".__('exist_email_invitions')."</b>";
                             // pr($err_emails);
                              foreach($err_emails as $user){                             
                                echo "<div dir='ltr'>(<a href='".__SITE_URL.$user['user_name']."' target='_blank'> @";
                                   echo $user['user_name'];
                                echo" </a>)".$user['email']."</div>";  
                              }                              
                             echo"</div>";
                             
                         }
                     ?>
                    
                    <?php echo $this->Form->create('Invitation', array('id'=>'invitation_form','name'=>'invitation_form','enctype'=>'multipart/form-data','action'=>'/invite','class'=>'myForm')); ?>
						<div id="allthesets">
							<input type='hidden'  name="send_invite" value="1">
							<?php
								if(!empty($_POST['invitation_email'])){
									$emails = explode(',',$_POST['invitation_email']);
								}	
								if(!empty($request)){
									$emails = $request['email'];
								}
									$i=1;
									if(!empty($emails)){
										foreach($emails as $key=>$email){
											if(!empty($request)){
												$name = $request['name'][$key];
											}else $name = '';
											if($i>10){
												break;
											}
											echo "
												<div id='".$i."' class='addr'>
								                    <div class='col-sm-6'>
								                        <input type='text' placeholder='".__('name')."' class='myFormComponent notTrans' name='name[]' value='".$name."' >
								                    </div>
								                    <div class='col-sm-6'>
								                        <input type='email' placeholder='".__('email')."' class='myFormComponent notTrans' name='email[]' value='".$email."' dir='ltr' >
								                    </div>
												</div>
											";
											$i++;
										}
									}else{
                                        //if(!empty($_POST['invitation_email'])){
                                            echo "
											<div id='".$i."' class='addr'>
							                    <div class='col-sm-6'>
							                        <input type='text' placeholder='".__('name')."' class='myFormComponent notTrans' name='name[]' >
							                    </div>
							                    <div class='col-sm-6'>
							                        <input type='email' placeholder='".__('email')."' class='myFormComponent notTrans' name='email[]' value='";if(!empty($_POST['invitation_email'])) echo $_POST['invitation_email'];
													echo"' dir='ltr' >
							                    </div>
											</div>
										";
                                        //}
										
									}
								 
								
							?>							
							
						</div>
	                    <div class="Horizontal_bar"></div>
	                    <div class="col-sm-6 col-sm-offset-6">
	                        <button type="submit" role="button" class="green" >
	                            <span class="icon  icon-plus"></span>
	                            <span class="text"><?php echo __('send_invitation') ?></span>
	                        </button>
	                    </div>
	                    <div class="clear"></div>
	                </form>
	            </div>
	        
	        </div>
	    </div>
	</div>
	</div>
	
	<!--
	<span id="add">Add</span>
 		<div id="allthesets">
		<div id="1" class="addr">
		    <input type="text" name="a">
		    <input type="text" name="b">
		    <input type="text" name="c">
		</div>
	</div>-->

<script>
    function addrow(){
		var id = $(".addr:last").attr('id');           
		if(id<10){
			$(".addr:last").clone().attr('id', ++id).insertAfter(".addr:last");
			$('#allthesets > div:last > div > input ').val('');
			$('#allthesets > div > div:last > input:last ').focus(function(){
				addrow();
			})
		}			
	}
	jQuery(document).ready(function($){
        $('#add').on('click',function() {
            addrow();
        });
		
		$('#allthesets > div > div:last > input:last ').focus(function(){
			addrow();
		})
		
    });
</script>