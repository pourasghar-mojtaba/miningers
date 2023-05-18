<?php echo $this->Form->create('Recipe', array('id'=>'ChangeCoverImage','name'=>'ChangeCoverImage','enctype'=>'multipart/form-data','action'=>'/edit/2.json','class'=>'cover_image')); ?>
		
	<input name="data[Recipe][name]" type="text" id="name" />
    <input class="btn ok" type="submit" value="<?php echo __('updates') ?>"  />
                            
</form>