<?php

namespace SoftService;
require_once 'db_link.inc.php';

use DB\DBLink;
use PDO;

setlocale(LC_TIME, 'fr_FR.utf8', 'fra');

/**
 * Classe Actualite : Actualité qui sont attribués à des départements
 * @author Dave FOTO
 * @version 1.0
 */
class Actualite
{
    private $aid;
    private $date_actualite;
    private $intitule;
    private $amorce;
    private $actualite;
    private $image;
    private $visible;
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
 * Classe ActualiteRepository : gestionnaire du dépôt contenant les actualités
 * @author Dave FOTO
 * @version 1.0
 */
class ActualiteRepository
{
    const TABLE_NAME = 'webB1_Actualite';

    /**
     * Retourne la liste de tous les actualités qui sont affiliées aux départements
     * @var string $message ensemble des messages à retourner à l'utilisateur, séparés par un saut de ligne
     * @return [Actualite,...] liste des rôles triés par ordre alphabétique des noms rôles.
     */
    public function findAllActualites(&$message){
        $result = array();
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $result = $bdd->query("SELECT * FROM " . self::TABLE_NAME . " ORDER BY date_actualite DESC;",
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                "SoftService\Actualite");

        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $result;
    }


    /**
     * Retourne une liste de deux actualités pour la page Acceuil
     * @var string $message ensemble des messages à retourner à l'utilisateur, séparés par un saut de ligne
     * @return [Actualite,...] liste des rôles triés par ordre alphabétique des noms rôles.
     */
    public function findActualitesForAccueil(&$message){
        $result = array();
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->query("SELECT * FROM " . self::TABLE_NAME . " WHERE visible = true 
                                                    ORDER BY date_actualite DESC LIMIT 2;");
            if ($stmt) {
                $result = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "SoftService\Actualite");
            }
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $result;
    }


    /**
     * Retourne la liste de tous les actualités qui sont affiliées aux départements
     * @var string $message ensemble des messages à retourner à l'utilisateur, séparés par un saut de ligne
     * @return [Actualite,...] liste des rôles triés par ordre alphabétique des noms rôles.
     */
    public function findVisibleActualities(&$message){
        $result = array();
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $result = $bdd->query("SELECT * FROM " . self::TABLE_NAME . " WHERE visible = true ORDER BY date_actualite DESC;",
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                "SoftService\Actualite");

        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $result;
    }

    /**
     * Retourne une actualité spécifique à partir de son id ('aid')
     * @var integer $id identifiant de l'actualité
     * @var string $message ensemble des messages à retourner à l'utilisateur, séparés par un saut de ligne
     * @return Actualite|null actualité associée au département
     */
    public function findActualiteById($id, &$message){
        $result = null;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("SELECT * FROM " . self::TABLE_NAME . " WHERE aid = :id;");
            //Permet de spécifier les données correspondant à 'id'
            $stmt->bindValue(':id', $id);
            if ($stmt->execute()) {
                $result = $stmt->fetchObject("SoftService\Actualite");
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
     * Crée une nouvelle actualité
     * @var Actualite $actualite actualité à ajouter
     * @var string $message ensemble des messages à retourner à l'utilisateur, séparés par un saut de ligne
     * @return integer valeur de l'id de l'actualité créee
     */
    public function createActualite($actualite, &$message){
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("INSERT INTO " . self::TABLE_NAME . " (aid, date_actualite, intitule, image, amorce, actualite, visible, did) 
                                    VALUES (:aid, :date_actualite, :intitule, :image, :amorce, :actualite, :visible, :did)");
            $stmt->bindValue(':aid', $actualite->aid);
            $stmt->bindValue(':date_actualite', $actualite->date_actualite);
            $stmt->bindValue(':intitule', $actualite->intitule);
            $stmt->bindValue(':image', $actualite->image);
            $stmt->bindValue(':amorce', $actualite->amorce);
            $stmt->bindValue(':actualite', $actualite->actualite);
            $stmt->bindValue(':visible', $actualite->visible);
            $stmt->bindValue(':did', $actualite->did);
            if ($stmt->execute()) {
                $actualiteId = $bdd->lastInsertId();
                $message = 'Création effectuée avec succès !';
            } else {
                $message .= 'Une erreur système est survenue.<br> Veuillez essayer à nouveau plus tard 
                        ou contactez l\'administrateur du site. (Code erreur: ' . $stmt->errorCode() . ')<br>';
            }
            $stmt = null;
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $actualiteId;
    }

    /**
     * Vérifie si l'intitule d'une actualité existe déjà en BD
     * @var string $intiutle intitulé de l'actualité
     * @var string $message ensemble des messages à retourner à l'utilisateur, séparés par un saut de ligne
     * @return boolean true si adresse existante, false sinon
     */
    public function isIntituleExist($intiutle, &$message)
    {
        $result = false;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("SELECT * FROM " . self::TABLE_NAME . " WHERE intitule = :intitule");
            $stmt->bindValue(':intitule', $intiutle);
            if ($stmt->execute()) {
                if ($stmt->fetch() !== false) {
                    $result = true;
                }
            } else {
                $message .= 'Une erreur système est survenue.<br> Veuillez essayer à nouveau plus tard ou contactez l\'administrateur du site. (Code erreur E: ' . $stmt->errorCode() . ')<br>';
            }
            $stmt = null;
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $result;
    }


    /**
     * Modifie une actualité déjà enregistrée dans la BD
     * @var Actualite $actualite actualité à modifier
     * @var string $message ensemble des messages à retourner à l'utilisateur, séparés par un saut de ligne
     * @return boolean true si modification OK, false sinon
     */
    public function modificationActualite($actualite, &$message){
        $noError = false;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("UPDATE " . self::TABLE_NAME . " SET date_actualite = :date_actualite, intitule = :intitule, image = :image, 
                            amorce = :amorce, actualite = :actualite, visible = :visible, did = :did WHERE aid = :aid");
            $stmt->bindValue(':aid', $actualite->aid);
            $stmt->bindValue(':date_actualite', $actualite->date_actualite);
            $stmt->bindValue(':intitule', $actualite->intitule);
            $stmt->bindValue(':image', $actualite->image);
            $stmt->bindValue(':amorce', $actualite->amorce);
            $stmt->bindValue(':actualite', $actualite->actualite);
            $stmt->bindValue(':visible', $actualite->visible);
            $stmt->bindValue(':did', $actualite->did);
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


    /**
     * Supprime un article sur base de son identifiant
     * @return boolean true si opération réalisée sans erreur, false sinon
     * @var string $message ensemble des messages à retourner à l'utilisateur, séparés par un saut de ligne
     * @var integer $id identifiant du département
     */
    public function deleteActualite($id, &$message)
    {
        $noError = false;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("DELETE FROM " . self::TABLE_NAME . " WHERE aid = :id");
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


    /**
     * Retourne la liste des articles par département pour l'utilisateur qui n'est pas authentifié
     * @var integer $id identifiant du département
     * @var string $message ensemble des messages à retourner à l'utilisateur, séparés par un saut de ligne
     * @return [Actualite, ...] liste d'actualité
     */
    public function filterByDidNoAuthentication($id, &$message){
        $result = null;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("SELECT * FROM " . self::TABLE_NAME . " WHERE did = :id AND visible = true
                                                    ORDER BY date_actualite DESC;");
            $stmt->bindValue(':id', $id);
            if ($stmt->execute()) {
                $result = $stmt->fetchAll(PDO::FETCH_CLASS,"SoftService\Actualite");
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
     * Retourne la liste des articles par département pour l'utilisateur authentifié
     * @var integer $id identifiant du département
     * @var string $message ensemble des messages à retourner à l'utilisateur, séparés par un saut de ligne
     * @return [Actualite, ...] liste d'actualité
     */
    public function filterByDidWithAuthentication($id, &$message){
        $result = null;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("SELECT * FROM " . self::TABLE_NAME . " WHERE did = :id
                                                    ORDER BY date_actualite DESC;");
            $stmt->bindValue(':id', $id);
            if ($stmt->execute()) {
                $result = $stmt->fetchAll(PDO::FETCH_CLASS,"SoftService\Actualite");
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
     * Retourne la liste des articles par mot cléf pour l'utilisateur authentifié
     * @var string $keyword identifiant du département
     * @var string $message ensemble des messages à retourner à l'utilisateur, séparés par un saut de ligne
     * @return [Actualite, ...] liste d'actualité
     */
    public function filterByKeywordWithAuthentication($keyword, &$message){
        $result = null;
        $bdd = null;
        $keyword = '%' . $keyword . '%';
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("SELECT * FROM " . self::TABLE_NAME . " WHERE intitule LIKE :kw
                                            OR actualite LIKE :kw ORDER BY date_actualite DESC;");
            $stmt->bindValue(':kw', $keyword);
            if ($stmt->execute()) {
                $result = $stmt->fetchAll(PDO::FETCH_CLASS,"SoftService\Actualite");
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
     * Retourne la liste des articles par mot cléf pour l'utilisateur non authentifié
     * @var string $keyword identifiant du département
     * @var string $message ensemble des messages à retourner à l'utilisateur, séparés par un saut de ligne
     * @return [Actualite, ...] liste d'actualité
     */
    public function filterByKeywordWithNoAuthentication($keyword, &$message){
        $result = null;
        $bdd = null;
        $keyword = '%' . $keyword . '%';
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("SELECT * FROM " . self::TABLE_NAME . " WHERE (intitule LIKE :kw
                                            OR actualite LIKE :kw) AND visible = true 
                                            ORDER BY date_actualite DESC;");
            $stmt->bindValue(':kw', $keyword);
            if ($stmt->execute()) {
                $result = $stmt->fetchAll(PDO::FETCH_CLASS,"SoftService\Actualite");
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
     * Retourne la liste des articles par département et par mot cléf pour l'utilisateur authentifié
     * @var string $keyword identifiant du département
     * @var string $message ensemble des messages à retourner à l'utilisateur, séparés par un saut de ligne
     * @return [Actualite, ...] liste d'actualité
     */
    public function filterByDidAndKeywordWithAuthentication($keyword, $did, &$message){
        $result = null;
        $bdd = null;
        $keyword = '%' . $keyword . '%';
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("SELECT * FROM " . self::TABLE_NAME . " WHERE (intitule LIKE :kw OR actualite LIKE :kw)
                                             AND did = :id 
                                             ORDER BY date_actualite DESC;");
            $stmt->bindValue(':kw', $keyword);
            $stmt->bindValue(':id', $did);
            if ($stmt->execute()) {
                $result = $stmt->fetchAll(PDO::FETCH_CLASS,"SoftService\Actualite");
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
     * Retourne la liste des articles par département et par mot cléf pour l'utilisateur non authentifié
     * @var string $keyword identifiant du département
     * @var string $message ensemble des messages à retourner à l'utilisateur, séparés par un saut de ligne
     * @return [Actualite, ...] liste d'actualité
     */
    public function filterByDidAndKeywordWithNoAuthentication($keyword, $did, &$message){
        $result = null;
        $bdd = null;
        $keyword = '%' . $keyword . '%';
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("SELECT * FROM " . self::TABLE_NAME . " WHERE (intitule LIKE :kw OR actualite LIKE :kw)
                                             AND did = :id AND visible = true
                                             ORDER BY date_actualite DESC;");
            $stmt->bindValue(':kw', $keyword);
            $stmt->bindValue(':id', $did);
            if ($stmt->execute()) {
                $result = $stmt->fetchAll(PDO::FETCH_CLASS,"SoftService\Actualite");
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

}