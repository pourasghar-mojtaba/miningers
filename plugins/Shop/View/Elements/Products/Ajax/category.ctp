<?php
	if(!empty($categories)){
		echo"<select multiple='multiple' size='10' class='category' name='data[Product][product_category_id]'  id='".$parent_id."' >";

		foreach ($categories as $kay=>$category){
			
			if(!empty($select_id)){
				if($select_id==$category['Productcategory']['id']){
					echo "<option value='".$category['Productcategory']['id']."' onclick='get_product_category(".$category['Productcategory']['id'].",".$category['Productcategory']['parent_id'].")' selected>
				 ".$category['Productcategory']['title']."</option>";
				}else
			echo "<option value='".$category['Productcategory']['id']."' onclick='get_product_category(".$category['Productcategory']['id'].",".$category['Productcategory']['parent_id'].")'>
				 ".$category['Productcategory']['title']."</option>";
			}
			else{
				if($kay==0){
				echo "<option value='".$category['Productcategory']['id']."' onclick='get_product_category(".$category['Productcategory']['id'].",".$category['Productcategory']['parent_id'].")' selected>
				 ".$category['Productcategory']['title']."</option>";
			}else
			echo "<option value='".$category['Productcategory']['id']."' onclick='get_product_category(".$category['Productcategory']['id'].",".$category['Productcategory']['parent_id'].")'>
				 ".$category['Productcategory']['title']."</option>";
			}
			
			
				 
				 
				 
		}
	  	echo"</select>";
	}
	
?>
