<?php
/**
 * Static content controller.
 *
 * This file will render views from views/productreviews/
 *
 */
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');
App::uses('CakeEmail', 'Network/Email'); 
 
class ProductreviewsController extends AppController {

/**
 * Controller name
 *
 * @var string
 */
	public $name = 'Productreviews';
		
	var $components = array('GilaceDate','Httpupload'); 
	
	 public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow();
	}

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array();



/**
 * 
 * 
*/
 function admin_index()
	{
		$this->Productreview->recursive = -1;
		if(isset($_REQUEST['filter'])){
			$limit = $_REQUEST['filter'];
		}else $limit = 10;
		
		if(isset($this->request->data['Productreview']['search'])){
			$this->request->data=Sanitize::clean($this->request->data);
			$this->paginate = array(
'conditions' => array('Productreview.title LIKE' => ''.$this->request->data['Productreview']['search'].'%'),
 				'fields'=>array(
					'Productreview.id',
					'Productreview.title',
					'Productreview.status',
					'Productreview.created'
				),
				'limit' => $limit,
				'order' => array(
					'Productreview.id' => 'desc'
				)
			);
		}
		else
		{				
			$this->paginate = array(
				'fields'=>array(
					'Productreview.id',
					'Productreview.title',
					'Productreview.status',
					'Productreview.created'
				),
				'limit' => $limit,
				'order' => array(
					'Productreview.id' => 'desc'
				)
			);
		}
		
		$productreviews = $this->paginate('Productreview');
		$this->set(compact('productreviews'));
	}
	
	
  function admin_add(){
  	if($this->request->is('post'))
		{
			$this->Productreview->create();
			if($this->Productreview->save($this->request->data))
			{
				$this->Session->setFlash(__('the_productreview_has_been_saved'), 'admin_success');
				$this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash(__('the_productreview_could_not_be_saved'));
			}
		}
  }	


	function admin_edit($id = null)
	{
		/*$this->Productreview->id = $id;
		if(!$this->Productreview->exists())
		{
			$this->Session->setFlash(__('invalid_id_for_productreview'));
			return;
		}*/
		
		if($this->request->is('post') || $this->request->is('put'))
		{
			
			$datasource = $this->Productreview->getDataSource();
			try{
			    $datasource->begin();

					
					$options=array();
					$options['conditions'] = array(
						'Productreview.product_id'=>$id
					);
					$productreview = $this->Productreview->find('first',$options);
					
					$options=array();
					$options['conditions'] = array(
						'Productreview.product_id'=>$id
					);
					$count = $this->Productreview->find('count',$options);
					if($count<=0){
						$this->request->data=Sanitize::clean($this->request->data);
						$this->request->data['Productreview']['product_id']=$id;
						if(!$this->Productreview->save($this->request->data))
							throw new Exception(__('the_productreview_could_not_be_saved')); 
						$productreview_id= $this->Productreview->getLastInsertID(); 	
					  }
					else{
							$ret= $this->Productreview->updateAll(
						    	array('Productreview.body' => "'".$this->request->data['Productreview']['body']."'" 
								),   //fields to update
						   		 array( 'Productreview.product_id' => $id )  //condition
						  	);	
						   if(!$ret)
						  	   throw new Exception(__('the_productreview_could_not_be_saved')); 
						   $productreview_id=$productreview['Productreview']['id'];	   	
					    }
				
					
					// pdf opration
			 	
				$options=array();
                $this->Productreview->Productreviewpdf->recursive=-1;
            	$options['fields'] = array(
            			'Productreviewpdf.id',
            			'Productreviewpdf.title',
            			'Productreviewpdf.pdf'
            		   );
            	$options['conditions'] = array(
            		'Productreviewpdf.productreview_id' => $productreview_id	
            		);
            	$productreviewpdfs = $this->Productreview->Productreviewpdf->find('all',$options);
				if(!empty($productreviewpdfs)){
				foreach($productreviewpdfs as $productreviewpdf)
				{
					if(!in_array($productreviewpdf['Productreviewpdf']['id'],$this->request->data['Productreviewpdf']['id']))
					{
						if(!$this->Productreview->Productreviewpdf->delete($productreviewpdf['Productreviewpdf']['id']))
							throw new Exception(__('the_productreviewpdf_not_saved'));	
						else
						{
							@unlink(__PRODUCT_FILE_PATH."/".$productreviewpdf['Productreviewpdf']['pdf']);				     
						}	
					}
				}
			  }		
				//pr($this->request->data);throw new Exception();	
				if(!empty($this->request->data['Productreviewpdf']['id'])){
					foreach($this->request->data['Productreviewpdf']['id'] as $key => $value)
					{
					  
					  if($this->request->data['Productreviewpdf']['pdf'][$key]['size']>0){
					  	
						@unlink(__PRODUCT_FILE_PATH."/".$this->request->data['Productreviewpdf']['old_pdf'][$key]);
					   						
					  	$output=$this->_file($this->request->data['Productreviewpdf']['pdf'][$key],$key);
        				if(!$output['error']) $pdf=$output['filename'];
        				else {
                                $pdf='';
                                if(!empty($pdf_list)){
                                    foreach($pdf_list as $img)
                                    {
                                        @unlink(__PRODUCT_FILE_PATH."/".$img);
                                    }
                                }
                                throw new Exception($output['message'].'  '.__('نام تصویر ').$this->request->data['Productreviewpdf']['pdf'][$key]['name']);
                             }
                        
                        $pdf_list[]=$pdf;
					  }else $pdf = $this->request->data['Productreviewpdf']['old_pdf'][$key];
					  
					  $ret= $this->Productreview->Productreviewpdf->updateAll(
					    array('Productreviewpdf.title' => '"'.$this->request->data['Productreviewpdf']['title'][$key].'"' ,
							  'Productreviewpdf.pdf' => '"'.$pdf.'"'
						),   //fields to update
					    array( 'Productreviewpdf.id' => $value )  //condition
					  );	
					  if(!$ret){
					  	throw new Exception(__('the_productreviewpdf_not_saved'));
						  }	
					}
			    }
				
                
                
                
                 
				foreach($this->request->data['Productreviewpdf']['id'] as $key => $value)
				{
					unset($this->request->data['Productreviewpdf']['title'][$key]);
					unset($this->request->data['Productreviewpdf']['pdf'][$key]);
				}
				 
				 
				 $data = array();
				if(!empty($this->request->data['Productreviewpdf']['pdf'])){	
								
					foreach($this->request->data['Productreviewpdf']['pdf'] as $key => $value)
					{						
						$output=$this->_file($value,$key);
        				if(!$output['error']) $pdf=$output['filename'];
        				else {
                                $pdf='';
                                if(!empty($pdf_list)){
                                    foreach($pdf_list as $img)
                                    {
                                        @unlink(__PRODUCT_FILE_PATH."/".$img);
                                    }
                                }
                                throw new Exception($output['message'].'  '.__('نام تصویر ').$value['name']);
                             }
                        
                        $pdf_list[]=$pdf;
                        
                        $data[]= array('Productreviewpdf' => array(
									'pdf' => $pdf ,
									'title'=> $this->request->data['Productreviewpdf']['title'][$key],
									'productreview_id' => $productreview_id
						));
					}		
					//pr($data);throw new Exception();			
					if(!empty($data)){
						if(!$this->Productreview->Productreviewpdf->saveMany($data,array('deep' => true)))
					        throw new Exception(__('the_productreviewpdf_not_saved'));	
					}					
				}
				
				
			 // pdf opration
					
				
					
				$datasource->commit();
				$this->Session->setFlash(__('the_productreview_has_been_saved'), 'admin_success');
				$this->redirect(array('controller'=>'products','action' => 'index'));	
			} catch(Exception $e) {
				    $datasource->rollback();
					$this->Session->setFlash($e->getMessage(),'admin_error');
			}
			
		}
		
		$options['fields'] = array(
			'Productreview.id',
			'Productreview.body'
		);
		$options['conditions'] = array(
			'Productreview.product_id'=>$id
		);
		$productreview = $this->Productreview->find('first',$options);
		$this->set(compact('productreview'));
		
		/*if(empty($productreview)){
			$productreview=array('Productreview'=>array('body'=>' '));
		}*/
		
		$options=array();
		$options['fields'] = array(
			'Product.id',
			'Product.title'
		);
		$options['conditions'] = array(
			'Product.id'=>$id
		);
		$product = $this->Productreview->Product->find('first',$options);
		$this->set(compact('product'));
		
		if(!empty($productreview)){
            $this->Productreview->Productreviewpdf->recursive = -1; 
    		$options=array();
    		$options['fields'] = array(
    			'Productreviewpdf.id',
    			'Productreviewpdf.title',
    			'Productreviewpdf.pdf'
    		);
    		$options['conditions'] = array(
    			'Productreviewpdf.productreview_id'=>$productreview['Productreview']['id']
    		);
    	    $productreviewpdfs = $this->Productreview->Productreviewpdf->find('all',$options);
    	    $this->set('productreviewpdfs', $productreviewpdfs); 
        }
		
				
	}	

function admin_delete($id = null){		
		$this->Productreview->id = $id;
		if(!$this->Productreview->exists())
		{
			$this->Session->setFlash(__('invalid_id_for_productreview'), 'admin_error');
			$this->redirect(array('action' => 'index'));
		}
		
		if($this->Productreview->delete($id))
		{
			$this->Session->setFlash(__('delete_productreview_success'), 'admin_success');
			$this->redirect(array('action'=>'index'));
		}else
		{
			$this->Session->setFlash(__('delete_productreview_not_success'), 'admin_error');
			$this->redirect($this->referer(array('action' => 'index')));
		}
  }
		


function _file($data,$index){
  App::uses('Sanitize', 'Utility');

  $output= array();  
	  
	  if($data['size']>0)
        {
			$ext=$this->Httpupload->get_extension($data['name']);
			$filename = md5(rand().$_SERVER['REMOTE_ADDR']);
            if(file_exists(__PRODUCT_IMAGE_PATH.$filename.'.'.$ext))
                $filename = md5(rand().$_SERVER[REMOTE_ADDR]);
           
            $this->Httpupload->setmodel('Productreviewpdf');
			$this->Httpupload->setuploadindex($index);
			$this->Httpupload->setuploaddir(__PRODUCT_FILE_PATH);
			$this->Httpupload->setuploadname('pdf');
			$this->Httpupload->settargetfile($filename.'.'.$ext);
			$this->Httpupload->setmaxsize(__UPLOAD_PDF_MAX_SIZE);
			$this->Httpupload->allowExt= __UPLOAD_PDF_EXTENSION;
            if(!$this->Httpupload->upload())
                {
					return array('error'=>true,'filename'=>'','message'=>$this->Httpupload->get_error());
                }
           $filename .= '.'.$ext;     

        }
		else return array('error'=>true,'filename'=>'','message'=>'');
		
	return array('error'=>false,'filename'=>$filename);
}
	
	
}

