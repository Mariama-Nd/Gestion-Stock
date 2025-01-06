<?php
session_start();
include("../../Categorie/Categorie/config/db.php");

function isProductInOrder($productId, $connexion) {
    $sql = "SELECT COUNT(*) FROM bon_livraison WHERE id_bc = :id_bc";
    $stmt = $connexion->prepare($sql);
    $stmt->execute(['id_bc' => $productId]);
    return $stmt->fetchColumn() > 0; // Retourne vrai si le produit est dans une commande
}

$bonCmd = $_POST['idboncmd'];

if (isProductInOrder($bonCmd, $connexion)) {
    // Produit est dans une commande
    echo json_encode(true);
} else {
    // Code pour "supprimer" le bon de commande
    $etats = 4;
    $r = "UPDATE bon_commande SET Etat_commander = :Etat_commander WHERE id_BC = :id_BC";
    $requette = $connexion->prepare($r);
    $requette->bindParam(':Etat_commander', $etats);
    $requette->bindParam(':id_BC', $bonCmd);
    $requette->execute();

    // Retourner une réponse de succès
    if ($requette) {
        echo json_encode(false);
    } else {
        echo json_encode("Erreur lors de la suppression.");
    }
}