<?php

namespace App\Models;



class AuthModel {
	private $_db = null;

	public function __construct($db){
		$this->_db = $db;
	}

	public function existUser($login, $pwd){
		$q = $this->_db->prepare('SELECT COUNT(*) FROM users WHERE login=:login and pwd=:pwd');
		$q->execute(array(':login' => $login, ':pwd' => $pwd));
		return array("message" => (bool) $q->fetchColumn());
	}
	
	public function isUser($token){
		$q = $this->_db->prepare('SELECT u.id_user, u.accesslevel, u.depends_on FROM users u WHERE u.token=:token');
		$q->execute(array(':token' => $token));
		$customize = $q->fetch();
		return array("message" => $customize);
	}

	public function getuserbyid($login, $pwd){
        $q = $this->_db->prepare('SELECT u.id_user, u.nom, u.prenom, u.accesslevel FROM users u  WHERE u.login=:login and u.pwd=:pwd');
        $q->execute(array(':login' => $login, ':pwd' => $pwd));
        $customize = $q->fetch();
        return array("message" => $customize);
    }

    public function login($login, $pwd){
        $etat = $this->getuserbyid($login, $pwd);
        if($etat['message'] ){
            $Allowed_Chars = 'BCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789./';
            $salt = "";
            for($i=0; $i<=20; $i++){$salt .= $Allowed_Chars[mt_rand(0,60)];}
            $token = crypt($pwd . $login, '$2a$05$' . $salt . '$');
            $q = $this->_db->prepare('UPDATE users u SET u.token=:token WHERE u.id_user=:id_user');
            $q->execute(array(':token' => $token, ':id_user' => $etat['message']['id_user']));
            return array(
            	"codeerror" => true, 
            	"message" => array(
            		"prenom" => $etat['message']['nom'], "basetoken" => $token, "accesslevel" => $etat['message']['accesslevel'])
            );
        }
        else {
            return array("codeerror" => false, "message" => $etat);
        }
    }

	public function logout(){
		$users = array();
		$q = $this->_db->query('SELECT u.login, u.prenom FROM users u');
		while( $user = $q->fetch() ){ 
			$users[] = $user; 
		}
		$q->closeCursor();
		return $users;
	}

}