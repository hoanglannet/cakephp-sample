<script>
$(document).ready(function(){
	$("label.inlined + input.login-input-text").each(function (type) {

		$(this).focus(function () {
			$(this).prev("label.inlined").addClass("focus");
		});
 
		$(this).keypress(function () {
			$(this).prev("label.inlined").addClass("has-text").removeClass("focus");
		});
 
		$(this).blur(function () {
			if($(this).val() == "") {
				$(this).prev("label.inlined").removeClass("has-text").removeClass("focus");
			}
		});
	});
});

</script>



<?php echo $form->create("User", array("action" => "login")); ?>
<div id="loginDiv">
	<div class="login-row login-row-first">
		<div class="intro">LogIn</div>
		<div class="flash-msg">
			<?php echo $session->flash("auth"); ?>
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
	<div class="login-row login-row-last clearfix">
		<div style="float: right;">
			<?php echo $form->submit("Log In", array("div" => false)); ?>&nbsp;
		</div>
		<div style="float: left; margin-top: 8px;">
			<?php echo $this->Html->link('Register', array('controller' => 'users', 'action' => 'register'), array('style' => ''));?> &nbsp;
			<?php
			Configure::load('appconfig'); 
			$webroot = Configure::read("App.CONTEXT_PATH");   
			//echo '<a href="'.$webroot.'userguide.html" target="_blank">Help</a>'; 
		?>
		</div>
	</div>
	
</div>