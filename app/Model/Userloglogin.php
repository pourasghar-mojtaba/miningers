<?php

class Userloglogin extends AppModel {
	public $name = 'Userloglogin';
	public $useTable = "user_log_logins"; 
	public $primaryKey = 'id';
	
	  var $actsAs = array('Containable');
   
	
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

  
 
	
}

?>