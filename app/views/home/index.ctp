<div class="form-container ui-corner-all-8 padding-all-20-10">
	<?php 
		Configure::load('appconfig'); 
		$this->webroot = Configure::read("App.CONTEXT_PATH");  
		
		echo $this->Form->create('Home');
		echo '<div class="form-row">';
		
		echo $this->Form->input('searchType', array('label' => 'Company', 'options' => $searchTypeList, 'after' => '&nbsp;', 'value' => 'All'));
		echo $this->Form->input('searchValue', array('label' => '-', 'after' => '&nbsp;'));
		
		echo '<div style="float: left; padding-top: 21px;">';
		echo $this->Form->button('Search', array('type' => 'button', 'class' => 'bntadd', 'id' => 'searchId'));
		
		echo '</div>';
	?>
		
		<div id="detailResult">
			<?php
				echo $this->element('home/company_feed_table');
			?>
		</div>
		<div style="clear: both;"></div>
		
	<?php echo $this->Form->end(); ?>
</div>