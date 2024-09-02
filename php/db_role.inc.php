<?php

namespace SoftService;
require_once 'db_link.inc.php';

use DB\DBLink;
use PDO;

setlocale(LC_TIME, 'fr_FR.utf8', 'fra');

/**
 * Classe Role : Rôle à attribuer aux differents membres
 * @author Dave FOTO
 * @version 1.0
 */
class Role
{
    private $rid;
    private $role;

    public function __get($field)
    {
        return $this->$field;
    }

    public function __set($field, $value)
    {
        switch ($field) {
            case "role":
                $this->$field = strtolower($value);
                break;
            default:
                $this->$field = $value;
        }
    }
}

/**
 * Classe RoleRepository : gestionnaire du dépôt contenant les rôles des membres
 * @author Dave FOTO
 * @version 1.0
 */
class RoleRepository
{
    const TABLE_NAME = 'webB1_Role';

    /**
     * Retourne la liste des rôles à attribuer aux differents membres
     * @var string $message ensemble des messages à retourner à l'utilisateur, séparés par un saut de ligne
     * @return [Roles,...] liste des rôles triés par ordre alphabétique des noms rôles.
     */
    public function findAllRoles(&$message){
        $result = array();
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $result = $bdd->query("SELECT * FROM " . self::TABLE_NAME . " ORDER BY role ASC;",
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                "SoftService\Role");

        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $result;
    }

    /**
     * Retourne un rôle spécifique à partir de l'id du rôle
     * @var integer $id identifiant du rôle
     * @var string $message ensemble des messages à retourner à l'utilisateur, séparés par un saut de ligne
     * @return Role|null le rôle associé à l'identifiant
     */
    public function findRoleById($id, &$message){
        $result = null;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("SELECT * FROM " . self::TABLE_NAME . " WHERE rid = :id;");
            //Permet de spécifier les données correspondant à 'id'
            $stmt->bindValue(':id', $id);
            if ($stmt->execute()) {
                $result = $stmt->fetchObject("SoftService\Role");
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
     * Crée un nouveau rôle
     * @var Role $role role à ajouter
     * @var string $message ensemble des messages à retourner à l'utilisateur, séparés par un saut de ligne
     * @return boolean true si l'enregistrement s'est fait sans erreur, false sinon.
     */
    public function createRole($role, &$message){
        $noError = false;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("INSERT INTO " . self::TABLE_NAME . " (role) VALUES (:role)");
            $stmt->bindValue(':role', $role->role);
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


}