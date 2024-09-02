<?php

    require_once 'inc/head.inc.php';
    require_once 'inc/header.inc.php';
    require_once 'php/fonctions_validation.inc.php';

    $message = '';

    if (isset($_POST['envoyer'])) {
        $email = cleanField($_POST['email']);
        $sujet = cleanField($_POST['sujet']);
        $contenu = cleanField($_POST['message']);
        if(validationMail($email, $sujet, $contenu, $message)){
          sendingMail($email, $sujet, $contenu, $message);
        }
    }


?>
        <main class="mainContact">
            <section class="sectionContact">
                <section class="flexCol articleContact">
                    <img src="imagesSiteWeb/logo3.png" class="imgContact" alt="logo-entreprise">
                    <h2>Nous contacter</h2>
                </section>
                <section>
                    <ul class="flexCol divInfos">
                        <li><img src="imagesSiteWeb/house.png" alt="logo-maison"> Rue des Vignerons 60, 4020 Liège</li>
                        <li><img src="imagesSiteWeb/phone.png" alt="logo-telephone"> +32 (0) 322 32 32 32</li>
                        <li><img src="imagesSiteWeb/email.png" alt="logo-email"> info@softservice.be</li>
                    </ul>
                </section>
            </section>

            <section class="sectionContact">
                <form method="POST" class="flexCol formContact" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <h1 class="centre">Formulaire de contact</h1>
                    <label for="email">Courriel *</label>
                    <input type="text" name="email" id="email"
                           placeholder="Votre courriel" value="<?php if (isset($_POST['envoyer'])){ echo $_POST['email'];}
                                else{ if(isset($_SESSION['email'])){ echo $_SESSION['email'];} }?>" required>
                    <label for="sujet">Sujet *</label>
                    <input type="text" name="sujet" id="sujet"
                           placeholder="Sujet du message" value="<?php if (isset($_POST['envoyer'])) echo $_POST['sujet']; ?>" required>
                    <label for="message">Message *</label>
                    <textarea id="message" name="message" cols="30" rows="10" required><?php if (isset($_POST['envoyer'])) echo $_POST['message']; ?></textarea>
                    <input type="submit" class="boutonContact position" name="envoyer" value="Envoyer">
                    <p><?php echo $message; ?></p>
                </form>
            </section>
        </main>
        
<?php require_once 'inc/footer.inc.php'; ?>