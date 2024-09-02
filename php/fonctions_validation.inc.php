<?php
//TIPS : IntelliJ IDEA creates stubs of PHPDoc blocks when you type the /** opening tag and press Enter

require_once 'db_personnel.inc.php';
require_once 'db_actualite.inc.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/Exception.php';

use SoftService\PersonnelRepository as PersonnelRepository;
use SoftService\ActualiteRepository as ActualiteRepository;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


const RECEIVER_ADRESS = 'd.fotofoto@student.helmo.be';
const NOREPLY_ADRESS = 'no-reply@softservice.be';
const DOSSIER_IMAGE_MEMBRE = 'upload/';
const TAILLE_MAXIMALE_IMAGE = 50485760;
const TAILLE_MOT_MINIMAL = 4;

/** Vérifie si une adresser mail est valide
 * @param $mail string : adresse mail à vérifier
 * @return bool : true adresse valide - false adresse non valide
 */
function isValidMail($mail){
    return (!filter_var($mail, FILTER_VALIDATE_EMAIL))? false : true ;
}

/**
 * Nettoie une chaine de caractères
 * Supprime les espaces blanc en début et fin de chaîne
 * Supprime les antislashs d'une chaîne
 * Convertit les caractères spéciaux en entités HTML
 * @param $chaine string, chaine à nettoyer
 * @return string, chaine nettoyée
 */
function cleanField($chaine){
    $chaine = trim($chaine);
    $chaine = stripslashes($chaine);
    $chaine = htmlspecialchars($chaine);
    return $chaine;
}


/**
 * Détecte si une chaine comporte minimum 4 lettres
 * @param $chaine string, chaine à nettoyer
 * @return boolean, true si c'est le cas, false sinon
 */
function hasMinimumLetters($chaine){
    return strlen($chaine) >= TAILLE_MOT_MINIMAL;
}


/**
 * Crypte et hache le mot de passe reçu et la retourne
 * @param $chaine string, chaine à nettoyer
 * @return string, mot de passe haché
 */
function cryptAndHashPassWord($chaine){
    return password_hash($chaine, PASSWORD_BCRYPT);
}

/**
 * Vérifie que tous les informations de connexion sont bien renseignées
 * @param string $email, courriel de l'utilisateur
 * @param string $mdp, mot de passe de l'utilisateur
 * @param string &$message adresse variable message;
 * @return boolean true si les champs sont encodés correctement, false sinon
 */
function validationConnexion($email, $mdp, &$message){
    $personnelRepository = new PersonnelRepository();
    $noError = true;

    if (empty($email) || empty($mdp)) {
        $message .= 'Veuillez remplir tous les champs obligatoires.<br>';
        $noErreur =  false;
    }
    if (!isValidMail($email)) {
        $message .= 'Votre adresse courriel est invalide.<br>';
        $noErreur =  false;
    }else{
        if (!$personnelRepository->isMailExist($email, $message)){
            $message .= 'Cette adresse mail n\'existe pas dans notre base de données.<br>';
            $noErreur =  false;
        }
    }
    return $noError;
}


/**
 * Vérifie si toutes les informations du personnel correspond à ce qui est demandé
 * @return boolean true si champs encodés correctement, false sinon
 */
function validationCreationPersonnel ($prenom, $nom, $courriel, $mpd, $telephone, $description,
                                      $fileOK, $taillePhoto, &$message) {
    $personnelRepository = new PersonnelRepository();
    $noErreur = true;

    if (empty($prenom) || empty($nom) || empty($courriel) || empty($mpd) || empty($telephone) || empty($description)) {
        $message .= 'Veuillez remplir tous les champs obligatoires.<br>';
        $noErreur =  false;
    }
    if (!isValidMail($courriel)) {
        $message .= 'Votre adresse courriel est invalide.<br>';
        $noErreur =  false;
    }
    if ($personnelRepository->isMailExist($courriel, $message)){
        $message .= 'Cette adresse mail est déjà attribuée.<br>';
        $noErreur =  false;
    }
    if ($personnelRepository->isFirstNameAndLastNameExist($nom, $prenom, $message)){
        $message .= 'Vous avez déjà crée ce membre.<br>';
        $noErreur =  false;
    }
    if (!$fileOK) {
        $message .= 'Photo manquante.<br>';
        $noErreur =  false;
    }else{
        if ($taillePhoto > TAILLE_MAXIMALE_IMAGE){
            $message .= 'La taille maximal de la photo doit être inferieur à 10Mo .<br>';
            $noErreur =  false;
        }
    }
    return $noErreur;
}


/**
 * Vérifie si toutes les informations du personnel correspond à ce qui est demandé
 * @param string $prenom , prenom de l'utilisateur
 * @param string $message , ensemble des messages à retourner à l'utilisateur, séparés par un saut de ligne
 * @return boolean true si champs encodés correctement, false sinon
 */
function validationModificationPersonnel ($prenom, $nom, $courriel, $mpd, $telephone, $description, $fileOK, $taillePhoto, &$message) {
    $personnelRepository = new PersonnelRepository();
    $noErreur = true;

    if (empty($prenom) || empty($nom) || empty($courriel) || empty($mpd) || empty($telephone) || empty($description)) {
        $message .= 'Veuillez remplir tous les champs obligatoires.<br>';
        $noErreur =  false;
    }
    if (!isValidMail($courriel)) {
        $message .= 'Votre adresse courriel est invalide.<br>';
        $noErreur =  false;
    }
//    if ($personnelRepository->isMailExist($courriel, $message)){
//        $message .= 'Cette adresse mail est déjà attribuée.<br>';
//        $noErreur =  false;
//    }
//    if ($personnelRepository->isFirstNameAndLastNameExist($nom, $prenom, $message)){
//        $message .= 'Vous avez déjà crée ce membre.<br>';
//        $noErreur =  false;
//    }
    if (!$fileOK) {
        $message .= 'Photo manquante.<br>';
        $noErreur =  false;
    }else{
        if ($taillePhoto > TAILLE_MAXIMALE_IMAGE){
            $message .= 'La taille maximal de la photo doit être inferieur à 10Mo .<br>';
            $noErreur =  false;
        }
    }
    return $noErreur;
}

/**
 * Vérifie si toutes les informations de l'actualité correspond à ce qui est demandé avant sa création
 * @return boolean true si champs encodés correctement, false sinon
 */
function validationCreationActualite ($date, $intitule, $amorce, $visible, $actualite, $fileOK, $taillePhoto, &$message) {
    $actualiteRepository = new ActualiteRepository();
    $noErreur = true;

    if (empty($date) || empty($intitule) || empty($amorce) || !isset($visible) || empty($actualite)) {
        $message .= 'Veuillez remplir tous les champs obligatoires.<br>';
        $noErreur =  false;
    }
    if ($actualiteRepository->isIntituleExist($intitule, $message)){
        $message .= 'Vous avez déjà crée cette actualité.<br>';
        $noErreur =  false;
    }
    if (!$fileOK) {
        $message .= 'Photo manquante.<br>';
        $noErreur =  false;
    }else{
        if ($taillePhoto > TAILLE_MAXIMALE_IMAGE){
            $message .= 'La taille maximal de la photo doit être inferieur à 50Mo .<br>';
            $noErreur =  false;
        }
    }
    return $noErreur;
}

/**
 * Vérifie si toutes les informations de l'actualité correspond à ce qui est demandé avant sa création
 * @return boolean true si champs encodés correctement, false sinon
 */
function validationModificationActualite ($date, $intitule, $amorce, $visible, $actualite, $fileOK, $taillePhoto, &$message) {
    $actualiteRepository = new ActualiteRepository();
    $noErreur = true;

    if (empty($date) || empty($intitule) || empty($amorce) || !isset($visible) || empty($actualite)) {
        $message .= 'Veuillez remplir tous les champs obligatoires.<br>';
        $noErreur =  false;
    }
    if (!$fileOK) {
        $message .= 'Photo manquante.<br>';
        $noErreur =  false;
    }else{
        if ($taillePhoto > TAILLE_MAXIMALE_IMAGE){
            $message .= 'La taille maximal de la photo doit être inferieur à 50Mo .<br>';
            $noErreur =  false;
        }
    }
    return $noErreur;
}

/**
 * Vérifie si la photo existe déjà dans le serveur
 * @param string $fileName , image à uploader
 * @param string $message , ensemble des messages à retourner à l'utilisateur, séparés par un saut de ligne
 * @return boolean true si l'image est OK, false sinon
 */
function isExist($fileName, &$message) {
    $noErreur = false;
    if (file_exists(DOSSIER_IMAGE_MEMBRE . $fileName)) {
        $message .= 'Image déjà existante.<br>';
        $noErreur =  true;
    }
    return $noErreur;
}

/**
 * Déplace l'image depuis le repertoire temporaire vers le dossier final sur le serveur
 * @param string $temporaire , Nom du dossier temporaire
 * @param string $message , ensemble des messages à retourner à l'utilisateur, séparés par un saut de ligne
 * @return boolean true si l'envoie de la photo s'est effectuée, false sinon
 */
function deplacerImageVersServeur ($temporaire, $filename, &$message) {
    $noErreur = move_uploaded_file($temporaire, DOSSIER_IMAGE_MEMBRE . $filename);
    if (!$noErreur) {
        $message .= 'Erreur survenue lors de l\' envoie de la photo.<br>';
    }
    return $noErreur;
}

/**
 * Vérifie si toutes les informations de l'utilisateur correspond à ce qui est demandé pour l'envoie des mails.
 * @param string $sender , email de l'émetteur
 * @param string $objet , objet du message
 * @param string $valueMail , contenu du message
 * @param string $message , ensemble des messages à retourner à l'utilisateur, séparés par un saut de ligne
 * @return boolean true si champs encodés correctement, false sinon
 */
function validationMail ($sender, $objet, $valueMail, &$message) {
    $noErreur = true;

    if (empty($sender) || empty($objet) || empty($valueMail)) {
        $message .= 'Veuillez remplir tous les champs obligatoires.<br>';
        $noErreur =  false;
    }
    if (!isValidMail($sender)) {
        $message .= 'Votre adresse courriel est invalide.<br>';
        $noErreur =  false;
    }
    return $noErreur;
}


/**
 * Procède à l'envoie du mail au destinataire
 * @param string $sender , email de l'émetteur
 * @param string $objet , objet du message
 * @param string $valueMail , contenu du message
 * @param string $message , ensemble des messages à retourner à l'utilisateur, séparés par un saut de ligne
 * @return boolean true si tout s'est bien passé, false sinon
 */
function sendingMail ($sender, $objet, $valueMail, &$message) {
    $mail = new PHPMailer(true);
    try {
        $mail->CharSet = 'UTF-8';
        $mail->setFrom($sender);
        $mail->addAddress(RECEIVER_ADRESS);
        $mail->addReplyTo(NOREPLY_ADRESS);
        $mail->addCC($sender, RECEIVER_ADRESS);
        $mail->isHTML(false);
        $mail->Subject = $objet;
        $mail->Body = $valueMail;
        $mail->send();
        $noError = true;
        $message .= "Courrier envoyé, merci !";
    } catch(Exception $e){
        $message .= 'Erreur survenue lors de l\'envoi de l\'email<br>'. $mail->ErrorInfo;
        return $message;
    }

    return $noError;
}