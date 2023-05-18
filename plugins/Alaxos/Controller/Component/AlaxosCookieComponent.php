<?php
App::import('Component', 'Cookie');

class AlaxosCookieComponent extends CookieComponent
{
    /**
     * @var AppController
     */
    private $controller = null;
    
    private $preference_cookie_name     = 'alaxos_pref';
    private $flash_message_cookie_name  = 'alaxos_flash_message';
    private $flash_error_cookie_name    = 'alaxos_flash_error';
    
    private $default_preferences        = array();
    private $cookie_validity_period     = '1 year';
    private $cookie_preferences_secure  = false;
    private $encrypt_preferences_cookie = true;
    
    /********************************************************************************/
    
    public function initialize(&$controller )
	{
	    parent:: initialize($controller);
	    
	    $this->controller = $controller;
	    
	    if(isset($this->settings) && is_array($this->settings))
	    {
	        foreach($this->settings as $k => $v)
	        {
	            $this->{$k} = $v;
	        }
	    }
	    
	    $this->check_flash();
	}
	
	/********************************************************************************/
	
	function set_default_preferences($default_preferences)
	{
	    $this->default_preferences = $default_preferences;
	}
	
	/********************************************************************************/
	
	function init_preferences($default_preferences = null)
	{
	    $preferences = $this->read($this->preference_cookie_name);
	    
	    if(!isset($preferences))
	    {
	        if(!isset($default_preferences))
	        {
	            $default_preferences = $this->default_preferences;
	        }
	        
	        $this->save_preferences($default_preferences);
	    }
	}
	
	function get_preferences()
	{
	    $this->init_preferences();
	    
	    $preferences = $this->read($this->preference_cookie_name);
	    
	    if(!empty($preferences))
	    {
	        return unserialize($preferences);
	    }
	    else
	    {
	        return null;
	    }
	}
	
	function check_preference($name)
	{
	    $preference = $this->get_preference($name);
	    
	    return (isset($preference) && !empty($preference));
	}
	
	function get_preference($name)
	{
	    $preferences = $this->get_preferences();
	    
	    return (isset($preferences[$name]) ? $preferences[$name] : null);
	}
	
	function save_preferences($preferences)
	{
	    if(is_array($preferences))
	    {
    	    $preferences = serialize($preferences);
    	    
    	    $this->secure = $this->cookie_preferences_secure;
    	    $this->write($this->preference_cookie_name, $preferences, $this->encrypt_preferences_cookie, $this->cookie_validity_period);
    	    
    	    return true;
	    }
	    else
	    {
	        return false;
	    }
	}
	
	function save_preference($name, $value)
	{
	    $preferences        = $this->get_preferences();
	    $preferences[$name] = $value;
	    
	    return $this->save_preferences($preferences);
	}
	
	function extend_preferences_life_time()
	{
	    $preferences = $this->get_preferences();
	    
	    return $this->save_preferences($preferences);
	}
	
	/********************************************************************************/
    
	function flash_message($message, $secure = false)
	{
	    $this->secure = $secure;
	    $this->write($this->flash_message_cookie_name, $message);
	}
	function check_flash_message()
	{
	    $flash_message = $this->read($this->flash_message_cookie_name);
	    if(!empty($flash_message))
	    {
	        if(isset($this->controller->Session))
	        {
	            $this->controller->Session->setFlash($flash_message, 'flash_message', array('plugin' => 'alaxos'));
	        }
	        
	        $this->delete($this->flash_message_cookie_name);
	    }
	}
	
	function flash_error($error, $secure = false)
	{
	    $this->secure = $secure;
	    $this->write($this->flash_error_cookie_name, $error);
	}
	function check_flash_error()
	{
	    $flash_error = $this->read($this->flash_error_cookie_name);
	    if(!empty($flash_error))
	    {
	        if(isset($this->controller->Session))
	        {
	            $this->controller->Session->setFlash($flash_error, 'flash_error', array('plugin' => 'alaxos'));
	        }
	        
	        $this->delete($this->flash_error_cookie_name);
	    }
	}
	
	function check_flash()
	{
	    $this->check_flash_message();
	    $this->check_flash_error();
	}
}