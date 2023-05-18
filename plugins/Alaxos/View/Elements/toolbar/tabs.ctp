<?php
/*******************************************************************************************************
 * This element allows to render an horizontal toolbar whose style looks like tabs
 *
 * Note: to be automatically CSS styled, the element must be called from a page view
 *       the CSS styles are not included if the element is called from a layout
 */

$tabs_container_css = isset($tabs_container_css) ? $tabs_container_css : 'tabs_container';

/*
 * Include required CSS file for the tabs element by default
 */
if(!isset($import_css) || $import_css === true)
{
	$this->AlaxosHtml->include_css_tabs();
}

if(isset($tabs) && count($tabs) > 0)
{
    echo '<div class="' . $tabs_container_css . '">';
    echo '<ul>';
    
    foreach($tabs as $tab_id => $tab)
    {
        if(isset($selected) && $selected == $tab_id)
        {
            echo '<li class="selected">';
        }
        else
        {
            echo '<li>';
        }
        
        echo $this->Html->link($tab['label'], $tab['link']);
        echo '</li>';
    }
    
    echo '</ul>';
    echo '</div>';
}
else
{
    echo '<em>uninitialized tabs</em>';
}
?>