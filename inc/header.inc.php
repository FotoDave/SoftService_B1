    <body class="flexCol body">
        <header>
           <nav class="menu">
               <a href="accueil.php">
                   <img src="imagesSiteWeb/logo3.png" class="logoEntete" alt="logo-entreprise">
               </a>
                <ul class="itemsMenu">
                    <li><a href="accueil.php">Accueil</a></li>
                    <li><a href="presentation-equipe.php">Equipes</a></li>
                    <li><a href="liste-actualites.php">Actualité</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
               <?php
                    session_start();
                    if(isset($_SESSION['prenom'])){
               ?>
               <ul class="itemsMenu">
                   <li><a href="#"><?php echo $_SESSION['prenom']; ?></a></li>
                   <li>
                       <form action="connexion.php" method="post">
                           <button type="submit" name="deconnexion">Se déconnecter</button>
                       </form>
                   </li>
               </ul>
               <?php } else {?>
                        <a href="connexion.php">Connexion</a>
               <?php }?>
            </nav>
        </header>
		