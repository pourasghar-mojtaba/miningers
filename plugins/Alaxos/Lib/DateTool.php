<?php
/**
 *
 * @author   Nicolas Rod <nico@alaxos.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.alaxos.ch
 */
class DateTool
{
    const DAY   = 1;
    const WEEK  = 2;
    const MONTH = 3;
    const YEAR  = 4;
    
    /******************************************************************************************************/
    
	/**
	 * Format a date according to the current locale
	 *
	 * @param $sql_date An SQL formatted date
	 * @param $locale string The locale to use. If no locale is given, the current locale is used
	 * @param $with_time boolean Indicates wether the time part must be added if present
	 * @return string A date formatted according to the current locale
	 */
	public static function sql_to_date($sql_date, $locale = null, $with_time = true)
	{
		$sql_date = trim($sql_date);
		
		if(strlen($sql_date) == 0 || $sql_date == '0000-00-00')
		{
			return null;
		}
		
		if($with_time && strpos($sql_date, ' ') !== false)
		{
			$format = DateTool :: get_datetime_format($locale);
		}
		else
		{
			$format = DateTool :: get_date_format($locale);
		}
		
		return date($format, strtotime($sql_date));
	}
	
	public static function sql_to_long_date($sql_date, $locale = null, $with_time = true)
	{
	    $sql_date = trim($sql_date);
		
		if(strlen($sql_date) == 0 || $sql_date == '0000-00-00' || $sql_date == '0000-00-00 00:00:00')
		{
			return null;
		}
		
		if($with_time && strpos($sql_date, ' ') !== false)
		{
			$format = DateTool :: get_long_datetime_format($locale);
		}
		else
		{
			$format = DateTool :: get_long_date_format($locale);
		}
		
		return strftime($format, strtotime($sql_date));
	}
	
	/**
	 * Format a given date to an SQL formatted date.
	 *
	 * @param $date string The date to format
	 * @param $locale string The locale in which the date is formatted. If no locale is given, the current locale is used
	 * @param $with_time boolean Indicates wether the time part must be added if present
	 * @return string
	 */
	public static function date_to_sql($date, $locale = null, $with_time = true)
	{
		$date = trim($date);
		
		if($with_time && strpos($date, ' ') !== false)
		{
			return DateTool :: datetime_to_sql($date, $locale);
		}
		else
		{
			$format = DateTool :: get_date_format($locale);
		}
		
		$format = str_replace('Y', '%Y', $format);
		$format = str_replace('m', '%m', $format);
		$format = str_replace('d', '%d', $format);

		$date_array = strptime($date, $format);
		
		if($date_array !== false)
		{
			$day   = sprintf("%02d", $date_array['tm_mday']);
			$month = sprintf("%02d", $date_array['tm_mon'] + 1);
			$year  = DateTool :: get_complete_year(1900 + $date_array['tm_year']);
			
			return $year . '-' . $month . '-' . $day;
		}
		else
		{
			return null;
		}
	}
	
	public static function format_date_interval($dateStr, $separator = ' - ', $locale = null)
    {
    	$dateStr = trim($dateStr);
    	
    	$separator_pos = strpos($dateStr, $separator);
    	if($separator_pos !== false)
    	{
			$first_date  = substr($dateStr, 0, $separator_pos);
    		$second_date = substr($dateStr, $separator_pos + strlen($separator));
    		
    		return DateTool :: sql_to_date($first_date, $locale) . ' - ' . DateTool :: sql_to_date($second_date, $locale);
    	}
    	else
    	{
    		return DateTool :: sql_to_date($dateStr, $locale);
    	}
    }
    
    public static function get_datetimes_interval($date1, $date2, $options = array())
    {
        $default_options = array(	'dates-separator'    => ' &ndash; ',
        							'hours-separator'    => ' &ndash; ',
        							'day-hour-separator' => ' ',
        							'seconds'            => false);
        
        $options = array_merge($default_options, $options);
        
        $day1  = !empty($date1) ? DateTool::sql_to_date($date1, null, false)               : '';
        $time1 = !empty($date1) ? DateTool::sql_to_time($date1, null, $options['seconds']) : '';
        
        $day2  = !empty($date2) ? DateTool::sql_to_date($date2, null, false)               : '';
        $time2 = !empty($date2) ? DateTool::sql_to_time($date2, null, $options['seconds']) : '';
        
        if(empty($date1) && empty($date2))
        {
            return null;
        }
        elseif(empty($date1))
        {
            return $day2 . $options['day-hour-separator'] . $time2;
        }
        elseif(empty($date2))
        {
            return $day1 . $options['day-hour-separator'] . $time1;
        }
        elseif($day1 == $day2)
        {
            /*
             * Print the day only once and print both hours (except if both hours are 00:00, because this probably means no time was given)
             */
            
            if($time1 != $time2)
            {
                $output = $day1 . $options['day-hour-separator'] . $time1 . $options['hours-separator'] . $time2;
            }
            else
            {
                if(($time1 == '00:00' || $time1 == '00:00:00') && ($time2 == '00:00' || $time2 == '00:00:00'))
                {
                    /*
                     * No times were probably given -> do not print times
                     */
                    $output = $day1;
                }
                else
                {
                    /*
                     * Same days and times were given
                     * -> do not print an interval but only the datetime once
                     */
                    $output = $day1 . $options['day-hour-separator'] . $time1;
                }
            }
        }
        else
        {
            /*
             * Print both complete datetimes
             */
            $output = $day1 . $options['day-hour-separator'] . $time1 . $options['dates-separator'] . $day2 . $options['day-hour-separator'] . $time2;
        }
        
        return $output;
    }
	
    public static function get_long_datetimes_interval($date1, $date2, $options = array())
    {
        $default_options = array(	'dates-separator'            => ' &ndash; ',
                                    'hours-separator'            => ' &ndash; ',
        							'seconds'                    => false,
                                    'hide_time_when_midnights'   => false,
                                    'locale'                     => DateTool::get_current_locale());
        
        $options = array_merge($default_options, $options);
        
        $day1  = !empty($date1) ? DateTool::sql_to_date($date1, null, false)               : '';
        $time1 = !empty($date1) ? DateTool::sql_to_time($date1, null, $options['seconds']) : '';
        
        $day2  = !empty($date2) ? DateTool::sql_to_date($date2, null, false)               : '';
        $time2 = !empty($date2) ? DateTool::sql_to_time($date2, null, $options['seconds']) : '';
        
        //$date_format  = '%a %d %B %G';
        $date_format  = DateTool::get_long_date_format($options['locale']);
        
//        $time_format  = '%H:%M';
//        $time_format .= $options['seconds'] ? ':%S' : '';
        $time_format = DateTool::get_long_time_format($options['locale'], $options['seconds']);
        
        //$datetime_format = $date_format . ' ' . $time_format;
        $datetime_format = DateTool::get_long_datetime_format($options['locale'], $options['seconds']);
        
        /*
         * - deux dates null
         * - date1 null
         * - date2 null
         * - jours pleins identiques
         * - jours pleins différents
         * - datetimes avec jours différents
         * - datetimes le même jour
         * - datetimes identiques
         */
        if(empty($date1) && empty($date2))
        {
            return null;
        }
        elseif(empty($date1))
        {
            $output = strftime($datetime_format, strtotime($date2));
        }
        elseif(empty($date2))
        {
            $output = strftime($datetime_format, strtotime($date1));
        }
        elseif($day1 == $day2)
        {
            if($time1 != $time2)
            {
                $output  = strftime($date_format, strtotime($date1));
                $output .= ' ';
                $output .= strftime($time_format, strtotime($date1));
                $output .= $options['hours-separator'];
                $output .= strftime($time_format, strtotime($date2));
            }
            else
            {
                if(($time1 == '00:00' || $time1 == '00:00:00') && ($time2 == '00:00' || $time2 == '00:00:00') && $options['hide_time_when_midnights'])
                {
                    /*
                     * No times were probably given -> do not print times
                     */
                    $output = strftime($date_format, strtotime($date1));;
                }
                else
                {
                    $output = strftime($datetime_format, strtotime($date1));
                }
            }
        }
        elseif($day1 != $day2)
        {
            if(($time1 == '00:00' || $time1 == '00:00:00') && ($time2 == '00:00' || $time2 == '00:00:00') && $options['hide_time_when_midnights'])
            {
                /*
                 * No times were probably given -> do not print times
                 * and remove 24h00 from the second date to have the second printed day being fully included in the interval
                 */
                $output  = strftime($date_format, strtotime($date1));
                $output .= $options['dates-separator'];
                $output .= strftime($date_format, strtotime($date2) - (24 * 3600));
            }
            else
            {
                $output  = strftime($datetime_format, strtotime($date1));
                $output .= $options['dates-separator'];
                $output .= strftime($datetime_format, strtotime($date2));
            }
        }
        
        return $output;
    }
	
    public static function get_dates_interval($date1, $date2, $options = array())
    {
        $default_options = array(	'dates-separator'  => ' &ndash; ',
                                    'locale'           => DateTool::get_current_locale());
        
        $options = array_merge($default_options, $options);
        
        $day1  = !empty($date1) ? DateTool::sql_to_date($date1, null, false)               : '';
        $day2  = !empty($date2) ? DateTool::sql_to_date($date2, null, false)               : '';
        
        $date_format  = DateTool::get_date_format($options['locale']);
        
        if(empty($day1) && empty($day2))
        {
            return null;
        }
        elseif(empty($day1))
        {
            $output = date($date_format, strtotime($day2));
        }
        elseif(empty($date2))
        {
            $output = date($date_format, strtotime($day1));
        }
        elseif($day1 == $day2)
        {
            $output = date($date_format, strtotime($day1));
        }
        elseif($day1 != $day2)
        {
            $output  = date($date_format, strtotime($day1));
            $output .= $options['dates-separator'];
            $output .= date($date_format, strtotime($day2));
        }
        
        return $output;
    }
    
    public static function get_long_dates_interval($date1, $date2, $options = array())
    {
        $default_options = array(	'dates-separator'  => ' &ndash; ',
                                    'locale'           => DateTool::get_current_locale());
        
        
        $options = array_merge($default_options, $options);
        
        $day1  = !empty($date1) ? DateTool::sql_to_date($date1, null, false)               : '';
        $day2  = !empty($date2) ? DateTool::sql_to_date($date2, null, false)               : '';
        
        $date_format  = DateTool::get_long_date_format($options['locale']);
        
        if(empty($day1) && empty($day2))
        {
            return null;
        }
        elseif(empty($day1))
        {
            $output = strftime($date_format, strtotime($day2));
        }
        elseif(empty($date2))
        {
            $output = strftime($date_format, strtotime($day1));
        }
        elseif($day1 == $day2)
        {
            $output = strftime($date_format, strtotime($day1));
        }
        elseif($day1 != $day2)
        {
            $output  = strftime($date_format, strtotime($day1));
            $output .= $options['dates-separator'];
            $output .= strftime($date_format, strtotime($day2));
        }
        
        return $output;
    }
    
	/**
	 * Get the current locale format to parse/format a date string
	 * @return string
	 */
	public static function get_date_format($locale = null)
	{
		if(!isset($locale))
		{
			$locale = strtolower(DateTool :: get_current_locale());
		}
		else
		{
		    $locale = strtolower($locale);
		}
		
		switch($locale)
		{
			case 'fr_ch':
			case 'fr_ch.utf-8':
			case 'fr_fr':
			case 'fr_fr.utf-8':
				return 'd.m.Y';
				break;
				
			case 'en_en':
			case 'en_us':
			case 'en_en.utf-8':
			case 'en_us.utf-8':
				return 'Y-m-d';
				break;
				
			default:
				return 'Y-m-d';
		}
	}
	
	/**
	 * Return a format string that can be used with strftime() function
	 * @param string $locale
	 */
	public static function get_long_date_format($locale = null)
	{
		if(!isset($locale))
		{
			$locale = strtolower(DateTool :: get_current_locale());
		}
		else
		{
		    $locale = strtolower($locale);
		}
		
		switch($locale)
		{
			case 'fr_ch':
			case 'fr_ch.utf-8':
			case 'fr_fr':
			case 'fr_fr.utf-8':
			    //lun 07 mai 2012
				return '%a %d %B %G';
				break;
				
			case 'en_en':
			case 'en_us':
			case 'en_en.utf-8':
			case 'en_us.utf-8':
			    //Sunday, 8 November 2003
				return '%a, %d %B %G';
				break;
				
			default:
				return '%a %d %B %G';
		}
	}
	
	/**
	 * If the date is not in yyyy-mm-dd, format it to this format
	 *
	 * @param string $sql_date
	 * @return string
	 */
	public static function check_sql_date($sql_date)
	{
	    if(!is_numeric(substr($sql_date, 0, 4)))
	    {
	        return DateTool::date_to_sql($date);
	    }
	    else
	    {
	        return $sql_date;
	    }
	}
	
	/*********************************************************************************************/
	
	public static function sql_to_datetime($sql_date, $locale = null, $with_seconds = true)
	{
		$sql_date = trim($sql_date);
		
		if(strlen($sql_date) == 0 || $sql_date == '0000-00-00' || $sql_date == '0000-00-00 00:00:00')
		{
			return null;
		}
		
		$format = DateTool :: get_datetime_format($locale, $with_seconds);
		
		return date($format, strtotime($sql_date));
	}
	
	public static function sql_to_long_datetime($sql_date, $locale = null, $with_seconds = false)
	{
		$sql_date = trim($sql_date);
		
		if(strlen($sql_date) == 0 || $sql_date == '0000-00-00' || $sql_date == '0000-00-00 00:00:00')
		{
			return null;
		}
		
		$format = DateTool :: get_long_datetime_format($locale);
		
		return strftime($format, strtotime($sql_date));
	}
	
	public static function datetime_to_sql($date, $locale = null, $force_datetime = false)
	{
		$date = trim($date);
		
		$format = DateTool :: get_datetime_format($locale);
		
		/*
		 * Check if the $date should be parsed as a date and not a datetime
		 */
		if(!$force_datetime && stripos($format, ' ') !== false && stripos($date, ' ') === false)
		{
			return DateTool :: date_to_sql($date, $locale);
		}
		else
		{
			if($force_datetime && stripos($date, ' ') === false)
			{
				$date .= ' 00:00:00';
			}
			
			$format = str_replace('Y', '%Y', $format);
			$format = str_replace('m', '%m', $format);
			$format = str_replace('d', '%d', $format);
			$format = str_replace('H', '%H', $format);
			$format = str_replace('i', '%M', $format);
			$format = str_replace('s', '%S', $format);
			
			$date_array = strptime($date, $format);
			
			if($date_array !== false)
			{
				$day   = sprintf("%02d", $date_array['tm_mday']);
				$month = sprintf("%02d", $date_array['tm_mon'] + 1);
				$year  = 1900 + $date_array['tm_year'];
				
				$hour  = sprintf("%02d", $date_array['tm_hour']);
				$min   = sprintf("%02d", $date_array['tm_min']);
				$sec   = sprintf("%02d", $date_array['tm_sec']);
				
				return $year . '-' . $month . '-' . $day . ' ' . $hour . ':' . $min . ':' . $sec;
			}
			else
			{
				return null;
			}
		}
	}
	
	public static function get_datetime_format($locale = null, $with_seconds = true)
	{
		if(!isset($locale))
		{
			$locale = strtolower(DateTool :: get_current_locale());
		}
		else
		{
		    $locale = strtolower($locale);
		}
		
		switch($locale)
		{
			case 'fr_ch':
			case 'fr_ch.utf-8':
			case 'fr_fr':
			case 'fr_fr.utf-8':
				$format = 'd.m.Y H:i';
				break;
				
			case 'en_en':
			case 'en_us':
			case 'en_en.utf-8':
			case 'en_us.utf-8':
			case 'sql':
				$format = 'Y-m-d H:i';
				break;
				
			default:
				$format = 'Y-m-d H:i';
				break;
		}
		
		if($with_seconds)
		{
		    $format .= ':s';
		}
		
		return $format;
	}
	
	/**
	 * Return a format string that can be used with strftime() function
	 * @param string $locale
	 */
	public static function get_long_datetime_format($locale = null, $with_seconds = false)
	{
		if(!isset($locale))
		{
			$locale = strtolower(DateTool :: get_current_locale());
		}
		else
		{
		    $locale = strtolower($locale);
		}
		
		switch($locale)
		{
			case 'fr_ch':
			case 'fr_ch.utf-8':
			case 'fr_fr':
			case 'fr_fr.utf-8':
				$format = '%a %d %B %G %H:%M';
				break;
				
			case 'en_en':
			case 'en_us':
			case 'en_en.utf-8':
			case 'en_us.utf-8':
			case 'sql':
				$format = '%a, %d %B %G %H:%M';
				break;
				
			default:
				$format = '%a %d %B %G %H:%M';
				break;
		}
		
		if($with_seconds)
		{
		    $format .= ':%S';
		}
		
		return $format;
	}
	
	public static function get_current_datetime($locale = null)
	{
	    return DateTool :: sql_to_datetime(date(DateTool :: get_datetime_format($locale)), $locale);
	}
	
	
	/*********************************************************************************************/
	
	/**
	 * Format a time according to the current locale
	 *
	 * @param $sql_date An SQL formatted date
	 * @param $locale string The locale to use. If no locale is given, the current locale is used
	 * @return string A time formatted according to the given locale
	 */
	public static function sql_to_time($sql_date, $locale = null, $with_seconds = true)
	{
		$sql_date = trim($sql_date);
		
		if(strlen($sql_date) == 0 || $sql_date == '0000-00-00' || $sql_date == '0000-00-00 00:00:00')
		{
			return null;
		}
		
		$format = DateTool :: get_time_format($locale, $with_seconds);
		
		return date($format, strtotime($sql_date));
	}
	
	function get_time_format($locale = null, $with_seconds = true)
    {
    	if(!isset($locale))
		{
			$locale = strtolower(DateTool :: get_current_locale());
		}
		else
		{
		    $locale = strtolower($locale);
		}
    	
    	if($with_seconds)
    	{
        	switch($locale)
        	{
        		case 'fr_ch':
        		case 'fr_ch.utf-8':
        		case 'fr_fr':
        			return 'H:i:s';
        		
        		case 'en_en':
    			case 'en_us':
    			case 'en_en.utf-8':
    			case 'en_us.utf-8':
    				return 'H:i:s';
    				
        		default:
        			return 'H:i:s';
        	}
    	}
    	else
    	{
    	    switch($locale)
        	{
        		case 'fr_ch':
        		case 'fr_ch.utf-8':
        		case 'fr_fr':
        			return 'H:i';
        		
        		case 'en_en':
    			case 'en_us':
    			case 'en_en.utf-8':
    			case 'en_us.utf-8':
    				return 'H:i';
    				
        		default:
        			return 'H:i';
        	}
    	}
    }
	
	/**
	 * Return a format string that can be used with strftime() function
	 * @param string $locale
	 */
	public static function get_long_time_format($locale = null, $with_seconds = false)
	{
		if(!isset($locale))
		{
			$locale = strtolower(DateTool :: get_current_locale());
		}
		else
		{
		    $locale = strtolower($locale);
		}
		
		switch($locale)
		{
			case 'fr_ch':
			case 'fr_ch.utf-8':
			case 'fr_fr':
			case 'fr_fr.utf-8':
			
			case 'en_en':
			case 'en_us':
			case 'en_en.utf-8':
			case 'en_us.utf-8':
			case 'sql':
				
			default:
				$format = '%H:%M';
				break;
		}
		
		if($with_seconds)
		{
		    $format .= ':%S';
		}
		
		return $format;
	}
    
	
	/**
	 * Complete timeStr if necessary
	 *
	 * 3        -> 03:00:00
	 * 16:35    -> 16:35:00
	 * 16:35:34 -> 16:35:34
	 *
	 * @param $timeStr
	 * @return unknown_type
	 */
	public static function get_complete_time($timeStr)
	{
	    $timeStr = trim($timeStr);
	    
	    $time_array = explode(':', $timeStr);
	    
	    $timeStr = '';
	    
	    for($i = 0; $i < 3; $i++)
	    {
	        if(array_key_exists($i, $time_array))
	        {
	            $time_part = $time_array[$i];
	        }
	        else
	        {
	            $time_part = '00';
	        }
	        
	        $timeStr .= sprintf('%02d', $time_part);
	        
	        if($i < 2)
	        {
	            $timeStr .= ':';
	        }
	    }
	    
	    //debug($timeStr);
	    
//		$timeStr = trim($timeStr);
//
//		if(substr_count($timeStr, ':') == 0)
//		{
//			$timeStr .= ':00:00';
//		}
//		elseif(substr_count($timeStr, ':') == 1)
//		{
//			$timeStr .= ':00';
//		}
		
		return $timeStr;
	}
	
	
	/**
	 * Complete timeStr if necessary
	 *
	 * 3        -> 03:00:00
	 * 16:35    -> 16:35:00
	 * 16:35:34 -> 16:35:34
	 *
	 * @param $timeStr
	 * @return unknown_type
	 */
	public static function get_complete_datetime($timeStr)
	{
	    $timeStr = trim($timeStr);
	    
	    if(substr_count($timeStr, ' ') > 0)
	    {
	        $date = substr($timeStr, 0, strpos($timeStr, ' '));
	        $time = DateTool :: get_complete_time(substr($timeStr, strpos($timeStr, ' ')));
	        
	        return $date . ' ' . $time;
	    }
	    else
	    {
	        return $timeStr . ' 00:00:00';
	    }
	}
	
	public static function get_complete_year($year)
	{
		$year_num = (int)$year;

		if($year_num < 100)
		{
			$current_year = date('y') + 100;
			
			if($current_year - $year_num > 80)
			{
				$year_num += 2000;
			}
			else
			{
				$year_num += 1900;
			}
		}
		
		return $year_num;
	}
	
	
	/**
	 * Return a formatted time string from a number of hours
	 * @param $hour float
	 * @return string
	 */
	public static function get_time_from_hour($hour)
	{
	    $date = new Datetime();
        $date->setTime(floor($hour), ($hour - floor($hour)) * 60, 0);
        return $date->format('H:i');
	}
	
	
	public static function get_hour_as_float($time_string)
	{
		if(stripos($time_string, ' ') !== false)
		{
			$time_string = substr($time_string, stripos($time_string, ' '));
		}
		
		$hour_array = explode(':', $time_string);
		$hour = $hour_array[0];
		$min  = isset($hour_array[1]) ? $hour_array[1] : 0;
		$sec  = isset($hour_array[2]) ? $hour_array[2] : 0;
		
		return $hour + $min / 60 + $sec / 3600;
	}
	
	
	/**
	 *
	 * @param float $start_hour
	 * @param float $end_hour
	 * @param float $step_hour
	 * @return array
	 */
	public static function get_time_array($start_hour, $end_hour, $step_hour, $minimum_hour = null, $maximum_hour = null, $locale = null)
	{
		if(!is_numeric($start_hour) && !is_numeric($end_hour) && $step_hour > 0)
		{
			$sql_date1 = DateTool :: datetime_to_sql($start_hour, $locale);
			$sql_date2 = DateTool :: datetime_to_sql($end_hour, $locale);
			
//			debug($sql_date1);
//			debug($sql_date2);
			
			$timestamp1 = strtotime($sql_date1);
			$timestamp2 = strtotime($sql_date2);
			
			$hour_difference = ($timestamp2 - $timestamp1) / 3600;
			
			//debug($hour_difference);
			
			$start_hour = DateTool :: get_hour_as_float($start_hour);
		}
	    elseif(is_numeric($start_hour) && is_numeric($end_hour) && $start_hour < $end_hour && $step_hour > 0)
	    {
    	    $hour_difference = $end_hour - $start_hour;
    	    
    	    //debug($hour_difference);
	    }

	    if(isset($hour_difference))
	    {
	    	//debug($hour_difference);
	    	
    	    $times = array();
    	    $current_time = $start_hour;
    	    $end_hour = $start_hour + $hour_difference;
    	    
    	    //debug($end_hour);
    	    
    	    while($current_time < $end_hour)
    	    {
    	    	if(isset($minimum_hour) && isset($maximum_hour))
    	    	{
    	    		if($minimum_hour <= $current_time &&  $current_time < $maximum_hour)
    	    		{
    	    			$times[] = DateTool :: get_time_from_hour($current_time);
    	    		}
    	    	}
    	    	elseif(!isset($minimum_hour) && isset($maximum_hour))
    	    	{
    	    		if($current_time < $maximum_hour)
    	    		{
    	    			$times[] = DateTool :: get_time_from_hour($current_time);
    	    		}
    	    		else
    	    		{
    	    			/*
    	    			 * Max time has been overcome
    	    			 */
    	    			break;
    	    		}
    	    	}
    	    	elseif(isset($minimum_hour) && !isset($maximum_hour))
    	    	{
    	    		if($minimum_hour <= $current_time)
    	    		{
    	    			$times[] = DateTool :: get_time_from_hour($current_time);
    	    		}
    	    	}
    	    	else
    	    	{
    	    		$times[] = DateTool :: get_time_from_hour($current_time);
    	    	}
    	    	
    	        $current_time += $step_hour;
    	    }
    	    
    	    //debug($times);
    	    
    	    return $times;
	    }
	    
	}
	
	
	/*********************************************************************************************/
	
	public static function compare_dates($date1, $date2, $locale = null)
	{
	    //debug($date1 . ' <---> ' . $date2);
	    
		$sql_date1 = DateTool :: datetime_to_sql($date1, $locale);
		$sql_date2 = DateTool :: datetime_to_sql($date2, $locale);
		
		//debug($sql_date1 . ' <---> ' . $sql_date2);
		
		$timestamp1 = strtotime($sql_date1);
		$timestamp2 = strtotime($sql_date2);
		
		if($timestamp1 == $timestamp2)
		{
			return '=';
		}
		elseif($timestamp1 > $timestamp2)
		{
			return '>';
		}
		elseif($timestamp1 < $timestamp2)
		{
			return '<';
		}
	}
	
	
	/**
	 *
	 * @param string $start_datetime
	 * @param string $end_datetime
	 * @param string $datetime_to_check
	 * @return bool Indicates wether the time to check is between the two given times
	 */
	public static function datetime_is_in_interval($start_datetime, $end_datetime, $datetime_to_check = null, $locale = 'sql')
	{
	    if(!isset($datetime_to_check))
	    {
	        $datetime_to_check = DateTool :: get_current_datetime($locale);
	    }
	    
	    $start_datetime    = DateTool :: get_complete_datetime($start_datetime);
	    $end_datetime      = DateTool :: get_complete_datetime($end_datetime);
	    $datetime_to_check = DateTool :: get_complete_datetime($datetime_to_check);
	    
	    $comparison1 = DateTool :: compare_dates($start_datetime, $datetime_to_check, $locale);
	    $comparison2 = DateTool :: compare_dates($datetime_to_check, $end_datetime, $locale);
	    
	    if(
	        ($comparison1 == '<' || $comparison1 == '=')
	        &&
	        ($comparison2 == '<')
	       )
	    {
	        return true;
	    }
	    else
	    {
	        return false;
	    }
	}
	
	/*********************************************************************************************/
	
	public static function get_first_date_of_month($sql_date)
	{
	    $year  = date('Y', strtotime($sql_date));
	    $month = date('m', strtotime($sql_date));
	    
	    return $year . '-' . $month . '-01';
	}
	public static function get_last_date_of_month($sql_date)
	{
	    $first_date_of_month = DateTool::get_first_date_of_month($sql_date);
	    return date('Y-m-d', strtotime('+ 1 month', strtotime($first_date_of_month))-1);
	}
	
	public static function get_month_dates($sql_date, $full_weeks = false)
	{
	    $first_timestamp_of_month = strtotime(DateTool::get_first_date_of_month($sql_date));
	    $last_timestamp_of_month  = strtotime(DateTool::get_last_date_of_month($sql_date));
	    
	    if($full_weeks)
	    {
	        $first_timestamp_of_month = strtotime(DateTool :: get_first_date_of_week(date('Y-m-d', $first_timestamp_of_month)));
	        $last_timestamp_of_month  = strtotime(DateTool :: get_last_date_of_week(date('Y-m-d', $last_timestamp_of_month)));
	    }
	    
	    return DateTool::get_dates_for_interval(date('Y-m-d', $first_timestamp_of_month), date('Y-m-d', $last_timestamp_of_month));
	}
	
	public static function get_dates_for_interval($sql_start_date, $sql_end_date)
	{
	    $first_timestamp = strtotime($sql_start_date);
	    $last_timestamp  = strtotime($sql_end_date);
	    
	    $dates = array();
	    $timestamp = $first_timestamp;
	    while($timestamp <= $last_timestamp)
	    {
	        $dates[] = date('Y-m-d', $timestamp);
	        $timestamp = strtotime('+1 day', $timestamp);
	    }
	    
	    return $dates;
	}
	
	/**
	 * Return the date corresponding to monday of the week containing the given date
	 *
	 * @param string $sql_date
	 */
	public static function get_first_date_of_week($sql_date)
	{
	    $dow = date('w', strtotime($sql_date));
	    
	    if($dow == 0)
	    {
	        $day_diff = -6;
	    }
	    else
	    {
	        $day_diff = 1 - $dow;
	    }
	    
	    return date('Y-m-d', strtotime($day_diff . ' day', strtotime($sql_date)));
	}
	
	/**
	 * Return the date corresponding to sunday of the week containing the given date
	 *
	 * @param string $sql_date
	 */
	public static function get_last_date_of_week($sql_date)
	{
	    $dow = date('w', strtotime($sql_date));
	    
	    if($dow != 0)
	    {
	        $day_diff = 7 - $dow;
	    }
	    else
	    {
	        $day_diff = 0;
	    }
	    
	    return date('Y-m-d', strtotime($day_diff . ' day', strtotime($sql_date)));
	}
	
	/**
	 * Return an array of dates corresponding dates based on the given repetition schema
	 *
	 * $options:
	 * 		- repetition_type   days/weeks/months/years (see DateTool :: DAY, ... constants)
	 * 		- repeat_each       Repeat every 1/2/3/... days/weeks/months/years
	 *      - occurrences		Maximum number of returned dates
	 *      - max_date          Do not return date after this date
	 * @param string $start_date
	 * @param array $options
	 */
	public static function get_repeated_dates($sql_date, $options = array())
	{
	    $sql_date = DateTool :: check_sql_date($sql_date);
	    
	    if(empty($sql_date))
	    {
	        return false;
	    }
	    
	    $default_options = array('repeat_each' => 1);
	    
	    $options = array_merge($default_options, $options);
	    
	    if(!isset($sql_date) || !isset($options['repetition_type']) || !isset($options['repeat_each'])
	        ||
	        (!isset($options['occurrences']) && !isset($options['max_date'])))
	    {
	        return false;
	    }
	    
	    if(isset($options['max_date']))
	    {
	        $max_date_ts = strtotime($options['max_date']);
	    }
	    
	    $date = $sql_date;
	    
	    $start_ts = strtotime($sql_date);
	    
	    $dates = array();
	    $dates[] = date('Y-m-d', strtotime($date));
	    
	    $increment = $options['repeat_each'];
	    $i = 1;
	    while($i < 10000)
	    {
	        if(isset($options['occurrences']) && count($dates) >= $options['occurrences'])
	        {
	            break;
	        }
	        
	        switch($options['repetition_type'])
	        {
	            case DateTool::DAY:
	                $date = DateTool::increment_day($sql_date, $increment);
	                break;
	            
	            case DateTool::WEEK:
	                $date = DateTool::increment_week($sql_date, $increment);
	                break;
	           
	            case DateTool::MONTH:
	                $date = DateTool::increment_month($sql_date, $increment);
	                break;
	            
	            case DateTool::YEAR:
	                $date = DateTool::increment_year($sql_date, $increment);
	                break;
	            
	            default:
	                return false;
	        }
	        
	        if($date !== false)
	        {
    	        $date_ts = strtotime($date);
    	        if(isset($options['max_date']) && ($date_ts > $max_date_ts))
    	        {
    	            break;
    	        }
    	        
    	        /*
    	         * Note:
    	         * 			$start_ts < $date_ts manage the case of a non recognized date and thus filter 1970-01-01
    	         *          It may arise if the date is too big (see http://en.wikipedia.org/wiki/Year_2038_problem)
    	         */
    	        if($start_ts <= $date_ts)
    	        {
    	            $dates[] = $date;
    	        }
	        }
	        
	        $increment = $increment + $options['repeat_each'];
	        $i++;
	    }
	    
	    return $dates;
	}
	
	public static function increment_one_day($sql_date)
	{
	    return DateTool::increment_day($sql_date, 1);
	}
	public static function increment_day($sql_date, $number_to_add = 1)
	{
	    return date('Y-m-d', strtotime('+' . $number_to_add . ' day', strtotime($sql_date)));
	}
	
	/**
	 * Get the date of the same day of week of the next week
	 *
	 * @param string $sql_date
	 */
	public static function increment_one_week($sql_date)
	{
	    return DateTool::increment_week($sql_date, 1);
	}
	public static function increment_week($sql_date, $number_to_add = 1)
	{
	    return date('Y-m-d', strtotime('+' . ($number_to_add * 7) . ' days', strtotime($sql_date)));
	}
	
	/**
	 * Get the date of the same day of month of the next month
	 * If adding one month gives a non existing date (e.g.: 31th of September or 30th of February), its return false
	 *
	 * @param string $sql_date
	 */
	public static function increment_one_month($sql_date)
	{
	    return DateTool::increment_month($sql_date, 1);
	}
	public static function increment_month($sql_date, $number_to_add = 1)
	{
	    $year  = date('Y', strtotime($sql_date));
	    $month = date('m', strtotime($sql_date));
	    $day   = date('d', strtotime($sql_date));

	    $year_to_add = floor(($month + $number_to_add - 1) / 12);
	    $year      = $year + $year_to_add;
	    $new_month = (($month + $number_to_add) % 12);
	    $new_month = ($new_month == 0) ? 12 : $new_month;
	    
	    $date = $year . '-' . sprintf('%02d', $new_month) . '-' . $day;
	        
        /*
	     * Check that the date is valid
	     * As strtotime supports not existing dates (eg: 2013-02-29 returns 2013-03-01),
	     * we parse it and recreate a date string to compare them
	     */
        $parsed_date = date('Y-m-d', strtotime($date));
        if($parsed_date == $date)
        {
            return $date;
        }
        else
        {
            return false;
        }
	}
	
	/**
	 * Get the date of the same day of year of the next year
	 * (support leap years)
	 *
	 * @param string $sql_date
	 */
	public static function increment_one_year($sql_date)
	{
	    return DateTool::increment_year($sql_date, 1);
	}
	public static function increment_year($sql_date, $number_to_add = 1)
	{
	    return date('Y-m-d', strtotime('+' . $number_to_add . ' year', strtotime($sql_date)));
	}
	
	/*********************************************************************************************/
	
	/**
	 * Set the PHP locale and set the CakePHP language
	 *
	 * @param $locale mixed string or array of string
	 * @return string the new current locale
	 */
	public static function set_current_locale($locale)
	{
		if(isset($locale))
		{
			if(is_string($locale))
			{
				$locale = strtolower($locale);
				
				/*
				 * Depending on the server configuration, the locale that the method 'setlocale()'
				 * is waiting for may be different.
				 *
				 * But as the 'setlocale()' can take an array of strings, we try to pass
				 * an array instead of a string
				 */
				switch($locale)
				{
					case 'fr':
        	        case 'fre':
        	        case 'fren':
        	        case 'french':
        	        case 'fr_ch':
        	        case 'fr_fr':
        	        case 'fr_ch.utf-8':
        	        case 'fr_fr.utf-8':
        	        case 'fr-ch':
        	        case 'fr-fr':
        	        case 'fr-ch.utf-8':
        	        case 'fr-fr.utf-8':
						$locale = array('fr_CH.UTF-8', 'fr_CH', 'fr_FR.UTF-8', 'fr_FR');
						break;
	
					case 'en':
					case 'eng':
					case 'english':
					case 'en_en':
					case 'en_us':
					case 'en_us.utf-8':
					case 'en_en.utf-8':
					case 'en-en':
					case 'en-us':
					case 'en-us.utf-8':
					case 'en-en.utf-8':
						$locale = array('en_US.UTF-8', 'en_US', 'en_EN.UTF-8', 'en_EN');
						break;
						
					default:
						$locale = array($locale);
						break;
				}
			}
			
			$new_locale = setlocale(LC_ALL, $locale);
			
			//debug($new_locale);
			
			if(stripos(strtolower($new_locale), 'utf-8') !== false)
			{
				header('Content-Type: text/html; charset=UTF-8');
			}
			
			
			if(StringTool :: start_with(strtolower($new_locale), 'fr_'))
			{
				Configure::write('Config.language', "fr");
			}
			elseif(StringTool :: start_with(strtolower($new_locale), 'en_'))
			{
				Configure::write('Config.language', "en");
			}
			
			return $new_locale;
		}
	}
	
	
	/**
	 *
	 * @return string The current locale code
	 */
	public static function get_current_locale()
	{
		return setlocale(LC_ALL, '0');
	}
	
	
	/*********************************************************************************************/
	
	
}
?>
