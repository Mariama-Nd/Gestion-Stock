<?php
session_start();
include '../../Categorie/Categorie/config/db.php';

$idBL = $_GET['idBL'];
$idP = $_GET['idP'];
$quantity = $_GET['quantity'];

try {
    $connexion->beginTransaction();

    // Vérifier si le produit existe dans le bon de livraison
    $r = "SELECT 1 FROM bon_livraison_produit WHERE idBL = :idBL AND idP = :idP";
    $requette = $connexion->prepare($r);
    $requette->execute([':idBL' => $idBL, ':idP' => $idP]);
    $produitExiste = $requette->fetchColumn();

    // Supprimer (mettre à jour la quantité à 0) le produit du bon de livraison s'il existe
    if ($produitExiste) {
        // Mettre à jour la quantité à 0 
        $sql = "UPDATE bon_livraison_produit SET quantite = 0 WHERE idBL = :idBL AND idP = :idP";
        $stmt = $connexion->prepare($sql);
        $stmt->execute([
            ':idBL' => $idBL,
            ':idP' => $idP
        ]);

        // Remettre la quantité supprimée dans le stock du produit
        $stmt = $connexion->prepare("UPDATE product SET Stock_actuel = Stock_actuel - :quantity WHERE idP = :idP");
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':idP', $idP);
        $stmt->execute();

        $connexion->commit();

        echo json_encode(['success' => true]);
    } else {
        // Le produit n'est pas dans le bon de livraison
        echo json_encode(['success' => false, 'message' => 'Le produit n\'est pas présent dans le bon de livraison.']);
    }

} catch (PDOException $e) {
    $connexion->rollBack();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

?>