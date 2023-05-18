<?php
/**
 */
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');


class AclitemsController extends AppController {

/**
 * Controller name
 *
 * @var string
 */
 
 

function admin_index()
	{
		$this->Aclitem->recursive = -1;
		$role_id=$_REQUEST['role_id'];
		$options['fields'] = array(
				'Aclitem.*',
				'Aclrole.aclitem_id'
			   );
		$options['joins'] = array(
				array('table' => 'aclroles',
					'alias' => 'Aclrole',
					'type' => 'LEFT',
					'conditions' => array(
					'Aclrole.aclitem_id = Aclitem.id AND role_id = '.$role_id,
					)
				) 
				
			);	
				
		
		$options['conditions'] = array(
			'Aclitem.active'=>1
		);
		
		$options['order'] = array(
				'Aclitem.controller, Aclitem.action'=>'asc'
			);
		
		$aclitems = $this->Aclitem->find('all',$options);
		$this->set(compact('aclitems'));
		
		
		App::import('Model','Role');
		$role= new Role();
		$role->recursive = -1;
		
		$options1['fields'] = array(
			'Role.name'
		   );
		$options1['conditions'] = array(
		'Role.id '=> $role_id
		);	   
		$role_info= $role->find('first',$options1);
		$this->set(compact('role_info'));
		
	}
 
 
 
 
 
 
 
 
 
}
