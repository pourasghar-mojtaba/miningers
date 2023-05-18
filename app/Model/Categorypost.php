<?php

class Categorypost extends AppModel {
	public $name = 'Categorypost';
	public $useTable = "category_posts"; 
	public $primaryKey = 'id';
	
	  var $actsAs = array('Containable');
   
	
	public $hasMany = array(
		'Post' => array(
			'className' => 'Post',
			'foreignKey' => 'categorypost_id',
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