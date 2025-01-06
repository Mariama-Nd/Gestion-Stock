<?php
// Connect to database
include("../../Categorie/Categorie/config/db.php");
// Get product data from AJAX request
$idP = $_POST['idP'];
$quantite = $_POST['quantite'];
$bonCmd = $_POST['idbc'];
$date = date('Y-m-d H:i:s');
// Save product data to database
$r = "INSERT INTO bon_commande_produit (idbc,idP, dateadd,quantite, reste_a_livrer) VALUES (:idBon,:idP,:dateadd, :quantite,:reste_a_livrer)";
$requette = $connexion->prepare($r);
$requette->bindParam(':idBon', $bonCmd);
$requette->bindParam(':idP', $idP);
$requette->bindParam(':dateadd', $date);
$requette->bindParam(':quantite', $quantite);
$requette->bindParam(':reste_a_livrer', $quantite);
$requette->execute();
$updateSql = "UPDATE bon_commande SET Etat_commander = 5 WHERE id_BC = :idBC";
$updateStmt = $connexion->prepare($updateSql);
$updateStmt->bindParam(':idBC', $bonCmd);
$updateStmt->execute();
// Return success response
if ($requette) {
    echo json_encode(true);
} else {
    echo json_encode(false);
}