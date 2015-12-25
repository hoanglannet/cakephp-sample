<?php
class HomeController extends AppController {

	var $name = 'Home';
	var $uses = array();
	
	public function beforeFilter() {
		parent::beforeFilter();
	}
	
	function index() {
		$searchTypeList = array("All" => "All", "Resource" => "Resource", "Investment" => "Investment");
		$this->set('searchTypeList', $searchTypeList);
	}
}
?>