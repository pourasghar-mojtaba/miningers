<?php
/**
 * Roles Controller
 *
 * @property Role $Role
 */
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');

class RolesController extends AppController {

	var $name = 'Roles';

	function admin_index()
	{
		$this->Role->recursive = -1;
		if(isset($_REQUEST['filter'])){
			$limit = $_REQUEST['filter'];
		}else $limit = 10;
				
		if(isset($this->request->data['Role']['search'])){
			$this->request->data=Sanitize::clean($this->request->data);
			$this->paginate = array(
'conditions' => array('Role.name LIKE' => ''.$this->request->data['Role']['search'].'%' ,'Role.id not in'=>array(1,2)),
				'limit' => $limit,
				'order' => array(
					'Role.id' => 'desc'
				)
			);
		}
		else
		{
			$this->paginate = array(
			    'conditions' => array('Role.id not in (1,2)'),
				'limit' => $limit,
				'order' => array(
					'Role.id' => 'desc'
				)
			);
		}		
		$roles = $this->paginate('Role');
		$this->set(compact('roles'));
	}


	function admin_add()
	{
		$this->Role->recursive = -1;
		if($this->request->is('post'))
		{
			$this->request->data=Sanitize::clean($this->request->data);
			$this->Role->create();
			if($this->Role->save($this->request->data))
			{
				$this->Session->setFlash(__('the_role_has_been_saved'), 'admin_success');
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash(__('the_role_could_not_be_saved'));
			}
		}
		 
		
	}

/**
* 
* @param undefined $id
* 
*/
	function _set_role($id)
	{
		$this->Role->recursive = -1;
		$this->Role->id = $id;
		if(!$this->Role->exists())
		{
			$this->Session->setFlash(__('invalid_id_for_role'));
			return;
		}
	    
	    /*
	    * Test allowing to not override submitted data
	    */
	    if(empty($this->request->data))
	    {
	    	$this->request->data = $this->Role->findById($id);
	    }
	    
	    $this->set('role', $this->request->data);
	    
	    return $this->request->data;
	}


	function admin_edit($id = null)
	{
		$this->Role->id = $id;
		if(!$this->Role->exists())
		{
			$this->Session->setFlash(__('invalid_id_for_role'));
			return;
		}
		
		if($this->request->is('post') || $this->request->is('put'))
		{
			$this->request->data=Sanitize::clean($this->request->data);
			if($this->Role->save($this->request->data))
			{
				$this->Session->setFlash(__('the_role_has_been_saved'), 'admin_success');
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash(__('the_role_could_not_be_saved'));
			}
		}
		
		$this->_set_role($id);
		
	}

	function admin_delete($id = null)
	{
		$this->Role->id = $id;
		if(!$this->Role->exists())
		{
			//$this->Session->setFlash(__('invalid_id_for_role'));
			$this->Session->setFlash(__('invalid_id_for_role'), 'admin_error');
			$this->redirect(array('action' => 'index'));
		}
		
		if($this->Role->delete($id))
		{
			$this->Session->setFlash(__('delete_role_success'), 'admin_success');
			$this->redirect(array('action' => 'index'));
		}
		else
		{
			$this->Session->setFlash(__('delete_role_not_success'), 'admin_error');
			$this->redirect($this->referer(array('action' => 'index')));
		}
		
	}
	
	
	
}
?>
