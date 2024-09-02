<?php

namespace SoftService;
require_once 'db_link.inc.php';

use DB\DBLink;
use PDO;

setlocale(LC_TIME, 'fr_FR.utf8', 'fra');

/**
 * Classe Departement : Département du site vitrine
 * @author Dave FOTO
 * @version 1.0
 */
class Departement
{
    private $did;
    private $departement;
    private $libelle;

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
 * Classe DepartementRepository : gestionnaire du dépôt contenant les départements du site vitrine
 * @author Dave FOTO
 * @version 1.0
 */
class DepartementRepository
{
    const TABLE_NAME = 'webB1_Departement';

    /**
     * Retourne la liste des départements présents dans la base de données
     * @var string $message ensemble des messages à retourner à l'utilisateur, séparés par un saut de ligne
     * @return [Departement,...] liste des départements triés par ordre alphabétique des noms des départements.
     */
    public function findAllDepartements(&$message){
        $result = array();
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $result = $bdd->query("SELECT * FROM " . self::TABLE_NAME . " ORDER BY departement ASC;",
                                    PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                                        "SoftService\Departement");

        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $result;
    }

    /**
     * Retourne un département spécifique à partir de son id
     * @var integer $id identifiant du département
     * @var string $message ensemble des messages à retourner à l'utilisateur, séparés par un saut de ligne
     * @return Departement|null le département associé à l'identifiant
     */
    public function findDepartementById($id, &$message){
        $result = null;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("SELECT * FROM " . self::TABLE_NAME . " WHERE did = :id;");
            //Permet de spécifier les données correspondant à 'id'
            $stmt->bindValue(':id', $id);
            if ($stmt->execute()) {
                $result = $stmt->fetchObject("SoftService\Departement");
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
     * Crée un nouveau département
     * @var Departement $departement département à ajouter
     * @var string $message ensemble des messages à retourner à l'utilisateur, séparés par un saut de ligne
     * @return boolean true si l'enregistrement s'est fait sans erreur, false sinon.
     */
    public function createDepartement($departement, &$message){
        $noError = false;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("INSERT INTO " . self::TABLE_NAME . " (departement, libelle) 
                                        VALUES (:departement, :libelle);");
            $stmt->bindValue(':departement', $departement->departement);
            $stmt->bindValue(':libelle', $departement->libelle);
            if ($stmt->execute()) {
                $noError = true;
                $message .= 'Création effectuée avec succès';
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
     * Modifie un département
     * @var Departement $departement département à modifier
     * @var string $message ensemble des messages à retourner à l'utilisateur, séparés par un saut de ligne
     * @return boolean true si l'enregistrement s'est fait sans erreur, false sinon.
     */
    public function updateDepartement($departement, &$message){
        $noError = false;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("UPDATE " . self::TABLE_NAME . " SET departement = :departement, 
                                libelle = :libelle WHERE did = :did");
            $stmt->bindValue(':did', $departement->did);
            $stmt->bindValue(':departement', $departement->departement);
            $stmt->bindValue(':libelle', $departement->libelle);
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
     * Supprime un département sur base de son identifiant
     * @return boolean true si opération réalisée sans erreur, false sinon
     * @var string $message ensemble des messages à retourner à l'utilisateur, séparés par un saut de ligne
     * @var integer $id identifiant du département
     */
    public function deleteDepartement($id, &$message)
    {
        $noError = false;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("DELETE FROM " . self::TABLE_NAME . " WHERE did = :id");
            $stmt->bindValue(':id', $id);
            if ($stmt->execute() && $stmt->rowCount() > 0) {
                $noError = true;
            }
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $noError;
    }


}