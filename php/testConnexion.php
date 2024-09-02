<!DOCTYPE html>
<?php


require 'db_role.inc.php';
require 'db_departement.inc.php';

use SoftService\RoleRepository as RoleRepository;
use SoftService\DepartementRepository as DepartementRepository;


$roleRepository = new RoleRepository();
$departementRepository = new DepartementRepository();

$listRole = $roleRepository->findAllRoles($message);
$listDepartement = $departementRepository->findAllDepartements($message);

?>

<html lang="fr">
<head>
    <meta charset="utf-8"/>
    <title>Newsletter</title>
</head>
<body>

<p><?php
    //affichage des messages d'erreurs éventuels
    echo $message;
    //echo var_dump($listmembres);
    ?>
</p>
<?php
echo "Liste des départements <br>";
//Affichage de la liste des départements
foreach ($listDepartement as $departement) {
    ?>
    <li>
        <a href="fiche.php?id=<?php  echo $departement->did; ?>"><?php echo $departement->departement; ?></a>
    </li>
    <?php
        }
    ?>
<?php
    echo "<br> Liste des rôles <br>";
//Affichage de la liste des roles
foreach ($listRole as $role) {
    ?>
    <li>
        <a href="fiche.php?id=<?php  echo $role->rid; ?>"><?php echo $role->role; ?></a>
    </li>
    <?php
}
?>
</body>
</html>