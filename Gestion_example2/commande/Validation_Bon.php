<?php
session_start();
// Connect to database
include("../../Categorie/Categorie/config/db.php");
// Get product data from AJAX request
$bonCmd = $_GET['idbon'];
$etats = 2;

// Vérifier l'état actuel de la commande
$r = "SELECT Etat_commander FROM bon_commande WHERE id_BC = :id_BC";
$requette = $connexion->prepare($r);
$requette->bindParam(':id_BC', $bonCmd);
$requette->execute();
$result = $requette->fetch(PDO::FETCH_ASSOC);

if ($result) {
    // Vérifier si l'état est déjà à 2
    if ($result['Etat_commander'] == 2) {
        $_SESSION["cmd"] = "Deja_Fait";
    } else {
        $date_validation =  date("Y-m-d H:i:s");
        // Mettre à jour l'état de la commande
        $updateQuery = "UPDATE bon_commande SET Etat_commander = :Etat_commander WHERE id_BC = :id_BC";
        $updateRequette = $connexion->prepare($updateQuery);
        $updateRequette->bindParam(':Etat_commander', $etats);
        $updateRequette->bindParam(':id_BC', $bonCmd);
        $updateRequette->execute();

        //Mettre place une date de validation du Bon de commande
        $updateQuery = "UPDATE bon_commande SET Date_validation = :date_valide WHERE id_BC = :id_BC";
        $updateRequette = $connexion->prepare($updateQuery);
        $updateRequette->bindParam(':date_valide', $date_validation);
        $updateRequette->bindParam(':id_BC', $bonCmd);
        $updateRequette->execute();

        if ($updateRequette) {
            $_SESSION["cmd"] = "Valider";
        } else {
            $_SESSION["cmd"] = "Erreur lors de la validation de la commande.";
        }
    }
} else {
    $_SESSION["cmd"] = "Commande non trouvée.";
}

header("Location:Liste_bon_cmd.php");
exit();