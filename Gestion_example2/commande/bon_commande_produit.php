<?php
session_start();
include '../../Categorie/Categorie/config/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer l'ID du bon de commande sélectionné
    $idBC = $_POST['bonSelect'];

    // Récupérer les produits sélectionnés
    $produits = $_POST['products'];
    $quantites = $_POST['quantity'];
    $rest = $quantites;
    $date = date('Y-m-d H:i:s');

    try {
        // Insérer chaque produit avec sa quantité
        $stmt = $connexion->prepare("INSERT INTO  BC_produit (idBC, idP, dateadd, quantite,reste_a_livrer) VALUES (?, ?, ?, ?,?)");

        foreach ($produits as $produit) {
            $stmt->execute([$idBC, $produit, $date, $quantites[$produit],$quantites[$produit]]);
        }
        
        if($stmt) {
            $_SESSION["fait"] = "Fait";
        header("Location:commander.php");
        }
    } catch (Exception $e) {
        echo "Erreur lors de l'insertion : " . $e->getMessage();
    }
  
}