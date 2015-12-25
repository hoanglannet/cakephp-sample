<div id='headerTop'>
	<ul>
	<?php 
		Configure::load('appconfig'); 
		$webroot = Configure::read("App.CONTEXT_PATH");

		echo '<li><span><label>Your account: </label>'.$fullName.'</span></li>';
		echo '<li>'.$this->Html->link('Log Out', array('controller' => 'users', 'action' => 'logout')).'</li>';
		echo '<li><a href="'.$webroot.'/userguide.html" target="_blank">Help</a></li>';

	?>
	</ul>
</div>	
<div id='headerBottom'>
	<ul>
		<?php
			echo '<li>'.$this->Html->link('Home', array('controller' => 'home', 'action' => 'index'), array('id'=>'homeId')).'</li>';
			echo '<li>'.$this->Html->link('Register your company', array('controller' => 'company', 'action' => 'register'), array('id'=>'registerId')).'</li>';
			echo '<li>'.$this->Html->link('Delegate your business', array('controller' => 'delegate', 'action' => 'delegate'), array('id'=>'delegateId')).'</li>';
		?>
	</ul>
</div>
