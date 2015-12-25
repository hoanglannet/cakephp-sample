<?php
class CompanyController extends AppController {

	var $name = 'Company';
	var $uses = array();
	
	public function beforeFilter() {
		parent::beforeFilter();
	}
	
	function register() {
	}
}
?>