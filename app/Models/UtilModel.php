<?php

namespace App\Models;



class UtilModel {
	private $_db = null;

	public function __construct($db){
		$this->_db = $db;
	}

    public function getZones(){
        $users = array();
        $q = $this->_db->query('SELECT z.* FROM zones z');
        while( $user = $q->fetch() ){
            $users[] = $user;
        }
        $q->closeCursor();
        return $users;
    }


}