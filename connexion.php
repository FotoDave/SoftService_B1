<?php
    session_start();

    require_once 'inc/head.inc.php';
    require_once 'php/fonctions_validation.inc.php';
    require_once 'php/db_personnel.inc.php';

    use SoftService\PersonnelRepository as PersonnelRepository;
    use SoftService\Personnel as Personnel;

    $message = '';


    /******** DECONNEXION ********/
    if (isset($_POST['deconnexion'])) {
        $username = '';
        $message = '';
        // on ré-initialise le tableau $_SESSION
        $_SESSION = array();
        // on détruit la session sur le serveur
        session_destroy();
        header('location: accueil.php');
        exit;
    }

    /******** GESTION DEJA CONNECTE ********/
    // Vérifier si l'utilisateur est déjà connecté
    if (isset($_SESSION['email'])) {
        // Si l'utilisateur est déjà connecté, rediriger vers une autre page, par exemple la page d'accueil
        header('location: accueil.php');
        exit;
    }

    /******** CONNEXION ********/
    if(isset($_POST['connexion'])){
        $email = cleanField($_POST['email']);
        $mdp = cleanField($_POST['mdp']);
        $noError = validationConnexion($email, $mdp, $message);

        if($noError){
            $personnelRepository = new PersonnelRepository();
            if($personnelRepository->checkConnexion($email, $mdp, $message)){
                $membre = new Personnel();
                $membre = $personnelRepository->findMemberByEmail($email, $message);
                $_SESSION['email'] = $email;
                $_SESSION['prenom'] = $membre->prenom;
                $_SESSION['pid'] = $membre->pid;
                header('location: accueil.php');
                exit;
            }
        }
    }

?>
    <body class="bodyConnexion">
        <section class="sectionConnexion">
            <form class="flexCol formConnexion" method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" autocomplete="off">
                <img src="imagesSiteWeb/logo2.png" alt="logo-entreprise">
                <h1 class="h1Connexion">Connexion</h1>
                <p><?php echo $message; ?></p>
                <input type="text" class="champs" name="email" placeholder="Adresse courriel">
                <input type="password" class="champs" name="mdp" placeholder="Mot de passe">
                <input type="submit" class="boutonConnexion" name="connexion" value="Connexion">
                <a href="#">Mot de passe oublié ?</a>
                <a href="#">Inscription</a>
            </form>
        </section>
    </body>
    
</html>