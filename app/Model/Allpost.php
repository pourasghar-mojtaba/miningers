<?php

class Allpost extends AppModel {
	public $name = 'Allpost';
	public $useTable = "all_posts"; 
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
   
	public $hasMany = array(
		'Sharepostnotification' => array(
			'className' => 'Sharepostnotification',
			'foreignKey' => 'sharepost_id',
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