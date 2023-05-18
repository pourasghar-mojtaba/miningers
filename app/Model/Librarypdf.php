<?php

class Librarypdf extends AppModel {
	public $name = 'Librarypdf';
	public $useTable = "librarypdfs"; 
	public $primaryKey = 'id';
	
	var $actsAs = array('Containable');
   

   public $belongsTo = array(
		'Library' => array(
			'className' => 'Library',
			'foreignKey' => 'library_id',
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