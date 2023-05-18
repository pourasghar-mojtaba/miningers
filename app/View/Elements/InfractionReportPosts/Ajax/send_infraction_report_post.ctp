<div class="bg modalCloser"></div>
<div class="dataBox modalMain col-md-6 col-md-offset-3">
<header class="modalHeader">
<div class="closer modalCloser icon-cancel"></div>
<?php echo __('send_infraction_report_post') ?>
</header>
 <form class="myForm">
	<section class="modalContent">
		 <div class="col-md-12">
		 	<label><b><?php echo  __('send_infraction_report_post_hint') ?></b></label>
			<label><b><?php echo  __('send_infraction_report_post_hint1') ?></b></label>
		 </div>
		 <div class="col-md-12">
		 	<label><?php echo  __('reason_for_infraction_report') ?></label>
			<textarea  maxlength="200" style="height:100px"
					  id='infraction_report_content' name='infraction_report_content' cols='70' rows='5' class="myFormComponent notTrans fixHeight" placeholder="<?php echo  __('reason_for_infraction_report') ?>">				  	
			</textarea>
		 </div>
	</section>
	<footer>
	    <div class="col-sm-12">
	        <button role="button" type="button" onclick="send_infraction_report_post(<?php echo $_REQUEST['id'] ?>)" class="myFormComponent green">
	            <span class="text"><?php echo __('send') ?></span>
	            <span class="icon icon-left-open"></span>
	        </button>
			<span id="infraction_report_post_loading"></span> 
	    </div>
	</footer>
  </form>
</div>
		 

<script>

	modalCloser();
	 makeCenterVer($("#modal .modalMain"));	 
	  
	function send_infraction_report_post(id)
{  
	$("#infraction_report_post_loading").html('<img width="24" src="'+_url+'/img/loader/metro/preloader-w8-cycle-white.gif" >');
	
	var report = $("textarea#infraction_report_content").val();
	
	if(String.trim(report) ==''){
		show_warning_msg(_enter_infraction_report);
		$("#infraction_report_post_loading").empty();
		return;
	}
			 
	
	$.ajax({
		type: "POST",
		url: _url+'infractionreportposts/send_infraction_report_post',
		data: 'id='+id+'&report='+report+'&action=send',
		dataType: "json",
		success: function(response)
		{
		 
		 if(response.success == true) {			
			if( response.message ) {
				show_success_msg(response.message);	
				remove_modal();	
			} 
			$("#infraction_report_post_loading").empty();	
		}
		else 
		 {
			if( response.message ) {
				show_error_msg(response.message);
			} 
			$("#infraction_report_post_loading").empty();
		 }
		 
		 
		}
	
	  });
	  
}

</script>  
	  
<style>
  div .help{
  	color: #696969;
    line-height: 22px;
    margin-bottom: 5px;
  }
</style>