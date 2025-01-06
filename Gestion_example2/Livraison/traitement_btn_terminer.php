<?php
session_start();
include "../../Categorie/Categorie/config/db.php";

try {
    $idBL = $_GET["idBL"];

    // Vérifier si l'idBL est valide
    if (empty($idBL)) {
        throw new Exception("ID de bon de livraison invalide.");
    }

            // Mise à jour de l'état de la commande
            $r = "UPDATE bon_livraison SET Etat_Livraison = 3 WHERE idBL = :idBL";
            $stmt = $connexion->prepare($r);
            $stmt->execute([':idBL' => $idBL]);

            header("Location: Liste_BL.php");
            exit;

} catch (PDOException $e) {
    echo "Erreur PDO : " . $e->getMessage();
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
?>