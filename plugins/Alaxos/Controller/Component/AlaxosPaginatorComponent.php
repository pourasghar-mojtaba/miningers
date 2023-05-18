<?php
class AlaxosPaginatorComponent extends Component
{
    /**
     * @var AppController
     */
    var $controller = null;
    
    /**
     * Other Components this component uses.
     *
     * @var array
     */
    public $components = array('Session');
    
    /**
     * @var string
     */
    public $limit_session_key = 'alaxos_pagination_limit';
    
    /****************************************************************************************/
    
    public function initialize(&$controller)
	{
	    $this->controller = $controller;
	    
	    $this->limit_session_key = $this->controller->request->params['plugin'] . '_' . $this->controller->request->params['controller'] . '_' . $this->controller->request->params['action'];
	    
	    if(!isset($this->settings['limit']))
	    {
	        $this->settings['limit'] = 20;
	    }
	    
	    $paginator = $this->controller->Components->load('Paginator');
	    $paginator->settings['limit'] = $this->get_pagination_limit();
	}

	/****************************************************************************************/
    
	/**
	 * Return a number of records that must be displayed per page when records are paginated
	 *
	 */
    function get_pagination_limit()
    {
        $limit = null;
        
        if(isset($this->controller->request->params['named']['limit']))
		{
		    $limit = $this->controller->request->params['named']['limit'];
		    
		    $this->Session->write($this->limit_session_key, $limit);
		}
        elseif($this->Session->check($this->limit_session_key))
        {
            $limit = $this->Session->read($this->limit_session_key);
        }
        elseif(isset($this->settings['limit']))
        {
            $limit = $this->settings['limit'];
        }
        
        /*
         * With many rows, there are chances that the SecurityComponent tokens are all used by requests made by each row
         */
        if(isset($this->controller->Security) && $this->controller->Security->csrfUseOnce)
        {
            $this->controller->Security->csrfLimit = (($limit * 2) > 100) ? ($limit * 2) : 100;
            
            $_token = $this->Session->read('_Token');
            
            while(count($_token['csrfTokens']) < $this->controller->Security->csrfLimit)
            {
                $this->controller->Security->generateToken($this->controller->request);
                $_token = $this->Session->read('_Token');
            }
        }
        
		return $limit;
    }
}