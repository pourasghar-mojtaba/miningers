<?php
	echo $this->Html->script('jquery.form');
?>
<div class="bg modalCloser"></div>
<div class="dataBox modalMain col-md-6 col-md-offset-3" style="hieght:500px">
<header class="modalHeader">
<div class="closer modalCloser icon-cancel"></div>
<?php echo __('add_pdf'); ?>
</header>
 
   <?php echo $this->Form->create('User', array('id'=>'ChangePdf','name'=>'ChangePdf','enctype'=>'multipart/form-data','action'=>'/edit_pdf','class'=>'myForm')); ?>
        <section class="modalContent">
            <div class="insertNewUser" >                
                    <div class="col-sm-12" style="text-align: center;">
							<div class="col-sm-12">
								<img style="cursor: pointer;" id="add_pdf" src="<?php echo __SITE_URL.'img/Add_PDF.png' ?>" />
							</div>                    							 
                            <div class="clear"></div>
							<div id="newpdf_loading" style="float:left;margin:auto "> </div>
                            <input type="file" id='pdf' style="display:none" name="data[User][pdf]">                                      				<input name='new_pdf' id="new_pdf" type='hidden' value='' > 
                    </div>
                    
					<div class="clear"></div>
					<div class='col-sm-12' id="NewUser">

					</div>
                    <div class="col-sm-12" style="text-align: center">
                        <?php echo __('add_resume_hint'); ?>
                    </div>
            </div>
        </section>
		
		<div id="pdf_result" style="float:right"></div>
         <footer>
            <?php if(!empty($pdf)){ ?>
			<div class="col-sm-12"id="delete_pdf_loading" >
                <button role="button" type="button" id="delete_pdf" class="myFormComponent red ">
                    <span class="text"> <?php echo __('delete'); ?></span>
                    <span class="icon icon-left-open"></span>
                </button>
            </div>
			<?php } ?>
        </footer>
    </form>
</div>
<script>
	modalCloser();
	makeCenterVer($("#modal .modalMain"));
	
	 
	$('.clearInput','#modal').click(function(e) {
        var parent = $(this).parent();
		$('input',parent).val('');
    });
	
	jQuery(document).ready(function(){
	 
	$('#pdf').change(function(){
	 	$('#ChangePdf').submit();
	 });
	 
     $('#ChangePdf').on('submit', function(e) {                         
			e.preventDefault();
			//$('#add_pdf').removeAttr('src');
            $("#newpdf_loading").html('<img width="24" src="'+_url+'/img/loader/5.gif" >');
			
            $(this).ajaxSubmit({
                target: '#pdf_result',
                success:  afterUserSuccess , //call function after success
				error  :  afterUserError
            });
        });
			
		function afterUserSuccess()  {
            $('#ChangePdf').resetForm();  // reset form
			$("#newpdf_loading").empty();			
			$('#pdf_attachment').parent().fadeOut(200);
		    $('#pdf_attachment').parent().remove();
   	    }
		  
	  	function afterUserError()  {
			$('#ChangePdf').resetForm();
			show_error_msg(_save_pdf_notsuccess);
   	  	}
	 
 		$('#pdf').change(function(){
			
			var count = 0;
			var arr = $("#pdf_attachment").map(function() {
				  count+=1;
			  });
			if(count>=1){
				show_warning_msg(_exist_pdf);return;
			}
		});
		
	 $('#add_pdf').click(function(){
		 $('#pdf').trigger('click');
		$("#closethis").click(function(e) {
        $(this).parent().fadeOut(200);
		$(this).parent().remove();
    });
		
    });
 
 	$("#closethis").click(function(e) {
        $(this).parent().fadeOut(200);
    });
	
	
	
	$('#delete_pdf').click(function(){ delete_pdf_alarm(); });
	
	function delete_pdf_alarm()
	{
		$.Zebra_Dialog(_are_you_sure_for_delete, {
				    'type':     'warning',
				    'title':    _warning,
					'modal': true ,
				    'buttons':  [
				                    {caption: _yes, callback: function() {delete_pdf();}},
									{caption: _no, callback: function() { }}
				                ]
		  });	
	}
	
	function delete_pdf()
	{
		$(".delete_pdf_loading").html('<img src="'+_url+'/img/loader/ui-anim_basic_16x16.gif" >');
		$.ajax({
			type: "POST",
			url: _url+'users/delete_pdf',
			data: 'old_pdf='+'<?php echo $pdf ?>',
			success: function(response)
			{	 		
				$('#ajax_result').html(response);
				remove_modal();
			}
		
		  });	  
	}
	
	
});	
	
</script>

