<?php

namespace SoftService;
require_once 'db_link.inc.php';

use DB\DBLink;
use PDO;

setlocale(LC_TIME, 'fr_FR.utf8', 'fra');

/**
 * Classe Personnel : Membre de l'entreprise
 * @author Dave FOTO
 * @version 1.0
 */
class Personnel
{
    private $pid;
    private $prenom;
    private $nom;
    private $courriel;
    private $mot_passe;
    private $telephone;
    private $photo;
    private $description;

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
 * Classe PersonnelRepository : gestionnaire du dépôt contenant le personnel de l'entreprise
 * @author Dave FOTO
 * @version 1.0
 */
class PersonnelRepository
{
    const TABLE_NAME = 'webB1_Personnel';

    /**
     * Retourne la liste des membres de l'entreprise
     * @var string $message ensemble des messages à retourner à l'utilisateur, séparés par un saut de ligne
     * @return [Personnel,...] liste des membres triés par ordre alphabétique des noms.
     */
    public function findAllMembres(&$message){
        $result = array();
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $result = $bdd->query("SELECT * FROM " . self::TABLE_NAME . " ORDER BY nom ASC;",
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                "SoftService\Personnel");
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $result;
    }

    /**
     * Retourne un membre spécifique à partir de son id.
     * @var integer $id identifiant du membre
     * @var string $message ensemble des messages à retourner à l'utilisateur, séparés par un saut de ligne
     * @return Personnel|null le personnel associé à l'identifiant
     */
    public function findMemberById($id, &$message){
        $result = null;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("SELECT * FROM " . self::TABLE_NAME . " WHERE pid = :id;");
            //Permet de spécifier les données correspondant à 'id'
            $stmt->bindValue(':id', $id);
            if ($stmt->execute()) {
                $result = $stmt->fetchObject("SoftService\Personnel");
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
     * Retourne un membre spécifique à partir de son email.
     * @var string $email courriel du membre
     * @var string $message ensemble des messages à retourner à l'utilisateur, séparés par un saut de ligne
     * @return Personnel|null le personnel associé à l'identifiant
     */
    public function findMemberByEmail($email, &$message){
        $result = null;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("SELECT * FROM " . self::TABLE_NAME . " WHERE courriel = :email;");
            //Permet de spécifier les données correspondant à 'id'
            $stmt->bindValue(':email', $email);
            if ($stmt->execute()) {
                $result = $stmt->fetchObject("SoftService\Personnel");
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
     * Vérifie si les informations renseignées correspondent aux infos de l'utilisateur en BD
     * @param string $email, courriel de l'utilisateur
     * @param string $mdp, mot de passe de l'utilisateur
     * @param string &$message, adresse du message d'erreur
     * @return boolean, true si le mot de passe correspond, false sinon
     */
    public function checkConnexion($email, $mdp, &$message){
        $result = false;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("SELECT mot_passe FROM " . self::TABLE_NAME . " WHERE courriel = :email");
            $stmt->bindValue(':email', $email);
            if ($stmt->execute()) {
                $hashedPassword = $stmt->fetchColumn(); // Récupérer le hash du mot de passe
                if ($hashedPassword !== false && password_verify($mdp, $hashedPassword)) {
                    // Si les mots de passe correspondent
                    $result = true;
                } else {
                    $message .= 'Nom d\'utilisateur ou mot de passe incorrect<br>';
                }
            } else {
                $message .= 'Une erreur système est survenue.<br> Veuillez essayer à nouveau plus tard ou contactez l\'administrateur du site. (Code erreur: ' . $stmt->errorCode() . ')<br>';
            }
            $stmt = null;
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        //var_dump($result);
        $bdd = null;
        return $result;
    }


    /**
     * Vérifie si une adresse email existe déjà dans la liste des membres
     * @var string $message ensemble des messages à retourner à l'utilisateur, séparés par un saut de ligne
     * @var string $email adresse email à vérifier
     * @return boolean true si adresse existante, false sinon
     */
    public function isMailExist($email, &$message)
    {
        $result = false;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("SELECT * FROM " . self::TABLE_NAME . " WHERE courriel = :email");
            $stmt->bindValue(':email', $email);
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
     * Crée un nouveau membre
     * @var Personnel $personnel membre à ajouter
     * @var string $message ensemble des messages à retourner à l'utilisateur, séparés par un saut de ligne
     * @return integer valeur de l'id du membre
     */
    public function createPersonnel($personnel, &$message){
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("INSERT INTO " . self::TABLE_NAME . " (pid, prenom, nom, courriel, mot_passe, telephone, photo, description) 
                                    VALUES (:pid, :prenom, :nom, :courriel, :mpd, :tel, :photo, :description)");
            $stmt->bindValue(':pid', $personnel->pid);
            $stmt->bindValue(':prenom', $personnel->prenom);
            $stmt->bindValue(':nom', $personnel->nom);
            $stmt->bindValue(':courriel', $personnel->courriel);
            $stmt->bindValue(':mpd', $personnel->mot_passe);
            $stmt->bindValue(':tel', $personnel->telephone);
            $stmt->bindValue(':photo', $personnel->photo);
            $stmt->bindValue(':description', $personnel->description);
            if ($stmt->execute()) {
                $personnelId = $bdd->lastInsertId();
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
        return $personnelId;
    }



    /**
     * Modifie un membre déjà enregistré dans la BD
     * @var Personnel $personnel membre à modifier
     * @var string $message ensemble des messages à retourner à l'utilisateur, séparés par un saut de ligne
     * @return boolean true si modification OK, false sinon
     */
    public function modificationPersonnel($personnel, &$message){
        $noError = false;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("UPDATE " . self::TABLE_NAME . " SET prenom = :prenom, nom = :nom, courriel = :courriel,
                            telephone = :tel, photo = :photo, description = :description WHERE pid = :pid");
            $stmt->bindValue(':pid', $personnel->pid);
            $stmt->bindValue(':prenom', $personnel->prenom);
            $stmt->bindValue(':nom', $personnel->nom);
            $stmt->bindValue(':courriel', $personnel->courriel);
//            $stmt->bindValue(':mpd', $personnel->mot_passe);
            $stmt->bindValue(':tel', $personnel->telephone);
            $stmt->bindValue(':photo', $personnel->photo);
            $stmt->bindValue(':description', $personnel->description);
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
     * Vérifie si le nom et le prénom d'un membre existe déjà dans la liste des membres
     * @var string $message ensemble des messages à retourner à l'utilisateur, séparés par un saut de ligne
     * @var string $email adresse email à vérifier
     * @return boolean true si adresse existante, false sinon
     */
    public function isFirstNameAndLastNameExist($nom, $prenom, &$message)
    {
        $result = false;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("SELECT * FROM " . self::TABLE_NAME . " WHERE nom = :nom AND prenom = :prenom");
            $stmt->bindValue(':nom', $nom);
            $stmt->bindValue(':prenom', $prenom);
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


}