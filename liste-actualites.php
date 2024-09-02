<?php

    require_once 'inc/head.inc.php';
    require_once 'inc/header.inc.php';
    require_once 'php/db_departement.inc.php';
    require_once 'php/db_actualite.inc.php';
    require_once 'php/fonctions_validation.inc.php';

    use SoftService\DepartementRepository as DepartementRepository;
    use SoftService\Departement as Departement;
    use SoftService\ActualiteRepository as ActualiteRepository;
    use SoftService\Actualite as Actualise;

    $message = '';
    $noError = true;
    $listeAcutalites = array();

    $departementRepository = new DepartementRepository();
    $actualiteRepository = new ActualiteRepository();
    $listDepartements = $departementRepository->findAllDepartements($message);

    if(isset($_POST['filtrer'])){
        $did = (!empty($_POST['did'])) ? cleanField($_POST['did']) : null;
        $keyword = (!empty($_POST['keyword'])) ? cleanField($_POST['keyword']) : null;

        $case = (empty($did) ? '0' : '1') . (empty($keyword) ? '0' : '1');
        switch ($case) {
            case '10': // $did est défini, $keyword est null
                if(isset($_SESSION['prenom'])){
                    $listeAcutalites = $actualiteRepository->filterByDidWithAuthentication($did,$message);
                }else{
                    $listeAcutalites = $actualiteRepository->filterByDidNoAuthentication($did, $message);
                }
                break;
            case '01': // $did est null, $keyword est défini
                if(hasMinimumLetters($keyword)){
                    if(isset($_SESSION['prenom'])){
                        $listeAcutalites = $actualiteRepository->filterByKeywordWithAuthentication($keyword,$message);
                    }else{
                        $listeAcutalites = $actualiteRepository->filterByKeywordWithNoAuthentication($keyword, $message);
                    }
                }else{
                    $message .= 'Le mot doit comporter 4 lettres minimum <br>';
                }
                break;
            case '11': // $did et $keyword sont tous deux définis
                if(hasMinimumLetters($keyword)){
                    if(isset($_SESSION['prenom'])){
                        $listeAcutalites = $actualiteRepository->filterByDidAndKeywordWithAuthentication($keyword, $did,$message);
                    }else{
                        $listeAcutalites = $actualiteRepository->filterByDidAndKeywordWithNoAuthentication($keyword, $did, $message);
                    }
                }else{
                    $message .= 'Le mot doit comporter 4 lettres minimum <br>';
                }
                break;
            default: // Aucun filtre sélectionné ou autres cas
                $message .= 'Aucun filtre spécifié <br>';
                break;
        }
    }else{
        if(isset($_SESSION['prenom'])){
            $list = $actualiteRepository->findAllActualites($message);
            $listeAcutalites = $list->fetchAll(PDO::FETCH_CLASS, SoftService\Actualite::class);
        }else{
            $list = $actualiteRepository->findVisibleActualities($message);
            $listeAcutalites = $list->fetchAll(PDO::FETCH_CLASS, SoftService\Actualite::class);
        }
    }

    if(empty($listeAcutalites)){
        $message .= "Aucun article trouvé !";
    }
?>
        <main class="flexCenter main">
            <section class="sectionListeActualite">
                <h1>Actualités</h1>
                <form method="POST" class="flexCol formActualite" action="#">
                    <section class="flexRow articleFiltre">
                        <div class="flexCenter bloc1">
                            <label>Département</label>
                            <select name="did">
                                <option value="">Aucun</option>
                                <?php
                                foreach ($listDepartements as $departement) {
                                    ?>
                                    <option value="<?php  echo $departement->did; ?>"><?php echo $departement->departement; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="flexCenter bloc2">
                            <label for="recherche">Mots clefs</label>
                            <input type="text" name="keyword" id="recherche" placeholder="Mots clefs"
                                value="<?php if (isset($_POST['keyword'])) echo $_POST['keyword']; ?>">
                        </div>
                        <div class="flexCenter bloc3">
                            <input type="submit" class="boutonFiltre" name="filtrer" value="Filtrer">
                        </div>
                    </section>

                    <section class="flexRow flWrap sectionProfil">
                        <p><?php echo $message; ?></p>
                        <?php
                                foreach ($listeAcutalites as $actualite) {
                            ?>
                            <article class="flexCol articleActualite">
                                <figure>
                                    <img src="<?php echo DOSSIER_IMAGE_MEMBRE . $actualite->image; ?>" class="imgArticle" alt="image">
                                </figure>
                                <h2><?php echo $actualite->intitule; ?></h2>
                                <p class="centre"><?php echo $actualite->amorce; ?></p>
                                <p class="datePublication">Publié le <?php echo date('d-m-Y', strtotime($actualite->date_actualite)); ?></p>
                                <?php if((boolean)$actualite->visible){ ?>
                                    <p class="datePublication"><span class="visible">Visible</span></p>
                                <?php } else{ ?> <p class="datePublication"><span class="nonVisible">Non visible</span></p> <?php } ?>
                                <a href="consulter-actualite.php?id=<?php echo $actualite->aid; ?>" class="datePublication">En savoir plus</a>
                            </article>
                            <?php } ?>

                    </section>
                </form>
            </section>
        </main>

<?php require_once 'inc/footer.inc.php'; ?>