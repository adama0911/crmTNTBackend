<?php

namespace App\Models;



class UserModel {
	private $_db = null;

	public function __construct($db){
		$this->_db = $db;
	}
}
