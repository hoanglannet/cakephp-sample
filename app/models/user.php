<?php

class User extends AppModel {
	var $name = 'User';
	var $belongsTo = 'Group';
	var $validate = array(
		    'username' => array(
				'isUnique' => array(
		        	'rule' => 'isUnique',
		        	'message' => 'This user existed.'
	        	),
	        	'notEmpty' => array(
		        	'rule' => 'notEmpty',
		        	'message' => 'Username is empty.'
	        	)

	    	),
	    	'email' => array(
				'email' => array(
		        	'rule' => 'email',
		        	'message' => 'This email is invalid.'
	        	)
	    	)
	);
}
?>