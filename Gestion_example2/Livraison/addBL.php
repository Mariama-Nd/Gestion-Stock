<?php
session_start();

if (isset($_POST["creer"])) {
    include "../../Categorie/Categorie/config/db.php";

    $idBC = $_POST["bc"];
    $date = date('Y-m-d H:i:s');
    $numBL = htmlSpecialChars($_POST["bordereau"]);
    $nomBL = htmlSpecialChars($_POST["nomBL"]);

    try {
        $r = "INSERT INTO Bon_livraison(id_bc, numBL, date, nomBL ) VALUES (:id_bc, :numBL, :date, :nomBL)";
        $stmt = $connexion->prepare($r);
        $stmt->bindParam(':id_bc', $idBC);
        $stmt->bindParam(':numBL', $numBL);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':nomBL', $nomBL);

        if ($stmt->execute()) {
            $_SESSION["creer"] = "Fait";
        } else {
            $_SESSION["creer"] = "pasFait";
        }

        header("Location: Liste_BL.php");
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>