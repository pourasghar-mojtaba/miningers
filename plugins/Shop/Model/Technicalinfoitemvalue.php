<?php

class Technicalinfoitemvalue extends ShopAppModel {
	public $name = 'Technicalinfoitemvalue';
	public $useTable = "technicalinfoitemvalues"; 
	public $primaryKey = 'id';
	
	var $actsAs = array('Containable');
   
   
   
   public $belongsTo = array(
		'Technicalinfoitem' => array(
			'className' => 'Technicalinfoitem',
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
		),
        'Product' => array(
			'className' => 'Product',
			'foreignKey' => 'product_id',
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