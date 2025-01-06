<?php
session_start();

try {
    include "../../Categorie/Categorie/config/db.php";

    if (isset($_POST["poursuivre"])) {
        $idP = $_POST['idP'];
        $quantite = $_POST['quantite'];
        $date = date("Y-m-d H:i:s");
        $idbon = $_GET["idbon"];

        // Récupération des id déjà présents dans la table
        $r = "SELECT idP FROM bon_commande_produit WHERE idbc = :id";
        $requette = $connexion->prepare($r);
        $requette->bindParam(':id', $idbon, PDO::PARAM_INT);
        $requette->execute();
        $reponse = $requette->fetchAll(PDO::FETCH_COLUMN); // Récupérer seulement la colonne idP
        foreach ($idP as $i => $id) {
            if (!in_array($id, $reponse)) {
                // L'id n'est pas présent, on peut insérer
                $r = "INSERT INTO bon_commande_produit (idbc, idP, dateadd, quantite, reste_a_livrer) 
                      VALUES (:idBC, :idP, :date, :quantite, :reste_a_livrer)";
                $stmt = $connexion->prepare($r);
                $stmt->bindParam(':idBC', $idbon, PDO::PARAM_INT);
                $stmt->bindParam(':idP', $id, PDO::PARAM_INT);
                $stmt->bindParam(':date', $date, PDO::PARAM_STR);
                $stmt->bindParam(':quantite', $quantite[$i], PDO::PARAM_INT);
                $stmt->bindParam(':reste_a_livrer', $quantite[$i], PDO::PARAM_INT);
                $stmt->execute();
                // Mise à jour de l'état de la commande
                $r = "UPDATE bon_commande SET Etat_commander = 2 WHERE id_BC = :idBC";
                $stmt = $connexion->prepare($r);
                $stmt->bindParam(':idBC', $idbon, PDO::PARAM_INT);
                $stmt->execute();
                $_SESSION["poursuit"] = "fait";
            } else {
                $_SESSION["poursuit"] = "fait";
            }
        }

        header("Location:poursuivre_cmd.php");
        exit(); // Ajout d'un exit pour s'assurer que le script se termine
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}