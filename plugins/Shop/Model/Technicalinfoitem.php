<?php

class Technicalinfoitem extends ShopAppModel {
	public $name = 'Technicalinfoitem';
	public $useTable = "technicalinfoitems"; 
	public $primaryKey = 'id';
	
	var $actsAs = array('Containable');
   
   public $hasMany = array(
		'Technicalinfoitemvalue' => array(
			'className' => 'Technicalinfoitemvalue',
			'foreignKey' => 'technical_info_item_id',
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
   
   public $belongsTo = array(
		'Technicalinfo' => array(
			'className' => 'Technicalinfo',
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