<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		COSIIN :: Partner
	</title>
	
	<?php
		echo $this->Html->meta('icon');

		echo $this->Html->css('jquery/jquery-ui.custom');
		echo $this->Html->css('style');

		echo $this->Html->script('jquery/jquery.min');
		echo $this->Html->script('jquery/jquery-ui.custom.min');
		echo $this->Html->script('jquery/jquery.autocomplete');
		echo $this->Html->script('jquery/jquery.jsort.0.4');
		echo $this->Html->script('fileuploader');
		echo $this->Html->script('common-helper');
        echo $this->Html->script('selectboxes');
		echo $this->Html->script('jquery/jquery-validators-1.2');
		
		echo $this->Html->script('jquery/jquery.metadata');
		echo $this->Html->script('jquery/jquery.tablesorter.min');

		echo $scripts_for_layout;

	?>
	<script type="text/javascript">
		$(document).ready(function(){
			var menuId = '<?php if (isset($menuActive)) echo $menuActive;?>';
			if (menuId != '') {
				activeMenu('headerBottom', menuId);
			}
		});
		var context = '<?php 
			Configure::load('appconfig'); 
			echo Configure::read("App.CONTEXT_PATH"); 
		?>';

		var uploadImageSpaces ='/<?php echo UPLOAD_IMAGE_SPACES;?>'; 
	</script>
</head>
<body>
	<div id="header" class="ui-corner-bottom-12">
		<div class="wrapper">
		<div id="logo"></div>
		<div class="app-name"></div>
		<?php echo $this->element('menu/header');?>
		</div>
	</div>
	
	<div id="bodyWrapper">
		<div id="bodyContainer">		
			<?php echo $content_for_layout; ?>			
		</div>
	</div>
	
	<?php echo $this->element('menu/footer');?>
	
	<?php echo $this->element('ajax_loading'); ?>
	<?php //echo $this->element('sql_dump'); ?>
</body>
</html>