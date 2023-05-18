<?php

class Libraryrelatetag extends AppModel {
	public $name = 'Libraryrelatetag';
	public $useTable = "libraryrelatetags"; 
	public $primaryKey = 'id';
	
	var $actsAs = array('Containable');
   

   public $belongsTo = array(
		'Librarytag' => array(
			'className' => 'Librarytag',
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
		),
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