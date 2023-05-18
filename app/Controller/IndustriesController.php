<?php
/**
 */
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');

class IndustriesController extends AppController {


 function admin_index()
	{
		$this->Industry->recursive = -1;
		if(isset($_REQUEST['filter'])){
			$limit = $_REQUEST['filter'];
		}else $limit = 10;
		
		
		if(isset($this->request->data['Industry']['search'])){
			$this->request->data=Sanitize::clean($this->request->data);
			$this->paginate = array(
'conditions' => array("OR"=>array(
				   'Industry.title_per LIKE' => ''.$this->request->data['Industry']['search'].'%' ,
				   'Industry.title_eng LIKE' => ''.$this->request->data['Industry']['search'].'%'
				)),
				'limit' => $limit,
				'order' => array(
					'Industry.id' => 'desc'
				)
			);
		}
		else
		{
			$this->paginate = array(
				/*'fields'=>array(
					'Industry.id',
					'Industry.title_'.$this->Session->read('Config.language').' as title'
				),*/
				'limit' => $limit,
				'order' => array(
					'Industry.id' => 'desc'
				)
			);
		}
		
		$industries = $this->paginate('Industry');
		$this->set(compact('industries'));
		
		
	}


	function admin_add()
	{
		$this->Industry->recursive = -1;
		if($this->request->is('post'))
		{
			$this->request->data=Sanitize::clean($this->request->data);
			$this->Industry->create();
			if($this->Industry->save($this->request->data))
			{
				$this->Session->setFlash(__('the_industry_has_been_saved'), 'admin_success');
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash(__('the_industry_could_not_be_saved'));
			}
		}
		 
		
	}

/**
* 
* @param undefined $id
* 
*/
	function _set_industry($id)
	{
		$this->Industry->recursive = -1;
		$this->Industry->id = $id;
		if(!$this->Industry->exists())
		{
			$this->Session->setFlash(__('invalid_id_for_industry'));
			return;
		}
	    
	    /*
	    * Test allowing to not override submitted data
	    */
	    if(empty($this->request->data))
	    {
	    	$this->request->data = $this->Industry->findById($id);
	    }
	    
	    $this->set('industry', $this->request->data);
	    
	    return $this->request->data;
	}


	function admin_edit($id = null)
	{
		$this->Industry->id = $id;
		if(!$this->Industry->exists())
		{
			$this->Session->setFlash(__('invalid_id_for_industry'));
			return;
		}
		
		if($this->request->is('post') || $this->request->is('put'))
		{
			$this->request->data=Sanitize::clean($this->request->data);
			if($this->Industry->save($this->request->data))
			{
				$this->Session->setFlash(__('the_industry_has_been_saved'), 'admin_success');
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash(__('the_industry_could_not_be_saved'));
			}
		}
		
		$this->_set_industry($id);
		
	}

	function admin_delete($id = null)
	{
		$this->Industry->id = $id;
		if(!$this->Industry->exists())
		{
			//$this->Session->setFlash(__('invalid_id_for_industry'));
			$this->Session->setFlash(__('invalid_id_for_industry'), 'admin_error');
			$this->redirect(array('action' => 'index'));
		}
		
		if($this->Industry->delete($id))
		{
			$ret= $this->Industry->User->updateAll(
			    array( 'User.industry_id' => '0' ),   //fields to update
			    array( 'User.industry_id' => $id )  //condition
			  );
			$this->Session->setFlash(__('delete_industry_success'), 'admin_success');
			$this->redirect(array('action' => 'index'));
		}
		else
		{
			$this->Session->setFlash(__('delete_industry_not_success'), 'admin_error');
			$this->redirect($this->referer(array('action' => 'index')));
		}
		
	}
 
 
 
 
}
