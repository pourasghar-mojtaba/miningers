<?php

class Comment extends AppModel {
	public $name = 'Comment';
	public $useTable = "comments"; 
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
		)
	);


 
	
}

?>