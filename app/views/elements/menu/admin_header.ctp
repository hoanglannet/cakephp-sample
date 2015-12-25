<ul>
<?php
	echo '<li>'.$this->Html->link('Users', array('controller' => 'users', 'action' => 'index'), array('id' => 'userId')).'</li>';
	echo '<li>'.$this->Html->link('Logout', array('controller' => 'users', 'action' => 'logout'), array('id' => 'logoutId')).'</li>';
?>
</ul>
