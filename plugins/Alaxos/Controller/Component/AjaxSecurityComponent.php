<?php

class AjaxSecurityComponent extends Component
{
    /**
     * @var AppController
     */
    private $controller = null;
    
    var $components = array('Session', 'Alaxos.AlaxosCookie');
    
    private $csrfExpires = '+30 minutes';
    
    public $ajax_security_url = array('plugin' => 'alaxos', 'admin' => false, 'controller' => 'alaxos_javascripts', 'action' => 'ajax_security');
    
    public function startup(&$controller)
	{
	    $this->controller = $controller;
	    
	    if(isset($controller->Security))
	    {
	        $this->csrfExpires = $controller->Security->csrfExpires;
	    }
	    
	    $this->_clean_expired_tokens();
	    
	    if(!$this->_validate_ajax_csrf($controller))
	    {
	        $this->_blackhole($controller, 'csrf');
	    }
	    
	    $this->_generate_token($controller);
	}
	
	function _clean_expired_tokens()
	{
	    $tokens = $this->_get_tokens();
	    
	    $now = time();
		foreach($tokens as $nonce => $expires)
		{
			if ($expires < $now)
			{
				unset($tokens[$nonce]);
			}
		}
		
		$this->Session->write('_AjaxTokens', $tokens);
	}
	
	/**
	 * Generate a new token whenever an Ajax request is sent
	 * -> necessary when the same page is used to makes many Ajax request without being refreshed
	 *
	 * @param AppController $controller
	 */
	function _generate_token($controller)
	{
	    if(
	        (
	        $controller->request->params['plugin'] == $this->ajax_security_url['plugin']
	        &&
	        !isset($controller->request->params['prefix'])
	        &&
	        $controller->request->params['controller'] == $this->ajax_security_url['controller']
	        &&
	        $controller->request->params['action'] == $this->ajax_security_url['action']
	        )
	        ||
	        $controller->request->is('ajax') && $controller->request->is('post'))
	    {
	        $ajax_tokens = $this->_get_tokens();
	        $new_token   = Security::generateAuthKey();
	        
	        $ajax_tokens[$new_token] = strtotime($this->csrfExpires);
	        
	        $this->Session->write('_AjaxTokens', $ajax_tokens);
            
            $this->AlaxosCookie->write('csrftoken', $new_token, false);
	    }
	}
	
	function _get_tokens()
	{
	    if(!$this->Session->check('_AjaxTokens'))
        {
            $this->Session->write('_AjaxTokens', array());
        }
        
        return $this->Session->read('_AjaxTokens');
	}
	
	function _validate_ajax_csrf($controller)
	{
	    if($controller->request->is('ajax') && $controller->request->is('post'))
	    {
    		$csrf_ajax_token = $controller->request->header('X-CSRFToken');
            
    		//DEBUG: decommenting the following line should prevent Ajax POST requests to work
    		//$csrf_ajax_token .= 'stop';
    		
            if(!empty($csrf_ajax_token))
            {
                $tokens = $this->_get_tokens();
                
                if(isset($tokens[$csrf_ajax_token]))
                {
                    /*
                     * Delete the currently used token from valid tokens
                     */
                    unset($tokens[$csrf_ajax_token]);
                    $this->Session->write('_AjaxTokens', $tokens);
                    
                    return true;
                }
            }
            
            return false;
	    }
	    else
	    {
	        return true;
	    }
	}
	
	function _blackhole($controller, $error)
	{
	    if(isset($controller->Security))
	    {
	        $controller->Security->blackHole($controller, $error);
	    }
	    else
	    {
	        $error_text = __d('alaxos', 'The request has been considered as not secure and thus has been black-holed');
	        
	        $errors = array('errors' => array());
            $errors['errors'][] = $error_text;
            
            header('HTTP/1.1 403 Forbidden');
            header('Content-Type: application/json');
            echo json_encode($errors);
            die();
	    }
	}
	
}