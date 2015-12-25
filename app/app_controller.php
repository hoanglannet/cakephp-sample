<?php
class AppController extends Controller {
	
	var $components = array('Session', 'Acl', 'Auth');
	
	public function beforeFilter() {
		parent::beforeFilter();
		
		$this->Auth->authorize = 'controller';
		$this->Auth->autoRedirect = true;
		$this->Auth->userScope = array('User.active'=>1);
		$this->Auth->loginAction = array('controller' => 'users', 'action' => 'login');
		
		//Role Admin
		$prefixes = Configure::read('Routing.prefixes'); 
        if ( (isset($this->params['prefix'])) && (strcmp($this->params['prefix'], $prefixes) == 0) ){
            $this->Auth->loginRedirect = array("controller" => "users", "action" => "index", $prefixes => 1);
            $this->Auth->logoutRedirect = array('controller' => 'users', 'action' => 'login', $prefixes => 1);
            
        } else {
        //Role User
	        $this->Auth->loginRedirect = array("controller" => "home", "action" => "index"); 
	        $this->Auth->logoutRedirect = array('controller' => 'users', 'action' => 'login');
        }
	}
	
	public function beforeRender() {
		parent::beforeRender();
		$fullName = '';
		if ($this->Auth->user()) {
			$fullName = $this->Auth->user('name');
		}
		$this->set('fullName', $fullName);
		
	}
	
	function isAuthorized(){
	  return true;
	}
}
?>
