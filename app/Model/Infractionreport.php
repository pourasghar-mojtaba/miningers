<?php

class Infractionreport extends AppModel {
	public $name = 'Infractionreport';
	public $useTable = "infraction_reports"; 
	public $primaryKey = 'id';
	
	  var $actsAs = array('Containable');
   
	
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'from_user_id',
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