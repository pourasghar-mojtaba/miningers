<?php

class Librarytag extends AppModel {
	public $name = 'Librarytag';
	public $useTable = "librarytags"; 
	public $primaryKey = 'id';
	
	var $actsAs = array('Containable');
   

   public $hasMany = array(
		'Libraryrelatetag' => array(
			'className' => 'Libraryrelatetag',
			'foreignKey' => 'library_tag_id',
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