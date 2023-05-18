<?php

class Libraryauthor extends AppModel {
	public $name = 'Libraryauthor';
	public $useTable = "libraryauthors"; 
	public $primaryKey = 'id';
	
	var $actsAs = array('Containable');
   

   public $hasMany = array(
		'Libraryrelateauthor' => array(
			'className' => 'Libraryrelateauthor',
			'foreignKey' => 'library_author_id',
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