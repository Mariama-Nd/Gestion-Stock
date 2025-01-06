<?php
session_start();
include "../../Categorie/Categorie/config/db.php";

try {
    // Gestion du formulaire d'enregistrement
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["enregistrerTout"])) {
        $idP = $_POST['products'];
        $quantite = $_POST['quantity'];
        $date = date("Y-m-d H:i:s");
        $idBL = $_SESSION["idBL"];
        $idBC = $_SESSION["idBC"];
        $nomBL = $_SESSION["nomBL"];
    
        // Validation des données d'entrée
        if (empty($idP) || !is_array($idP) || empty($quantite) || !is_array($quantite) || empty($idBL)) {
            throw new Exception("Données d'entrée invalides");
        }

        // Récupération des id déjà présents dans la table
        $r = "SELECT idP FROM bon_livraison_produit WHERE idBL = :idBL";
        $requette = $connexion->prepare($r);
        $requette->bindParam(':idBL', $idBL, PDO::PARAM_INT);
        $requette->execute();
        $produitsExistants = $requette->fetchAll(PDO::FETCH_COLUMN, 0);

        foreach ($idP as $i => $id) {
            if (!in_array($id, $produitsExistants)) {
                // Récupérer la quantité totale livrée pour un produit
                $r = "SELECT SUM(quantite) as somme1 FROM bon_livraison_produit WHERE idP = :idP";
                $requette = $connexion->prepare($r);
                $requette->execute([':idP' => $id]);
                $resultat = $requette->fetch(PDO::FETCH_ASSOC);
                $qte_bl = $resultat['somme1'] ?? 0;

                // Récupérer la quantité du produit dans le bon de commande
                $q = "SELECT SUM(quantite) as somme2 FROM bon_commande_produit WHERE idP = :idP";
                $rqt = $connexion->prepare($q);
                $rqt->execute([':idP' => $id]);
                $rep = $rqt->fetch(PDO::FETCH_ASSOC);
                $qte_bc = $rep['somme2'];

                //$reste = $qte_bc - $qte_bl;
                echo $reste.'  '.$quantite[$i];

                if ($quantite[$i] > $reste) {
                    $_SESSION["incoherent"]= "ok";
                    header("Location:approvisionner.php?idBL=$idBL&idBC=$idBC&nomBL=$nomBL");
                } else {
                    // Insertion des données dans la table bon_livraison_produit
                    $sql = "INSERT INTO bon_livraison_produit (idBL, idP, quantite, dateadd) VALUES (:idBL, :idP, :quantite, :dateadd)";
                    $stmt = $connexion->prepare($sql);
                    $stmt->execute([
                        ':idBL' => $idBL,
                        ':idP' => $id,
                        ':quantite' => $quantite[$i],
                        ':dateadd' => $date
                    ]);
                    if ($stmt) {
                        // Mise à jour de l'état de la commande
                        $r = "UPDATE bon_livraison SET Etat_Livraison = 5 WHERE idBL = :idBL";
                        $stmt = $connexion->prepare($r);
                        $stmt->execute([':idBL' => $idBL]);
                        if ($stmt) {
                            $_SESSION["enregistrer"] = "Fait";
                            header("Location:Liste_BL.php");
                            exit;
                        }
                    }
                }
            }
        }

       
    }
} catch (PDOException $e) {
    echo "Erreur PDO : " . $e->getMessage();
}
?>