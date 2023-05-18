<?php

App::uses('CakeEmail', 'Network/Email');

class Appuser extends AppModel {

    public $name = 'Appuser';
    public $useTable = "appusers"; 

    var $actsAs = array( 'Containable');
  

   public $hasOne = array(
		'User' => array(
			'className'  => 'User',
			'foreignKey' => 'id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		) 
	);

   
}