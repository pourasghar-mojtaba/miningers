<?php
/**
 */
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');

class CategorypostsController extends AppController {


 function admin_index()
	{
		$this->Categorypost->recursive = -1;
		if(isset($_REQUEST['filter'])){
			$limit = $_REQUEST['filter'];
		}else $limit = 10;
		
		
		if(isset($this->request->data['Categorypost']['search'])){
			$this->request->data=Sanitize::clean($this->request->data);
			$this->paginate = array(
'conditions' => array("OR"=>array(
				   'Categorypost.title_per LIKE' => ''.$this->request->data['Categorypost']['search'].'%' ,
				   'Categorypost.title_eng LIKE' => ''.$this->request->data['Categorypost']['search'].'%'
				)),
				'limit' => $limit,
				'order' => array(
					'Categorypost.id' => 'desc'
				)
			);
		}
		else
		{
			$this->paginate = array(
				/*'fields'=>array(
					'Categorypost.id',
					'Categorypost.title_'.$this->Session->read('Config.language').' as title'
				),*/
				'limit' => $limit,
				'order' => array(
					'Categorypost.id' => 'desc'
				)
			);
		}
		
		$categoryposts = $this->paginate('Categorypost');
		$this->set(compact('categoryposts'));
		
		
	}


	function admin_add()
	{
		$this->Categorypost->recursive = -1;
		if($this->request->is('post'))
		{
			$this->request->data=Sanitize::clean($this->request->data);
			$this->Categorypost->create();
			if($this->Categorypost->save($this->request->data))
			{
				$this->Session->setFlash(__('the_categorypost_has_been_saved'), 'admin_success');
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash(__('the_categorypost_could_not_be_saved'));
			}
		}
		 
		
	}

/**
* 
* @param undefined $id
* 
*/
	function _set_categorypost($id)
	{
		$this->Categorypost->recursive = -1;
		$this->Categorypost->id = $id;
		if(!$this->Categorypost->exists())
		{
			$this->Session->setFlash(__('invalid_id_for_categorypost'));
			return;
		}
	    
	    /*
	    * Test allowing to not override submitted data
	    */
	    if(empty($this->request->data))
	    {
	    	$this->request->data = $this->Categorypost->findById($id);
	    }
	    
	    $this->set('categorypost', $this->request->data);
	    
	    return $this->request->data;
	}


	function admin_edit($id = null)
	{
		$this->Categorypost->id = $id;
		if(!$this->Categorypost->exists())
		{
			$this->Session->setFlash(__('invalid_id_for_categorypost'));
			return;
		}
		
		if($this->request->is('post') || $this->request->is('put'))
		{
			$this->request->data=Sanitize::clean($this->request->data);
			if($this->Categorypost->save($this->request->data))
			{
				$this->Session->setFlash(__('the_categorypost_has_been_saved'), 'admin_success');
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash(__('the_categorypost_could_not_be_saved'));
			}
		}
		
		$this->_set_categorypost($id);
		
	}

	function admin_delete($id = null)
	{
		$this->Categorypost->id = $id;
		if(!$this->Categorypost->exists())
		{
			//$this->Session->setFlash(__('invalid_id_for_categorypost'));
			$this->Session->setFlash(__('invalid_id_for_categorypost'), 'admin_error');
			$this->redirect(array('action' => 'index'));
		}
		
		if($this->Categorypost->delete($id))
		{
			$this->Session->setFlash(__('delete_categorypost_success'), 'admin_success');
			$this->redirect(array('action' => 'index'));
		}
		else
		{
			$this->Session->setFlash(__('delete_categorypost_not_success'), 'admin_error');
			$this->redirect($this->referer(array('action' => 'index')));
		}
		
	}
 
 
 
 
}
