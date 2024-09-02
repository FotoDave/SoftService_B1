<?php

namespace SoftService;
require_once 'db_link.inc.php';

use DB\DBLink;
use PDO;

setlocale(LC_TIME, 'fr_FR.utf8', 'fra');

/**
 * Classe Fonction : permet d'attribuer un rôle ou un département à un membre.
 * @author Dave FOTO
 * @version 1.0
 */
class Fonction
{
    private $fid;
    private $pid;
    private $rid;
    private $did;

    public function __get($field)
    {
        return $this->$field;
    }

    public function __set($field, $value)
    {
        $this->$field = $value;
    }
}

/**
 * Classe FonctionRepository : gestionnaire du dépôt contenant les fonctions des membres.
 * @author Dave FOTO
 * @version 1.0
 */
class FonctionRepository
{
    const TABLE_NAME = 'webB1_Fonction';
    const TABLE_PERSONNEL = 'webB1_Personnel';
    const TABLE_DEPARTEMENT = 'webB1_Departement';
    const TABLE_ROLE = 'webB1_Role';

    /**
     * Retourne une fonction spécifique à un memnbre
     * @var integer $id identifiant du membre
     * @var string $message ensemble des messages à retourner à l'utilisateur, séparés par un saut de ligne
     * @return Fonction|null la fonction associée à l'identifiant
     */
    public function findFunctionByPersonnelId($id, &$message){
        $result = null;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("SELECT * FROM " . self::TABLE_NAME . " WHERE pid = :id;");
            //Permet de spécifier les données correspondant à 'id'
            $stmt->bindValue(':id', $id);
            if ($stmt->execute()) {
                $result = $stmt->fetchAll(PDO::FETCH_CLASS, Fonction::class);
            } else {
                $message .= 'Une erreur système est survenue.<br> Veuillez essayer à nouveau plus tard 
                        ou contactez l\'administrateur du site. (Code erreur: ' . $stmt->errorCode() . ')<br>';
            }
            $stmt = null;
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $result;
    }

    /**
     * Retourne une fonction spécifique à un memnbre
     * @var integer $id identifiant du membre
     * @var string $message ensemble des messages à retourner à l'utilisateur, séparés par un saut de ligne
     * @return Fonction|null la fonction associée à l'identifiant
     */
    public function findFunctionByPersonnelAndDepartmentId($id, $did, &$message){
        $result = null;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("SELECT * FROM " . self::TABLE_NAME . " WHERE pid = :id AND did = :did;");
            //Permet de spécifier les données correspondant à 'id'
            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':did', $did);
            if ($stmt->execute()) {
                $result = $stmt->fetchObject("SoftService\Fonction");
            } else {
                $message .= 'Une erreur système est survenue.<br> Veuillez essayer à nouveau plus tard 
                        ou contactez l\'administrateur du site. (Code erreur: ' . $stmt->errorCode() . ')<br>';
            }
            $stmt = null;
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $result;
    }

    /**
     * Retourne les membres par département
     * @var integer $id identifiant du département
     * @var string $message ensemble des messages à retourner à l'utilisateur, séparés par un saut de ligne
     * @return [Personnel,....] personnel(s) associé au département
     */
    public function findPersonnelByDepartement($id, &$message){
        $result = array();
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("SELECT * FROM " . self::TABLE_PERSONNEL . " pers
            JOIN ". self::TABLE_NAME ." fonct ON pers.pid = fonct.pid 
            WHERE fonct.did = :id AND fonct.rid IS NOT NULL
            ORDER BY nom ASC");
            //Permet de spécifier les données correspondant à 'id'
            $stmt->bindValue(':id', $id);
            if ($stmt->execute()) {
                $result = $stmt->fetchAll(PDO::FETCH_CLASS,"SoftService\Personnel");
            } else {
                $message .= 'Une erreur système est survenue.<br> Veuillez essayer à nouveau plus tard 
                        ou contactez l\'administrateur du site. (Code erreur: ' . $stmt->errorCode() . ')<br>';
            }
            $stmt = null;
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $result;
    }

    /**
     * Crée la nouvelle fonction du membre
     * @var Fonction $fonction fonction à ajouter
     * @var string $message ensemble des messages à retourner à l'utilisateur, séparés par un saut de ligne
     * @return boolean true si l'enregistrement s'est fait sans erreur, false sinon.
     */
    public function createFonction($fonction, &$message){
        $noError = false;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("INSERT INTO " . self::TABLE_NAME . " (fid, pid, rid, did) VALUES (:fid, :pid, :rid, :did)");
            $stmt->bindValue(':fid', $fonction->fid);
            $stmt->bindValue(':pid', $fonction->pid);
            $stmt->bindValue(':rid', $fonction->rid);
            $stmt->bindValue(':did', $fonction->did);
            if ($stmt->execute()) {
                $noError = true;
            } else {
                $message .= 'Une erreur système est survenue.<br> Veuillez essayer à nouveau plus tard 
                        ou contactez l\'administrateur du site. (Code erreur: ' . $stmt->errorCode() . ')<br>';
            }
            $stmt = null;
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $noError;
    }

    /**
     * Modifie la fonction du membre
     * @var Fonction $fonction fonction à modifier
     * @var string $message ensemble des messages à retourner à l'utilisateur, séparés par un saut de ligne
     * @return boolean true si la modification s'est effectuée, false sinon.
     */
    public function modificationFonctionPersonnel($fonction, &$message) {
        $noError = false;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("UPDATE " . self::TABLE_NAME . " SET pid = :pid, rid = :rid, did = :did 
                                                            WHERE fid = :fid");
            $stmt->bindValue(':fid', $fonction->fid);
            $stmt->bindValue(':pid', $fonction->pid);
            $stmt->bindValue(':rid', $fonction->rid);
            $stmt->bindValue(':did', $fonction->did);
            if ($stmt->execute()) {
                $noError = true;
            } else {
                $message .= 'Une erreur système est survenue lors de la mise à jour.<br> Veuillez essayer à nouveau plus tard 
                    ou contactez l\'administrateur du site. (Code erreur: ' . $stmt->errorCode() . ')<br>';
            }
            $stmt = null;
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }

        DBLink::disconnect($bdd);
        return $noError;
    }


}