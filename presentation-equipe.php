<?php

    require_once 'inc/head.inc.php';
    require_once 'inc/header.inc.php';
    require_once 'php/db_role.inc.php';
    require_once 'php/db_departement.inc.php';
    require_once 'php/db_fonction.inc.php';
    require_once 'php/db_personnel.inc.php';
    require_once 'php/fonctions_validation.inc.php';

    use SoftService\RoleRepository as RoleRepository;
    use SoftService\Role as Role;
    use SoftService\DepartementRepository as DepartementRepository;
    use SoftService\Departement as Departement;
    use SoftService\FonctionRepository as FonctionRepository;
    use SoftService\Fonction as Fonction;
    use SoftService\PersonnelRepository as PersonnelRepository;
    use SoftService\Personnel as Personnel;

    $roleRepository = new RoleRepository();
    $departementRepository = new DepartementRepository();
    $fonctionRepository = new FonctionRepository();

    //Récupération de la liste des roles
    $listRoles = $roleRepository->findAllRoles($message);
    //Récupération de la liste des départements
    $listDepartements = $departementRepository->findAllDepartements($message);

    $message = '';
    $noError = true;

    $listDepartement = $departementRepository->findAllDepartements($message);
?>
        <main class="flexCenter mainAjoutDep">
            <section class="sectionEquipe">
                <h1>Nos équipes</h1>
                <?php
                    foreach ($listDepartement as $departement) {
                ?>
                        <h2 class="titreEquipe">Département <?php echo $departement->departement; ?></h2>
                        <section class="flexRow flWrap sectionProfil">
                            <?php
                            $listPersonnels = $fonctionRepository->findPersonnelByDepartement($departement->did, $message);
                            if(!empty($listPersonnels)){
                            foreach ($listPersonnels as $personnel) {
                                $fonctionPersonnel = $fonctionRepository->findFunctionByPersonnelAndDepartmentId($personnel->pid, $departement->did, $message);
                                $rolePersonnel = $roleRepository->findRoleById($fonctionPersonnel->rid, $message);
                            ?>
                            <article class="flexCol articleProfil">
                                <figure class="figureEquipe">
                                    <img src="<?php echo DOSSIER_IMAGE_MEMBRE . $personnel->photo; ?>" class="photoProfil" alt="photo-profile">
                                </figure>
                                <h2 class="titreProfil"><?php echo $personnel->prenom .' '. $personnel->nom; ?></h2>
                                <p><?php if(!empty($rolePersonnel)) echo $rolePersonnel->role; ?></p>
                                <a href="fiche-membre.php?id=<?php echo $personnel->pid; ?>" class="boutonModifierDep position">Fiche complète</a>
                            </article>
                            <?php
                                }
                            }else{
                            ?>
                            <p> Aucun membre dans ce département pour le moment !</p>
                            <?php } ?>
                        </section>
                <?php
                    }
                ?>
            </section>
            
        </main>

<?php require_once 'inc/footer.inc.php'; ?>
