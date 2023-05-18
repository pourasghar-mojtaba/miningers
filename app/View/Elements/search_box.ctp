<div class="searchBar">
<div class="searchM">
    <?php echo $this->Form->create('User', array('id'=>'SearchForm','name'=>'SearchForm','class'=>'searchBar_form','action'=>'/search','autocomplete'=>'off')); ?>
    	<ul class="SelectOption">
        	<li class="header" style=""><div class="topBtm2"></div>
			<span title="1" role="/madaner/users/search"><?php echo __('users') ?></span></li>
            <li style="display: none;" title="1" role="/users/search"><?php echo __('users') ?></li>
            <li style="display: none;" title="2" role="/posts/search"> <?php echo __('news_content') ?></li>
            <li style="display: none;" title="3" role="/posts/search_tag"> <?php echo __('tag') ?></li>
        </ul>
    	<input type="search" placeholder="<?php echo __('search'); ?>" name="data[User][search_word]" id="search_box" class="learn_search_box">
		
        <input type="submit" class="tile size_2 gray1" value=" ">
    </form>
	<div id="search_display">
			 <div id="search_result"></div>
			 <div id="search_loading"></div>
    </div>
    </div>
</div>