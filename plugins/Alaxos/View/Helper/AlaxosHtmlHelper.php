<?php
/**
 *
 * @author   Nicolas Rod <nico@alaxos.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.alaxos.ch
 */
class AlaxosHtmlHelper extends HtmlHelper
{
	var $helpers = array('Html', 'Form', 'Javascript');
	
	var $js_included  = array();
	var $css_included = array();
	
	/****************************************************************************/
	
	/**
	 * Returns an html table filled with the cells contents given in the $cellContents array.
	 *
	 * All cells are added from left to right to the table.
	 * A new line is created each time the number of cells per row reaches the $cellPerRow value.
	 *
	 * @param array $cellContents The content of all cells that must be added to the table. Values may be a string or an array.
	 *                            Value as array:
	 *                                           ['content']         =>   the content of the cell
	 *                                           ['cell_options']    =>   array of attributes to add to the cell
	 *                                                                    the 'skip_cell' option may be used to get an empty cell
	 *
	 * @param integer $cellsPerRow The number of cells per line in the table
	 * @param array $cellOptions Array of attributes to add to all <td> tags
	 * @param array $tableOptions Array of attributes to add to the <table> tag
	 */
    function formatTable($cellContents, $cellsPerRow, $cellOptions = null, $tableOptions = null)
    {
    	$cellArray = array();
    	
    	foreach ($cellContents as $cellContent)
    	{
    		$cellArray[] = $this->getCell($cellContent, $cellOptions);
    	}
    	
    	$table_attributes = '';
    	if(isset($tableOptions))
    	{
	    	foreach ($tableOptions as $attribute_name => $attribute_value)
	    	{
	    		$table_attributes .= ' ' . $attribute_name . '="' . $attribute_value . '"';
	    	}
    	}
    	
    	$table = '<table' . $table_attributes . '>';
    	
    	$index = 1;
    	foreach ($cellArray as $htmlCell)
    	{
    		if($index == 1)
    		{
    			$table .= '<tr>';
    		}
    		
    		$table .= $htmlCell;
    		
    		if($index == $cellsPerRow)
    		{
    			$table .= '</tr>';
    			$index = 1;
    		}
    		else
    		{
    			$index++;
    		}
    	}
    	
    	/*
    	 * Complete last empty cells
    	 */
    	if($index != 1)
    	{
    		for($i = $index; $i <= $cellsPerRow; $i++)
    		{
    			$table .= '<td></td>';
    		}
    		
    		$table .= '</tr>';
    	}
    	
    	$table .= '</table>';
    	
    	echo $table;
    }
    
    /**
     * Returns a table cell
     *
     * @param mixed $cellContent The content of the cell as a astring or an array
     *                           Value as array:
	 *                                           ['content']         =>   the content of the cell
	 *                                           ['cell_options']    =>   array of attributes to add to the cell
	 *                                                                    the 'skip_cell' option may be used to get an empty cell
     * @param unknown_type $cellOptions array of attributes to add to the cell
     *                                  used only if $cellContent['cell_options'] is not set
     */
    private function getCell($cellContent, $cellOptions = array())
    {
    	$cell_attributes = '';
    	if(is_array($cellOptions))
    	{
    		if(isset($cellOptions['skip_cell']))
    		{
    			return null;
    		}
    		
	    	foreach ($cellOptions as $attribute_name => $attribute_value)
	    	{
	    		$cell_attributes .= ' ' . $attribute_name . '="' . $attribute_value . '"';
	    	}
    	}
    	
    	$cell_string = null;
    	if(is_array($cellContent))
    	{
    		if(isset($cellContent['content']))
    		{
    			$cell_string = $cellContent['content'];
    		}
    		
    		if(isset($cellContent['cell_options']) && is_array($cellContent['cell_options']))
    		{
	    		if(isset($cellContent['cell_options']['skip_cell']))
	    		{
	    			return null;
	    		}
    			
    			$cell_attributes = '';
		    	foreach ($cellContent['cell_options'] as $attribute_name => $attribute_value)
		    	{
		    		$cell_attributes .= ' ' . $attribute_name . '="' . $attribute_value . '"';
		    	}
    		}
    	}
    	else
    	{
    		$cell_string = $cellContent;
    	}
    	
    	return '<td' . $cell_attributes . '>' . $cell_string . '</td>';
    }
    
    /******************************************************************************************/
	/* Images */
    
    /**
     * This function overrides the HtmlHelper image() function.
     * It provides:
     *
     *  - an automatic image resize if a 'maxWidth' and/or 'maxHeight' value are given in the options
     *    the function tries to determine the physical height and width of the image, in order to prevent enlarging
     *    an image if the given maxWidth and/or maxHeight are larger the image dimensions.
     *
     *  - the replacement of the image by a alternative image set as 'not_found' in the options
     *    if the $path does not point to an existing image
     *
     * @param $path
     * @param $options
     */
	function image($path, $options = array())
	{
	    /*
	     * Parse $path to determine if it is a path in a plugin or not
	     */
		$url    = Router :: parse($path);
		$plugin = empty($url['plugin']) ? null : $url['plugin'];
		
		/*
		 * If the options contains an image path to replace a missing image
		 * -> checks if the requested image exists
		 */
		$file_path = '';
		$pic_path  = '';
		
		if(isset($options) && is_array($options))
		{
		    if(isset($plugin))
		    {
				$plugin_path = App :: pluginPath($plugin);
				$subpath     = str_replace('/' . $plugin, '', $path);
				
		    	if(file_exists($plugin_path . 'webroot/' . $subpath) && is_file($plugin_path . 'webroot/' . $subpath))
        		{
        			$file_path = $plugin_path . 'webroot/' . $subpath;
        		}
        		elseif(isset($options['not_found']))
        		{
        		    $path = $options['not_found'];
        		}
		    }
		    else
		    {
		        if(file_exists(WWW_ROOT . 'img/' . $path) && is_file(WWW_ROOT . 'img/' . $path))
        		{
        			$file_path = WWW_ROOT . 'img/' . $path;
        		}
        		elseif(file_exists(WWW_ROOT . $path) && is_file(WWW_ROOT . $path))
        		{
        			$file_path = WWW_ROOT . $path;
        		}
        		elseif(isset($options['not_found']))
        		{
        		    $path = $options['not_found'];
        		}
		    }
		}
		
		if(strlen($file_path) > 0)
		{
			if(file_exists($file_path))
			{
				$imgInfos 	= getimagesize($file_path);
			}
			
			$width  	= $imgInfos[0];
			$height 	= $imgInfos[1];
			
			if(isset($options['maxWidth']) && isset($options['maxHeight']))
			{
				if($options['maxWidth'] < $width)
				{
					$options['width'] = $options['maxWidth'];
					
					$newHeight = $options['width'] * $height / $width;
					
					if($options['maxHeight'] < $newHeight)
					{
						$options['height'] = $options['maxHeight'];
						unset($options['width']);
					}
				}
				else
				{
					if($options['maxHeight'] < $height)
					{
						$options['height'] = $options['maxHeight'];
					}
				}
			}
			elseif(isset($options['maxWidth']))
			{
				if($options['maxWidth'] < $width)
				{
					$options['width'] = $options['maxWidth'];
				}
			}
			elseif(isset($options['maxHeight']))
			{
				if($options['maxHeight'] < $height)
				{
					$options['height'] = $options['maxHeight'];
				}
			}
			
			if(isset($options['maxHeight']))
			{
				unset($options['maxHeight']);
			}
			
			if(isset($options['maxWidth']))
			{
				unset($options['maxWidth']);
			}
		}
		
		return parent :: image($path, $options);
	}
    
	function spinner($dom_id = 'spinner', $size = 24, $options = array())
	{
	    if($size != 16 && $size != 24)
	    {
	        $size = 24;
	    }
	    
	    $options['id'] = $dom_id;
	    
	    return parent::image('/alaxos/img/ajax/waiting' . $size . '.gif', $options);
	}
	
    /******************************************************************************************/
	/* Text */
    
	/**
	 * Replaces some text codes by corresponding smileys.
	 *
	 * The smileys are not included with the plugin.
	 * To use this method, create a 'smileys' folder in your app/webroot/img folder and place the smileys inside
	 *
	 * @param string $text
	 */
    function replace_smileys($text)
    {
    	$text = str_replace(':-)',      $this->Html->image('smileys/smile.gif'), $text);
    	$text = str_replace(';-)',      $this->Html->image('smileys/wink.gif'), $text);
    	$text = str_replace(':cligne:', $this->Html->image('smileys/wink.gif'), $text);
    	$text = str_replace(':-(',      $this->Html->image('smileys/sad.gif'), $text);
    	$text = str_replace(':haha:',   $this->Html->image('smileys/laugh.gif'), $text);
    	$text = str_replace(':alien:',  $this->Html->image('smileys/alien.gif'), $text);
    	$text = str_replace(':oops:',   $this->Html->image('smileys/embarassed.gif'), $text);
    	$text = str_replace(':grr:',    $this->Html->image('smileys/angry.gif'), $text);
    	$text = str_replace(':bière:',  $this->Html->image('smileys/beer.gif'), $text);
    	$text = str_replace(':beer:',   $this->Html->image('smileys/beer.gif'), $text);
    	$text = str_replace(':-D',      $this->Html->image('smileys/bigsmile.gif'), $text);
    	$text = str_replace(':cool:',   $this->Html->image('smileys/cool.gif'), $text);
    	$text = str_replace(':bravo:',  $this->Html->image('smileys/applause.gif'), $text);
    	$text = str_replace(':zut:',    $this->Html->image('smileys/zut.gif'), $text);
    	$text = str_replace(':no:',     $this->Html->image('smileys/nono.gif'), $text);
    	$text = str_replace(':heu:',    $this->Html->image('smileys/perplexe.gif'), $text);
    	$text = str_replace(':ping:',   $this->Html->image('smileys/ping.gif'), $text);
    	$text = str_replace(':!:',      $this->Html->image('smileys/exclamation.gif'), $text);
    	$text = str_replace(':?:',      $this->Html->image('smileys/question.gif'), $text);
    	$text = str_replace(':quoi!?:', $this->Html->image('smileys/eek.gif'), $text);
    	$text = str_replace(':boing:',  $this->Html->image('smileys/boing.gif'), $text);
    	$text = str_replace(':rougit:', $this->Html->image('smileys/bigblush.gif'), $text);
    	$text = str_replace(':help:',   $this->Html->image('smileys/help.gif'), $text);
    	
    	return $text;
    }

    /**
     * Apply some basic formatting to a text in order to print it out in a webpage
     *
     * It provides:
     * 	- nl2br
     *  - smileys replacement
     *  - auto link URL
     *  - auto link email
     *  - email JS encoding to prevent email harvesting
     *  -
     *
     * @param $text
     * @param $options
     */
	function format_text($text, $options = array())
	{
		if(!array_key_exists('nl2br', $options))
	    {
	        $options['nl2br'] = true;
	    }
	    if(!array_key_exists('smileys', $options))
	    {
	        $options['smileys'] = false;
	    }
	    if(!array_key_exists('encode_url', $options))
	    {
	        $options['link_url'] = true;
	    }
	    if(!array_key_exists('link_email', $options))
	    {
	        $options['link_email'] = true;
	    }
	    if(!array_key_exists('encode_email', $options))
	    {
	        $options['encode_email'] = true;
	    }
	    
	    if($options['link_url'])
		{
            $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
            
            if(preg_match_all($reg_exUrl, $text, $urls))
            {
                foreach($urls[0] as $url)
                {
                    $url  = StringTool::remove_trailing($url, '.');
                    $text = str_replace($url, '<a href="' . $url . '">' . $url . '</a>', $text);
                }
            }
		}
	    
		if($options['nl2br'])
		{
			$text = nl2br($text);
		}
		
		if($options['smileys'])
		{
			$text = $this->replace_smileys($text);
		}
		
		if($options['link_email'])
		{
		    $reg_email = "/[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,3})/";
		    
		    if(preg_match_all($reg_email, $text, $emails))
            {
                foreach($emails[0] as $email)
                {
                    if($options['encode_email'])
                    {
                        $encoded_email = $this->encode_email($email);
                        $text = str_replace($email, $encoded_email, $text);
                    }
                    else
                    {
                        $text = str_replace($email, '<a href="mailto:' . $email . '">' . $email . '</a>', $text);
                    }
                }
            }
		}
		
		return $text;
	}
	
	/**
	 * Replaces all spaces by the non-breaking space html entity.
	 *
	 * Note: when possible, use CSS instead of this method
	 *
	 * @param string $text
	 */
	function nowrap($text)
	{
	    return str_replace(' ', '&nbsp;', $text);
	}
	
	/**
	 * Easy method to return an i18n value of 'yes' or 'no', depending of the given value.
	 *
	 * @param mixed $value
	 */
	function get_yes_no($value = null)
	{
	    switch($value)
	    {
	        case '1':
	        case 1:
	        case 'true':
	        case 'yes':
	        case true:
	            return __d('Alaxos', 'yes');
	            break;
	        default:
	            return __d('Alaxos', 'no');
	            break;
	    }
	}
	
	/**
	 * Easy method to return an i18n value of 'true' or 'false', depending of the given value.
	 *
	 * @param mixed $value
	 */
	function get_true_false($value = null)
	{
	    switch($value)
	    {
	        case '1':
	        case 1:
	        case 'true':
	        case 'yes':
	        case true:
	            return __d('Alaxos', 'true');
	            break;
	        default:
	            return __d('Alaxos', 'false');
	            break;
	    }
	}
	
	/******************************************************************************************/
	/* Email */
	
	/**
	 * Return a string that is a JS encoded email address.
	 *
	 * Printing this JS string instead of the plain email text should reduce the probability to get the email harvested by spamming robots.
	 *
	 * Note:
	 * 			The returned string is made of a <script> block and a <a> block.
	 *
	 * @param $email
	 */
	function encode_email($email)
	{
	    $this->include_js_jquery();
	    $this->include_js_jquery_no_conflict();
	    $this->include_js_encode();
	    
	    $js_code = '<script type="text/javascript">';
	    
	    $email_id = intval(mt_rand());
	    $js_code .= 'alaxos_' . $email_id . '=';
	    
	    for($i = 0; $i < strlen($email); $i++)
	    {
	        $char = strtolower($email[$i]);
	        
	        switch($char)
	        {
	            case '.':
	                $js_code .= 'l_dot.charAt(0)';
	                break;
	            case '_':
	                $js_code .= 'l_under.charAt(0)';
	                break;
	            case '-':
	                $js_code .= 'l_dash.charAt(0)';
	                break;
	            case '@':
	                $js_code .= 'l_at.charAt(0)';
	                break;
	            default:
	                $js_code .= 'l_' . $char . '.charAt(0)';
	                break;
	        }
	        
	        $js_code .= ($i < strlen($email) - 1) ? '+' : '';
	    }
	    
	    if(!$this->isAjax())
	    {
	        $js_code .= ';$j(document).ready(function(){	$j("#' . $email_id . '").attr("href", "mailto:" + alaxos_' . $email_id . ');$j("#' . $email_id . '").html(alaxos_' . $email_id . ');	});</script><a id="' . $email_id . '"><em>missing email</em></a>';
	    }
	    else
	    {
	        $js_code .= ';$j("#' . $email_id . '").attr("href", "mailto:" + alaxos_' . $email_id . ');$j("#' . $email_id . '").html(alaxos_' . $email_id . ');</script><a id="' . $email_id . '"><em>missing email</em></a>';
	    }
	    
	    return $js_code;
	}

	/******************************************************************************************/
	/* Tooltip */
	
	/**
	 * Add an hover tooltip to the given HTML element id
	 *
	 * @param string $element_id An HTML element id
	 * @param string $content_url The URL that contains the tooltip content to display
	 * @param array  $options 	css           true                      => include alaxos tooltip.css
	 * 							position      'br', 'bl', 'tl', 'tr'    => bottom right, bottom left, top left, top right (position relative to the hovered element)
	 */
	function add_ajax_tooltip($element_id, $content_url, $options = array())
	{
	    $this->include_js_jquery();
	    $this->include_js_jquery_no_conflict();
	    $this->include_js_tooltip();
	    
	    if(!empty($options['css']) && $options['css'])
	    {
    	    /*
    	     * include CSS needed to show the tooltip
    	     */
    	    $this->include_css_tooltip(false);
	    }
	    
	    if(empty($options['position']))
	    {
	        $options['position'] = 'br';
	    }
	    
	    $element_id = StringTool :: ensure_start_with($element_id, '#');
	    
	    unset($options['css']);
	    
	    $tooltip_js_params = '{';
	    $tooltip_js_params .= 'url : "' . $this->url($content_url, true) . '",'."\n";
	    
	    foreach($options as $key => $value)
	    {
	        if(is_string($value))
	        {
	            $tooltip_js_params .= $key . ' : "' . $value . '",'."\n";
	        }
	        elseif(is_bool($value))
	        {
	            $tooltip_js_params .= $key . ' : ' . ($value ? 'true' : 'false') . ','."\n";
	        }
	        else
	        {
	            $tooltip_js_params .= $key . ' : ' . $value . ','."\n";
	        }
	    }
	    $tooltip_js_params .= '}';
	    
	    echo $this->scriptBlock('$j(document).ready(function(){
			$j("' . $element_id . '").tooltip(' . $tooltip_js_params . ');
		});');
	}
	
	/******************************************************************************************/
	/* CSS includes */
	
	function include_css_datepicker($inline = false)
	{
	    if(!in_array('datepicker', $this->css_included))
	    {
	        $this->css('/alaxos/css/datepicker.min', null, array('inline' => $inline));
	        
	        $this->css_included[] = 'datepicker';
	    }
	}
	
	function include_css_tooltip($inline = false)
	{
	    if(!in_array('tooltip', $this->css_included))
	    {
	        $this->css('/alaxos/css/tooltip', null, array('inline' => $inline));
	        
	        $this->css_included[] = 'tooltip';
	    }
	}
	
	function include_css_tabs($inline = false)
	{
	    if(!in_array('tabs', $this->css_included))
	    {
	        $this->css('/alaxos/css/tabs', null, array('inline' => $inline));
	        
	        $this->css_included[] = 'tabs';
	    }
	}
	
	/**
     * Render the required CSS inline, as if an element is rendered from a layout, it wouldn't work with inline => false
     * And a menu is very likely going to be included in a layout
     */
	function include_css_menu()
	{
	    if(!in_array('menu', $this->css_included))
	    {
	        echo $this->css('/alaxos/css/menu', 'import');
	        $this->css_included[] = 'menu';
	    }
	}
	
	/******************************************************************************************/
	/* Javascript includes */
	
	function include_js_all($inline = false)
	{
	    $this->include_js_jquery($inline);
	    $this->include_js_jquery_ui($inline);
	    $this->include_js_jquery_no_conflict($inline);
	    $this->include_js_datepicker($inline);
	    $this->include_js_alaxos($inline);
	    $this->include_js_tooltip($inline);
	}
	
	function include_js_jquery($inline = false)
	{
	    $this->script('/alaxos/js/jquery/jquery', array('inline' => $inline));
	}
	
	function include_js_jquery_ui($inline = false)
	{
	    $this->include_js_jquery($inline);
	    $this->script('/alaxos/js/jquery/jquery-ui', array('inline' => $inline));
	}

	function include_js_jquery_no_conflict($inline = false)
	{
	    $this->include_js_jquery($inline);
	    $this->script('/alaxos/js/jquery/jquery_no_conflict', array('inline' => $inline));
	}
	
	function include_js_alaxos($inline = false)
	{
	    /*
	     * Test allowing to not print more than once the scriptBlocks
	     * Note: in Cake2, by default script() includes only once the same script URL,
	     *       but scriptBlock() always prints the script when it is called
	     */
	    if(!in_array('alaxos', $this->js_included))
	    {
    		/*
    		 * The alaxos.js file contains code that need jquery declared as $j
    		 */
    		$this->include_js_jquery($inline);
    		$this->include_js_jquery_no_conflict($inline);
    	    
    	    /*
    	     * The alaxos.js file contains code that need a date_format global variable
    	     */
    	    $this->scriptBlock('var date_format = "' . strtolower(DateTool :: get_date_format()) . '";', array('inline' => $inline));
    	    $this->scriptBlock('var application_root = "' . $this->url('/') . '";', array('inline' => $inline));
    	    
    	    $this->script('/alaxos/js/alaxos/alaxos.js', array('inline' => $inline));
    	    
    	    $this->js_included[] = 'alaxos';
	    }
	}
	
	function include_js_encode($inline = false)
	{
	    $this->script('/alaxos/js/alaxos/encode.js', array('inline' => $inline));
	}
	
	function include_js_datepicker($inline = false)
	{
	    $this->include_datepicker_language();
	    $this->script('/alaxos/js/datepicker/js/datepicker.js', array('inline' => $inline));
	}
	
	function include_datepicker_language($inline = false)
    {
        $locale = DateTool :: get_current_locale();
    	
    	$locale = strtolower($locale);
    	
    	switch($locale)
    	{
    		case 'fr_ch':
    		case 'fr_ch.utf-8':
    		case 'fr_fr':
    			$lang = 'fr';
    			break;
    			
    		case 'en_en':
			case 'en_us':
			case 'en_en.utf-8':
			case 'en_us.utf-8':
				$lang = 'en';
				break;
				
    		default:
    			$lang = 'en';
    			break;
    	}
    	
    	$this->script('/alaxos/js/datepicker/js/lang/' . $lang, array('inline' => $inline));
    }
	
	function include_js_tooltip($inline = false)
	{
	    /*
		 * The tooltip.js file contains code that need jquery declared as $j
		 */
	    $this->include_js_jquery($inline);
	    $this->include_js_jquery_no_conflict($inline);
	    
	    $this->script('/alaxos/js/alaxos/jquery.tooltip', array('inline' => $inline));
	}
	
	function include_js_alaxos_security($inline = false)
	{
	    return $this->script('/alaxos/alaxos_javascripts/ajax_security', array('inline' => $inline));
	}
	
	function include_js_textarea_autosize($inline = false)
	{
	    $this->include_js_jquery_no_conflict($inline);
	    return $this->script('/alaxos/js/jquery/jquery.autosize-min', array('inline' => $inline));
	}
	
	function include_js_antispam($form_dom_id, $model_name, $inline = false)
	{
	    $js_url = Router::url(array('plugin' => 'alaxos', 'controller' => 'alaxos_javascripts', 'action' => 'antispam'), false);
	    $js_url = '/' . StringTool::remove_leading($js_url, Router::url('/', false));
	    
	    $this->Html->script($js_url . '/' . $form_dom_id . '/' . $model_name, array('inline' => false));
	}
	
	/*
     * Check if the query is an Ajax query
     * May be used to determine if the JS libraries must be included or not in the response
     */
    function isAjax()
    {
		return (isset($this->params['isAjax']) && $this->params['isAjax'] === true);
	}
	
	/******************************************************************************************/
	/* Difference engine */
	
	/**
	 * Return an HTML table allowing to display differences between two texts, the same way MediaWiki does
	 *
	 * @param string $value1
	 * @param string $value2
	 * @param array $table_options attributes to add on the <table> tag
	 */
	function format_differences($value1, $value2, $table_options = array())
	{
	    if(!is_array($value1))
	    {
	        $value1 = explode("\r\n", $value1);
	    }
	    
	    if(!is_array($value2))
	    {
	        $value2 = explode("\r\n", $value2);
	    }
	    
	    require_once(App :: pluginPath('Alaxos') . '/Lib/DifferenceEngine.php');
	    
	    $formatter = new TableDiffFormatter();
	    $formatter->line_name = __d('Alaxos', 'line');
	    
	    $table_rows = $formatter->format(new Diff($value1, $value2));
	    
	    if(!empty($table_rows))
	    {
	        return $this->Html->tag('table', $table_rows, $table_options);
	    }
	    else
	    {
	        return null;
	    }
	}
	
	/**
	 * Lighten a color given in hexadecimal (= CSS like) by the given $light_difference
	 *
	 * $light_difference can be between -255 and 255.
	 *
	 * The result is always included between 000000 and ffffff.
	 *
	 * @param unknown_type $hexa_color
	 * @param unknown_type $light_difference
	 */
	function color_lighten($hexa_color, $light_difference)
	{
	    $hexa_color =  StringTool::remove_leading($hexa_color, '#');
	    
	    $r_hexa = substr($hexa_color, 0, 2);
	    $g_hexa = substr($hexa_color, 2, 2);
	    $b_hexa = substr($hexa_color, 4, 2);
	    
	    $r_dec = hexdec($r_hexa) + $light_difference;
	    $g_dec = hexdec($g_hexa) + $light_difference;
	    $b_dec = hexdec($b_hexa) + $light_difference;
	     
	    $r_dec = $r_dec <= 255 ? $r_dec : 255;
	    $r_dec = $r_dec >= 0   ? $r_dec : 0;
	     
	    $g_dec = $g_dec <= 255 ? $g_dec : 255;
	    $g_dec = $g_dec >= 0   ? $g_dec : 0;
	     
	    $b_dec = $b_dec <= 255 ? $b_dec : 255;
	    $b_dec = $b_dec >= 0   ? $b_dec : 0;
	     
	    $r_hexa = dechex($r_dec);
	    $g_hexa = dechex($g_dec);
	    $b_hexa = dechex($b_dec);
	     
	    $r_hexa = strlen($r_hexa) == 2 ? $r_hexa : '0' . $r_hexa;
	    $g_hexa = strlen($g_hexa) == 2 ? $g_hexa : '0' . $g_hexa;
	    $b_hexa = strlen($b_hexa) == 2 ? $b_hexa : '0' . $b_hexa;
	     
	    return '#' . $r_hexa . $g_hexa . $b_hexa;
	}
	
}
?>
