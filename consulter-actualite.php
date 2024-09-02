<?php

    require_once 'inc/head.inc.php';
    require_once 'inc/header.inc.php';
    require_once 'php/db_actualite.inc.php';
    require_once 'php/db_departement.inc.php';
    require_once 'php/fonctions_validation.inc.php';

    use SoftService\DepartementRepository as DepartementRepository;
    use SoftService\Departement as Departement;
    use SoftService\ActualiteRepository as ActualiteRepository;
    use SoftService\Actualite as Actualite;

    $message = '';
    $noError = true;

    $departementRepository = new DepartementRepository();
    $actualiteRepository = new ActualiteRepository();

    if (isset($_POST['confirmer'])) {
        $actualiteRepository->deleteActualite($_GET['id'], $message);
        header("Location: liste-actualites.php");
    }

    if(isset($_GET['id']) && is_numeric($_GET['id'])){
        $actualite = new Actualite();
        $actualite = $actualiteRepository->findActualiteById($_GET['id'],$message);
    }else{
        header("Location: liste-actualites.php");
    }
?>
        <main class="flexCenter main">
            <section class="sectionActualite">
                <form method="POST" class="flexCol formActualite" action="#">
                    <?php
                    if (isset($_POST['supprimer'])) {
                        ?>
                        <div class="confirmation">
                            <p class="message">Voulez-vous supprimer cet article ?</p>
                            <input type="submit" class="boutonAjouterDep position centre" name="confirmer" value="Confirmer">
                            <input type="submit" class="boutonAnnulerDep position centre" name="annuler" value="Annuler">
                        </div>
                    <?php } ?>
                    <div class="flexRow divListeDep">
                        <h1><?php echo $actualite->intitule; ?></h1>
                        <?php if(isset($_SESSION['prenom'])){ ?>
                            <button class="boutonActualite position">
                                <a class="modifierActu" href="editer-actualite.php?id=<?php echo $actualite->aid; ?>">Modifier</a>
                            </button>
                            <input type="submit" class="boutonAnnulerDep position" name="supprimer" value="Supprimer">
                        <?php }?>
                    </div>
                    <img src="<?php echo DOSSIER_IMAGE_MEMBRE . $actualite->image; ?>" class="imgActualite" alt="image-article">

                    <?php if(is_numeric($actualite->did)){
                            $departement = new Departement();
                            $departement = $departementRepository->findDepartementById($actualite->did, $message);
                        ?>
                        <h1 class="centre">Département <?php echo $departement->departement; ?></h1>
                    <?php }?>
                    <p class="texteArticle"><?php echo $actualite->actualite; ?></p>

                    <p class="datePublication">Posté le <?php echo date('d-m-Y', strtotime($actualite->date_actualite)); ?></p>

                    <?php if((boolean)$actualite->visible){ ?>
                        <p class="datePublication"><span class="visible">Visible</span></p>
                    <?php } else{ ?>
                        <p class="datePublication"><span class="nonVisible">Non visible</span></p>
                    <?php } ?>
                </form>
            </section>
        </main>
<?php require_once 'inc/footer.inc.php'; ?>