<?php
session_start();
try {
    include "../../Categorie/Categorie/config/db.php";

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["soumettre"])) {
        $idP = $_POST['idP'];
        $quantite = $_POST['quantite'];
        $date = date("Y-m-d H:i:s");
        $idBC = $_SESSION["idBC"];

        // Validate input data
        if (empty($idP) || empty($quantite)) {
            throw new Exception("Invalid input data");
        }

        // Insert data into BC_produit table
        $sql = "INSERT INTO bon_commande_produit (idbc, idP, dateadd, quantite, reste_a_livrer) VALUES (:idBC, :idP, :date, :quantite, :reste_a_livrer)";
        $stmt = $connexion->prepare($sql);

        foreach ($idP as $i => $id) {
            $stmt->execute([
                ':idBC' => $idBC,
                ':idP' => $id,
                ':date' => $date,
                ':quantite' => $quantite[$i],
                ':reste_a_livrer' => $quantite[$i]
            ]);
        }
        $r = "UPDATE bon_commande set Etat_commander = 2 where id_BC = $idBC ";
        $stmt = $connexion->query($r);
        $_SESSION["message"] = "Fait";
        header("Location: Liste_bon_cmd.php");
        exit; // Ensure the script stops executing after redirect
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["sauvegarder"])) {
        $idP = $_POST['idP'];
        $quantite = $_POST['quantite'];
        $date = date("Y-m-d H:i:s");
        $idBC = $_SESSION["idBC"];

        // Validate input data
        if (empty($idP) || empty($quantite)) {
            throw new Exception("Invalid input data");
        }

        // Update the state of the order
        $updateSql = "UPDATE bon_commande SET Etat_commander = 5 WHERE id_BC = :idBC";
        $updateStmt = $connexion->prepare($updateSql);
        $updateStmt->bindParam(':idBC', $idBC);
        $updateStmt->execute();

        // Insert data into bon_commande_produit table
        $sql = "INSERT INTO bon_commande_produit (idbc, idP, dateadd, quantite, reste_a_livrer) VALUES (:idBC, :idP, :date, :quantite, :reste_a_livrer)";

        $stmt = $connexion->prepare($sql);

        foreach ($idP as $i => $id) {
            // Check if the product already exists in the table
            $checkSql = "SELECT COUNT(*) FROM bon_commande_produit WHERE idbc = :idBC AND idP = :idP";
            $checkStmt = $connexion->prepare($checkSql);
            $checkStmt->execute([':idBC' => $idBC, ':idP' => $id]);
            $exists = $checkStmt->fetchColumn();

            if ($exists > 0) {
                // Product already exists, skip insertion
                continue;
            }

            // Insert the product if it does not exist
            $stmt->execute([
                ':idBC' => $idBC,
                ':idP' => $id,
                ':date' => $date,
                ':quantite' => $quantite[$i],
                ':reste_a_livrer' => $quantite[$i]
            ]);
        }

        $_SESSION["sauvegarde"] = "Fait";
        header("Location: Liste_bon_cmd.php");
        exit; // Ensure the script stops executing after redirect
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}