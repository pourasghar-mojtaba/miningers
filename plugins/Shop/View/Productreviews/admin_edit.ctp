<?php
    echo $this->Html->css('/shop/css/addrow');
?> 
<div class="container-fluid">
		<div class="row-fluid">
				
			<!-- right menu starts -->
				<?php echo $this->element('Admin/right_menu'); ?>       
			<!-- right menu ends -->
			
			
			<div id="content" class="span10">
			<!-- content starts -->
			

			<div>
				<?php if($this->Session->check('Message.flash')) {?>

					  <?php echo $this->Session->flash(); ?>
	
				<?php } ?>
			</div>
			<?php echo $this->Form->create('Productreview', array('id'=>'EditFrom','name'=>'EditFrom','enctype'=>'multipart/form-data')); ?>
			<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-edit"></i><?php echo __('productreview') ?></h2>
						<div class="box-icon">
							<a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
						</div>
					</div>
					
					<div class="box-content">
						<form class="form-horizontal">
							<fieldset>
							  <div class="control-group">
								
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('product_name') ?> :</label>
								  <input class="input-xlarge focused" name="data[Productreview][title]" id="focusedInput" type="text"  value="<?php echo $product['Product']['title'] ; ?>" disabled="">
								</div>
								
								
								
								<div class="controls">
                                  <label class="control-label" for="focusedInput"><?php echo __('add_productreviewpdf') ?> :</label>
									<table id="expense_table" class="expense_table" cellspacing="0" cellpadding="0" width="500">
	                                        <thead>
	                                            <tr>
	                                                <th><?php echo __('pdf'); ?></th>
                                                    <th><?php echo __('title'); ?></th>
	                                                <th>&nbsp;</th>
	                                            </tr>
	                                        </thead>
	                                        <tbody>
											 <?php 
												if(!empty($productreviewpdfs))
												{
													foreach($productreviewpdfs as $productreviewpdf)
													{
														echo "
				                                            <tr>
				                                                <td>
				                                                    <input type='file' name='data[Productreviewpdf][pdf][]' id='pdf_no_01' maxlength='255'  />
				                                                </td>
			                                                    <td>
				                                                    <input type='text' name='data[Productreviewpdf][title][]' id='title_no_01' 
																	maxlength='255' value='".$productreviewpdf['Productreviewpdf']['title']."' />
				                                                </td>";
																if(fileExistsInPath(__PRODUCT_FILE_PATH.$productreviewpdf['Productreviewpdf']['pdf'] ) && $productreviewpdf['Productreviewpdf']['pdf']!='' ) 
										{
											echo "<td><a target='_blank' href= '".__SITE_URL.__PRODUCT_FILE_PATH.$productreviewpdf['Productreviewpdf']['pdf']."' >";
											echo $this->Html->image('/img/icons/pdf.png',array('id'=>'pdf_img','height'=>100));
											echo "</a></td>";
										}
																
																
																
																echo"<td><input type='button' value='Delete' class='del_ExpenseRow' /></td>";
																echo "<input type='hidden' value='".$productreviewpdf['Productreviewpdf']['id']."' name='data[Productreviewpdf][id][]'>";
																echo "<input type='hidden' value='".$productreviewpdf['Productreviewpdf']['pdf']."' name='data[Productreviewpdf][old_pdf][]'>";
																
				                                           echo"</tr>";
													}
												}
												else
												{
													echo "
														<tr>
			                                                <td>
			                                                    <input type='file' name='data[Productreviewpdf][pdf][]' id='pdf_no_01' maxlength='255'  />
			                                                </td>
		                                                    <td>
			                                                    <input type='text' name='data[Productreviewpdf][title][]' id='title_no_01' maxlength='255'  />
			                                                </td>
			                                                <td>&nbsp;</td>
			                                            </tr>
													";
												}
												
											  ?>	
												
	                                        </tbody>
	                                    </table>
										
                                  <input type="button" value="<?php echo __('add_file'); ?>" id="add_ExpenseRow" />
                                </div> 
								
							
								<div class="controls">
								  <label class="control-label" for="focusedInput"><?php echo __('body') ?> :</label>
								  <textarea class="input-xlarge focused" name="data[Productreview][body]" id="first_editor" >
								  	<?php 
											if(!empty($productreview['Productreview']['body'])){
												echo $this->Gilace->convert_character_editor($productreview['Productreview']['body']) ;	
											}
											 
									?>
								  </textarea>
								</div>	
								
														
								
							  </div>
							  
							  <div class="form-actions">
								<button type="submit" class="btn btn-primary"><?php echo __('save_change') ?></button>
								<button class="btn"><?php echo __('cancel') ?></button>
							  </div>
							</fieldset>
						  </form>
					
					</div>
				</div><!--/span-->
			
			</div><!--/row-->
			</form>
			
		
    
					<!-- content ends -->
			</div><!--/#content.span10-->
				</div><!--/fluid-row-->
				
		 
		
	</div><!--/.fluid-container-->
	
	<script>
		CKEDITOR.replace( 'first_editor' );
		
		
		$(function(){
        	// GET ID OF last row and increment it by one
        	var $lastChar =1, $newRow;
        	$get_lastID = function(){
        		var $id = $('.expense_table tr:last-child td:first-child input').attr("id");
        		$lastChar = parseInt($id.substr($id.length - 2), 10);
        		console.log('GET id: ' + $lastChar + ' | $id :'+$id);
        		$lastChar = $lastChar + 1;
        		$newRow = "<tr> \
        					<td><div class='uploader' id='uniform-pdf_no_0"+$lastChar+"'><input type='file' name='data[Productreviewpdf][pdf][]' id='pdf_no_0"+$lastChar+"' maxlength='255' style='opacity: 0;' /><span class='filename' style='-moz-user-select: none;'>No file selected</span><span class='action' style='-moz-user-select: none;'>Choose File</span></div></td> \<td><input type='text' name='data[Productreviewpdf][title][]' id='title_no_0"+$lastChar+"' maxlength='255' /></td> \<td><input type='button' value='Delete' class='del_ExpenseRow' /></td> \
        				</tr>"
        		return $newRow;
        	}
        	
        	// ***** -- START ADDING NEW ROWS
        	$('#add_ExpenseRow').live("click", function(){ 
        		//if($lastChar <= 9){
        			$get_lastID();
        			$('.expense_table tbody').append($newRow);
        		/*} else {
        			alert("Reached Maximum Rows!");
        		};*/
        	});
        	
        	$(".del_ExpenseRow").live("click", function(){ 
        		$(this).closest('tr').remove();
        		$lastChar = $lastChar-2;
        	});	
        });		
		
		
	</script>