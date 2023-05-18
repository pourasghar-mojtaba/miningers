<?php

class Infractionreportpost extends AppModel {
	public $name = 'Infractionreportpost';
	public $useTable = "infraction_report_posts"; 
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