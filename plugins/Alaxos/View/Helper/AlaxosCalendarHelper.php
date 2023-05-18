<?php
/**
 *
 * @author   Nicolas Rod <nico@alaxos.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.alaxos.ch
 */
class AlaxosCalendarHelper extends Helper
{
    public $helpers = array('Html', 'Alaxos.AlaxosHtml');
    
    public function month_calendar($date_to_show, $options = array())
    {
        $default_options = array(	'events'                   => array(),
                                    'model_name'               => 'Event',
                            		'title_field'              => 'title',
                                    'description_field'        => 'description',
                                    'start_date_field'         => 'start_date',
                                    'end_date_field'           => 'end_date',
                                    'date_field'               => 'date',
        							'start_time_field'         => 'start_time',
        							'end_time_field'           => 'end_time',
                                    'link_field'               => 'link',
                                    'preview_link_field'       => 'preview_link',
                                    'preload_previews'         => false,
                                    'preview_top_offset'       => -20,
                                    'preview_left_offset'      => -20,
                                    'show_description'         => false,
                                    'show_days_of_week'        => true,
        							'title_max_length'         => 30,
        							'description_max_length'   => 30,
                                    'bg_colors'                => array('#D1E5FD', '#F8B8B8', '#E5B8F8', '#A1E7BA', '#FCF698'),
                                    'background_lighting_step' => 5,
                                    'css'                      => false);
        
        $options = array_merge($default_options, $options);
        
        /*
         * Get dates to display in calendar
         */
        $month_dates   = DateTool :: get_month_dates($date_to_show, true);
        $current_month = date('m', strtotime($date_to_show));
        
        /*
         * Include basic CSS
         */
        if($options['css'])
        {
            $this->Html->css('/Alaxos/css/calendar', null, array('inline' => false));
        }
        
        /*
         * Indexes events by date
         */
        if(count($options['events']) > 0)
        {
            $events_by_date = $this->get_events_by_date($options['events'], $month_dates, $options);
        }
        else
        {
            $events_by_date = array();
        }
        
        /*
         * Build calendar to display
         */
        $output = '<table class="calendar_month" cellspacing="0">';
    
        if($options['show_days_of_week'])
        {
            $output .= '<tr>';
            for($i = 0; $i < 7; $i++)
            {
                $output .= '<td class="day_of_week">';
                $output .= strftime('%A', strtotime($month_dates[$i]));
                $output .= '</td>';
            }
            $output .= '</tr>';
        }
        
        $i            = 0;
        $dom_id_index = 0;
        $table_row    = 0;
        
        foreach($month_dates as $date)
        {
            $start_line   = ($i % 7 == 0);
            $end_line     = ($i % 7 == 6);
            $day_of_month = date('d', strtotime($date));
            
            if($start_line)
            {
                $output .= '<tr>';
                $table_row++;
            }
        
            $cell_class = ($current_month == date('m', strtotime($date))) ? 'current_month' : 'other_month';
            
            $output .= '<td class="' . $cell_class . '" style="width:14%;">';
            
            $output .= '<div class="day_number">';
            $output .= $day_of_month;
            if($day_of_month == '01')
            {
                $output .= ' ';
                $output .= strftime('%b', strtotime($date));
            }
            $output .= '</div>';
            
            $output .= '<div class="content">';
            
            foreach($events_by_date[$date] as $day_event)
            {
                $event_dom_id = 'event_' . $day_event[$options['model_name']]['id'] . '_' . $dom_id_index++;
                
                $output .= '<div class="event" id="' . $event_dom_id . '"';
                
                if(isset($day_event[$options['model_name']]['background-color']))
                {
                    $output .= ' style="background-color:' . $day_event[$options['model_name']]['background-color'] . ';"';
                }
                $output .= '>';
                
                    $output .= '<div class="title">';
                    
                    /*
                     * Time
                     */
                    $start_time  = isset($day_event[$options['model_name']][$options['start_time_field']]) ? substr($day_event[$options['model_name']][$options['start_time_field']], 0, 5) : null;
                    $end_time    = isset($day_event[$options['model_name']][$options['end_time_field']])   ? substr($day_event[$options['model_name']][$options['end_time_field']], 0, 5)   : null;
                    if(isset($start_time) && isset($end_time))
                    {
                        $output .= $start_time . ' &ndash; ' . $end_time . '<br/>';
                    }
                    elseif(isset($start_time))
                    {
                        $output .= $start_time . ' &rarr;' . '<br/>';
                    }
                    elseif(isset($end_time))
                    {
                        $output .= '&rarr; ' . $end_time . '<br/>';
                    }
                    elseif(isset($day_event[$options['model_name']]['included_day']))
                    {
                        $output .= '&harr; ' . '<br/>';
                    }
                    
                    /*
                     * Title
                     */
                    $title = StringTool::shorten($day_event[$options['model_name']][$options['title_field']], $options['title_max_length']);
                    if(isset($day_event[$options['model_name']][$options['link_field']]))
                    {
                        $output .= $this->Html->link($title, $day_event[$options['model_name']][$options['link_field']]);
                    }
                    else
                    {
                        $output .= $title;
                    }
                    
                    $output .= '</div>';
                    
                    if($options['show_description'])
                    {
                        $output .= '<div class="description">';
                        $output .= StringTool::shorten($day_event[$options['model_name']][$options['description_field']], $options['description_max_length']);
                        $output .= '</div>';
                    }
                
                $output .= '</div>';
                
                if(isset($day_event[$options['model_name']][$options['preview_link_field']]) && !empty($day_event[$options['model_name']][$options['preview_link_field']]))
                {
                    $loading_template = $this->AlaxosHtml->spinner();
                    $loading_template .= ' ';
                    $loading_template .= __d('Alaxos', 'loading');
                    $loading_template = str_replace('"', '\"', $loading_template);
                    
                    if($table_row < 4)
                    {
                        $tooltip_position = 'br';
                        $top_offset       = $options['preview_top_offset'];
                    }
                    else
                    {
                        $tooltip_position = 'tr';
                        $top_offset       = -1 * $options['preview_top_offset'];
                    }
                    
                    $output .= $this->AlaxosHtml->add_ajax_tooltip($event_dom_id, $day_event[$options['model_name']][$options['preview_link_field']], array('css' => true,
                    																																		'loading_template' => $loading_template,
                    																																		'position'         => $tooltip_position,
                    																																		'preload'          => $options['preload_previews'],
                                                                                                                                                            'top_offset'       => $top_offset,
                                                                                                                                                            'left_offset'      => $options['preview_left_offset']));
                }
            }
            
            $output .= '</div>';
            
            $output .= '</td>';
            
            if($end_line)
            {
                $output .= '</tr>';
            }
            
            $i++;
        }
        
        $output .= '</table>';
        
        
        return $output;
    }
    
    private function get_events_by_date($events, $dates, $options)
    {
        $events_by_date = array_flip($dates);
        foreach($events_by_date as $k => $event_by_date)
        {
            $events_by_date[$k] = array();
        }
        
        /*
         * Foreach events, check if they last more than one day and fill the days accordingly
         */
        $color_lighten   = 0;
        $bg_colors_index = 0;
        foreach($events as $k => $event)
        {
            $start_day = date('Y-m-d', strtotime($event[$options['model_name']][$options['start_date_field']]));
            $end_day   = date('Y-m-d', strtotime($event[$options['model_name']][$options['end_date_field']]));
            $all_days  = DateTool :: get_dates_for_interval($start_day, $end_day);
            
            if(!isset($event[$options['model_name']]['background-color']))
            {
                $bgcolor = $options['bg_colors'][$bg_colors_index];
                $bg_colors_index++;
                $bg_colors_index = $bg_colors_index < count($options['bg_colors']) ? $bg_colors_index : 0;
                
                $event[$options['model_name']]['background-color'] = $this->AlaxosHtml->color_lighten($bgcolor, $color_lighten);
                $color_lighten += $options['background_lighting_step'];
            }
            
            foreach($all_days as $included_day)
            {
                $event_clone = $event;
                
                /*
                 * Test if the times must be included
                 * (we do not include start_time if the day if greater that the start day)
                 */
                if(count($all_days) > 1 && $included_day == $start_day)
                {
                    unset($event_clone[$options['model_name']][$options['end_time_field']]);
                }
                elseif(strtotime($included_day) > strtotime($start_day) && $included_day != $end_day)
                {
                    unset($event_clone[$options['model_name']][$options['start_time_field']]);
                    unset($event_clone[$options['model_name']][$options['end_time_field']]);
                    $event_clone[$options['model_name']]['included_day'] = true;
                }
                elseif(count($all_days) > 1 && $included_day == $end_day)
                {
                    unset($event_clone[$options['model_name']][$options['start_time_field']]);
                }
                
                $events_by_date[$included_day][] = $event_clone;
            }
        }
        
        return $events_by_date;
    }
}