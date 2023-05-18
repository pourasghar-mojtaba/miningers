<?php
/**
 */
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');

class TechnicalinfoitemsController extends ShopAppController {

//var $helpers = array('Gilace');
//var $components = array('Gilace','Httpupload'); 
/**
 * Controller name
 *
	 
 /**
* follow to anoher user
* @param undefined $id
* 
*/

/****** Transaction *****

$datasource = $this->Product->getDataSource();
try{
    $datasource->begin();
    if(!$this->Product->save($data)
        throw new Exception();

    if(!$this->Price->save($data_one)
        throw new Exception();

    if(!$this->Property->save($my_data)
        throw new Exception();

    $datasource->commit();
} catch(Exception $e) {
    $datasource->rollback();
}
 
*/

/**
* 
* 
*/
function admin_index()
{
	 
		$this->Technicalinfoitem->Technicalinfo->recursive = -1;
		if(isset($_REQUEST['filter'])){
			$limit = $_REQUEST['filter'];
		}else $limit = 50;
				
		if(isset($this->request->data['Technicalinfo']['search'])){
			$this->request->data=Sanitize::clean($this->request->data);
			$this->paginate = array(
'conditions' => array('Technicalinfo.title LIKE' => '%'.$this->request->data['Technicalinfo']['search'].'%'),
				'fields'=>array(
				'Technicalinfo.id',
				'Technicalinfo.title',
				'Technicalinfo.created'
				),
				'limit' => $limit,
				'order' => array(
					'Technicalinfo.id' => 'asc'
				)
			);
		}
		else
		{
			$this->paginate = array(
				'fields'=>array(
				'Technicalinfo.id',
				'Technicalinfo.title',
				'Technicalinfo.created'
				),
				'limit' => $limit,
				'order' => array(
					'Technicalinfo.id' => 'asc'
				)
			);
		}		
		$technicalinfos = $this->paginate('Technicalinfo');
		$this->set(compact('technicalinfos')); 
}

function admin_add()
	{
				
		if($this->request->is('post') || $this->request->is('put'))
		{
            //pr($this->request->data['Technicalinfo']);
			$datasource = $this->Technicalinfoitem->getDataSource();
			try{
			    $datasource->begin();
			    if(!$this->Technicalinfoitem->Technicalinfo->save($this->request->data))
			        throw new Exception();

			    $technicalinfo_id= $this->Technicalinfoitem->Technicalinfo->getLastInsertID();
				 
				if(!empty($technicalinfo_id)){
					foreach($this->request->data['Technicalinfoitem']['item'] as $key => $value)
					{
						if(trim($value)==''){
							unset($this->request->data['Technicalinfoitem']['item'][$key]);
							unset($this->request->data['Technicalinfoitem']['arrangment'][$key]);
							unset($this->request->data['Technicalinfoitem']['status'][$key]);
						}
					}
				}
				else throw new Exception();
				 
				if(!empty($this->request->data['Technicalinfoitem']['item'])){
					foreach($this->request->data['Technicalinfoitem']['item'] as $key => $value)
					{
						$data[]= array('Technicalinfoitem' => array(
									'item' => $value ,
									'arrangment'=> $this->request->data['Technicalinfoitem']['arrangment'][$key],
									'status'=> $this->request->data['Technicalinfoitem']['status'][$key],
									'technical_info_id' => $technicalinfo_id
								));
					}
				}
				 
						
				//pr($data);throw new Exception();
				
				if(!$this->Technicalinfoitem->saveMany($data,array('deep' => true)))
			        throw new Exception();
					
				echo "4";
				
			    $datasource->commit();
				$this->Session->setFlash(__('the_technicalinfoitem_has_been_saved'), 'admin_success');
				$this->redirect(array('action' => 'index'));
			} catch(Exception $e) {
			    $datasource->rollback();
				$this->Session->setFlash(__('the_technicalinfoitem_not_saved'),'admin_error');
			}
             
		}
	}
 
/**
* 
* @param undefined $id
* 
*/
function admin_edit($id = null)
{
	$this->Technicalinfoitem->Technicalinfo->id = $id;
	if(!$this->Technicalinfoitem->Technicalinfo->exists())
	{
		$this->Session->setFlash(__('invalid_id_for_technicalinfoitem'));
		$this->redirect(array('action' => 'index'));
	}
	
	if($this->request->is('post') || $this->request->is('put'))
	{
	   $datasource = $this->Technicalinfoitem->getDataSource();
		try{
		    $datasource->begin();
			
			$ret= $this->Technicalinfoitem->Technicalinfo->updateAll(
			    array( 'Technicalinfo.title' => '"'.$this->request->data['Technicalinfo']['title'].'"' ),   //fields to update
			    array( 'Technicalinfo.id' => $id )  //condition
			);
			
			//pr($this->request->data);throw new Exception();
			
			$this->Technicalinfoitem->recursive = -1;
			$options['conditions'] = array(
				'Technicalinfoitem.technical_info_id' => $id		
			);
			$technicalinfoitems = $this->Technicalinfoitem->find('all',$options);
			if(!empty($technicalinfoitems)){
				foreach($technicalinfoitems as $technicalinfoitem)
				{
					if(!in_array($technicalinfoitem['Technicalinfoitem']['id'],$this->request->data['Technicalinfoitem']['id']))
					{
						$this->Technicalinfoitem->Technicalinfoitemvalue->recursive = -1;
						$options['conditions'] = array(
							'Technicalinfoitemvalue.technical_info_item_id' => $technicalinfoitem['Technicalinfoitem']['id']
						);
						$count = $this->Technicalinfoitem->Technicalinfoitemvalue->find('count',$options);
						if($count>0){
							throw new Exception('مشخصات فنی با نام "'.$technicalinfoitem['Technicalinfoitem']['item'].'" و شماره "'.$technicalinfoitem['Technicalinfoitem']['id'].'" با '.$count.' محصول در ارتباط می باشد و قابل حذف نیست');
						}
						
						if(!$this->Technicalinfoitem->delete($technicalinfoitem['Technicalinfoitem']['id']))
							throw new Exception(__('the_technicalinfoitem_not_saved'));				
					}
					
				}
			}
			
		    if(!empty($this->request->data['Technicalinfoitem']['id'])){
				foreach($this->request->data['Technicalinfoitem']['id'] as $key => $value)
				{
				  $ret= $this->Technicalinfoitem->updateAll(
				    array('Technicalinfoitem.item' => '"'.$this->request->data['Technicalinfoitem']['item'][$key].'"' ,
					      'Technicalinfoitem.arrangment' => '"'.$this->request->data['Technicalinfoitem']['arrangment'][$key].'"' ,
						  'Technicalinfoitem.status' => '"'.$this->request->data['Technicalinfoitem']['status'][$key].'"'
					),   //fields to update
				    array( 'Technicalinfoitem.id' => $value )  //condition
				  );	
				  if(!$ret){
				  	throw new Exception(__('the_technicalinfoitem_not_saved'));
				  }	
				}
			}
			
			
			foreach($this->request->data['Technicalinfoitem']['id'] as $key => $value)
			{
				unset($this->request->data['Technicalinfoitem']['item'][$key]);
				unset($this->request->data['Technicalinfoitem']['arrangment'][$key]);
				unset($this->request->data['Technicalinfoitem']['status'][$key]);
			}
			
			if(!empty($this->request->data['Technicalinfoitem']['item'])){
				foreach($this->request->data['Technicalinfoitem']['item'] as $key => $value)
				{
					$data[]= array('Technicalinfoitem' => array(
								'item' => $value ,
								'arrangment'=> $this->request->data['Technicalinfoitem']['arrangment'][$key],
								'status'=> $this->request->data['Technicalinfoitem']['status'][$key],
								'technical_info_id' => $id
							));
				}
			}
			if(!empty($data)){
				if(!$this->Technicalinfoitem->saveMany($data,array('deep' => true)))
			        throw new Exception(__('the_technicalinfoitem_not_saved'));	
			}
			
			
			$datasource->commit();
			$this->Session->setFlash(__('the_technicalinfoitem_has_been_saved'), 'admin_success');
			$this->redirect(array('action' => 'index'));
			
		} catch(Exception $e) {
			    $datasource->rollback();
				$this->Session->setFlash($e->getMessage(),'admin_error');
		}
                	 
	}
	
	$this->Technicalinfoitem->Technicalinfo->recursive = -1; 
    $technicalinfo = $this->Technicalinfoitem->Technicalinfo->findById($id); 
    $this->set('technicalinfo', $technicalinfo);
	
    $this->Technicalinfoitem->recursive = -1; 
	$options['conditions'] = array(
		'Technicalinfoitem.technical_info_id' => $id		
		);
	$technicalinfoitems = $this->Technicalinfoitem->find('all',$options);
    $this->set('technicalinfoitems', $technicalinfoitems);
}

/**
* 
* @param undefined $id
* 
*/
function admin_delete($id = null)
{
	
	$this->Technicalinfoitem->recursive = -1;
	$this->Technicalinfoitem->Technicalinfoitemvalue->recursive = -1;
	
	$options['conditions'] = array(
		'Technicalinfoitem.technical_info_id' => $id		
	);
	$technicalinfoitems = $this->Technicalinfoitem->find('all',$options);
	if(!empty($technicalinfoitems)){
		foreach($technicalinfoitems as $technicalinfoitem)
			{
				$options['conditions'] = array(
					'Technicalinfoitemvalue.technical_info_item_id' => $technicalinfoitem['Technicalinfoitem']['id']
				);
				$count = $this->Technicalinfoitem->Technicalinfoitemvalue->find('count',$options);
				if($count>0){
					$this->Session->setFlash(__('مشخصات فنی با نام "'.$technicalinfoitem['Technicalinfoitem']['item'].'" و شماره "'.$technicalinfoitem['Technicalinfoitem']['id'].'" با '.$count.' محصول در ارتباط می باشد و قابل حذف نیست'),'admin_error');
					$this->redirect(array('action' => 'index'));
				}
			}
	}
	   
   if($this->Technicalinfoitem->Technicalinfo->delete($id)){
	   $this->Session->setFlash(__('the_technicalinfoitem_deleted'), 'admin_success');
       $this->redirect(array('action' => 'index'));
   }
   else $this->Session->setFlash(__('the_technicalinfoitem_not_deleted'),'admin_error');
}



 
}
