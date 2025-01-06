<?php
// Connect to database
include("../../Categorie/Categorie/config/db.php");
// Get product data from AJAX request
$idP = $_POST['idP'];
$bonCmd = $_POST['idbc'];
$quantite = $_POST['quantite'];
// Save product data to database
$r = "DELETE FROM bon_sortie_produit WHERE idS = :idBon and idP = :idP";
$requette = $connexion->prepare($r);
$requette->bindParam(':idBon', $bonCmd);
$requette->bindParam(':idP', $idP);
$requette->execute();

$stmt = $connexion->prepare("UPDATE product SET Stock_actuel = Stock_actuel + :quantity WHERE idP = :idP");
$stmt->bindParam(':quantity', $quantite);
$stmt->bindParam(':idP', $idP);


// Exécuter la mise à jour
$stmt->execute();
$updateStateStmt = $connexion->prepare("UPDATE product SET id_statut = 1 WHERE idP = :idP");
$updateStateStmt->bindParam(':idP', $idP);
$updateStateStmt->execute();
// Return success response
if($requette && $stmt) {
    echo json_encode(true);
} else {
    echo json_encode(false);
}