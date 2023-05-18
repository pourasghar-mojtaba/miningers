<?php

class Blogcomment extends AppModel {
	public $name = 'Blogcomment';
	public $useTable = "blog_comments"; 
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
		)
	);


 
	
}

?>