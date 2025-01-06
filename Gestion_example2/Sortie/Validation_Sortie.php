<?php
session_start();
// Connect to database
include("../../Categorie/Categorie/config/db.php");

// Get product data from AJAX request
$bonCmd = $_GET['idBon'];
$etats = 2;

// Vérifier l'état actuel de la commande
$r = "SELECT Etat_bon_sortie FROM bon_sortie WHERE idBS = :id_BC";
$requette = $connexion->prepare($r);
$requette->bindParam(':id_BC', $bonCmd);
$requette->execute();
$result = $requette->fetch(PDO::FETCH_ASSOC);
// Vérification de l'état de la commande
if ($result) {
    // Vérifier si l'état est déjà à 2
    if ($result['Etat_bon_sortie'] == 2) {
        $_SESSION["cmd"] = "Deja_Fait";
    } else {
        $date_validation = date("Y-m-d H:i:s");
        
        // Mettre à jour l'état de la commande
        $updateQuery = "UPDATE bon_sortie SET Etat_bon_sortie = :Etat_commander WHERE idBS = :id_BC";
        $updateRequette = $connexion->prepare($updateQuery);
        $updateRequette->bindParam(':Etat_commander', $etats);
        $updateRequette->bindParam(':id_BC', $bonCmd);
        $updateRequette->execute();

        // Mettre à jour la date de validation du bon de commande
        /* 
        $updateQuery = "UPDATE bon_commande SET Date_validation = :date_valide WHERE id_BC = :id_BC";
        $updateRequette = $connexion->prepare($updateQuery);
        $updateRequette->bindParam(':date_valide', $date_validation);
        $updateRequette->bindParam(':id_BC', $bonCmd);
        $updateRequette->execute();
        */

        // Sélectionner tous les produits pour mettre à jour leur stock
        $r_list = "SELECT idP, quantite FROM bon_sortie_produit WHERE idS = :id_BC";
        $requette_list = $connexion->prepare($r_list);
        $requette_list->bindParam(':id_BC', $bonCmd);
        $requette_list->execute();
        $result_list = $requette_list->fetchAll(PDO::FETCH_ASSOC); // Utiliser fetchAll pour obtenir tous les résultats

        foreach ($result_list as $key) {
            // Mettre à jour le stock des produits
            $updateStockQuery = "UPDATE product SET Stock_actuel = Stock_actuel - :quantite WHERE idP = :idP";
            $requette_stock = $connexion->prepare($updateStockQuery);
            $requette_stock->bindParam(':quantite', $key["quantite"]);
            $requette_stock->bindParam(':idP', $key["idP"]);
            $requette_stock->execute();
        }

        // Vérifier si la mise à jour a réussi
        if ($updateRequette) {
            $_SESSION["cmd"] = "Valider";
        } else {
            $_SESSION["cmd"] = "Erreur lors de la validation de la commande.";
        }
    }
} else {
    $_SESSION["cmd"] = "Sortie non trouvée.";
}

// Redirection
header("Location: Liste_bon_sortie.php");
exit();