<?php
// Connect to database
include("../../Categorie/Categorie/config/db.php");
// Get product data from AJAX request
$idP = $_POST['idP'];
$idP_ancien = $_POST['id'];
$quantite = $_POST['quantite'];
$bonCmd = $_POST['idbc'];
$date = date('Y-m-d H:i:s');
// Save product data to database
$r = "UPDATE bon_sortie_produit SET idP = :idP , dateadd = :dateadd ,quantite = :quantite WHERE idS = :idBon and idP = :id_ancien";
$requette = $connexion->prepare($r);
$requette->bindParam(':idBon', $bonCmd);
$requette->bindParam(':idP', $idP);
$requette->bindParam(':id_ancien', $idP_ancien);
$requette->bindParam(':dateadd', $date);
$requette->bindParam(':quantite', $quantite);
$requette->execute();

// Return success response
if($requette) {
    echo json_encode(true);
} else {
    echo json_encode(false);
}