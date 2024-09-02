<?php

    require_once 'php/session.inc.php';
    require_once 'inc/head.inc.php';
    require_once 'inc/headerAdmin.inc.php';
    require_once 'php/db_departement.inc.php';
    require_once 'php/fonctions_validation.inc.php';

    use SoftService\DepartementRepository as DepartementRepository;
    use SoftService\Departement as Departement;

    $message = "";

    if(isset($_POST['ajouter'])){
        $departement = new Departement();
        $noError = true;

        if(empty($_POST['dep']) || empty($_POST['libelle'])){
            $message .= 'Veuillez remplir tous les champs obligatoires...<br>';
            $noError =  false;
        }

        $departement->departement = cleanField($_POST['dep']);
        $departement->libelle = cleanField($_POST['libelle']);

        if($noError){
            $departementRepository = new DepartementRepository();
            $departementRepository->createDepartement($departement, $message);
            header("Location: liste-departements.php");
        }
    }
?>
        <main class="flexCenter main">
            <section class="sectionDepartement">
                <form method="POST" class="flexCol formAjoutDep" action="#">
                    <h1>Ajouter Département</h1>
                    <p><?php echo"$message";  ?></p>
                    <label for="nom">Département *</label>
                    <input type="text" name="dep" id="nom"
                           placeholder="Nom du déparement" value="<?php if (isset($departement)) echo $departement->departement; ?>">
                    <label for="libelle">Libellé *</label>
                    <input type="text" name="libelle" id="libelle"
                           placeholder="Libellé.." value="<?php if (isset($departement)) echo $departement->libelle; ?>">

                    <div class="divAjoutDep">
                        <input type="submit" class="boutonAjouter position" name="ajouter" value="Ajouter">
                        <input type="reset" class="boutonAnnuler position" name="annuler" value="Annuler">
                    </div>
                </form>
            </section>
        </main>

<?php require_once 'inc/footer.inc.php'; ?>