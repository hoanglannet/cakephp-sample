<?php echo $form->create("User", array("action" => "register")); ?>
<div id="loginDiv">
	<div class="login-row login-row-first">
		<div class="intro">Register</div>
		<div class="flash-msg">
			<?php echo $this->Session->flash(); ?>
		</div>
	</div>
	<div class="login-row">
		<label for="UserUsername" class="inlined">Username</label>
		<?php echo $form->text("username", array("class" => "login-input-text")); ?>&nbsp;
	</div>
	<div class="login-row">
		<label for="UserPassword" class="inlined">Password</label>
		<?php echo $form->password("password",  array("class" => "login-input-text")); ?>&nbsp;
	</div>
	<div class="login-row">
		<label for="UserPassword" class="inlined">Confirm Password</label>
		<?php echo $form->password("confirm", array("class" => "login-input-text")); ?>&nbsp;
	</div>
	<div class="login-row">
		<label for="UserEmail" class="inlined">Email</label>
		<?php echo $form->text("email", array("class" => "login-input-text")); ?>&nbsp;
	</div>
	
	<div class="login-row">
		<label for="UserUsername" class="inlined">Fullname</label>
		<?php echo $form->text("name", array("class" => "login-input-text")); ?>&nbsp;
	</div>
	
	<div class="login-row login-row-last clearfix">
		<div style="float: right;">
			<?php echo $form->submit("Register", array("div" => false)); ?>
		</div>
		<div style="float: left; margin-top: 8px;">
			<?php echo $this->Html->link('Login', array('controller' => 'users', 'action' => 'login'), array());?>
		</div>
		
	</div>
</div>