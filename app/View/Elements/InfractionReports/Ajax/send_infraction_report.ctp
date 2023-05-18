<div class="bg modalCloser"></div>
<div class="dataBox modalMain col-md-6 col-md-offset-3">
<header class="modalHeader">
<div class="closer modalCloser icon-cancel"></div>
<?php echo __('send_infraction_report') ?>
</header>
 <form class="myForm">
	<section class="modalContent">
		 <div class="col-md-12">
		 	<label><?php echo  __('send_infraction_report_hint') ?></label>
			<label> <?php echo  __('send_infraction_report_post_hint1') ?> </label>
	     </div>
		 
		 <div class="col-md-12">
		 	<label><?php echo  __('reason_for_infraction_report') ?></label>
		   
		   <textarea maxlength="200" class="myFormComponent" id='infraction_report_content' 
					 name='infraction_report_content' cols='70' rows='5'></textarea>
		   
	     </div>
	</section>
	<footer>
	    <div class="col-sm-12">
			<span id="infraction_report_loading"></span>
	        <button role="button" type="button" id="infraction_report_btn" class="myFormComponent green">
	            <span class="text"><?php echo __('send') ?></span>
	            <span class="icon icon-left-open"></span>
	        </button>
	    </div>
	</footer>
  </form>
</div>



<script>

	modalCloser();
	makeCenterVer($("#modal .modalMain"));

	$('#infraction_report_btn').click(function(){
	  	send_infraction_report("<?php echo $_REQUEST['id']; ?>");
	  });
	  
	function send_infraction_report(id)
{  
	$("#infraction_report_loading").html('<img width="24" src="'+_url+'/img/loader/metro/preloader-w8-cycle-white.gif" >');
	
	var report = $("textarea#infraction_report_content").val();
	
	if(String.trim(report) ==''){
		show_warning_msg(_enter_infraction_report);
		$("#infraction_report_loading").empty();
		return;
	}
			 
	
	$.ajax({
		type: "POST",
		url: _url+'infractionreports/send_infraction_report',
		data: 'id='+id+'&report='+report+'&action=send',
		dataType: "json",
		success: function(response)
		{
		 
		 if(response.success == true) {			
			if( response.message ) {
				show_success_msg(response.message);	
				remove_modal();
			} 
			$("#infraction_report_loading").empty();	
		}
		else 
		 {
			if( response.message ) {
				show_error_msg(response.message);
			} 
			$("#infraction_report_loading").empty();
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