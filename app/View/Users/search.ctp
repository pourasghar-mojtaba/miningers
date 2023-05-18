
<?php 
	$this->Gilace->autoCompileLess(ROOT.DS.APP_DIR.DS.'webroot'.DS.'less_'.$locale. DS .'userlist.less', ROOT.DS.APP_DIR.DS.'webroot'.DS.'css'.DS.'userlist_'.$locale.'.css');
		
	echo $this->Html->css('userlist_'.$locale);

	//echo $this->Html->script('jquery-ui-1.10.3.custom.min');
	$User_Info= $this->Session->read('User_Info');
	
?>

	<aside class="col-sm-3">
    	<div class="dataBox">
        	<!--rightsite-->
        </div>
    </aside>
    
    <section class="mainSection col-md-6 col-sm-9">
         
        <div class="dataBox userListBox" >
            <input type="hidden" value="<?php if(isset($action_type)) echo $action_type; ?>" id="action_type" />
		    <input type="hidden" value="<?php if(isset($action_type_value)) echo $action_type_value; ?>" id="action_type_value" />
		    <input type="hidden" value="<?php if(isset($user_type))  echo $user_type; ?>" id="user_type" />
            
                  
            <div class="clear"></div>
            <div id="search_result_body"></div>
            <div id="result_search_loading" ></div>
        </div>
    </section>
    <div class="col-md-3">
    	<div class="dataBox">
        	<!--<header>
            	<h3>اطلاعات جانبی</h3>
            </header>-->
            <div>
            <!--rightsite-->
            </div>
        </div>
    </div>



<script>
var count=0;		
$(window).scroll(function(){  
		if  ($(window).scrollTop() == $(document).height() - $(window).height()){    
		   count++; 
		   search_users(count);
		}  
}); 
search_users(count);

</script>