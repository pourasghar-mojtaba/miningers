<?php

class Blogrelatetag extends AppModel {
	public $name = 'Blogrelatetag';
	public $useTable = "blogrelatetags"; 
	public $primaryKey = 'id';
	
	var $actsAs = array('Containable');
   
	public $belongsTo = array(
		'Blog' => array(
			'className' => 'Blog',
			'foreignKey' => 'blog_id',
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
		'Blogtag' => array(
			'className' => 'Blogtag',
			'foreignKey' => 'blogtag_id',
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