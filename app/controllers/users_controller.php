<?php
class UsersController extends AppController {

	var $name = 'Users';
	var $uses = array('User', 'Group');
	var $components = array('Commons');
	var $helpers = array('AjaxPaginator');
	var $paginate = array(
		'limit' => 10
	);
	
	function isAuthorized(){
	  parent::isAuthorized();
	  
	  Utils::useModels($this, array('Group'));
	  
	  $aro = $this->Group->field('name', array('id' => $this->Auth->user('group_id')));
	  $access = $this->Acl->check($aro, 'Admin');
	  
	  if (!$access) {
	  	if ($this->action != 'login') {
	  		$this->Session->setFlash(__('You do not allow to access this action. ', true), 'default', array(), 'auth');
	  		$this->redirect(array('controller' => 'users', 'action' => 'login'));
	  	}
	  }
	  	 
      return $access;
	}
	
	public function beforeFilter() {
		parent::beforeFilter();
		$this->layout = 'admin';
		$this->Auth->allow('login', 'logout', 'register');	
	}
	
	public function login() {
		$this->set('title_for_layout', 'Login');
		$this->layout = 'login';
		
		
	}
	
	public function logout() {
		$this->Session->delete('cityID');
		$this->Session->delete('cityState');
		$this->redirect($this->Auth->logout());
	}
	
	public function register() {
		$this->set('title_for_layout', 'Register');
		$this->layout = 'login';
		if(!empty($this->data)) {
			if ($this->data['User']['password'] == $this->Auth->password($this->data['User']['confirm'])) {
				$this->data['User']['active'] = 0;
				$this->data['User']['group_id'] = GROUP_NORMAL_ID;				
				if($this->User->save($this->data)) {
					
					//Send mail for Administrator					
					$email = $this->data['User']['email'];
					$subject = "One account has just registered";		
					Configure::load('appconfig');	
					$emailSetting = Configure::read('email');	
					$data = array(
			           		'setting' => $emailSetting,
							'subject'   => $subject,
							'from'  => $emailSetting['email'],
							'fromName' => $emailSetting['username'],
			           		'to'       => array($emailSetting['email']),
							'toName'       => array($emailSetting['username'])
					);					
					$url = Configure::read('App.CONTEXT_PATH');
					$data['htmlBody'] = $this->Commons->buildNotifyToAdminEmail($this->data, $url);					
  					$this->output = '';
  		
					$result = $this->Commons->sendMail($data);
					//End send mail for Administrator
					if ($result) {
						$this->Session->setFlash('Register successfully. Please wait for administrator enable your account. ', 'default', array('class' => 'success'));
					} else {
						$this->Session->setFlash($result, 'default', array('class' => 'error-mess'));
					}
				} else {
					$this->Session->setFlash(implode('</br>', $this->User->invalidFields()), 'default', array('class' => 'error-mess'));
				}
			} else {
				$this->Session->setFlash('Password and Confirm password do not match.', 'default', array('class' => 'error-mess'));
			}
			unset($this->data['User']['password']);
		}
	}
	
	//Admin
	public function admin_login() {
		$this->set('title_for_layout', 'Login');
		$this->layout = 'login';
	}
	
	public function admin_logout() {
		$this->redirect($this->Auth->logout());
	}
	
	function admin_index() {
		$this->User->recursive = 0;
		$this->set('users', $this->paginate());
		$this->set('menuActive', 'userId');
	}

	function admin_add() {
		if(!empty($this->data)) {
			if ($this->data['User']['password'] == $this->Auth->password($this->data['User']['confirm'])) {
				if($this->User->save($this->data)) {
					$this->Session->setFlash('Register successfully.', 'default', array('class' => 'success'));
				} else {
					$this->Session->setFlash(implode('</br>', $this->User->invalidFields()), 'default', array('class' => 'error-message'));
				}
			} else {
				$this->Session->setFlash('Password and Confirm password do not match.', 'default', array('class' => 'error-message'));
			}
			unset($this->data['User']['password']);
		}
		
		$groups = $this->User->Group->find('list');
		$this->set(compact('groups'));
		$this->set('menuActive', 'userId');
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid user', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$oldData = $this->User->read(null, $this->data['User']['id']);
			$isSendMail = false;
			if ($oldData['User']['active'] != $this->data['User']['active']) {
				$isSendMail = true;	
			}		
			if ($this->User->save($this->data)) {
				if($isSendMail == true) {
					$result = $this->sendEmailToUser($this->data);
					if (!$result) {
						$this->Session->setFlash("Send mail to $email fail.");
						$this->redirect(array('action' => 'index'));
					} else {
						$this->Session->setFlash(__('The user has been saved', true));
						$this->redirect(array('action' => 'index'));
					}					
				}
				//End enable account
				$this->Session->setFlash(__('The user has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->User->read(null, $id);
			unset($this->data['User']['password']);
		}
		
		$groups = $this->User->Group->find('list');
		$this->set(compact('groups'));
		$this->set('menuActive', 'userId');
	}

	function admin_delete($id = null, $status = 0) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for user', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->User->save(array('active' => $status, 'id' => $id))) {
			($status == 1)?$this->Session->setFlash(__('User enabled', true)):$this->Session->setFlash(__('User disabled', true));
			$result = $this->sendEmailToUser($this->User->read(null, $id));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('User was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
	
	public function sendEmailToUser($user) {
		$email = $user['User']['email'];
		if ($user['User']['active'] == 1) {
			$subject = "Your mkbexcited account was enabled";
		} else {
			$subject = "Your mkbexcited account was disabled";
		}		
		Configure::load('appconfig');	
		$emailSetting = Configure::read('email');	
		$data = array(
           		'setting' 	=> $emailSetting,
				'subject'   => $subject,
				'from'  	=> $emailSetting['email'],
				'fromName' 	=> $emailSetting['username'],
           		'to'       	=> array($email),
				'toName'	=> array($email)
		);	
		$url = Configure::read('App.CONTEXT_PATH');
		$data['htmlBody'] = $this->Commons->buildNotifyToUserEmail($user, $url); 					
  		$this->output = '';
  
		return $this->Commons->sendMail($data);
	}
}
?>