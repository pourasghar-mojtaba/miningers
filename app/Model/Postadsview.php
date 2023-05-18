<?php

class Postadsview extends AppModel {
	public $name = 'Postadsview';
	public $useTable = "post_ads_views"; 
	public $primaryKey = 'id';
	
	var $actsAs = array('Containable');
   
	
	public $belongsTo = array(
		'Postad' => array(
			'className' => 'Postad',
			'foreignKey' => 'post_ads_id',
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