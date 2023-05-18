<?php
/**
 */
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');

class SiteinformationsController extends AppController {

 
function admin_edit($id = null)
	{
		
        $AdminUser_Info=$this->Session->read('AdminUser_Info');
        //pr($AdminUser_Info);
        
		if($this->request->is('post') || $this->request->is('put'))
		{
			$this->request->data=Sanitize::clean($this->request->data);
			$this->request->data['Siteinformation']['site_description']=trim($this->request->data['Siteinformation']['site_description']);
			$this->request->data['Siteinformation']['site_keywords']=trim($this->request->data['Siteinformation']['site_keywords']);
			
			$ret= $this->Siteinformation->updateAll(
			    array( 'Siteinformation.site_description' =>'"'.$this->request->data['Siteinformation']['site_description'].'"',
					   'Siteinformation.site_keywords' =>'"'.$this->request->data['Siteinformation']['site_keywords'].'"' ,
					   'Siteinformation.postads_level1_price' =>'"'.$this->request->data['Siteinformation']['postads_level1_price'].'"' ,
					   'Siteinformation.postads_level2_price' =>'"'.$this->request->data['Siteinformation']['postads_level2_price'].'"'
				)
			  );
			
			if($ret)
			{
				$this->Session->setFlash(__('the_setting_has_been_saved'), 'admin_success');
			}
			else
			{
				$this->Session->setFlash(__('the_setting_could_not_be_saved','admin_error'));
			}
		}
		
		$setting = $this->Siteinformation->find('first');
		$this->set(compact('setting'));
				
	}

 
 
}
