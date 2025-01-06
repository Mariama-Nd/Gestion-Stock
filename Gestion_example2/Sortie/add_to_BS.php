<?php
session_start();
try {
    include "../../Categorie/Categorie/config/db.php";

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["sauvegarder"])) {
        $idP = $_POST['idP'];
        $quantite = $_POST['quantite'];
        $date = date("Y-m-d H:i:s");
        $idBS = $_GET["idBon"];

        // Validate input data
        if (empty($idP) || empty($quantite)) {
            throw new Exception("Invalid input data");
        }

        // Prepare the insert statement
        $sql = "INSERT INTO bon_sortie_produit (idP, idS, quantite, dateadd) VALUES (:idP, :idBS, :quantite, :dateadd)";
        $stmt = $connexion->prepare($sql);

        foreach ($idP as $i => $id) {
            // Verify if the product with the same idP and idS already exists
            $checkProductSql = "SELECT COUNT(*) FROM bon_sortie_produit WHERE idP = :idP AND idS = :idBS";
            $checkProductStmt = $connexion->prepare($checkProductSql);
            $checkProductStmt->execute([':idP' => $id, ':idBS' => $idBS]);
            $productExists = $checkProductStmt->fetchColumn();

            // If the product exists, skip to the next iteration
            if ($productExists > 0) {
                continue; // Skip this product
            }

            // If the product does not exist, we insert it
            $stmt->execute([
                ':idBS' => $idBS,
                ':idP' => $id,
                ':quantite' => $quantite[$i],
                ':dateadd' => $date
            ]);
        }

        // Update the bon_sortie status
        $r = "UPDATE bon_sortie SET Etat_bon_sortie = 5 WHERE idBS = :idBS";
        $updateStmt = $connexion->prepare($r);
        $updateStmt->execute([':idBS' => $idBS]);

        $_SESSION["message"] = "Fait";
        header("Location: Liste_bon_sortie.php");
        exit;
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}