<?php

class Postrelatetag extends AppModel {
	public $name = 'Postrelatetag';
	public $useTable = "postrelatetags"; 
	public $primaryKey = 'id';
	
	var $actsAs = array('Containable');
   
	public $belongsTo = array(
		'Post' => array(
			'className' => 'Post',
			'foreignKey' => 'post_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Posttag' => array(
			'className' => 'Posttag',
			'foreignKey' => 'posttag_id',
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