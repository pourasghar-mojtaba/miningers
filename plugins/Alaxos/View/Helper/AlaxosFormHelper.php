<?php
/**
 *
 * @author   Nicolas Rod <nico@alaxos.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.alaxos.ch
 */
class AlaxosFormHelper extends FormHelper
{
    const START_PREFIX 	= 'start__';
	const END_PREFIX 	= 'end__';
	const DATE_PREFIX   = '__Date__';
	const TIME_PREFIX   = '__Time__';
	
    /**
     * Other helpers used by AlaxosFormHelper
     *
     * @var array
     * @access public
     */
	var $helpers = array('Html', 'Alaxos.AlaxosHtml');
	
	/******************************************************************************************/
	/* Filters fields */
	
	/**
	 * Create an input field that can be used as a filter at the top of a data table.
	 * Depending on the field type, the generated filter may be composed of two input fields that can be used
	 * to make a from-to search.
	 *
	 * $options:
	 * 			See FormHelper->input() for available options
	 * 			+
	 * 			'start_field' 	=> array of options for the first input field when from-to input boxes are used
	 * 			'end_field'		=> array of options for the second input field when from-to input boxes are used
	 *
	 * @param string $fieldname
	 * @param array $options
	 */
	function filter_field($fieldname, $options = array())
	{
	    /*
	     * Set default filter width to 95% of the container
	     * Should be OK in most cases when the filter is included at the top of a table column
	     */
	    $options['style'] = array_key_exists('style', $options) ? $options['style'] : 'width:95%;';
	    
	    /*
	     * Determine the field type in order to know what kind of filter must be shown
	     */
	    $fieldtype = !empty($options['type']) ? $options['type'] : null;
	    unset($options['type']);
	    
	    if(!isset($fieldtype))
	    {
    	    $fieldtype = $this->get_fieldtype($fieldname);
    	    
    	    if(empty($fieldtype))
    	    {
    	        $fieldtype = 'string';
    	    }
	    }
	    
	    /*
	     * Check if the fieldname is a foreign key
	     * If this is the case, build a select filter instead of a number filter
	     */
	    if (preg_match('/_id$/', $fieldname))
	    {
	        /*
	         * If this is a foreign key, and no options list is given as parameters, look for a corresponding variable in the view containing the linked model elements.
	         * If such a list is found, use it to build a select filter
	         * If such a list is not found, print a standard filter
	         */
	        if(empty($options['options']))
	        {
    	    	/*
    	         * Look for an existing View variable whose name correspond to the fk model to build the select input
    	         *
    	         * e.g.:   fieldname = role_id ---> look for a variable called $roles
    	         */
	            $varName = Inflector :: variable(Inflector :: pluralize(preg_replace('/_id$/', '', $fieldname)));
                
//	            debug($varName);
//	            debug($this->_View->viewVars);
	            
	            if(stripos($varName, '.') !== false)
                {
                    $varName = substr($varName, stripos($varName, '.') + 1);
                }
	            
    			if(array_key_exists($varName, $this->_View->viewVars))
    			{
    			    /*
    			     * A corresponding variable was found -> use it to fill the select list
    			     */
    			    $options['options'] = $this->_View->viewVars[$varName];
    			    $fieldtype = 'select';
    			}
	        }
	        elseif(is_array($options['options']))
	        {
	            $fieldtype = 'select';
	        }
	    }
			
	    switch($fieldtype)
	    {
	        case 'select':
	            return $this->filter_select($fieldname, $options);
	            break;
	            
	        case 'number':
	        case 'integer':
	        case 'numeric':
	        case 'long':
	        case 'float':
	        case 'int':
	            return $this->filter_number($fieldname, $options);
	            break;
	        
	        case 'date':
	            return $this->filter_date($fieldname, $options);
	            break;
	        case 'datetime':
	            return $this->filter_datetime($fieldname, $options);
	            break;
	            
	        case 'boolean':
	            return $this->filter_boolean($fieldname, $options);
	            break;
	        
	        case 'varchar':
	        case 'text':
	        case 'string':
	        default:
	            return $this->filter_text($fieldname, $options);
	            break;
	    }
	}

	/**
	 * Create a filter made of two input fields that allow to only enter numbers.
	 * These fields can be used as a from-to search filter.
	 *
	 * $options:
	 * 			See FormHelper->text() for available options
	 * 			+
	 * 			'start_field' 	=> array of options for the first input field
	 * 			'end_field'		=> array of options for the second input field
	 *
	 * @param string $fieldname
	 * @param array $options
	 */
	function filter_number($fieldname, $options = array())
	{
	    $start_field_options = array_key_exists('start_field', $options) ? $options['start_field'] : array();
	    $end_field_options   = array_key_exists('end_field',   $options) ? $options['end_field']   : array();
	    
	    if(!array_key_exists('style',   $start_field_options))
	    {
	        $start_field_options['style'] = 'width:100%';
	    }
	    
	    if(!array_key_exists('style',   $end_field_options))
	    {
	        $end_field_options['style'] = 'width:100%';
	    }
	    
	    $start_fieldname = $this->get_start_fieldname($fieldname);
	    $end_fieldname   = $this->get_end_fieldname($fieldname);
	    
	    $cells   = array();
        $cells[] = $this->input_number($start_fieldname, $start_field_options);
        $cells[] = $this->input_number($end_fieldname, $end_field_options);
		
		return $this->AlaxosHtml->formatTable($cells, 1, null, array('style' => 'width:100%;'));
	}

	/**
	 * Create a filter made of two input fields linked to a datepicker.
	 * These fields can be used as a from-to search filter.
	 *
	 * $options:
	 * 			See FormHelper->text() for available options
	 * 			+
	 * 			'start_field' 	=> array of options for the first input field
	 * 			'end_field'		=> array of options for the second input field
	 *
	 * @param string $fieldname
	 * @param array $options
	 */
	function filter_date($fieldname, $options = array())
	{
	    $start_field_options = array_key_exists('start_field', $options) ? $options['start_field'] : array();
	    $end_field_options   = array_key_exists('end_field',   $options) ? $options['end_field']   : array();
	    
	    $start_fieldname = $this->get_start_fieldname($fieldname);
	    $end_fieldname   = $this->get_end_fieldname($fieldname);
	    
	    $start_field_options['datepicker_upper_date_datepicker'] = $end_fieldname;
	    
	    $cells   = array();
        $cells[] = $this->input_date($start_fieldname, $start_field_options);
        $cells[] = $this->input_date($end_fieldname, $end_field_options);
		
		return $this->AlaxosHtml->formatTable($cells, 1, array('style' => 'white-space:nowrap;'));
	}
	
	function filter_datetime($fieldname, $options = array())
	{
	    $start_field_options = array_key_exists('start_field', $options) ? $options['start_field'] : array();
	    $end_field_options   = array_key_exists('end_field',   $options) ? $options['end_field']   : array();
	    
	    $start_fieldname = $this->get_start_fieldname($fieldname);
	    $end_fieldname   = $this->get_end_fieldname($fieldname);
	    
	    $start_field_options['datepicker_upper_date_datepicker'] = $end_fieldname;
	    
	    $cells   = array();
        $cells[] = $this->input_datetime($start_fieldname, $start_field_options);
        $cells[] = $this->input_datetime($end_fieldname, $end_field_options);
		
		return $this->AlaxosHtml->formatTable($cells, 1, array('style' => 'white-space:nowrap;'));
	}
	
	/**
	 * Create a text field that can be used as a filter.
	 * The method is only a wrapper for the text() method.
	 *
	 * $options:
	 * 			See FormHelper->text() for available options
	 *
	 * @param string $fieldname
	 * @param array $options
	 */
	function filter_text($fieldname, $options = array())
	{
	    return $this->text($fieldname, $options);
	}
	
	/**
	 * Create a dropdown list containing 'yes' and 'no' values that can be used as a filter.
	 * List options may be overriden in the $options array.
	 *
	 * $options:
	 * 			See FormHelper->select() for available options
	 *
	 * @param string $fieldname
	 * @param array $options
	 */
	function filter_boolean($fieldname, $attributes = array())
	{
	    if(empty($attributes['options']))
	    {
	        $attributes['options'] = array('1' => __d('Alaxos', 'yes'), '0' => __d('Alaxos', 'no'));
	    }
	    
	    return $this->filter_select($fieldname, $attributes);
	}
	
	/**
	 * Create a dropdown list that can be used as a filter.
	 * If not explicitly given, list options may be automatically found according to the fieldname value.
	 *
	 * $options:
	 * 			See FormHelper->select() for available options
	 * 			or  FormHelper->radio() if $options['type'] is 'radio'
	 *
	 * @param string $fieldname
	 * @param array $options
	 */
	function filter_select($fieldname, $attributes = array())
	{
	    if(empty($attributes['options']))
	    {
	        /*
	         * Look for an existing View variable whose name correspond to the fk model to build the select input
	         *
	         * e.g.:   fieldname = role_id ---> look for a variable called $roles
	         */
			$varName = Inflector :: variable(Inflector :: pluralize(preg_replace('/_id$/', '', $fieldname)));
                                			
			if(array_key_exists($varName, $this->_View->viewVars))
			{
			    /*
			     * A corresponding variable was found -> use it to fill the select list
			     */
			    $options['options'] = $this->_View->viewVars[$varName];
			}
	    }
	    else
	    {
	        $options = $attributes['options'];
            unset($attributes['options']);
	    }
	    
	    $attributes['empty'] = !empty($attributes['empty'])    ? $attributes['empty']    : true;
	    
	    if (isset($attributes['type']) && $attributes['type'] === 'radio')
		{
			return $this->radio($fieldname, $options, $attributes);
		}
		else
		{
		    return $this->select($fieldname, $options, $attributes);
		}
	}
	
	/**
	 * Override of FormHelper::create() to get rid of any eventual filter passed in URL parameters
	 * in order to reset it when a new search is done by submitting the form
	 *
	 * @see FormHelper::create()
	 */
	function create($model = null, $options = array())
	{
		if(array_key_exists('url', $options) && is_array($options['url']) && array_key_exists('filter', $options['url']))
	    {
	        unset($options['url']['filter']);
	    }
	    
	    return parent::create($model, $options);
	}
	
	/******************************************************************************************/
	/* Simple fields */
	
	/**
	 * Override of FormHelper::input() to be able to call automatically input_date() or input_number()
	 * when the field is of the corresponfing type
	 *
	 * @see cake/libs/view/helpers/FormHelper::input()
	 */
	public function input($fieldname, $options = array())
	{
	    $this->setEntity($fieldname);
	    
		/*
	     * Determine the field type in order to know if a special input must be shown
	     */
	    $fieldtype = !empty($options['type']) ? $options['type'] : null;
	    
	    if(!isset($fieldtype))
	    {
    	    $fieldtype = $this->get_fieldtype($fieldname);
    	    
    	    if(empty($fieldtype))
    	    {
    	        $fieldtype = 'string';
    	    }
	    }
	    
	    if($fieldtype == 'number' || $fieldtype == 'numeric' || $fieldtype == 'float' || $fieldtype == 'long')
	    {
	        $options['integer'] = false;
	    }
	    
	    if (preg_match('/_id$/', $fieldname) || (array_key_exists($this->model(), $this->fieldset) && $fieldname == $this->fieldset[$this->model()]['key']))
	    {
	    	/*
	    	 * In case of foreign key, use the standard parent :: input() function
	    	 * as it will print a select box
	    	 *
	    	 * In case of primaryKey, use the standard parent :: input() function
	    	 * as it will print a hidden field
	    	 */
	    	$fieldtype = 'default';
	    }
	    
	    switch($fieldtype)
	    {
	        case 'date':
	            unset($options['type']);
	            return $this->get_start_div($fieldname, $options) . $this->_get_label($fieldname, $options) . $this->input_date($fieldname, $options) . $this->get_end_div($fieldname, $options);
	            break;
	        case 'datetime':
	            unset($options['type']);
	            return $this->get_start_div($fieldname, $options) . $this->_get_label($fieldname, $options) . $this->input_datetime($fieldname, $options) . $this->get_end_div($fieldname, $options);
	            break;
	        
	        case 'number':
	        case 'integer':
	        case 'numeric':
	        case 'long':
	        case 'float':
	        case 'int':
	            unset($options['type']);
	            return $this->get_start_div($fieldname, $options) . $this->_get_label($fieldname, $options) . $this->input_number($fieldname, $options) . $this->get_end_div($fieldname, $options);
	            break;
	            
	        default:
	            return parent :: input($fieldname, $options);
	            break;
	    }
	}
	
	/**
	 * Return a text field that allow to enter only numbers.
	 * Depending on the numeric type, the field may only allow to enter an integer.
	 *
	 * @param string $fieldname
	 * @param array $options
	 */
	public function input_number($fieldname, $options = array())
	{
	    $this->AlaxosHtml->include_js_alaxos();
	    
	    $numeric_class = !array_key_exists('integer', $options) || $options['integer'] == true ? 'inputInteger' : 'inputFloat';
	    
	    if(!array_key_exists('class', $options))
		{
		    $options = array_merge($options, array('class' => $numeric_class));
		}
		else
		{
		    $class = $options['class'];
		    unset($options['class']);
		    $options = array_merge($options, array('class' => $numeric_class . ' ' . $class));
		}
		
		$input_field = $this->text($fieldname, $options);
		$input_field .= $this->error($fieldname);
		//$input_field .= $this->_get_validation_error_zone($this->_get_validation_error($fieldname));
		
		return $input_field;
	}
	
	/**
	 * Return a text field linked to a datepicker (see frequency-decoder.com)
	 * The date format is set according to the current locale that may have been set with the DateTool :: set_current_locale() method.
	 *
	 * When the date is entered manually in the textbox, the date is automatically completed when the textbox loose focus
	 * ex: 	'10.08'	=> automatically completed to '10.08.2010'
	 * 		'10'	=> automatically completed to '10.08.2010'
	 *
	 * @param string $fieldname
	 * @param array $options
	 */
	public function input_date($fieldname, $options = array())
	{
	    $this->AlaxosHtml->include_js_datepicker();
	    $this->AlaxosHtml->include_js_jquery();
	    $this->AlaxosHtml->include_js_jquery_no_conflict();
	    $this->AlaxosHtml->include_js_alaxos();
	    
	    /*
	     * include CSS needed to show the datepicker
	     */
	    $this->AlaxosHtml->include_css_datepicker();
	    
	    /*
	     * Set the class attribute needed to display the datepicker
	     * The class has to be retrieved from a PHP function, because it depends on the current locale
	     */
	    if(!array_key_exists('class', $options))
		{
			$options = array_merge($options, array('class' => 'inputDate'));
		}
		
		if(!array_key_exists('value', $options))
		{
		    $options = $this->value($options, $fieldname);
		    $options['value'] = DateTool :: sql_to_date($options['value']);
		}
		
	    if(!array_key_exists('maxlength', $options))
		{
		    $options['maxlength'] = '10';
		}
		
		$input_field = $this->text($fieldname, $options);
		$input_field .= $this->error($fieldname);
		//$input_field .= $this->_get_validation_error_zone($this->_get_validation_error($fieldname));
		
		/*****************************
		 * datepicker javascript code
		 */
		$script_block = $this->get_datepicker_js_block($fieldname, $options);
		
		return $input_field . $script_block;
	}
	
	public function input_datetime($fieldname, $options = array())
	{
	    $this->AlaxosHtml->include_js_datepicker();
	    $this->AlaxosHtml->include_js_jquery();
	    $this->AlaxosHtml->include_js_jquery_no_conflict();
	    $this->AlaxosHtml->include_js_alaxos();
	    
	    $date_options = $options;
		$time_options = $options;
		
		if(!array_key_exists('value', $options))
		{
		    $options = $this->value($options, $fieldname);
		}
		
		$date_options['value'] = DateTool :: sql_to_date($options['value'], null, false);
		
		/*
		 * check if a datetime is given. If only a date is given, do not set any time
		 *
		 * Note:
		 * 		without this test, 00:00:00 would be used for the time
		 */
		if(strlen($options['value']) > 10)
		{
		    $time_options['value'] = DateTool :: sql_to_time($options['value']);
		}
		
		$options['value']      = DateTool :: sql_to_date($options['value']);
		
		$show_seconds = true;
		if(!isset($time_options['show_seconds']) || !$time_options['show_seconds'])
		{
		    if(isset($time_options['value']))
		    {
		        $time_options['value'] = substr($time_options['value'], 0, 5);
		    }
		    $show_seconds = false;
		}
		
	    /*
	     * include CSS needed to show the datepicker
	     */
	    $this->AlaxosHtml->include_css_datepicker();
	    
	    /*
	     * Set the class attribute needed to display the datepicker
	     * The class has to be retrieved from a PHP function, because it depends on the current locale
	     */
	    if(!array_key_exists('class', $date_options))
		{
			$date_options = array_merge($date_options, array('class' => 'inputDate inputDatetimeD'));
		}
		
	    if(!array_key_exists('maxlength', $date_options))
		{
		    $date_options['maxlength'] = '10';
		}
		
		if(!array_key_exists('class', $time_options))
		{
		    if($show_seconds)
		    {
		        $time_options = array_merge($time_options, array('class' => 'inputTimeHms'));
		    }
		    else
		    {
		        $time_options = array_merge($time_options, array('class' => 'inputTimeHm'));
		    }
		}
		
		if(!array_key_exists('maxlength', $time_options))
		{
		    if($show_seconds)
		    {
		        $time_options['maxlength'] = '8';
		    }
		    else
		    {
		        $time_options['maxlength'] = '5';
		    }
		}
		
		$input_field = $this->text(AlaxosFormHelper :: DATE_PREFIX . $fieldname, $date_options);
		
		if(!isset($time_options['time_separator']))
		{
		    /*
		     * Note: 'class' => 'span_input_time' allows to force the color of the label in CSS when the field is contained in a .error CSS div
		     */
		    $input_field .= $this->label(AlaxosFormHelper :: TIME_PREFIX . $fieldname, __('time'), array('style' => 'margin-left:15px;', 'class' => 'span_input_time')) . ' ';
		}
		else
		{
		    $input_field .= $time_options['time_separator'];
		}
		
		$input_field .= $this->text(AlaxosFormHelper :: TIME_PREFIX . $fieldname, $time_options);
		$input_field .= $this->hidden($fieldname, array('value' => $options['value'], 'secure' => false));
		$input_field .= $this->error($fieldname);
		
		/*
		 * Prevent hidden field value to be checked by the SecurityComponent
		 *
		 * Note:
		 * 		Check with both $fieldname and $fieldname prefixed by the current Model scope as the field is stored with the model name,
		 * 		so this is necessary if $fieldname is given is given without prefix
		 *
		 * Note 2:
		 * 		Adding 'secure' => false in the call of $this->hidden(...) is not enough (always or sometimes ?).
		 * 		unlockField() also add the field to $this->_unlockedFields which is used when the form is closed
		 * 		to submit the list of fields that are not secured
		 */
		if(stripos($fieldname, '.') !== false)
		{
		    $this->unlockField($fieldname);
		}
		else
		{
		    $this->unlockField($this->_modelScope . '.' . $fieldname);
		}
		
		/*****************************
		 * datepicker javascript code
		 */
		if(isset($options['datepicker_upper_date_datepicker']))
		{
		    $options['datepicker_upper_date_datepicker'] = AlaxosFormHelper :: DATE_PREFIX . $options['datepicker_upper_date_datepicker'];
		}
		$script_block = $this->get_datepicker_js_block(AlaxosFormHelper :: DATE_PREFIX . $fieldname, $options);
		
		return $input_field . $script_block;
	}
	
	public function input_time($fieldname, $options = array())
	{
	    $this->AlaxosHtml->include_js_jquery();
	    $this->AlaxosHtml->include_js_jquery_no_conflict();
	    $this->AlaxosHtml->include_js_alaxos();
	    
	    if(!array_key_exists('value', $options))
	    {
	        $options = $this->value($options, $fieldname);
	    }
	    
	    $options['value'] = DateTool :: sql_to_time($options['value']);
	    
	    $show_seconds = true;
	    if(!isset($options['show_seconds']) || !$options['show_seconds'])
	    {
	        if(isset($options['value']))
	        {
	            $options['value'] = substr($options['value'], 0, 5);
	        }
	        $show_seconds = false;
	    }
	    
	    if(!array_key_exists('class', $options))
	    {
	        if($show_seconds)
	        {
	            $options = array_merge($options, array('class' => 'inputTHms'));
	        }
	        else
	        {
	            $options = array_merge($options, array('class' => 'inputTHm'));
	        }
	    }
	    
	    if(!array_key_exists('maxlength', $options))
	    {
	        if($show_seconds)
	        {
	            $options['maxlength'] = '8';
	        }
	        else
	        {
	            $options['maxlength'] = '5';
	        }
	    }
	    
	    $input_field = $this->text($fieldname, $options);
	    $input_field .= $this->error($fieldname);
	    
	    return $input_field;
	}
	
	/**
	 * Wrapper method for the FormHelper->select() method called automatically with 'yes' and 'no' options
	 *
	 * @param string $fieldname
	 * @param array $options
	 * @param array $attributes
	 */
	public function input_yes_no($fieldname, $options = array(), $attributes = array())
	{
	    if(empty($options))
	    {
	        $options = array('1' => __d('Alaxos', 'yes'), '0' => __d('Alaxos', 'no'));
	    }
	    
	    return $this->select($fieldname, $options, $attributes);
	}
	
	/**
	 * Wrapper method for the FormHelper->select() method called automatically with 'true' and 'false' options
	 *
	 * @param string $fieldname
	 * @param array $options
	 * @param array $attributes
	 */
	public function input_true_false($fieldname, $options = array(), $attributes = array())
	{
	    if(empty($options))
	    {
	        $options = array('1' => __d('Alaxos', 'true'), '0' => __d('Alaxos', 'false'));
	    }
	    
	    return $this->select($fieldname, $options, $attributes);
	}
	
	public function textarea($fieldName, $options = array())
	{
	    $options['autosize'] = isset($options['autosize']) ? $options['autosize'] : true;
	    
	    $script_block = null;
	    if(isset($options['autosize']) && $options['autosize'])
	    {
	        $this->AlaxosHtml->include_js_textarea_autosize();
	        
	        if(isset($options['id']))
	        {
	            $dom_id = $options['id'];
	        }
	        else
	        {
	            $dom_id = $this->domId($fieldName);
	        }
	        
	        $script  = '$j(document).ready(function(){' . "\n";
	        $script .= '  if(typeof($j("#' . $dom_id . '").autosize) != "undefined"){';
	        $script .= '    $j("#' . $dom_id . '").autosize();' . "\n";
	        $script .= '  }';
	        $script .= '});' . "\n";
	        
	        $script_block = $this->AlaxosHtml->scriptBlock($script);
	        
	        unset($options['autosize']);
	    }
	    
	    return parent :: textarea($fieldName, $options) . $script_block;
	}
	
	/******************************************************************************************/
	/* Special fields */
	
	/**
	 * Return a dropdown list filled with actions that can be performed on the selected elements of a datat list
	 * It also automatically set the needed translated Javascript variables.
	 *
	 * @param string $fieldName
	 * @param array $options
	 */
	public function input_actions_list($fieldName = '_Tech.action', $options = array())
	{
	    $deleteAll_action = (isset($this->request->params['prefix']) && !empty($this->request->params['prefix'])) ? $this->request->params['prefix'] . '_deleteAll' : 'deleteAll';
	    
	    $options['id'] = !empty($options['id'])      ? $options['id']      : 'ActionToPerform';
	    $actions       = !empty($options['actions']) ? $options['actions'] : array($deleteAll_action => ___d('Alaxos', 'delete all'));
	    
	    /*
	     * Include translated texts for JS confirm box
	     */
	    $script  = 'var confirmDeleteAllText =            "' . ___d('Alaxos', 'are you sure you want to delete all those items ?') . '";' . "\n";
	    $script .= 'var pleaseChooseActionToPerformText = "' . ___d('Alaxos', 'please choose the action to perform') . '";' . "\n";
	    $script .= 'var pleaseSelectAtLeastOneItem = "' . ___d('Alaxos', 'please select at least one item') . '";' . "\n";
	    
	    $this->AlaxosHtml->scriptBlock($script, array('inline' => false));
	    
	    unset($options['actions']);
	    
	    return $this->select($fieldName, $actions, $options);
	}
	
	/**
	 * Return a dropdown list that can be used to change the number of records displayed in a paginated list of records
	 *
	 * Note: use the AlaxosPaginatorComponent to have the number of records to display stored during session
	 *
	 * @param string $fieldname
	 * @param array $options
	 */
	public function input_pagination_limit($fieldname = '_Tech.pagination_limit', $options = array())
	{
	    $default_options = array('numbers'           => array(10 => 10, 20 => 20, 50 => 50, 100 => 100),
	                             'id'                => 'select_pagination_limit',
	                             'empty'             => false,
	                             'label'             => ___d('alaxos', 'show %s items per page'),
	                             'auto_refresh_page' => true,
	                             'model'             => $this->model());
	    
	    if(!isset($options['model']))
	    {
    		/*
             * modelScope may not be initialized (probably if we are not in a form)
             * -> we take the first model in request after having checked it is paginated
             */
            if(empty($options['model']))
            {
                $first_model = array_shift(array_keys($this->request->params['models']));
                
                if(isset($this->request->params['paging'][$first_model]))
                {
                    $default_options['model'] = $first_model;
                }
            }
	    }
	    
	    $options = array_merge($default_options, $options);
	    
	    if(stripos($fieldname, '.') !== false)
        {
            $parts      = explode('.', $fieldname);
            $tech_model = $parts[0];
            $fieldname  = $parts[count($parts) - 1];
        }
        else
        {
            $tech_model = '_Tech';
        }
	    
	    if(isset($this->request->params['paging'][$options['model']]['limit']))
	    {
    	    $this->request->data[$tech_model][$fieldname] = $this->request->params['paging'][$options['model']]['limit'];
	    }
	    
	    $select = $this->select('_Tech.pagination_limit', $options['numbers'], $options);
	    
	    if(!empty($options['label']))
	    {
	        $select =  sprintf($options['label'], $select);
	    }
	    
	    if($options['auto_refresh_page'])
	    {
	        $this->AlaxosHtml->include_js_jquery_no_conflict();
	        
	        $select.= $this->get_auto_refresh_js_block($fieldname, $options);
	    }
	    
	    return $select;
	}
	
	/******************************************************************************************/
	
    function get_datepicker_js_block($fieldname, $options = array())
    {
        if(!isset($options['id']))
        {
            $dom_id = $this->domId($fieldname);
        }
        else
        {
            $dom_id = $options['id'];
        }
		
		$datepicker_options = array();
		$datepicker_options['formElements'] = array($dom_id => '%d.%m.%Y');
		
		if(isset($options['datepicker_rangeLow']))
		{
		    $datepicker_options['rangeLow'] = $options['datepicker_rangeLow'];
		}
		
		if(isset($options['datepicker_rangeHigh']))
		{
		    $datepicker_options['rangeHigh'] = $options['datepicker_rangeHigh'];
		}
		
		if(isset($options['callbackFunctions']))
		{
		    $datepicker_options['callbackFunctions'] = $options['callbackFunctions'];
		}
		
		if(isset($options['datepicker_upper_date_datepicker']))
		{
		    $upper_datefield_id = $this->domId($options['datepicker_upper_date_datepicker']);
		    
		    if(!isset($datepicker_options['callbackFunctions']))
		    {
		        $datepicker_options['callbackFunctions'] = array();
		    }
		    
		    if(!isset($datepicker_options['callbackFunctions']['dateset']))
		    {
		        $datepicker_options['callbackFunctions']['dateset'] = '[set_min_upper_date_' . $upper_datefield_id . ']';
		    }
		    else
		    {
		        $dateset = $datepicker_options['callbackFunctions']['dateset'];
		        $dateset = substr($dateset, 0, (strlen($dateset)-1));
		        $dateset = $dateset . ', set_min_upper_date_' . $upper_datefield_id . ']';
		        
		        $datepicker_options['callbackFunctions']['dateset'] = $dateset;
		    }
		}
		
		$js_dp_opts = 'var opts = {';
		$i = 0;
		foreach($datepicker_options as $k => $v)
		{
		    if(is_array($v))
		    {
		        $value = '{';
		        foreach($v as $sub_k => $sub_v)
		        {
		            if(StringTool::start_with($sub_v, '['))
		            {
		                $value .= '"' . $sub_k . '":' . $sub_v;
		            }
		            else
		            {
		                $value .= '"' . $sub_k . '":"' . $sub_v . '"';
		            }
		        }
		        $value .= '}';
		    }
		    else
		    {
		        $value = '"' . $v . '"';
		    }
		    
		    $js_dp_opts .= $k . ':' . $value;
		    $js_dp_opts .= ($i < count($datepicker_options) - 1) ? ',' : '';
		    
		    $i++;
		}
		$js_dp_opts .= "};";
		
		$script  = '$j(document).ready(function(){' . "\n";
		$script .= '  if(typeof(datePickerController) != "undefined"){' . "\n";
		$script .= '    ' . $js_dp_opts . "\n";
		$script .= '    datePickerController.createDatePicker(opts);' . "\n";
		$script .= '  }' . "\n";
		
		if(isset($options['datepicker_upper_date_datepicker']))
		{
		    $script .= '  ' . "\n";
		    $script .= '  /* required for IE9 at least */' . "\n";
    		$script .= '  $j("#' . $dom_id . '").change(function(){' . "\n";
    		$script .= '    datePickerController.setDateFromInput("' . $dom_id . '");' . "\n";
    		$script .= '    var date1     = datePickerController.getSelectedDate("' . $dom_id . '");' . "\n";
    		$script .= '    var yyyymmdd1 = datePickerController.dateToYYYYMMDDStr(date1);' . "\n";
    		$script .= '    datePickerController.setRangeLow("' . $upper_datefield_id . '", yyyymmdd1);' . "\n";
    		$script .= '  });' . "\n";
    		$script .= '  ' . "\n";
		}
		
        $script .= "});\n";
		
		if(isset($options['datepicker_upper_date_datepicker']))
		{
    		$script .= 'function set_min_upper_date_' . $upper_datefield_id . '(o){' . "\n";
    		$script .= '  if(o != null && o.date != null){' . "\n";
    		$script .= '    var date1     = o.date;' . "\n";
    		$script .= '    var yyyymmdd1 = datePickerController.dateToYYYYMMDDStr(date1);' . "\n";
    		$script .= '    datePickerController.setRangeLow("' . $upper_datefield_id . '", yyyymmdd1);' . "\n";
    		$script .= '    ' . "\n";
    		$script .= '    var date2     = datePickerController.getSelectedDate("' . $upper_datefield_id . '");' . "\n";
    		$script .= '    var yyyymmdd2 = datePickerController.dateToYYYYMMDDStr(date2);' . "\n";
    		$script .= '    ' . "\n";
    		
    		if(isset($options['datepicker_upper_date_autoset']) && $options['datepicker_upper_date_autoset'])
    		{
    		    $script .= '    if(yyyymmdd2 < yyyymmdd1){' . "\n";
        		$script .= '      datePickerController.setSelectedDate("' . $upper_datefield_id . '", yyyymmdd1);';
        		$script .= '    }' . "\n";
    		}
    		else
    		{
        		$script .= '    if(yyyymmdd2 < yyyymmdd1){' . "\n";
        		$script .= '      $j("#' . $upper_datefield_id . '").val("");' . "\n";
        		$script .= '    }' . "\n";
    		}
    		
    		$script .= '  }' . "\n";
    		$script .= '}' . "\n";
		}
		
		return $this->AlaxosHtml->scriptBlock($script);
    }
    
    function get_auto_refresh_js_block($fieldname, $options = array())
    {
        /******/
        
        $url               = array();
        $url['admin']      = isset($this->request->params['admin'])  ? $this->request->params['admin']  : null;
        $url['plugin']     = isset($this->request->params['plugin']) ? $this->request->params['plugin'] : null;
        $url['controller'] = $this->request->params['controller'];
        $url['action']     = $this->request->params['action'];
        foreach($this->request->params['pass'] as $pass)
        {
            $url[] = $pass;
        }
        
        $named_params = $this->request->params['named'];
        if(!array_key_exists('limit', $named_params))
        {
            $named_params['limit'] = 0;
        }
        foreach($named_params as $name => $value)
        {
            $url[$name] = $value;
        }
        
        /******/
        
        if(isset($options['id']))
        {
            $dom_id = $options['id'];
        }
        else
        {
            $dom_id = $this->domId($fieldname);
        }
 
        $script   = array();
        $script[] = '$j(document).ready(function(){';
        $script[] = '  $j("#' . $dom_id . '").change(function(){';
        $script[] = '    var url = "' . Router::url($url, true) . '".replace(/\/limit:[0-9]+/, "");';
        $script[] = '    url = url + "/limit:" + $j(this).val();';
        $script[] = '    document.location = url;';
        $script[] = '  });';
        $script[] = '});';
        
        $script = implode("\n", $script);
        
        return $this->AlaxosHtml->scriptBlock($script);
    }
    
    /******************************************************************************************/
    
    /**
     * Return the type of a field
     *
     * @param string $fieldname
     */
    function get_fieldtype($fieldname)
    {
        if(stripos($fieldname, '.') !== false)
        {
            $parts = explode('.', $fieldname);
            $fieldname = $parts[count($parts) - 1];
        }
        
        $modelKey = $this->model();
        $fieldDef = $this->_introspectModel($modelKey, 'fields', $fieldname);
        
        return $fieldDef['type'];
    }
    
    function is_required($fieldname)
    {
        if(stripos($fieldname, '.') !== false)
        {
            $parts = explode('.', $fieldname);
            $fieldname = $parts[count($parts) - 1];
        }
        
        $modelKey = $this->model();
        return $this->_introspectModel($modelKey, 'validates', $fieldname);
    }
    
    /**
     * Return the fieldname to use for the first field a from-to search filter
     *
     * @param string $fieldname
     */
    function get_start_fieldname($fieldname)
    {
        if(stripos($fieldname, '.') !== false)
	    {
	        $model_name = substr($fieldname, 0, strpos($fieldname, '.'));
	        $field_name = substr($fieldname, strpos($fieldname, '.') + 1);
	        
	        $start_fieldname = $model_name . '.' . AlaxosFormHelper :: START_PREFIX . $field_name;
	    }
	    else
	    {
	        $start_fieldname = AlaxosFormHelper :: START_PREFIX . $fieldname;
	    }
	    
	    return $start_fieldname;
    }
    
    /**
     * Return the fieldname to use for the second field a from-to search filter
     *
     * @param string $fieldname
     */
    function get_end_fieldname($fieldname)
    {
        if(stripos($fieldname, '.') !== false)
	    {
	        $model_name = substr($fieldname, 0, strpos($fieldname, '.'));
	        $field_name = substr($fieldname, strpos($fieldname, '.') + 1);
	        
    	    $end_fieldname   = $model_name . '.' . AlaxosFormHelper :: END_PREFIX   . $field_name;
	    }
	    else
	    {
    	    $end_fieldname   = AlaxosFormHelper :: END_PREFIX   . $fieldname;
	    }
	    
	    return $end_fieldname;
    }
    
//    /**
//     * Return an eventual error message for a field
//     *
//     * @param string $fieldname
//     */
//    function _get_validation_error($fieldname)
//    {
//    	$validationErrors = $this->_View->validationErrors;
//
//        if(stripos($fieldname, '.') !== false)
//	    {
//	        $model_name = substr($fieldname, 0, strpos($fieldname, '.'));
//	        $fieldname = substr($fieldname, strpos($fieldname, '.') + 1);
//	    }
//	    else
//	    {
//    		$model_name = $this->model();
//	    }
//
//		if(isset($validationErrors[$model_name][$fieldname]))
//		{
//		    return $validationErrors[$model_name][$fieldname];
//		}
//		else
//		{
//		    return null;
//		}
//    }
    
//    /**
//     * Return a div containing an error if the given one is not empty
//     *
//     * @param string $error
//     */
//    function _get_validation_error_zone($error)
//    {
//        if(!empty($error))
//        {
//            $error_zone = '<div class="error-message">';
//    	    $error_zone .= $error;
//    	    $error_zone .= '</div>';
//    	    return $error_zone;
//        }
//        else
//        {
//            return false;
//        }
//    }
    
    /**
     * Get the label for the given fieldname
     *
     * @param $fieldname
     * @param $options
     */
    function _get_label($fieldname, $options)
    {
        $label = null;
		if(!isset($options['label']) || $options['label'] !== false)
		{
		    $options['type'] = 'text';
		    
		    if(!isset($options['label']))
		    {
		        $label = $this->_inputLabel($fieldname, null, $options);
		    }
		    else
		    {
		        $label = $this->_inputLabel($fieldname, $options['label'], $options);
		    }
		}
		unset($options['label']);
		
		return $label;
    }
    
    function get_start_div($fieldname, $options)
    {
        $class = '';
		if($this->is_required($fieldname) && (!isset($options['div']) || $options['div'] != false))
		{
		    $class .= 'input required';
		}
		elseif(!isset($options['div']) || $options['div'] != false)
		{
		    $class .= 'input';
		}
		
		$this->setEntity($fieldname);
		$error = $this->tagIsInvalid();
        if($error !== false)
        {
            $class .= ' error';
        }
		
		$start_div = '<div class="' . $class . '">';
		
		return $start_div;
    }
    
    function get_end_div($fieldname, $options)
    {
        if(!isset($options['div']) || $options['div'] != false)
		{
		    return '</div>';
		}
		else
		{
		    return '';
		}
    }
}
?>
