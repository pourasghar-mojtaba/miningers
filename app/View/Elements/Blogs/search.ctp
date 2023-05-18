<aside class="archive">
    <header> <?php  echo __('search_in_archive') ?> </header>
	<?php echo $this->Form->create('Blog', array('id'=>'SearchBlog','name'=>'SearchBlog','action'=>'/search')); ?>
        <label> <?php  echo __('year') ?> </label>
        <select name="year">
            <?php
			   echo "<option value=''>--------</option>";
				for($i=2013;$i<=2030;$i++)
				{
					if(isset($year)|| !empty($year))
					{
						if($year==$i){
							echo "<option value='".$i."' selected>".$i."</option>";
							continue;
						}
					}
					echo "<option value='".$i."'>".$i."</option>";
				}
			?>
        </select>
        <label><?php  echo __('month') ?></label>
        <select name="month">
            <?php
				echo "<option value=''>--------</option>";
				for($i=1;$i<=12;$i++)
				{
					if(strlen($i)==1) $i='0'.$i;
					if(isset($month)|| !empty($month))
					{
						if($month==$i){
							echo "<option value='".$i."' selected>".$i."</option>";
							continue;
						}
					}
					echo "<option value='".$i."'>".$i."</option>";
				}
			?>
        </select>
        <br>
        <input name="writer" type="text" placeholder="<?php  echo __('writer') ?>" value="<?php if(isset($writer)) echo $writer ?>">
        <br>
        <input name="search_text" type="text" placeholder=" <?php  echo __('part_of_search_text') ?>" value="<?php if(isset($search_text)) echo $search_text ?>">
        <br>
		<input name="tag" type="text" placeholder="<?php  echo __('tag') ?>" value="<?php if(isset($tag)) echo $tag ?>">
        <br>
        <input type="submit" value="<?php  echo __('search') ?>" name="">
    </form>
</aside>