<?php
/**
 */
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');

class ChannelsController extends AppController {


 function admin_index()
	{
		$this->Channel->recursive = -1;
		if(isset($_REQUEST['filter'])){
			$limit = $_REQUEST['filter'];
		}else $limit = 10;
		
		
		if(isset($this->request->data['Channel']['search'])){
			$this->request->data=Sanitize::clean($this->request->data);
			$this->paginate = array(
'conditions' => array("OR"=>array(
				   'Channel.title_per LIKE' => ''.$this->request->data['Channel']['search'].'%' ,
				   'Channel.title_eng LIKE' => ''.$this->request->data['Channel']['search'].'%'
				)),
				'limit' => $limit,
				'order' => array(
					'Channel.id' => 'desc'
				)
			);
		}
		else
		{
			$this->paginate = array(
				/*'fields'=>array(
					'Channel.id',
					'Channel.title_'.$this->Session->read('Config.language').' as title'
				),*/
				'limit' => $limit,
				'order' => array(
					'Channel.id' => 'desc'
				)
			);
		}
		
		$channels = $this->paginate('Channel');
		$this->set(compact('channels'));
		
		
	}


	function admin_add()
	{
		$this->Channel->recursive = -1;
		if($this->request->is('post'))
		{
			$this->request->data=Sanitize::clean($this->request->data);
			$this->Channel->create();
			if($this->Channel->save($this->request->data))
			{
				$this->Session->setFlash(__('the_channel_has_been_saved'), 'admin_success');
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash(__('the_channel_could_not_be_saved'));
			}
		}
		 
		
	}

/**
* 
* @param undefined $id
* 
*/
	function _set_channel($id)
	{
		$this->Channel->recursive = -1;
		$this->Channel->id = $id;
		if(!$this->Channel->exists())
		{
			$this->Session->setFlash(__('invalid_id_for_channel'));
			return;
		}
	    
	    /*
	    * Test allowing to not override submitted data
	    */
	    if(empty($this->request->data))
	    {
	    	$this->request->data = $this->Channel->findById($id);
	    }
	    
	    $this->set('channel', $this->request->data);
	    
	    return $this->request->data;
	}


	function admin_edit($id = null)
	{
		$this->Channel->id = $id;
		if(!$this->Channel->exists())
		{
			$this->Session->setFlash(__('invalid_id_for_channel'));
			return;
		}
		
		if($this->request->is('post') || $this->request->is('put'))
		{
			$this->request->data=Sanitize::clean($this->request->data);
			if($this->Channel->save($this->request->data))
			{
				$this->Session->setFlash(__('the_channel_has_been_saved'), 'admin_success');
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash(__('the_channel_could_not_be_saved'));
			}
		}
		
		$this->_set_channel($id);
		
	}

	function admin_delete($id = null)
	{
		$this->Channel->id = $id;
		if(!$this->Channel->exists())
		{
			//$this->Session->setFlash(__('invalid_id_for_channel'));
			$this->Session->setFlash(__('invalid_id_for_channel'), 'admin_error');
			$this->redirect(array('action' => 'index'));
		}
		
		if($this->Channel->delete($id))
		{
			/*$ret= $this->Channel->User->updateAll(
			    array( 'User.channel_id' => '0' ),   //fields to update
			    array( 'User.channel_id' => $id )  //condition
			  );*/
			$this->Session->setFlash(__('delete_channel_success'), 'admin_success');
			$this->redirect(array('action' => 'index'));
		}
		else
		{
			$this->Session->setFlash(__('delete_channel_not_success'), 'admin_error');
			$this->redirect($this->referer(array('action' => 'index')));
		}
		
	}
 
 
 
 
}
