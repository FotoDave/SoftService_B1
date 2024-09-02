<?php

    require_once 'php/session.inc.php';
    require_once 'inc/head.inc.php';
    require_once 'inc/headerAdmin.inc.php';

?>
        <main class="flexCenter main">
            <section class="sectionDepartement">
                <form method="POST" class="flexCol formAjoutDep" action="#">
                    <h1>Supprimer Département</h1>
                    <label for="nom">Département </label>
                    <input type="text" name="nom" id="nom" placeholder="Nom du déparement">
                    <div class="divAjoutDep">
                        <input type="submit" class="boutonAjouter position" name="supprimer" value="Supprimer">
                        <input type="reset" class="boutonAnnuler position" name="annuler" value="Annuler">
                    </div>
                </form>
            </section>
        </main>
        
<?php require_once 'inc/footer.inc.php'; ?>