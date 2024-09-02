<?php

    require_once 'php/session.inc.php';
    require_once 'inc/head.inc.php';
    require_once 'inc/headerAdmin.inc.php';
    require_once 'php/db_departement.inc.php';
    require_once 'php/db_actualite.inc.php';
    require_once 'php/fonctions_validation.inc.php';

    use SoftService\DepartementRepository as DepartementRepository;
    use SoftService\Departement as Departement;
    use SoftService\ActualiteRepository as ActualiteRepository;
    use SoftService\Actualite as Actualite;

    $message = '';
    $noError = true;

    $departementRepository = new DepartementRepository();
    $actualiteRepository = new ActualiteRepository();

    //Récupération de la liste des départements
    $listDepartements = $departementRepository->findAllDepartements($message);

    if(isset($_GET['id']) && is_numeric($_GET['id'])){
        $actualite = new Actualite();
        $actualite = $actualiteRepository->findActualiteById($_GET['id'],$message);
        if(empty($actualite)){
            $message .= 'Aucun membre trouvé !<br>';
            $noError = false;
        }
    }else{
        header("Location: liste-actualites.php");
    }

    if(isset($_POST['modifier']) && $noError){
        $actualite->date_actualite = cleanField($_POST['date']);
        $actualite->intitule = cleanField($_POST['intitule']);
        $actualite->amorce = cleanField($_POST['amorce']);
        $actualite->did = (!empty($_POST['did'])) ? cleanField($_POST['did']) : null;
        $actualite->actualite = cleanField($_POST['actualite']);

        $fileOK = isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK;

        if(isset($_POST['visible']) && !empty($_POST['visible'])){
            $actualite->visible = ($_POST['visible'] == 'Oui') ? (int) true : (int) false;
        }

        $noError = validationModificationActualite($actualite->date_actualite, $actualite->intitule, $actualite->amorce,
            $actualite->visible, $actualite->actualite, $fileOK, $_FILES['image']['size'], $message);

        if($noError){
            $newFileName = basename($_FILES['image']['name']);
            if(!isExist($newFileName, $message)){
                $actualiteRepository = new ActualiteRepository();
                $randomCodeAlphaNumeric = substr(uniqid(), -5);
                $newFileName = $randomCodeAlphaNumeric . basename($_FILES['image']['name']);
                $actualite->image = $newFileName;
                $_FILES['image']['name'] = $newFileName;

                //var_dump($actualite);

                if(deplacerImageVersServeur($_FILES['image']['tmp_name'], $_FILES['image']['name'], $message)){
                    $actualiteRepository->modificationActualite($actualite, $message);
                    header("Location: liste-actualites.php");
                }
            }
        }
    }

?>
        <main class="flexCenter main">
                <section class="sectionActualite">
                    <form method="POST" class="flexCol formContact" enctype="multipart/form-data" action="#">
                        <h1 class="centre">Modifier actualité</h1>
                        <p><?php echo $message; ?></p>

                        <label for="date">Date actualité *</label>
                        <input type="date" name="date" id="date" value="<?php if (isset($actualite)) echo $actualite->date_actualite; ?>" required>

                        <label for="intitile">Intitulé *</label>
                        <input type="text" name="intitule" id="intitule"
                               placeholder="Intitulé de l'article" value="<?php if (!empty($actualite)) echo $actualite->intitule; ?>" required>

                        <label for="amorce">Amorce *</label>
                        <input type="text" name="amorce" id="amorce"
                               placeholder="Amorce de l'article" value="<?php if (!empty($actualite)) echo $actualite->amorce; ?>" required>

                        <label for="departement">Liasion à un département *</label>
                        <select name="did" id="departement">
                            <option value="">Aucun</option>
                            <?php
                            foreach ($listDepartements as $departement) {
                                ?>
                                <option value="<?php  echo $departement->did; ?>"><?php echo $departement->departement; ?></option>
                                <?php
                            }
                            ?>
                        </select>

                        <label for="description">Texte complet de l'article *</label>
                        <textarea id="description" name="actualite" cols="50" rows="15" required><?php if (!empty($actualite)) echo $actualite->actualite; ?></textarea>

                        <label for="visible">Visible *</label>
                        <div>
                            <input type="radio" id="visible" name="visible"
                                   value="Oui" checked <?php echo (!empty($_POST['visible']) && ($_POST['visible'] == "Oui")) ? 'checked' : ''; ?>> Oui
                            <input type="radio" id="visible" name="visible"
                                   value="Non" <?php echo (!empty($_POST['visible']) && ($_POST['visible'] == "Non")) ? 'checked' : ''; ?>> Non
                        </div>

                        <label for="image">Image de l'article *</label>
                        <input type="file" name="image" id="image" accept=".jpeg, .jpg, .png" required>
                            
                        <div class="divAjoutDep">
                            <input type="submit" class="boutonAjouter position" name="modifier" value="Modifier">
                            <input type="reset" class="boutonAnnuler position" name="annuler" value="Annuler">
                        </div>
                    </form>
                </section>
        </main>
        
<?php require_once 'inc/footer.inc.php'; ?>