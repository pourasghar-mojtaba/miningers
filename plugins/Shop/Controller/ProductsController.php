<?php
/**
 */
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');

class ProductsController extends ShopAppController {

  var $name = 'Products';
  var $components = array('Httpupload');

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



public function beforeFilter() {
	parent::beforeFilter();
	$this->Auth->allow();
}

/**
* 
* 
*/
 function admin_index()
	{
		$this->Product->recursive = -1;
		if(isset($_REQUEST['filter'])){
			$limit = $_REQUEST['filter'];
		}else $limit = 50;
				
		if(isset($this->request->data['Product']['search'])){
			$this->request->data=Sanitize::clean($this->request->data);
			$this->paginate = array(
'conditions' => array('Product.title LIKE' => '%'.$this->request->data['Product']['search'].'%'),
                'joins'=>array(
					array('table' => 'productcategories',
					'alias' => 'Productcategory',
					'type' => 'INNER',
					'conditions' => array(
				    	'Product.product_category_id = `Productcategory`.id ',
					)
				   ) 
				),
				'fields'=>array(
    				'Product.id',
    				'Product.title',
    				'Productcategory.title',
    				'Product.price',
                    'Product.num',
                    'Product.view',
    				'Product.arrangment',
    				'Product.status',
    				'Product.created'
				),
				'limit' => $limit,
				'order' => array(
					'Product.id' => 'asc'
				)
			);
		}
		else
		{
			$this->paginate = array(
                'joins'=>array(
					array('table' => 'productcategories',
					'alias' => 'Productcategory',
					'type' => 'INNER',
					'conditions' => array(
				    	'Product.product_category_id = `Productcategory`.id ',
					)
				   ) 
				),
				'fields'=>array(
    				'Product.id',
    				'Product.title',
    				'Productcategory.title',
    				'Product.price',
                    'Product.num',
                    'Product.view',
    				'Product.arrangment',
    				'Product.status',
    				'Product.created'
				),
				'limit' => $limit,
				'order' => array(
					'Product.id' => 'asc'
				)
			);
		}		
		$products = $this->paginate('Product');
		$this->set(compact('products'));
	}

/**
* 
* 
*/
function admin_add()
{		
	if($this->request->is('post') || $this->request->is('put'))
	{
        $datasource = $this->Product->getDataSource();
        $User_Info= $this->Session->read('AdminUser_Info');
        try{
            $datasource->begin();
            
             //pr($this->request->data);throw new Exception();
             
             if(trim($this->request->data['Productrate']['rate'])=='' || trim($this->request->data['Productrate']['rate'])==0){
                throw new Exception(__('the_productrate_not_valid')); 
             }
             
             if(!$this->Product->save($this->request->data))
			        throw new Exception(__('the_product_not_saved'));
             $product_id= $this->Product->getLastInsertID();    
                       
             if(!empty($this->request->data['Productrate'])){
					foreach($this->request->data['Productrate'] as $value)
					{
                        $data[]= array('Productrate' => array(
									'product_id' => $product_id ,
									'user_id'=> $User_Info['id'],
									'rate'=> $value
								));
					}
                    if(!$this->Product->Productrate->saveMany($data,array('deep' => true)))
			            throw new Exception(__('the_productrate_not_saved'));
				} 
             if(!empty($this->request->data['Technicalinfoitemvalue']['value']))
             {
                foreach($this->request->data['Technicalinfoitemvalue']['value'] as $key => $value)
				{
					if(trim($value)==''){
						unset($this->request->data['Technicalinfoitemvalue']['value'][$key]);
						unset($this->request->data['Technicalinfoitemvalue']['technical_info_item_id'][$key]);
					}
				} 
                $data = array();
                foreach($this->request->data['Technicalinfoitemvalue']['value'] as $key => $value)
					{
						$data[]= array('Technicalinfoitemvalue' => array(
									'value' => $value ,
									'technical_info_item_id'=> $this->request->data['Technicalinfoitemvalue']['technical_info_item_id'][$key],
									'product_id' => $product_id
								));
					}
                if(!$this->Product->Technicalinfoitemvalue->saveMany($data,array('deep' => true)))
			            throw new Exception(__('the_technicalinfoitemvalue_not_saved'));
             } 
             
             
             if(isset($_POST['new_tags'])&& !empty($_POST['new_tags'])){
					$tags=$_POST['new_tags'];//explode('#',$this->request->data['Productrelatetag']['tag']);
					$tags=array_filter($tags,'strlen');
					$this->loadModel('Producttag');
					if(!empty($tags)){
						foreach($tags as $tag)
						{
							$this->request->data['Producttag']['title']= $tag;
							$this->Producttag->create();
							
							if($this->Producttag->save($this->request->data))
							{
								$tag_id[]=$this->Producttag->getLastInsertID();					
							}
                            else throw new Exception(__('the_tag_not_saved'));
						}
					}
					
				}
				$data = array();
				if(isset($this->request->data['Productrelatetag']['product_tag_id'])){
					foreach($this->request->data['Productrelatetag']['product_tag_id'] as $id)
					{
						$dt=array('Productrelatetag' => array('product_id' => $product_id,'product_tag_id'=>$id));
						array_push($data,$dt);
					}
				}
				
				if(!empty($tag_id))
					{
						foreach($tag_id as $tid)
						{
							$dt=array('Productrelatetag' => array('product_id' => $product_id,'product_tag_id'=>$tid));
							array_push($data,$dt);
						}
						
					}
				 
				if(!empty($this->request->data['Productrelatetag']['product_tag_id']) || !empty($tag_id))
				{
					$this->Product->Productrelatetag->create();
					if(!$this->Product->Productrelatetag->saveMany($data))
                            throw new Exception(__('the_product_tag_not_saved'));
				}
				
            // /*pr($this->request->data);*/pr($_FILES);throw new Exception();
			 
                if(!empty($this->request->data['Productimage']['image']))
                 {
                    
                    foreach($this->request->data['Productimage']['image'] as $key => $value)
    				{
    					if(trim($value['name'])==''){
    						unset($this->request->data['Productimage']['image'][$key]);
    						unset($this->request->data['Productimage']['title'][$key]);
    					}
    				} 
                    $data = array();
                    $image_list=array();
                    foreach($this->request->data['Productimage']['image'] as $key => $value)
					{
						$output=$this->_picture($value,$key);
        				if(!$output['error']) $image=$output['filename'];
        				else {
                                $image='';
                                if(!empty($image_list)){
                                    foreach($image_list as $img)
                                    {
                                        @unlink(__PRODUCT_IMAGE_PATH."/".$img);
					                    @unlink(__PRODUCT_IMAGE_PATH."/".__UPLOAD_THUMB."/".$img);
                                    }
                                }
                                throw new Exception($output['message'].'  '.__('نام تصویر ').$value['name']);
                             }
                        
                        $image_list[]=$image;
                        
                        $data[]= array('Productimage' => array(
									'image' => $image ,
									'title'=> $this->request->data['Productimage']['title'][$key],
									'product_id' => $product_id
								));
					}
                    if(!$this->Product->Productimage->saveMany($data,array('deep' => true)))
			            throw new Exception(__('the_product_image_not_saved'));
                 }
                         
            
            
            $datasource->commit();
            
            $this->Session->setFlash(__('the_product_has_been_saved'), 'admin_success');
			$this->redirect(array('action' => 'index'));
        } catch(Exception $e) {
            $datasource->rollback();
            $this->Session->setFlash($e->getMessage(),'admin_error');
        }    
	}
	$this->set('technicalinfos',$this->_get_Technicalinfo());
	
	
}

function _picture($data,$index){
  App::uses('Sanitize', 'Utility');

  $output= array();  
	  
	  if($data['size']>0)
        {
			$ext=$this->Httpupload->get_extension($data['name']);
			$filename = md5(rand().$_SERVER['REMOTE_ADDR']);
            if(file_exists(__PRODUCT_IMAGE_PATH.$filename.'.'.$ext))
                $filename = md5(rand().$_SERVER[REMOTE_ADDR]);
           
            $this->Httpupload->setmodel('Productimage');
			$this->Httpupload->setuploadindex($index);
			$this->Httpupload->setuploaddir(__PRODUCT_IMAGE_PATH);
			$this->Httpupload->setuploadname('image');
			$this->Httpupload->settargetfile($filename.'.'.$ext);
			$this->Httpupload->setmaxsize(__UPLOAD_IMAGE_MAX_SIZE);
			$this->Httpupload->setimagemaxsize(__UPLOAD_IMAGE_MAX_WIDTH,__UPLOAD_IMAGE_MAX_HEIGHT);
			$this->Httpupload->allowExt= __UPLOAD_IMAGE_EXTENSION;
			$this->Httpupload->create_thumb=true;
			$this->Httpupload->thumb_folder=__UPLOAD_THUMB;
			$this->Httpupload->thumb_width=180;
			$this->Httpupload->thumb_height=120; 
            if(!$this->Httpupload->upload())
                {
					return array('error'=>true,'filename'=>'','message'=>$this->Httpupload->get_error());
                }
           $filename .= '.'.$ext;     

        }
		else return array('error'=>true,'filename'=>'','message'=>'');
		
	return array('error'=>false,'filename'=>$filename);
}



/**
* 
* @param undefined $id
* 
*/
function admin_edit($id = null)
{
	$this->Product->recursive = -1; 
	$this->Product->id = $id;
	if(!$this->Product->exists())
	{
		$this->Session->setFlash(__('invalid_id_for_product'));
		$this->redirect(array('action' => 'index'));
	}
	
	$User_Info= $this->Session->read('AdminUser_Info');
	
	if($this->request->is('post') || $this->request->is('put'))
	{
	  
	   $datasource = $this->Product->getDataSource();
		try{
		    $datasource->begin();
			
            
            if(trim($this->request->data['Productrate']['rate'])=='' || trim($this->request->data['Productrate']['rate'])==0){
                throw new Exception(__('the_productrate_not_valid')); 
             }
            
            
			if(!$this->Product->save($this->request->data))
			        throw new Exception(__('the_product_not_saved'));
				
					
			$ret= $this->Product->Productrate->updateAll(
			    array('Productrate.rate' => '"'.$this->request->data['Productrate']['rate'].'"' 
				),   //fields to update
			    array( 'Productrate.product_id' => $id )  //condition
			  );	
			  if(!$ret){
			  	throw new Exception(__('the_productrate_not_saved'));
			  }	
			// pr($this->request->data); throw new Exception();
		// tecnical item oprtion   
			 if(!$this->Product->Technicalinfoitemvalue->deleteAll(array('Technicalinfoitemvalue.product_id'=>$id),FALSE))
                 throw new Exception(__('the_technicalinfoitemvalue_not_saved'));
                 
			 if(!empty($this->request->data['Technicalinfoitemvalue']['value']))
             {
                foreach($this->request->data['Technicalinfoitemvalue']['value'] as $key => $value)
				{
					if(trim($value)==''){
						unset($this->request->data['Technicalinfoitemvalue']['value'][$key]);
						unset($this->request->data['Technicalinfoitemvalue']['technical_info_item_id'][$key]);
					}
				} 
                $data = array();
                foreach($this->request->data['Technicalinfoitemvalue']['value'] as $key => $value)
					{
						$data[]= array('Technicalinfoitemvalue' => array(
									'value' => $value ,
									'technical_info_item_id'=> $this->request->data['Technicalinfoitemvalue']['technical_info_item_id'][$key],
									'product_id' => $id
								));
					}
                if(!$this->Product->Technicalinfoitemvalue->saveMany($data,array('deep' => true)))
			            throw new Exception(__('the_technicalinfoitemvalue_not_saved'));
             }
		// tecnical item oprtion 
			 
			 // tag oprtion  
			    
                if(!$this->Product->Productrelatetag->deleteAll(array('Productrelatetag.product_id'=>$id),FALSE))
					throw new Exception(__('the_tag_not_saved'));
                             
					if(isset($_POST['new_tags'])&& !empty($_POST['new_tags'])){
						$tags=$_POST['new_tags'];//explode('#',$this->request->data['Productrelatetag']['tag']);
						$tags=array_filter($tags,'strlen');
						$this->loadModel('Producttag');
						if(!empty($tags)){
							foreach($tags as $tag)
							{
								$this->request->data['Producttag']['title']= $tag;
								$this->Producttag->create();
								
								if($this->Producttag->save($this->request->data))
								{
									$tag_id[]=$this->Producttag->getLastInsertID();					
								}else throw new Exception(__('the_tag_not_saved'));
							}
						}
						
					}
					$data = array();
					if(isset($this->request->data['Productrelatetag']['product_tag_id'])){
						foreach($this->request->data['Productrelatetag']['product_tag_id'] as $tagid)
						{
							$dt=array('Productrelatetag' => array('product_id' => $id,'product_tag_id'=>$tagid));
							array_push($data,$dt);
						}
					}
					
					if(!empty($tag_id))
						{
							foreach($tag_id as $tid)
							{
								$dt=array('Productrelatetag' => array('product_id' => $id,'product_tag_id'=>$tid));
								array_push($data,$dt);
							}
							
						}
					//pr($data);throw new Exception();
					 
					if($this->request->data['Productrelatetag']['product_tag_id'] || !empty($tag_id))
					{
                        if(!$this->Product->Productrelatetag->saveMany($data,array('deep' => true)))
							throw new Exception(__('the_product_tag_not_saved'));
					}			  
			 // tag opration
			  
			 
			 // image opration
			 	
				$options=array();
                $this->Product->Productimage->recursive=-1;
            	$options['fields'] = array(
            			'Productimage.id',
            			'Productimage.title',
            			'Productimage.image'
            		   );
            	$options['conditions'] = array(
            		'Productimage.product_id' => $id	
            		);
            	$productimages = $this->Product->Productimage->find('all',$options);
				//pr($this->request->data);throw new Exception();
				if(!empty($productimages)){
				foreach($productimages as $productimage)
				{
					if(!in_array($productimage['Productimage']['id'],$this->request->data['Productimage']['id']))
					{
						if(!$this->Product->Productimage->delete($productimage['Productimage']['id']))
							throw new Exception(__('the_productimage_not_saved'));	
						else
						{
							@unlink(__PRODUCT_IMAGE_PATH."/".$productimage['Productimage']['image']);
					        @unlink(__PRODUCT_IMAGE_PATH."/".__UPLOAD_THUMB."/".$productimage['Productimage']['image']);
						}	
					}
				}
			  }		
				
				
				if(!empty($this->request->data['Productimage']['id'])){
					foreach($this->request->data['Productimage']['id'] as $key => $value)
					{
					  
					  if($this->request->data['Productimage']['image'][$key]['size']>0){
					  	
						@unlink(__PRODUCT_IMAGE_PATH."/".$this->request->data['Productimage']['old_image'][$key]);
					    @unlink(__PRODUCT_IMAGE_PATH."/".__UPLOAD_THUMB."/".$this->request->data['Productimage']['old_image'][$key]);						
					  	$output=$this->_picture($this->request->data['Productimage']['image'][$key],$key);
        				if(!$output['error']) $image=$output['filename'];
        				else {
                                $image='';
                                if(!empty($image_list)){
                                    foreach($image_list as $img)
                                    {
                                        @unlink(__PRODUCT_IMAGE_PATH."/".$img);
					                    @unlink(__PRODUCT_IMAGE_PATH."/".__UPLOAD_THUMB."/".$img);
                                    }
                                }
                                throw new Exception($output['message'].'  '.__('نام تصویر ').$this->request->data['Productimage']['image'][$key]['name']);
                             }
                        
                        $image_list[]=$image;
					  }else $image = $this->request->data['Productimage']['old_image'][$key];
					  
					  $ret= $this->Product->Productimage->updateAll(
					    array('Productimage.title' => '"'.$this->request->data['Productimage']['title'][$key].'"' ,
							  'Productimage.image' => '"'.$image.'"'
						),   //fields to update
					    array( 'Productimage.id' => $value )  //condition
					  );	
					  if(!$ret){
					  	throw new Exception(__('the_productimage_not_saved'));
						  }	
					}
			    }
				
                
                
                
                if(!empty($this->request->data['Productimage']['id'])){
					foreach($this->request->data['Productimage']['id'] as $key => $value)
					{
						unset($this->request->data['Productimage']['title'][$key]);
						unset($this->request->data['Productimage']['image'][$key]);
					}
				} 
				
				 
				 
				 $data = array();
				if(!empty($this->request->data['Productimage']['image'])){	
								
					foreach($this->request->data['Productimage']['image'] as $key => $value)
					{						
						$output=$this->_picture($value,$key);
        				if(!$output['error']) $image=$output['filename'];
        				else {
                                $image='';
                                if(!empty($image_list)){
                                    foreach($image_list as $img)
                                    {
                                        @unlink(__PRODUCT_IMAGE_PATH."/".$img);
					                    @unlink(__PRODUCT_IMAGE_PATH."/".__UPLOAD_THUMB."/".$img);
                                    }
                                }
                                throw new Exception($output['message'].'  '.__('نام تصویر ').$value['name']);
                             }
                        
                        $image_list[]=$image;
                        
                        $data[]= array('Productimage' => array(
									'image' => $image ,
									'title'=> $this->request->data['Productimage']['title'][$key],
									'product_id' => $id
						));
					}		
					//pr($data);throw new Exception();			
					if(!empty($data)){
						if(!$this->Product->Productimage->saveMany($data,array('deep' => true)))
					        throw new Exception(__('the_productimage_not_saved'));	
					}					
				}
				
				
			 // image opration
			
			
		    $datasource->commit();
			$this->Session->setFlash(__('the_technicalinfoitem_has_been_saved'), 'admin_success');
			$this->redirect(array('action' => 'index'));	
		} catch(Exception $e) {
			    $datasource->rollback();
				$this->Session->setFlash($e->getMessage(),'admin_error');
		}
		  	
	}
     
    
	$options['conditions'] = array(
		'Product.id' => $id		
		);
	$product = $this->Product->find('first',$options);
    $this->set('product', $product);
	
	$options=array();
	
	$options['conditions'] = array(
		'Productrate.user_id' => $User_Info['id']		
		);
	$productrate = $this->Product->Productrate->find('first',$options);
    $this->set('productrate', $productrate);
    
    $this->set('technicalinfos',$this->_get_Technicalinfo());
    
    $options=array();
	
	$options['fields'] = array(
			'Technicalinfoitemvalue.id',
			'Technicalinfoitemvalue.value',
			'Technicalinfoitemvalue.technical_info_item_id',
			'Technicalinfoitem.item'
		   );
     $options['joins'] = array(
    		array('table' => 'technicalinfoitems',
        		'alias' => 'Technicalinfoitem',
        		'type' => 'INNER',
        		'conditions' => array(
        		'Technicalinfoitem.id = Technicalinfoitemvalue.technical_info_item_id'
    		)
		)
    );	      
    $options['conditions'] = array(
		'Technicalinfoitemvalue.product_id' => $id		
		);
	$technicalinfoitemvalues = $this->Product->Technicalinfoitemvalue->find('all',$options);
    $this->set('technicalinfoitemvalues', $technicalinfoitemvalues);
    
    $options=array();
	$options['fields'] = array(
			'Productimage.id',
			'Productimage.title',
			'Productimage.image'
		   );
	$options['conditions'] = array(
		'Productimage.product_id' => $id	
		);
	$productimages = $this->Product->Productimage->find('all',$options);
    $this->set('productimages', $productimages);
	
	$options=array();
	$options['fields'] = array(
			'Productrelatetag.id',
			'Producttag.title',
			'Producttag.id' 
		   );
     $options['joins'] = array(
    		array('table' => 'producttags',
        		'alias' => 'Producttag',
        		'type' => 'INNER',
        		'conditions' => array(
        		'Producttag.id = Productrelatetag.product_tag_id'
    		)
		)
    );	      
    $options['conditions'] = array(
		'Productrelatetag.product_id' => $id		
		);
	$productrelatetags = $this->Product->Productrelatetag->find('all',$options);
    $this->set('productrelatetags', $productrelatetags);
	
    $this->set('technicalinfos',$this->_get_Technicalinfo());
	
	$this->set('categories',$this->_getback_categories($product['Product']['product_category_id']));
}



function admin_delete($id = null)
{
	$this->Product->Productimage->recursive = -1;
	$options['fields'] = array(
		'Productimage.id',
		'Productimage.image'
	   );
			   
	$options['conditions'] = array(
		'Productimage.product_id'=>$id
	);
	$images = $this->Product->Productimage->find('all',$options);
   
	$datasource = $this->Product->getDataSource();
	try{
    	$datasource->begin();   
	   //pr($images);throw new Exception();
	   if($this->Product->delete($id)){
			 if(!empty($images)){
			 	foreach($images as $img){
					@unlink(__PRODUCT_IMAGE_PATH."/".$img['Productimage']['image']);
					@unlink(__PRODUCT_IMAGE_PATH."/".__UPLOAD_THUMB."/".$img['Productimage']['image']);
				}
			 }
		    
		  
	   }
	   else throw new Exception(__('the_product_not_deleted'));
	   
		$datasource->commit();
		 $this->Session->setFlash(__('the_product_deleted'), 'admin_success');
         $this->redirect(array('action' => 'index'));
	 } catch(Exception $e) {
	    $datasource->rollback();
		$this->Session->setFlash($e->getMessage(),'admin_error');
	}   
	   
}


/**
* 
* @param undefined $parent_id
* 
*/
public function _getback_categories($id) {	
    $this->Product->Productcategory->recursive = -1;
	$category_data = array();		
	$query=	$this->Product->Productcategory->find('all',array('conditions' => array('id' => $id)));

			foreach ($query as $result) {
				$category_data[] = array(
					'parent_id'    => $result['Productcategory']['parent_id'],
					'select_id'    => $result['Productcategory']['id']			 
				);
	         $category_data = array_merge($this->_getback_categories($result['Productcategory']['parent_id']),$category_data);
			}	
		return $category_data;
}


/**
* 
* @param undefined $parent_id
* 
*/
function admin_get_product_category($parent_id){
	
	$select_id=$_REQUEST['select_id'];
	if(isset($select_id)&&!empty($select_id)){
		$this->set('select_id',$select_id);
	}
	
	$this->Product->Productcategory->recursive = -1;
	
	$options['fields'] = array(
		'Productcategory.id ',
		'Productcategory.parent_id ',
		'Productcategory.title'
	   );
			   
	$options['conditions'] = array(
		'Productcategory.parent_id'=>$parent_id
	);
	$response = $this->Product->Productcategory->find('all',$options);
	
	$this->set(array('categories' => $response));
	$this->set('parent_id',$parent_id);
 
	$this->render('Shop.Elements/Products/Ajax/category', 'ajax');
}

function admin_get_technical_info($id,$product_id){
	
	$this->loadModel('Technicalinfoitem');
    $this->Technicalinfoitem->recursive = -1;
	
	
	if($product_id>0){
		$response = $this->Product->Technicalinfoitemvalue->query("
						SELECT 
							Technicalinfoitemvalue.id, 
							Technicalinfoitemvalue.value,
							Technicalinfoitem.item,
							Technicalinfoitem.id 
						FROM technicalinfoitems AS Technicalinfoitem 
							LEFT JOIN technicalinfoitemvalues AS Technicalinfoitemvalue 
								   ON (Technicalinfoitem.id = Technicalinfoitemvalue.technical_info_item_id) 
								  AND Technicalinfoitemvalue.product_id = ".$product_id." 
						WHERE Technicalinfoitem.technical_info_id = ".$id."
		");
		$this->set(array('product_id' => $product_id));
	}
	else
	{
		$options['fields'] = array(
		'Technicalinfoitem.id ',
		'Technicalinfoitem.item'
	   );
			   
		$options['conditions'] = array(
			'Technicalinfoitem.technical_info_id'=>$id ,
			'Technicalinfoitem.status'=>1
		);
		$options['order'] = array(
			'Technicalinfoitem.arrangment'=>'asc'
		);
		
		$response = $this->Technicalinfoitem->find('all',$options);
	}
	
	
	$this->set(array('technicalinfoitems' => $response));
 
	$this->render('Shop.Elements/Products/Ajax/technicalinfoitem', 'ajax');
}

 function admin_tag_search()
 {
 	$this->loadModel('Producttag');
    $this->Producttag->recursive = -1;
	
	$search_word =  $_GET["term"] ;
	$options['fields'] = array(
				'Producttag.id',
				'Producttag.title'
			   );
				   
	$options['conditions'] = array(
		   'Producttag.title LIKE'=> "$search_word%" ,
	);
	
	$options['order'] = array(
			'Producttag.id'=>'desc'
		);
	$options['limit'] = 5;
	
	$producttags = $this->Producttag->find('all',$options);
    $this->set('search_result',$producttags);
    $this->render('Shop.Elements/Producttags/Ajax/tag_search','ajax'); 											 
 }

function _get_Technicalinfo(){
	
	$this->loadModel('Technicalinfo');
    $this->Technicalinfo->recursive = -1;
	
	$options['fields'] = array(
		'Technicalinfo.id ',
		'Technicalinfo.title'
	   );
	$response = $this->Technicalinfo->find('all',$options);
	
	return $response;
}

/**
* 
* 
*/
function index()
{
	
}



 
}
