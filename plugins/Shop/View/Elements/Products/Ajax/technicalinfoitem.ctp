<?php
	if(!empty($technicalinfoitems)){
		echo "
			<table id='expense_table' cellspacing='0' cellpadding='0'>
                <thead>
                    <tr>
                        <th>".__('title')."</th>
						<th>".__('value')."</th>
                    </tr>
                </thead>
                <tbody>
		";
 		foreach($technicalinfoitems as $key=>$technicalinfoitem)
		{
			if($product_id>0){
				echo "
				<tr>
				  <td>
				  	<label >".$technicalinfoitem['Technicalinfoitem']['item']." :</label> 
				  </td>
				  <td>
					 <input class='input-xlarge focused' name='data[Technicalinfoitemvalue][value][]' 
					 id='focusedInput' type='text'  value='".$technicalinfoitem['Technicalinfoitemvalue']['value']."'>
					 <input type='hidden' value='".$technicalinfoitem['Technicalinfoitem']['id']."'  
					 name='data[Technicalinfoitemvalue][technical_info_item_id][]' >
				  </td>
				</tr>
			    ";
			}
			else{
				echo "
				<tr>
				  <td>
				  	<label >".$technicalinfoitem['Technicalinfoitem']['item']." :</label> 
				  </td>
				  <td>
					 <input class='input-xlarge focused' name='data[Technicalinfoitemvalue][value][]' 
					 id='focusedInput' type='text'  >
					 <input type='hidden' value='".$technicalinfoitem['Technicalinfoitem']['id']."'  
					 name='data[Technicalinfoitemvalue][technical_info_item_id][]' >
				  </td>
				</tr>
			    ";
			}
			
		}
		echo"
			</tbody>
          </table>
		";
	}
	//print_r($technicalinfoitems);
?>
 