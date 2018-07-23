<?php

namespace App\Models;



class AssignationModel {
	private $_db = null;

	public function __construct($db){
		$this->_db = $db;
	}

	public function objectifassignation($region, $zone, $sous_zone, $id_user, $ids_assignation, $objectif, $accesslevel){
		$q = $this->_db->prepare('INSERT INTO objectif_assignations SET region=:region, zone=:zone, sous_zone=:sous_zone, id_user=:id_user, ids_assignation=:ids_assignation, objectif=:objectif, accesslevel=:accesslevel, date_assigner=NOW(), date_update=NOW() ');
		$q->execute(array(':region' => $region, ':zone' => $zone, ':sous_zone' => $sous_zone, ':id_user' => $id_user, ':ids_assignation' => $ids_assignation, ':objectif' => $objectif, ':accesslevel' => $accesslevel));
		return array("message" => $accesslevel);
	}

    public function assignationsuperviseur($id_client, $client, $id_superviseur, $superviseur, $commentaire, $objectif, $region, $zone, $sous_zone, $infosup){
        $qqq = $this->_db->prepare('INSERT INTO assignations SET id_client=:id_client, client=:client, id_superviseur=:id_superviseur, superviseur=:superviseur, commentaire=:commentaire, objectif=:objectif, region=:region, zone=:zone, sous_zone=:sous_zone, infosup=:infosup, date_assignation=NOW() ');
        $qqq->execute(array(':id_client' => $id_client, ':client' => $client, ':id_superviseur' => $id_superviseur, ':superviseur' => $superviseur, ':commentaire' => $commentaire, ':objectif' => $objectif, ':region' => $region, ':zone' => $zone, ':sous_zone' => $sous_zone, ':infosup' => $infosup));
        return array("message" => $this->_db->lastInsertId());
    }

    public function en_assignation($id_client){
        $q = $this->_db->prepare('SELECT id, infosup FROM points WHERE id=:id');
        $q->execute( array(':id' => $id_client) );
        $customize = $q->fetch();

        $infosup = json_decode($customize['infosup']);
        $infosup->en_assignation = 1;
        $qq = $this->_db->prepare('UPDATE points SET infosup=:infosup WHERE id=:id');
        $qq->execute( array(':id' => $id_client, ':infosup' => json_encode($infosup)) );

        return $this->_db->lastInsertId();
    }

    public function assignationcommercial($id_assignation, $id_commercial, $commercial, $infosup, $commentaire){
		$q = $this->_db->prepare('UPDATE assignations SET id_commercial=:id_commercial, commercial=:commercial, infosup=:infosup, commentaire=:commentaire WHERE id=:id_assignation');
		$q->execute(array(':id_assignation' => $id_assignation, ':id_commercial' => $id_commercial, ':commercial' => $commercial, ':infosup' => $infosup, ':commentaire' => $commentaire));
		return array("message" => $this->_db->lastInsertId());
	}
	
	public function getAssignationsBySuperviseur($id_superviseur){
		$users = array();
		$q = $this->_db->prepare('SELECT ag.*, p_pts.prenom AS p_prenom, p_pts.nom AS p_nom, p_pts.telephone AS p_telephone, pts.adresse_point AS p_adresse_point, pts.nom_point AS p_nom_point
          FROM assignations ag, points pts, proprietaires_point p_pts 
          WHERE ag.id_superviseur=:id_superviseur and ag.id_commercial IS NULL and ag.id_client=pts.id and pts.id_proprietaire_point=p_pts.id
        ');
		$q->execute(array(':id_superviseur' => $id_superviseur));
		while( $user = $q->fetch() ){ 
			$users[] = $user; 
		}
		$q->closeCursor();
		return $users;
	}

	public function getAssignationsByCommercial($id_commercial){
		$users = array();
		$q = $this->_db->prepare('SELECT ag.*, p_pts.prenom AS p_prenom, p_pts.nom AS p_nom, p_pts.telephone AS p_telephone, pts.adresse_point AS p_adresse_point, pts.nom_point AS p_nom_point 
          FROM assignations ag, points pts, proprietaires_point p_pts 
          WHERE ag.id_commercial=:id_commercial and ag.id_client=pts.id and pts.id_proprietaire_point=p_pts.id');
		$q->execute(array(':id_commercial' => $id_commercial));
		while( $user = $q->fetch() ){ 
			$users[] = $user; 
		}
		$q->closeCursor();
		return $users;
	}

	public function deleteAssignationAfterProspect($id){
		$q = $this->_db->prepare('DELETE FROM assignations WHERE id=:id');
		$q->execute(array(':id' => $id));
		return array("message" => "ok");
	}





}