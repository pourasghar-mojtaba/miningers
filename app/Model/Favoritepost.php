<?php

class Favoritepost extends AppModel {
	public $name = 'Favoritepost';
	public $useTable = "favorite_posts"; 
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