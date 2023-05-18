jQuery(document).ready(function()
{
	jQuery("<input type=\"hidden\" value=\"<?php echo $today_fieldname; ?>\" name=\"data[<?php echo $model_name; ?>][<?php echo $today_fieldname; ?>]\" />").prependTo(jQuery("#<?php echo $form_dom_id; ?>"));
});