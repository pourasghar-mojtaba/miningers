<?php

class Technicalinfo extends ShopAppModel {
	public $name = 'Technicalinfo';
	public $useTable = "technicalinfos"; 
	public $primaryKey = 'id';
	
	var $actsAs = array('Containable');
   
   public $hasMany = array(
		'Technicalinfoitem' => array(
			'className' => 'Technicalinfoitem',
			'foreignKey' => 'technical_info_id',
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