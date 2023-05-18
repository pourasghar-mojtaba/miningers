<?php

class Industry extends AppModel {
	public $name = 'Industry';
	public $useTable = "industries"; 
	public $primaryKey = 'id';
	
	  var $actsAs = array('Containable');
   
	
	public $hasMany = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'industry_id',
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