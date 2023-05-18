<?php
/**
 */
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');

class BanksController extends GetwayAppController {

  var $name = 'Banks';
  var $components = array('Httpupload');

public function beforeFilter() {
	parent::beforeFilter();
	$this->Auth->allow('pay');
}

/**
* 
* @param undefined $token
* 
*/
function pay($token){
	
	$this->Bank->recursive = -1;
	$banks = $this->Bank->find('all');
	$this->set(compact('banks'));
	
	$this->set('title_for_layout',__('getway'));
    $this->set('description_for_layout',__('getway'));
    $this->set('keywords_for_layout',__('getway'));
	
	$this->set('token',$token);
	 
	$this->set('cn',$_REQUEST['cn']);
	$this->set('ac',$_REQUEST['ac']);
}

 
}
