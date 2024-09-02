<?php

    require_once 'inc/head.inc.php';
    require_once 'inc/header.inc.php';
    require_once 'php/db_role.inc.php';
    require_once 'php/db_departement.inc.php';
    require_once 'php/db_fonction.inc.php';
    require_once 'php/db_personnel.inc.php';
    require_once 'php/fonctions_validation.inc.php';

    use SoftService\FonctionRepository as FonctionRepository;
    use SoftService\Fonction as Fonction;
    use SoftService\PersonnelRepository as PersonnelRepository;
    use SoftService\Personnel as Personnel;
    use SoftService\DepartementRepository as DepartementRepository;
    use SoftService\Departement as Departement;
    use SoftService\RoleRepository as RoleRepository;
    use SoftService\Role as Role;

    $message = '';
    $noError = true;

    $departement = new Departement();
    $role = new Role();
    $personnel = new Personnel();
    $fonction = new Fonction();

    $personnelRepository = new PersonnelRepository();
    $fonctionRepository = new FonctionRepository();
    $departementRepositort = new DepartementRepository();
    $roleRepository = new RoleRepository();

    if(isset($_GET['id']) && is_numeric($_GET['id'])){
        //Récupération du membre et de sa fonction via son id reçu dans l'URL
        $personnel = $personnelRepository->findMemberById($_GET['id'], $message);
        $fonctions = $fonctionRepository->findFunctionByPersonnelId($_GET['id'], $message);
        if(empty($personnel)){
            $message .= 'Aucun membre trouvé !<br>';
            $noError = false;
        }
    }else{
        header("Location: presentation-equipe.php");
    }

?>
    <main class="main">
        <h1 class="centre">Fiche d'informations</h1>
        <section class="container">
            <div class="left">
                <div>
                    <img class="photo" src="<?php echo DOSSIER_IMAGE_MEMBRE . $personnel->photo; ?>" alt="icone-significative">
                    <?php
                    if(isset($_SESSION['pid']) && $_SESSION['pid'] === $personnel->pid){
                        ?>
                        <a href="editer-membre.php?id=<?php echo $personnel->pid; ?>" class="boutonModifierDep position">Editer mon profil</a>
                    <?php }?>
                </div>
            </div>
            <div class="right">
                <div class="info">
                    <label>Noms :</label>
                    <span><?php echo $personnel->nom; ?></span>
                </div>
                <div class="info">
                    <label>Prénoms :</label>
                    <span><?php echo $personnel->prenom; ?></span>
                </div>
                <div class="info">
                    <label>Courriel :</label>
                    <span><?php echo $personnel->courriel; ?></span>
                </div>
                <div class="info">
                    <label>Téléphone :</label>
                    <span><?php echo $personnel->telephone; ?></span>
                </div>
                <div class="info">
                    <label>Description :</label>
                    <p><?php echo $personnel->description; ?></p>
                </div>
                <div class="info">
                    <label>Département  ->  Rôle </label>
                    <?php foreach ($fonctions as $fonction){
                        if (is_numeric($fonction->rid)){
                        $departement = $departementRepositort->findDepartementById($fonction->did, $message);
                        $role = $roleRepository->findRoleById($fonction->rid, $message);
                        ?>
                    <p><b><?php echo $departement->departement; ?></b>  ->  <?php echo $role->role; ?></p>
                    <?php }
                    }?>
                </div>
            </div>
        </section>
    </main>
<?php require_once 'inc/footer.inc.php'; ?>