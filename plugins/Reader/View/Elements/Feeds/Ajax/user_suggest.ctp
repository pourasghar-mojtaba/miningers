<?php
	if(!empty($users)){
		foreach ($users as $kay=>$user){

			echo "<option value='".$user['User']['id']."' >
				 ".$user['User']['name']."</option>";
		}	
	}
	
?>
