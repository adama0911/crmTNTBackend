<?php

namespace App\Models;



class UserModel {
	private $_db = null;

	public function __construct($db){
		$this->_db = $db;
	}

	public function getAdministratifs(){
		$users = array();
		$q = $this->_db->query('SELECT u.id_user as id, u.prenom, u.nom FROM users u where u.accesslevel=6');
		while( $user = $q->fetch() ){
			$users[] = $user;
		}
		$q->closeCursor();
		return $users;
	}

    public function getCommerciaux(){
        $users = array();
        $q = $this->_db->query('SELECT u.id_user as id, u.depends_on AS id_superviseur, u.prenom, u.nom, u.telephone, u.login AS email, CONCAT(u.prenom,\' \', u.nom) AS fullname, CONCAT(u_depends.prenom," ", u_depends.nom) AS fullname_depends FROM users u, users u_depends WHERE u.accesslevel=5 and u.depends_on=u_depends.id_user');
        while( $user = $q->fetch() ){
            $users[] = $user;
        }
        $q->closeCursor();
        return $users;
    }

    public function ajoutCommercialBySuperviseur($prenom, $nom, $login, $pwd, $id_superviseur, $telephone){
        $etat = $this->getUserByEmail($login);
        if($etat == false){
            $q = $this->_db->prepare('INSERT INTO users SET prenom=:prenom, nom=:nom, login=:login, pwd=:pwd, accesslevel=5, depends_on=:depends_on, date_creation=:date_creation, telephone=:telephone, token=:token');
            $q->execute(array(':prenom' => $prenom, ':nom' => $nom, ':login' => $login, ':pwd' => $pwd, ':depends_on' => $id_superviseur, ':date_creation' => date("Y-m-d H:i:s"), ':telephone' => $telephone, ':token' => "-"));
            return $this->_db->lastInsertId();
        }
        else{
            return 'exist';
        }
    }

    public function ajoutSuperviseur($prenom, $nom, $login, $pwd, $id_superviseur, $telephone){
        $etat = $this->getUserByEmail($login);
        if($etat == false){
            $q = $this->_db->prepare('INSERT INTO users SET prenom=:prenom, nom=:nom, login=:login, pwd=:pwd, accesslevel=4, depends_on=:depends_on, date_creation=:date_creation, telephone=:telephone, token=:token');
            $q->execute(array(':prenom' => $prenom, ':nom' => $nom, ':login' => $login, ':pwd' => $pwd, ':depends_on' => $id_superviseur, ':date_creation' => date("Y-m-d H:i:s"), ':telephone' => $telephone, ':token' => "-"));
            return $this->_db->lastInsertId();
        }
        else{
            return 'exist';
        }
    }

    public function getUserByEmail($login){
        $q = $this->_db->prepare('SELECT u.prenom, u.nom, u.depends_on FROM users u WHERE u.login=:login');
        $q->execute(array(':login' => $login));
        $customize = $q->fetch();
        return $customize;
    }

    public function getCommerciauxBySuperviseur($id_superviseur){
        $users = array();
        $q = $this->_db->prepare('SELECT u.id_user as id, u.prenom, u.nom, u.telephone, u.login AS email, CONCAT(u.prenom,\' \', u.nom) AS fullname FROM users u where u.accesslevel=5 and u.depends_on=:id_superviseur');
        $q->execute(array(':id_superviseur' => $id_superviseur));
        while( $user = $q->fetch() ){
            $users[] = $user;
        }
        $q->closeCursor();
        return $users;
    }

    public function getSuperviseurs(){
		$users = array();
		$q = $this->_db->query('SELECT u.id_user as id, u.prenom, u.nom, u.telephone FROM users u where u.accesslevel=4');
		while( $user = $q->fetch() ){
			$users[] = $user;
		}
		$q->closeCursor();
		return $users;
	}

	public function getAdminCommerciaux(){
		$users = array();
		$q = $this->_db->query('SELECT u.id_user as id, u.prenom, u.nom FROM users u where u.accesslevel=3');
		while( $user = $q->fetch() ){
			$users[] = $user;
		}
		$q->closeCursor();
		return $users;
	}

    public function getRecouvreurs(){
        $users = array();
        $q = $this->_db->query("SELECT u.id_user as id, u.prenom, u.nom, u.telephone, u.login AS email, CONCAT(u.prenom,' ', u.nom) AS fullname FROM users u where u.accesslevel=12 ORDER BY u.date_creation");
        while( $user = $q->fetch() ){
            $users[] = $user;
        }
        $q->closeCursor();
        return $users;
    }

	public function getAdminAdministratifs(){
		$users = array();
		$q = $this->_db->query('SELECT u.id_user as id, u.prenom, u.nom FROM users u where u.accesslevel=2');
		while( $user = $q->fetch() ){
			$users[] = $user;
		}
		$q->closeCursor();
		return $users;
	}

	public function getUser($id){
		$q = $this->_db->prepare('SELECT u.prenom, u.nom, u.depends_on FROM users u WHERE u.id_user=:id');
		$q->execute(array(':id' => $id));
		$customize = $q->fetch();
		return array("message" => $customize);
	}

}
