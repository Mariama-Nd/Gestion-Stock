<?php
// Connect to database
include("../../Categorie/Categorie/config/db.php");
// Get product data from AJAX request
$idP = $_POST['idP'];
$bonCmd = $_POST['idbc'];
// Save product data to database
$r = "DELETE FROM bon_commande_produit WHERE idbc = :idBon and idP = :idP";
$requette = $connexion->prepare($r);
$requette->bindParam(':idBon', $bonCmd);
$requette->bindParam(':idP', $idP);
$requette->execute();

// Return success response
if($requette) {
    echo json_encode(true);
} else {
    echo json_encode(false);
}