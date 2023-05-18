<?php
/*******************************************************************************************************
 * This element allows to render an horizontal toolbar containing icons for CRUD actions
 * By default, the toolbar also contains the Paginator %count and %pages numbers.
 */

/*
* include CSS required to format the toolbar
*/
$this->AlaxosHtml->css('/alaxos/css/alaxos', null, array('inline' => false));

$container_class  = isset($container_class)  ? $container_class  : 'toolbar_container';
$toolbar_class    = isset($toolbar_class)    ? $toolbar_class    : 'toolbar';
$separator        = isset($separator)        ? $separator        : '&nbsp;&nbsp;';
$counter          = isset($counter)          ? $counter          : true;
$pagination_limit = isset($pagination_limit) ? $pagination_limit : false;

if($pagination_limit && isset($this->AlaxosForm) && isset($this->Paginator) && isset($this->Paginator->params['paging']) && count($this->Paginator->params['paging']) > 0)
{
    echo '  <div style="text-align:right;">';
    echo    $this->AlaxosForm->input_pagination_limit();
    echo '  </div>';
}

echo '<div class="' . $container_class . '">';
echo '  <div class="' . $toolbar_class . '">';
	
	/*******************************************************************************************
	 * add link
	 */
    if((isset($add) && $add) || isset($add_link))
    {
    	if(isset($add) && $add)
    	{
    		echo $this->Html->link($this->Html->image('/alaxos/img/toolbar/add.png', array('alt' => ___d('alaxos', 'add'))), array('action'=>'add'), array('title' => ___d('alaxos', 'add'), 'escape' => false));
    	}
    	elseif(isset($add_link))
    	{
    		echo $this->Html->link($this->Html->image('/alaxos/img/toolbar/add.png', array('alt' => ___d('alaxos', 'add'))), $add_link, array('title' => ___d('alaxos', 'add'), 'escape' => false));
    	}
    	
    	if(isset($add_text) && $add_text)
	    {
	        echo '&nbsp;' . $add_text;
	    }
	    
		echo $separator;
    }
	
    /*******************************************************************************************
	 * back to list link
	 */
    if((isset($list) && $list) || isset($list_link))
    {
    	if(isset($list) && $list)
    	{
    		echo $this->Html->link($this->Html->image('/alaxos/img/toolbar/list.png', array('alt' => __d('alaxos', 'list'))), array('action' => 'index'), array('title' => ___d('alaxos', 'list'), 'escape' => false));
    	}
    	elseif(isset($list_link))
    	{
    		echo $this->Html->link($this->Html->image('/alaxos/img/toolbar/list.png', array('alt' => __d('alaxos', 'list'))), $list_link, array('title' => ___d('alaxos', 'list'), 'escape' => false));
    	}
    	
    	echo $separator;
    }
    
    /*******************************************************************************************
	 * separator between general actions and actions that apply to a specific action
	 */
    if(((isset($add) && $add) || isset($add_link) || (isset($list) && $list) || isset($list_link))
        &&
        (isset($back_to_view_id) || isset($back_to_view_link) || isset($edit_id) || isset($edit_link) || isset($copy_id) || isset($copy_link) || isset($delete_id) || isset($delete_link) || isset($deactivate_id) || isset($deactivate_link) || isset($reactivate_id) || isset($reactivate_link)))
    {
        echo $this->Html->image('/alaxos/img/toolbar/separator.png');
        echo $separator;
    }
    
    /*******************************************************************************************
	 * back to view link
	 */
    if(isset($back_to_view_id) || isset($back_to_view_link))
    {
    	if(isset($back_to_view_id))
    	{
    		echo $this->Html->link($this->Html->image('/alaxos/img/toolbar/undo.png', array('alt' => ___d('alaxos', 'back'))), array('action'=>'view', $back_to_view_id), array('title' => ___d('alaxos', 'cancel'), 'escape' => false));
    	}
    	elseif(isset($back_to_view_link))
    	{
    		echo $this->Html->link($this->Html->image('/alaxos/img/toolbar/undo.png', array('alt' => ___d('alaxos', 'undo'))), $back_to_view_link, array('title' => ___d('alaxos', 'cancel'), 'escape' => false));
    	}
    	
    	echo $separator;
    }
    
	/*******************************************************************************************
	 * edit link
	 */
    if(isset($edit_id) || isset($edit_link))
    {
    	if(isset($edit_id))
    	{
    		echo $this->Html->link($this->Html->image('/alaxos/img/toolbar/editor.png', array('alt' => __d('alaxos', 'edit'))), array('action'=>'edit', $edit_id), array('title' => ___d('alaxos', 'edit'), 'escape' => false));
    	}
    	elseif(isset($edit_link))
    	{
    		echo $this->Html->link($this->Html->image('/alaxos/img/toolbar/editor.png', array('alt' => __d('alaxos', 'edit'))), $edit_link, array('title' => ___d('alaxos', 'edit'), 'escape' => false));
    	}
    	
    	echo $separator;
    }
    
    /*******************************************************************************************
	 * copy link
	 */
    if(isset($copy_id) || isset($copy_link))
    {
    	if(isset($copy_id))
    	{
    		echo $this->Html->link($this->Html->image('/alaxos/img/toolbar/copy.png', array('alt' => __d('alaxos', 'copy'))), array('action' => 'copy', $copy_id), array('title' => ___d('alaxos', 'copy'), 'escape' => false));
    	}
    	elseif(isset($copy_link))
    	{
    		echo $this->Html->link($this->Html->image('/alaxos/img/toolbar/copy.png', array('alt' => __d('alaxos', 'copy'))), $copy_link, array('title' => ___d('alaxos', 'copy'), 'escape' => false));
    	}
    	
    	echo $separator;
    }
    
	
	
	/*******************************************************************************************
	 * delete link
	 */
    if(isset($delete_id) || isset($delete_link))
    {
        $delete_text = isset($delete_text) ? $delete_text : ___d('alaxos', 'do you really want to delete this item ?');
        
    	if(isset($delete_id))
    	{
    		echo $this->Form->postLink($this->Html->image('/alaxos/img/toolbar/drop.png', array('alt' => __d('alaxos', 'delete'))), array('action' => 'delete', $delete_id), array('title' => ___d('alaxos', 'delete'), 'escape' => false), $delete_text);
    		
    	}
    	elseif(isset($delete_link))
    	{
    	    echo $this->Form->postLink($this->Html->image('/alaxos/img/toolbar/drop.png', array('alt' => __d('alaxos', 'delete'))), $delete_link, array('title' => ___d('alaxos', 'delete'), 'escape' => false), $delete_text);
    	}
    	
    	echo $separator;
    }
	
	/*******************************************************************************************
	 * deactivate link
	 */
    if(isset($deactivate_id) || isset($deactivate_link))
    {
        $deactivate_text = isset($deactivate_text) ? $deactivate_text : ___d('alaxos', 'do you really want to deactivate this item ?');
        
    	if(isset($deactivate_id))
    	{
    		echo $this->Html->link($this->Html->image('/alaxos/img/toolbar/deactivate22.png', array('alt' => __d('alaxos', 'deactivate'))), array('action' => 'deactivate', $deactivate_id), array('title' => ___d('alaxos', 'deactivate'), 'escape' => false), $deactivate_text);
    	}
    	elseif(isset($deactivate_link))
    	{
    		echo $this->Html->link($this->Html->image('/alaxos/img/toolbar/deactivate22.png', array('alt' => __d('alaxos', 'deactivate'))), $deactivate_link, array('title' => ___d('alaxos', 'deactivate'), 'escape' => false), $deactivate_text);
    	}
    	
    	echo $separator;
    }
	
	/*******************************************************************************************
	 * reactivate link
	 */
    if(isset($reactivate_id) || isset($reactivate_link))
    {
        $reactivate_text = isset($reactivate_text) ? $reactivate_text : ___d('alaxos', 'do you really want to reactivate this item ?');
        
    	if(isset($reactivate_id))
    	{
    		echo $this->Html->link($this->Html->image('/alaxos/img/toolbar/reactivate22.png', array('alt' => __d('alaxos', 'reactivate'))), array('action' => 'activate', $reactivate_id), array('title' => ___d('alaxos', 'reactivate'), 'escape' => false), $reactivate_text);
    	}
    	elseif(isset($reactivate_link))
    	{
    		echo $this->Html->link($this->Html->image('/alaxos/img/toolbar/reactivate22.png', array('alt' => __d('alaxos', 'reactivate'))), $reactivate_link, array('title' => ___d('alaxos', 'reactivate'), 'escape' => false), $reactivate_text);
    	}
    	
    	echo $separator;
    }
	
	/*******************************************************************************************
	 * additional links
	 */
	if(isset($additional_buttons))
	{
	    foreach($additional_buttons as $additional_button)
	    {
	        echo $additional_button;
	        echo $separator;
	    }
	}
	
	echo '</div>';
	
	/*******************************************************************************************
	 * PaginatorHelper counters
	 */
	if($counter)
	{
		if(isset($this->Paginator) && isset($this->Paginator->params['paging']) && count($this->Paginator->params['paging']) > 0)
		{
		    echo '<div class="paging_info">';
		    
			echo $this->Paginator->counter(array('format' => ___d('alaxos', 'elements: %count%')));
			echo '<br/>';
			echo $this->Paginator->counter(array('format' => ___d('alaxos', 'page %page% on %pages%')));
			
			echo '</div>';
		}
	}
	
echo '</div>';
?>
