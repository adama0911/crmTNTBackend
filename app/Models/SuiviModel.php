<?php

namespace App\Models;



class SuiviModel {
	private $_db = null;

	public function __construct($db){
		$this->_db = $db;
	}

	public function ajoutsuivifromprospect($dates_suivi, $id_client, $id_commercial, $id_superviseur, $client, $commercial, $superviseur, $note, $infosup, $id_assigner, $reponse){
		$q = $this->_db->prepare('INSERT INTO suivis SET dates_suivi=:dates_suivi, id_client=:id_client, id_commercial=:id_commercial, id_superviseur=:id_superviseur, client=:client, commercial=:commercial, superviseur=:superviseur, note=:note, infosup=:infosup, id_assigner=:id_assigner, reponse=:reponse, niveau=1');
		$q->execute(array(':dates_suivi' => $dates_suivi, ':id_client' => $id_client, ':id_commercial' => $id_commercial, ':id_superviseur' => $id_superviseur, ':client' => $client, ':commercial' => $commercial, ':superviseur' => $superviseur, ':note' => $note, ':infosup' => $infosup, ':id_assigner' => $id_assigner, ':reponse' => $reponse));
		return array("message" => $this->_db->lastInsertId());
	}

    public function getlastsuivibyidclient($id_client){
        $q = $this->_db->prepare("SELECT suivi.client FROM suivis suivi where suivi.id_client=:id_client ORDER BY id DESC limit 1");
        $q->execute(array(':id_client' => $id_client));
        $customize = $q->fetch();
        return $customize;
    }

    public function updatesuivibyidclient($id_client, $client){
        $q = $this->_db->prepare('UPDATE suivis SET client=:client WHERE id_client=:id_client');
        $q->execute(array(':id_client' => $id_client, ':client' => $client));
        return array('message' => 'oksuivi');
    }

    public function listsuiviforsuperviseur($id_superviseur){
		$users = array();
		$q = $this->_db->prepare('SELECT 
          suivi.*, p_pts.prenom AS p_prenom, p_pts.nom AS p_nom, p_pts.telephone AS p_telephone, pts.adresse_point AS p_adresse_point, pts.nom_point AS p_nom_point 
          FROM suivis suivi, points pts, proprietaires_point p_pts 
          WHERE suivi.id_superviseur=:id_superviseur and suivi.niveau=1 and suivi.id_client=pts.id and pts.id_proprietaire_point=p_pts.id
        ');
		$q->execute(array(':id_superviseur' => $id_superviseur));
		while( $user = $q->fetch() ){ 
			$users[] = $user; 
		}
		$q->closeCursor();
		return $users;
	}

    public function listsuiviarelancerforsuperviseur($id_superviseur, $option){
        $users = array();
        $q = $this->_db->prepare('SELECT suivi.*, p_pts.prenom AS p_prenom, p_pts.nom AS p_nom, p_pts.telephone AS p_telephone, pts.adresse_point AS p_adresse_point, pts.nom_point AS p_nom_point
          FROM suivis suivi, points pts, proprietaires_point p_pts 
          WHERE suivi.id_superviseur=:id_superviseur and suivi.niveau=1 and suivi.qualification=:option and suivi.id_client=pts.id and pts.id_proprietaire_point=p_pts.id ');
        $q->execute(array(':id_superviseur' => $id_superviseur, ':option' => $option));
        while( $user = $q->fetch() ){
            $users[] = $user;
        }
        $q->closeCursor();
        return $users;
    }

    public function listsuivivalider(){
        $users = array();
        $q = $this->_db->query('SELECT pts.id FROM points pts WHERE pts.infosup LIKE "%service_%1%en_assignation%"');
        while( $user = $q->fetch() ){
            $users[] = $user;
        }
        $q->closeCursor();
        return $users;
    }

    public function ajoutsuivifromsuperviseur($id, $reponse, $qualification, $dates_suivi, $niveau){
		$q = $this->_db->prepare('UPDATE suivis SET reponse=:reponse, qualification=:qualification, dates_suivi=:dates_suivi, niveau=:niveau WHERE id=:id');
		$q->execute(array(':id' => $id, ':reponse' => $reponse, ':qualification' => $qualification, ':dates_suivi' => $dates_suivi, ':niveau' => $niveau));
		return array('message' => 'ok');
	}

	public function deletesuivi($id){
		$q = $this->_db->prepare('DELETE FROM suivis WHERE id= :id');
      	$q->execute(array(':id' => $id));
		return array("message" => 'ok');
	}

    public function en_suivi_assignation($id_client, $type, $option){
        $q = $this->_db->prepare('SELECT id, infosup FROM points WHERE id=:id');
        $q->execute( array(':id' => $id_client) );
        $customize = $q->fetch();

        $infosup = json_decode($customize['infosup']);
        if($type=="Valider"){
            if($option=="SenT-Wafa"){
                $infosup->service_sentool = 1;
                $infosup->service_wafacash = 1;
                $infosup->en_deployement = 0;
            }
            if($option=="SenTool"){
                $infosup->service_sentool = 1;
                $infosup->en_deployement = 0;
            }
            if($option=="Wafa"){
                $infosup->service_wafacash = 1;
            }
        }
        $infosup->en_suivi = 1;
        $infosup->en_assignation = 0;
        $qq = $this->_db->prepare('UPDATE points SET infosup=:infosup WHERE id=:id');
        $qq->execute( array(':id' => $id_client, ':infosup' => json_encode($infosup)) );

        return $this->_db->lastInsertId();
    }

    public function getObjectifAssignerCommercial($id_user){
		$q = $this->_db->prepare("SELECT * FROM objectif_assignations WHERE id_user=:id_user and accesslevel='commercial' ORDER BY id DESC limit 1");
		$q->execute(array(':id_user' => $id_user));
		$customize = $q->fetch();
		return $customize;
	}
	
	public function getObjectifAssignerSuperviseur($id_user){
		$q = $this->_db->prepare("SELECT * FROM objectif_assignations WHERE id_user=:id_user and accesslevel='superviseur' ORDER BY id DESC limit 1");
		$q->execute(array(':id_user' => $id_user));
		$customize = $q->fetch();
		return $customize;
	}
	
	public function ajoutObjectifAssigner($id, $atteint){
		$q = $this->_db->prepare('UPDATE objectif_assignations SET date_update=NOW(), atteint=:atteint WHERE id=:id');
		$q->execute(array(':id' => $id, ':atteint' => $atteint+1));
		return array('message' => $id.'-'.$atteint);
	}

    public function getsuperviseursforperformance($id_user){
        $users = array();
        $q = $this->_db->prepare("SELECT u.id_user as id_superviseur, u.prenom, u.nom, u.telephone, o_a.* FROM users u, objectif_assignations o_a where u.accesslevel=4 and u.depends_on=:id_user and u.id_user=o_a.id_user ORDER BY o_a.id DESC ");
        $q->execute(array(':id_user' => $id_user));
        while( $user = $q->fetch() ){
            $users[] = $user;
        }
        $q->closeCursor();

        return $users;
    }

    public function getsuperviseursforperformancetest(){
        $users = array();
        $q = $this->_db->prepare("SELECT u.id_user as id_superviseur, u.prenom, u.nom, u.telephone, o_a.* FROM users u, objectif_assignations o_a where u.accesslevel=4 and u.id_user=o_a.id_user ORDER BY o_a.id_user ");
        $q->execute(array());
        while( $user = $q->fetch() ){
            $users[] = $user;
        }
        $q->closeCursor();

        return $users;
    }

    public function getsuperviseurs($id_user){
		$users = array();
		$q = $this->_db->prepare("SELECT u.id_user as id_superviseur, CONCAT(u.prenom,' ', u.nom) AS fullname_superviseur FROM users u WHERE u.accesslevel=4 and u.depends_on=:id_user");
		$q->execute(array(':id_user' => $id_user));
		while( $user = $q->fetch() ){ 
			$users[] = $user; 
		}
		$q->closeCursor();
		return $users;
	}
	
	public function getcommerciauxforperformance($id_user){
		$users = array();
		$q = $this->_db->prepare("SELECT u.id_user as id_commercial, CONCAT(u.prenom,' ', u.nom) AS fullname_commercial, u.telephone, o_a.* FROM users u, objectif_assignations o_a where u.accesslevel=5 and u.depends_on=:id_user and u.id_user=o_a.id_user ORDER BY o_a.id DESC ");
		$q->execute(array(':id_user' => $id_user));
		while( $user = $q->fetch() ){ 
			$users[] = $user; 
		}
		$q->closeCursor();
		return $this->unique_multidim_array($users, 'id_commercial');
	}

	private function unique_multidim_array($array, $key) { 
    	$temp_array = array(); 
    	$i = 0; 
    	$key_array = array(); 

    	foreach($array as $val) { 
        		if (!in_array($val[$key], $key_array)) { 
            		$key_array[$i] = $val[$key]; 
            		$temp_array[$i] = $val; 
        		} 
        		$i++; 
    	} 
   		return $temp_array; 
	} 

}