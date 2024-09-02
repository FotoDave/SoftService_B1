<?php

    require_once 'php/session.inc.php';
    require_once 'inc/head.inc.php';
    require_once 'inc/headerAdmin.inc.php';
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

    //Récupération de la liste des roles
    $listRoles = $roleRepository->findAllRoles($message);
    //Récupération de la liste des départements
    $listDepartements = $departementRepository->findAllDepartements($message);

    $rolesArray = $listRoles->fetchAll(PDO::FETCH_CLASS, SoftService\Role::class);
    $departementArray = $listDepartements->fetchAll(PDO::FETCH_CLASS, SoftService\Departement::class);

    $message = '';
    $noError = true;

    $personnel = new Personnel();
    $fonction = new Fonction();
    $personnelRepository = new PersonnelRepository();
    $fonctionRepository = new FonctionRepository();


    if(isset($_GET['id']) && is_numeric($_GET['id'])){
        //Récupération du membre et de sa fonction via son id reçu dans l'URL
        $personnel = $personnelRepository->findMemberById($_GET['id'], $message);
        $fonction = $fonctionRepository->findFunctionByPersonnelId($_GET['id'], $message);

        if(empty($personnel)){
            $message .= 'Aucun membre trouvé !<br>';
            $noError = false;
        }
    }else{
        header("Location: presentation-equipe.php");
    }

    if(isset($_POST['enregistrer']) && $noError){
        //Provient de l'ajout des membres
        $roleByDepartement = array();
        foreach ($departementArray as $departement){
            if(isset($_POST['rid_'.$departement->did])){
                $roleByDepartement[$departement->did] = (!empty($_POST['rid_'.$departement->did]))
                    ? cleanField($_POST['rid_'.$departement->did]) : null;
            }
        }
        $personnel->prenom = cleanField($_POST['prenom']);
        $personnel->nom = cleanField($_POST['nom']);
        $personnel->courriel = cleanField($_POST['email']);
        $personnel->mot_passe = cryptAndHashPassWord(cleanField($_POST['mpd']));
        $personnel->telephone = cleanField($_POST['telephone']);
        $personnel->description = cleanField($_POST['description']);

        $fileOK = isset($_FILES['photo']) && $_FILES['photo']['error'] == UPLOAD_ERR_OK;

        $noError = validationModificationPersonnel($personnel->prenom, $personnel->nom, $personnel->courriel,
            $personnel->mot_passe, $personnel->telephone, $personnel->description, $fileOK, $_FILES['photo']['size'], $message);

        if ($noError){
            $newFileName = basename($_FILES['photo']['name']);
            if(!isExist($newFileName, $message)) {
                $randomCodeAlphaNumeric = substr(uniqid(), -5);
                $newFileName = $randomCodeAlphaNumeric . basename($_FILES['photo']['name']);
                $personnel->photo = $newFileName;
                $_FILES['photo']['name'] = $newFileName;

                if (deplacerImageVersServeur($_FILES['photo']['tmp_name'], $_FILES['photo']['name'], $message)) {
                    $personnelRepository->modificationPersonnel($personnel,$message);
                        foreach ($roleByDepartement as $did => $rid) {
                            if(is_numeric($rid)){
                                $fonction = $fonctionRepository->findFunctionByPersonnelAndDepartmentId($personnel->pid, $did, $message);
                                $fonction->rid = $rid;
                                $fonctionRepository->modificationFonctionPersonnel($fonction, $message);
                            }
                        }
                        header("Location: presentation-equipe.php");
                    }
                }
            }
    }


?>
    <main class="flexCenter main">
        <section class="sectionDepartement">
            <form method="POST" class="flexCol formContact" enctype="multipart/form-data" action="#">
                <h1 class="centre">Editer membre</h1>
                <p><?php echo $message; ?></p>
                <label for="nom">Noms *</label>
                <input type="text" name="nom" id="nom" value="<?php if (!empty($personnel)) echo $personnel->nom; ?>">

                <label for="prenom">Prénoms *</label>
                <input type="text" name="prenom" id="prenom" value="<?php if (!empty($personnel)) echo $personnel->prenom; ?>">

                <label for="email">Courriel *</label>
                <input type="text" name="email" id="email" value="<?php if (!empty($personnel)) echo $personnel->courriel; ?>">

                <label for="mpd">Mot de passe *</label>
                <!-- A verifier -->
                <input type="password" name="mpd" id="mpd" value="" >

                <p>Liaison Département -> Rôle </p>
                <div>
                    <?php
                    foreach ($departementArray as $departement) {
                        ?>
                        <div>
                            <label for="role_<?php echo $departement->did; ?>"><?php echo $departement->departement; ?> -> </label>
                            <select name="rid_<?php echo $departement->did; ?>" id="role_<?php echo $departement->did; ?>">
                                <option value="">Aucun</option>
                                <?php foreach ($rolesArray as $role) { ?>
                                    <option value="<?php echo $role->rid; ?>"><?php echo $role->role; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <?php
                    }
                    ?>
                </div>

                <label for="telephone">Téléphone *</label>
                <input type="text" name="telephone" id="telephone" value="<?php if (!empty($personnel)) echo $personnel->telephone; ?>">

                <label for="description">Description *</label>
                <textarea name="description" id="description" cols="30" rows="10" ><?php if (!empty($personnel)) echo $personnel->description; ?></textarea>

                <label for="photo">Photo *</label>
                <input type="file" name="photo" id="photo" accept="image/*">

                <div class="divAjoutDep">
                    <input type="submit" class="boutonAjouter position" name="enregistrer" value="Enregistrer">
                    <input type="reset" class="boutonAnnuler position" name="annuler" value="Annuler">
                </div>
            </form>
        </section>
    </main>
<?php require_once 'inc/footer.inc.php'; ?>