<?php
// Connect to database
include("../../Categorie/Categorie/config/db.php");
// Get product data from AJAX request
$idP = $_POST['idP'];
$quantite = $_POST['quantite'];
$bonCmd = $_POST['idbc'];
$date = date('Y-m-d H:i:s');
// Save product data to database
$r = "INSERT INTO bon_sortie_produit (idP,idS,quantite,dateadd) VALUES (:idP,:idBon, :quantite,:dateadd)";
$requette = $connexion->prepare($r);
$requette->bindParam(':idBon', $bonCmd);
$requette->bindParam(':idP', $idP);
$requette->bindParam(':dateadd', $date);
$requette->bindParam(':quantite', $quantite);
$requette->execute();
$updateSql = "UPDATE bon_sortie SET Etat_bon_sortie = 5 WHERE idBS = :idBS";
$updateStmt = $connexion->prepare($updateSql);
$updateStmt->bindParam(':idBS', $bonCmd);
$updateStmt->execute();

// Return success response
if ($requette) {
    echo json_encode(true);
} else {
    echo json_encode(false);
}

/*

////////////////////////////DEBUT///////////////////////
 Ce code que voici nous permettra de decrementer le stock

 try {
    // Préparer la mise à jour du stock
    $stmt = $connexion->prepare("UPDATE product SET Stock_actuel = Stock_actuel - :quantity WHERE idP = :idP");
    $stmt->bindParam(':quantity', $quantite);
    $stmt->bindParam(':idP', $idP);
    
    // Exécuter la mise à jour
    $stmt->execute();

    // Vérifier le nouveau stock
 
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}

////////////////////////////////FIN//////////////////////////////

//////////////////////////////DEBUT/////////////////////////////
Ce code vous permettra de verifier si le stock du produit est egale a zero si oui on set l'etat a Hors service(2)
   $checkStockStmt = $connexion->prepare("SELECT Stock_actuel FROM product WHERE idP = :idP");
    $checkStockStmt->bindParam(':idP', $idP);
    $checkStockStmt->execute();

    // Récupérer le stock actuel
    $currentStock = $checkStockStmt->fetchColumn();

    // Si le stock est égal à zéro, mettre à jour l'état du produit
    if ($currentStock === 0) {
        $updateStateStmt = $connexion->prepare("UPDATE product SET id_statut = 2 WHERE idP = :idP");
        $updateStateStmt->bindParam(':idP', $idP);
        $updateStateStmt->execute();
    }

/////////////////////////FIN////////////////////////////////////

*/