<?php
/**
 * Bake Template for Controller action generation.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2009, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under the MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2009, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.console.libs.template.objects
 * @since         CakePHP(tm) v 1.3
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 *
 * @author Nicolas Rod <nico@alaxos.com> (adaptation for Alaxos plugin)
 */
?>

	function <?php echo $admin ?>index()
	{
		$this-><?php echo $currentModelName ?>->recursive = 0;
		$this->set('<?php echo $pluralName ?>', $this->paginate($this-><?php echo $currentModelName ?>, $this->AlaxosFilter->get_filter()));
		
<?php
	foreach (array('belongsTo', 'hasAndBelongsToMany') as $assoc):
		foreach ($modelObj->{$assoc} as $associationName => $relation):
			if(!empty($associationName)):
				$otherModelName = $this->_modelName($associationName);
				$otherPluralName = $this->_pluralName($associationName);
				echo "\t\t\${$otherPluralName} = \$this->{$currentModelName}->{$otherModelName}->find('list');\n";
				$compact[] = "'{$otherPluralName}'";
			endif;
		endforeach;
	endforeach;
	if(!empty($compact)):
		echo "\t\t\$this->set(compact(".join(', ', $compact)."));\n";
	endif;
?>
	}

	function <?php echo $admin ?>view($id = null)
	{
		$this->_set_<?php echo $singularName; ?>($id);
	}

<?php $compact = array(); ?>
	function <?php echo $admin ?>add()
	{
		if($this->request->is('post'))
		{
			$this-><?php echo $currentModelName; ?>->create();
			if($this-><?php echo $currentModelName; ?>->save($this->request->data))
			{
<?php if($wannaUseSession): ?>
				$this->Session->setFlash(___('the <?php echo strtolower($singularHumanName); ?> has been saved'), 'flash_message', array('plugin' => 'alaxos'));
				$this->redirect(array('action' => 'index'));
<?php else: ?>
				$this->flash(___('<?php echo ucfirst(strtolower($currentModelName)); ?> saved.'), array('action' => 'index'), 1, 'flash_message'));
<?php endif; ?>
			}
			else
			{
<?php if($wannaUseSession): ?>
				$this->Session->setFlash(___('the <?php echo strtolower($singularHumanName); ?> could not be saved. Please, try again.'), 'flash_error', array('plugin' => 'alaxos'));
<?php endif; ?>
			}
		}
		
<?php
	foreach (array('belongsTo', 'hasAndBelongsToMany') as $assoc):
		foreach ($modelObj->{$assoc} as $associationName => $relation):
			if(!empty($associationName)):
				$otherModelName = $this->_modelName($associationName);
				$otherPluralName = $this->_pluralName($associationName);
				echo "\t\t\${$otherPluralName} = \$this->{$currentModelName}->{$otherModelName}->find('list');\n";
				$compact[] = "'{$otherPluralName}'";
			endif;
		endforeach;
	endforeach;
	if(!empty($compact)):
		echo "\t\t\$this->set(compact(".join(', ', $compact)."));\n";
	endif;
?>
	}

<?php $compact = array(); ?>
	function <?php echo $admin; ?>edit($id = null)
	{
		$this-><?php echo $currentModelName; ?>->id = $id;
		if(!$this-><?php echo $currentModelName; ?>->exists())
		{
			throw new NotFoundException(___('invalid id for <?php echo strtolower($singularHumanName); ?>'));
		}
		
		if($this->request->is('post') || $this->request->is('put'))
		{
			if($this-><?php echo $currentModelName; ?>->save($this->request->data))
			{
<?php if($wannaUseSession): ?>
				$this->Session->setFlash(___('the <?php echo strtolower($singularHumanName); ?> has been saved'), 'flash_message', array('plugin' => 'alaxos'));
				$this->redirect(array('action' => 'index'));
<?php else: ?>
				$this->flash(___('the <?php echo strtolower($singularHumanName); ?> has been saved.'), array('action' => 'index'), 1, 'flash_message');
<?php endif; ?>
			}
			else
			{
<?php if($wannaUseSession): ?>
				$this->Session->setFlash(___('the <?php echo strtolower($singularHumanName); ?> could not be saved. Please, try again.'), 'flash_error', array('plugin' => 'alaxos'));
<?php endif; ?>
			}
		}
		
		$this->_set_<?php echo $singularName; ?>($id);
		
<?php
		foreach (array('belongsTo', 'hasAndBelongsToMany') as $assoc):
			foreach ($modelObj->{$assoc} as $associationName => $relation):
				if(!empty($associationName)):
					$otherModelName = $this->_modelName($associationName);
					$otherPluralName = $this->_pluralName($associationName);
					echo "\t\t\${$otherPluralName} = \$this->{$currentModelName}->{$otherModelName}->find('list');\n";
					$compact[] = "'{$otherPluralName}'";
				endif;
			endforeach;
		endforeach;
		if(!empty($compact)):
			echo "\t\t\$this->set(compact(".join(', ', $compact)."));\n";
		endif;
	?>
	}

<?php $compact = array(); ?>
	function <?php echo $admin; ?>copy($id = null)
	{
		$this-><?php echo $currentModelName; ?>->id = $id;
		if(!$this-><?php echo $currentModelName; ?>->exists())
		{
			throw new NotFoundException(___('invalid id for <?php echo strtolower($singularHumanName); ?>'));
		}
		
		if($this->request->is('post') || $this->request->is('put'))
		{
			$this-><?php echo $currentModelName; ?>->create();
			
			if($this-><?php echo $currentModelName; ?>->save($this->request->data))
			{
<?php if($wannaUseSession): ?>
				$this->Session->setFlash(___('the <?php echo strtolower($singularHumanName); ?> has been saved'), 'flash_message', array('plugin' => 'alaxos'));
				$this->redirect(array('action' => 'index'));
<?php else: ?>
				$this->flash(___('the <?php echo strtolower($singularHumanName); ?> has been saved.'), array('action' => 'index'), 1, 'flash_message');
<?php endif; ?>
			}
			else
			{
				//reset id to copy
				$this->request->data['<?php echo $currentModelName; ?>'][<?php echo "\$this->{$currentModelName}->primaryKey"; ?>] = $id;
<?php if($wannaUseSession): ?>
				$this->Session->setFlash(___('the <?php echo strtolower($singularHumanName); ?> could not be saved. Please, try again.'), 'flash_error', array('plugin' => 'alaxos'));
<?php endif; ?>
			}
		}
		
		$this->_set_<?php echo $singularName; ?>($id);
				
<?php
		foreach (array('belongsTo', 'hasAndBelongsToMany') as $assoc):
			foreach ($modelObj->{$assoc} as $associationName => $relation):
				if(!empty($associationName)):
					$otherModelName = $this->_modelName($associationName);
					$otherPluralName = $this->_pluralName($associationName);
					echo "\t\t\${$otherPluralName} = \$this->{$currentModelName}->{$otherModelName}->find('list');\n";
					$compact[] = "'{$otherPluralName}'";
				endif;
			endforeach;
		endforeach;
		if(!empty($compact)):
			echo "\t\t\$this->set(compact(".join(', ', $compact)."));\n";
		endif;
	?>
	}
	
	function <?php echo $admin; ?>delete($id = null)
	{
		if(!$this->request->is('post'))
		{
			throw new MethodNotAllowedException();
		}
		
		$this-><?php echo $currentModelName; ?>->id = $id;
		if(!$this-><?php echo $currentModelName; ?>->exists())
		{
			throw new NotFoundException(___('invalid id for <?php echo strtolower($singularHumanName); ?>'));
		}
		
		if($this-><?php echo $currentModelName; ?>->delete($id))
		{
<?php if($wannaUseSession): ?>
			$this->Session->setFlash(___('<?php echo strtolower($singularHumanName); ?> deleted'), 'flash_message', array('plugin' => 'alaxos'));
			$this->redirect(array('action'=>'index'));
<?php else: ?>
			$this->flash(___('<?php echo ucfirst(strtolower($singularHumanName)); ?> deleted'), array('action' => 'index'), 1, 'flash_message');
<?php endif; ?>
		}
		elseif(count($this-><?php echo $currentModelName; ?>->validationErrors) > 0)
		{
			$errors_str = '<ul>';
		    
		    foreach($this-><?php echo $currentModelName; ?>->validationErrors as $field => $errors)
		    {
		        foreach($errors as $error)
		        {
		            $errors_str .= '<li>';
		            $errors_str .= $error;
		            $errors_str .= '</li>';
		        }
		    }
		    $errors_str .= '</ul>';
		    
<?php if($wannaUseSession): ?>
			$this->Session->setFlash(sprintf(___('the <?php echo strtolower($singularHumanName); ?> was not deleted: %s'), $errors_str), 'flash_error', array('plugin' => 'alaxos'));
<?php else: ?>
			$this->flash(sprintf(___('the <?php echo strtolower($singularHumanName); ?> was not deleted: %s'), $errors_str), array('action' => 'index'), 1, 'flash_error');
<?php endif; ?>
		    $this->redirect($this->referer(array('action' => 'index')));
		}
		else
		{
<?php if($wannaUseSession): ?>
			$this->Session->setFlash(___('<?php echo strtolower($singularHumanName); ?> was not deleted'), 'flash_error', array('plugin' => 'alaxos'));
<?php else: ?>
			$this->flash(___('<?php echo ucfirst(strtolower($singularHumanName)); ?> was not deleted'), array('action' => 'index'), 1, 'flash_error');
<?php endif; ?>
			$this->redirect($this->referer(array('action' => 'index')));
		}
	}
	
	function <?php echo $admin; ?>actionAll()
	{
	    if(!empty($this->request->data['_Tech']['action']))
	    {
        	if(isset($this->Auth))
	        {
	        	$request           = $this->request;
	            $request['action'] = $this->request->data['_Tech']['action'];
	            
	            if($this->Auth->isAuthorized($this->Auth->user(), $request))
	            {
	                $this->setAction(<?php //echo (!empty($admin) ? "'admin_' . " : null)?>$this->request->data['_Tech']['action']);
	            }
	            else
	            {
<?php if($wannaUseSession): ?>
	                $this->Session->setFlash(___d('alaxos', 'not authorized'), 'flash_error', array('plugin' => 'alaxos'));
<?php else: ?>
                    $this->flash(___d('alaxos', 'not authorized'), array('action' => 'index'), 1, 'flash_error');
<?php endif; ?>
	                $this->redirect($this->referer());
	            }
	        }
	        else
	        {
	        	/*
	             * Auth is not used -> grant access
	             */
	        	$this->setAction($this->request->data['_Tech']['action']);
	        }
	    }
	    else
	    {
<?php if($wannaUseSession): ?>
	        $this->Session->setFlash(___d('alaxos', 'the action to perform is not defined'), 'flash_error', array('plugin' => 'alaxos'));
<?php else: ?>
			$this->flash(___d('alaxos', 'the action to perform is not defined'), array('action' => 'index'), 1, 'flash_error');
<?php endif; ?>
	        $this->redirect($this->referer());
	    }
	}
	
	function <?php echo $admin; ?>deleteAll()
	{
	    $ids = Set :: extract('/<?php echo $currentModelName; ?>/id[id > 0]', $this->request->data);
	    if(count($ids) > 0)
	    {
    	    if($this-><?php echo $currentModelName; ?>->deleteAll(array('<?php echo $currentModelName; ?>.id' => $ids), false, true))
    	    {
<?php if($wannaUseSession): ?>
    	        $this->Session->setFlash(___('<?php echo $pluralName ?> deleted'), 'flash_message', array('plugin' => 'alaxos'));
<?php else: ?>
				$this->flash(___('<?php echo $pluralName ?> deleted'), array('action' => 'index'), 1, 'flash_error');
<?php endif; ?>
    			$this->redirect(array('action'=>'index'));
    	    }
    	    else
    	    {
<?php if($wannaUseSession): ?>
    	        $this->Session->setFlash(___('<?php echo $pluralName ?> were not deleted'), 'flash_error', array('plugin' => 'alaxos'));
<?php else: ?>
				$this->flash(___('<?php echo $pluralName ?> were not deleted'), array('action' => 'index'), 1, 'flash_error');
<?php endif; ?>
    	        $this->redirect($this->referer(array('action' => 'index')));
    	    }
	    }
	    else
	    {
<?php if($wannaUseSession): ?>
	        $this->Session->setFlash(___('no <?php echo $singularName; ?> to delete was found'), 'flash_error', array('plugin' => 'alaxos'));
<?php else: ?>
			$this->flash(___('no <?php echo $singularName; ?> to delete was found'), array('action' => 'index'), 1, 'flash_error');
<?php endif; ?>
    	    $this->redirect($this->referer(array('action' => 'index')));
	    }
	}
	
	
	
<?php if(empty($admin)): ?>
	function _set_<?php echo $singularName; ?>($id)
	{
		$this-><?php echo $currentModelName; ?>->id = $id;
		if(!$this-><?php echo $currentModelName; ?>->exists())
		{
			throw new NotFoundException(___('invalid id for <?php echo strtolower($singularHumanName); ?>'));
		}
	    
	    /*
	    * Test allowing to not override submitted data
	    */
	    if(empty($this->request->data))
	    {
	    	$this->request->data = $this-><?php echo $currentModelName; ?>->findById($id);
	    }
	    
	    $this->set('<?php echo $singularName; ?>', $this->request->data);
	    
	    return $this->request->data;
	}
	
<?php endif; ?>
	