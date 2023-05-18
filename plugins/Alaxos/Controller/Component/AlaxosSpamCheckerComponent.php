<?php 

class AlaxosSpamCheckerComponent extends Component
{
    /**
     * @var AppController
     */
    private $controller = null;
    
    /********************************************************************************/
    
    public function initialize(&$controller )
	{
	    parent:: initialize($controller);
	    
	    $this->controller = $controller;
	    
	    $this->disable_spam_field_security_check();
	}

	public function disable_spam_field_security_check()
	{
	    if(isset($this->controller->Security))
	    {
	        $this->controller->Security->unlockedFields[] = $this->get_today_fieldname();
	        $this->controller->Security->unlockedFields[] = $this->get_yesterday_fieldname();
	    }
	}
	
	public function check_request($model_name = null)
	{
	    list($today_fieldname, $yesterday_fieldname) = $this->get_valid_fieldnames();
	    
	    $model_name = isset($model_name) ? $model_name : $this->controller->modelClass;
	    
	    if(isset($this->controller->request->data[$model_name][$today_fieldname]) 
	        || 
	       isset($this->controller->request->data[$model_name][$yesterday_fieldname]))
	    {
	        return true;
	    }
	    else
	    {
	        return false;
	    }
	}
	
	/**
	 * Return an array of accepted fieldnames for the posted values
	 */
	public function get_valid_fieldnames()
	{
	    $today_fieldname     = $this->get_today_fieldname();
	    $yesterday_fieldname = $this->get_yesterday_fieldname();
	    
	    return array($today_fieldname, $yesterday_fieldname);
	}
	
	public function get_today_fieldname()
	{
	    return Security::hash(date('d.M.Y', time()), 'sha1', true);
	}
	
	public function get_yesterday_fieldname()
	{
	    return Security::hash(date('d.M.Y', strtotime(date('Y-m-d')) - 1), 'sha1', true);
	}
}