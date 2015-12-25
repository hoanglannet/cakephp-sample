<?php

class Group extends AppModel {
	var $name = 'Group';
	var $validate = array(
	    'name' => array(
        	'rule' => 'isUnique',
        	'message' => 'This group existed.'
    	)
	);
}
?>