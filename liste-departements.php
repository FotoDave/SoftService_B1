<?php

    require_once 'php/session.inc.php';
    require_once 'inc/head.inc.php';
    require_once 'inc/headerAdmin.inc.php';
    require_once 'php/db_departement.inc.php';

    use SoftService\DepartementRepository as DepartementRepository;
    use SoftService\Departement as Departement;

    $departementRepository = new DepartementRepository();
    $message = '';
    $nbDeleted = 0;
    $noError = true;

    if (isset($_POST['supprimer'])) {
        if (empty($_POST['idDep'])) {
            $message .= 'Aucun département sélectionné !<br>';
            $noError = false;
        }
    }

    if (isset($_POST['confirmer'])) {
        foreach ($_POST['idDep'] as $did) {
            if (!$departementRepository->deleteDepartement($did, $message)) {
                $noError = false;
            } else {
                $nbDeleted += 1;
            }

        }
        $message = $nbDeleted . " département(s) supprimé(s)";
    }

    $listDepartements = $departementRepository->findAllDepartements($message);

?>
        <main class="flexCenter main">
            <section class="sectionDepartement">
                <div class="flexRow divListeDep">
                    <h1>Liste des départements</h1>
                    <a href="ajout-departement.php" class="boutonAjouterDep">Nouveau</a>
                </div>
                <form method="POST" class="flexCol formContact" action="#">
                    <?php
                    if (isset($_POST['supprimer']) && $noError) {
                        ?>
                        <div class="confirmation">
                            <p class="message">Voulez-vous supprimer ce département ?</p>
                            <input type="submit" class="boutonAjouterDep position centre" name="confirmer" value="Confirmer">
                            <input type="submit" class="boutonAnnulerDep position centre" name="annuler" value="Annuler">
                        </div>
                    <?php } ?>
                    <p> <?php echo $message; ?> </p>
                    <?php foreach ($listDepartements as $departement){ ?>
                        <section class="articleListeDep">
                            <div class="flexRow">
                                <input type="checkbox"
                                       name="idDep[]" <?php echo (isset($_POST['idDep']) && in_array($departement->did, $_POST['idDep'])) ? 'checked' : ''; ?>
                                       value="<?php echo $departement->did; ?>">
                                <h2><?php echo "$departement->departement"; ?></h2>
                                <p> <?php echo "$departement->libelle"; ?> </p>
                            </div>
                            <div class="flexRow divListeDep">
                                <a href="editer-departement.php?id=<?php echo $departement->did; ?>" class="boutonModifierDep position">Modifier</a>
                            </div>
                        </section>
                    <?php }?>
                    <input type="submit" class="boutonAnnulerDep position" name="supprimer" value="Supprimer les départements">
                </form>        
            </section>
        </main>
        
<?php require_once 'inc/footer.inc.php'; ?>