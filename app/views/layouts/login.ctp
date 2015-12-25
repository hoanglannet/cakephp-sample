<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		COSIIN :: <?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $this->Html->meta('icon');

		echo $this->Html->css('login');
		
		echo $this->Html->script('jquery.min');
		echo $this->Html->script('jquery.autocomplete');

		echo $scripts_for_layout;
	?>
	<script type="text/javascript">
		var context = '<?php 
			Configure::load('appconfig'); 
			echo Configure::read("App.CONTEXT_PATH"); 
		?>';
	</script>
</head>
<body>
	<div id='productLogo'>
		<div style="padding-bottom: 5px;"><img src="<?php echo $this->webroot; ?>images/logo-cosiin-product-partner.png"/></div>
		<div style="font-size: 11px; text-transform: uppercase; color: #aaa; letter-spacing: 3px;"></div>
	</div>
	
	<div id="bodyContainer">
	
		<?php echo $this->Session->flash(); ?>

		<?php echo $content_for_layout; ?>
		
	</div>

	<div id="footer" class='container'>
	<a href="http://cosiin.com" style="color: #0071bb" target="_blank"><img src="<?php echo $this->webroot; ?>images/logo-cosiin.png" width="50px"/></a>
	<br>
		<br>
		Copyright © 2015 Partner. All Rights Reserved. Powered by <a href="http://cosiin.com" style="color: #0071bb" target="_blank">Cosiin</a>
		
		
	</div>
</body>
</html>