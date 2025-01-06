<?php
include "../../Categorie/Categorie/config/db.php";

$id_produit = $_GET['id_produit'];

$r = "SELECT Stock_actuel 
     FROM product
     WHERE idP = :id_produit";

$requette = $connexion->prepare($r);
$requette->bindParam(':id_produit', $id_produit);
$requette->execute();
$reponse = $requette->fetch(PDO::FETCH_ASSOC);

echo json_encode($reponse);