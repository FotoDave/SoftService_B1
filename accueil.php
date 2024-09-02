<?php

    require_once 'inc/head.inc.php';
    require_once 'inc/header.inc.php';
    require_once 'php/db_actualite.inc.php';
    require_once 'php/fonctions_validation.inc.php';

    use SoftService\ActualiteRepository as ActualiteRepository;
    use SoftService\Actualite as Actualite;

    $message = '';
    $actualiteRepository = new ActualiteRepository();
    $actualite = new Actualite();

    $listeActualites = $actualiteRepository->findActualitesForAccueil($message);
?>
    <main>
        <section class="sectionImageAccueil">
            <div class="divAcceuil">
                <h1 class="titreImage">SoftService SA</h1>
                <p>Est une entreprise évoluant dans les domaines des services informatiques, de l'audit et du conseil.</p>
                <p>Nous mettons notre dévouement et notre expérience de gestion dans ces domaines au service du développement de votre activité.</p>
            </div>
        </section>

        <h1 class="titreServiceAccueil">Nos Services</h1>
        <section class="sectionServicesAccueil">
            <article class="flexCol articleServicesAccueil">
                <figure class="flexCenter figureService">
                    <img class="imageServiceAccueil" src="imagesSiteWeb/dev.png" alt="icone-significative">
                </figure>
                <h2>Développement</h2>
                <p>
                    Nous fournissons des logiciels de gestion à des organisations de toute taille avec pour buts de constituer les bases de données d’information de l’entreprise.
                </p>
            </article>

            <article class="flexCol articleServicesAccueil">
                <figure class="flexCenter figureService">
                    <img class="imageServiceAccueil" src="imagesSiteWeb/audit.png" alt="icone-significative">
                </figure>
                <h2>Audit</h2>
                <p>
                    Notre service d'audit s'engage à fournir une évaluation précise et approfondie de vos processus, de vos opérations et de vos pratiques financières.
                </p>
            </article>

            <article class="flexCol articleServicesAccueil">
                <figure class="flexCenter figureService">
                    <img class="imageServiceAccueil" src="imagesSiteWeb/conseil.png" alt="icone-significative">
                </figure>
                <h2>Conseil</h2>
                <p>
                    Notre service de conseil a pour objectif de guider nos clients vers le succès en leur fournissant des conseils stratégiques et des solutions personnalisées.
                </p>
            </article>

        </section>

        <h1 class="titreActuAccueil">Nos Actualités</h1>

        <section class="flexRow sectionActuAccueil">
            <?php foreach ($listeActualites as $actualite){?>
                <article class="flexCol articleActuAccueil">
                    <img src="<?php echo DOSSIER_IMAGE_MEMBRE . $actualite->image; ?>" class="imageActuAccueil" alt="image">
                    <h2><?php echo $actualite->intitule; ?></h2>
                    <p>Posté le <?php echo date('d-m-Y', strtotime($actualite->date_actualite)); ?></p>
                    <a href="consulter-actualite.php?id=<?php echo $actualite->aid; ?>">En savoir plus</a>
                </article>
            <?php } ?>
        </section>

    </main>
		
<?php require_once 'inc/footer.inc.php'; ?>