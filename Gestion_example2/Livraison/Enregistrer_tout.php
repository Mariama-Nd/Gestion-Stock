<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "../../Categorie/Categorie/config/db.php";

header('Content-Type: application/json');

try {
    $input = json_decode(file_get_contents('php://input'), true);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($input['enregistrerTout'])) {
        $idP = $input['products'];
        $Prix = $input['prix'];
        $quantite = $input['quantity'];
        $date = date("Y-m-d H:i:s");
        $idBL = $input['idBL'] ?? $_SESSION["idBL"];
        $idBC = $input["idBC"];
        $nomBL = $input["nomBL"] ?? 'Nom par défaut';

        if (empty($idBL)) {
            throw new Exception("ID du bon de livraison manquant.");
        }

        if (empty($idP) || !is_array($idP) || empty($quantite) || !is_array($quantite) || empty($Prix) || !is_array($Prix)) {
            throw new Exception("Données d'entrée invalides");
        }

        $produitsIncoherents = [];

        foreach ($idP as $i => $id) {
            // Récupérer le nom du produit
            $queryNom = "SELECT nomproduit FROM product WHERE idP = :idP";
            $stmtNom = $connexion->prepare($queryNom);
            $stmtNom->execute([':idP' => $id]);
            $resultNom = $stmtNom->fetch(PDO::FETCH_ASSOC);
            $nomProduit = $resultNom['nomproduit'] ?? 'Nom inconnu';

            // Vérifier si le produit est déjà présent dans bon_livraison_produit
            $checkQuery = "SELECT quantite FROM bon_livraison_produit WHERE idP = :idP AND idBL = :idBL";
            $checkStmt = $connexion->prepare($checkQuery);
            $checkStmt->execute([':idP' => $id, ':idBL' => $idBL]);
            $checkResult = $checkStmt->fetch(PDO::FETCH_ASSOC);
            $qte_bl = $checkResult['quantite'] ?? 0;

            // Récupérer la quantité du produit dans le bon de commande
            $q = "SELECT quantite FROM bon_commande_produit WHERE idP = :idP AND idBC = :idBC";
            $rqt = $connexion->prepare($q);
            $rqt->execute([':idP' => $id, ':idBC' => $idBC]);
            $rep = $rqt->fetch(PDO::FETCH_ASSOC);
            $qte_bc = $rep['quantite'] ?? 0;

            $reste = $qte_bc - $qte_bl;

            if ($quantite[$i] > $reste) {
                $produitsIncoherents[] = [
                    'nomProduit' => $nomProduit,
                    'quantiteSaisie' => $quantite[$i],
                    'reste' => $reste
                ];
            } else {
                if ($qte_bl > 0) {
                    // Produit déjà présent, passer au suivant
                    continue;
                } else {
                    // Insérer le produit dans bon_livraison_produit
                    $sql = "INSERT INTO bon_livraison_produit (idBL, idP, quantite, prix_unitaire, dateadd) VALUES (:idBL, :idP, :quantite, :prix_unitaire, :dateadd)";
                    $stmt = $connexion->prepare($sql);
                    $stmt->execute([
                        ':idBL' => $idBL,
                        ':idP' => $id,
                        ':quantite' => $quantite[$i],
                        ':prix_unitaire' => $Prix[$i],
                        ':dateadd' => $date
                    ]);
                }
            }
        }

        if (!empty($produitsIncoherents)) {
            echo json_encode(['success' => false, 'message' => 'Incohérence dans les quantités.', 'produitsIncoherents' => $produitsIncoherents]);
            exit;
        }

        $r = "UPDATE bon_livraison SET Etat_Livraison = 5 WHERE idBL = :idBL";
        $stmt = $connexion->prepare($r);
        $stmt->execute([':idBL' => $idBL]);

        if ($stmt) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Échec de la mise à jour.']);
        }
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => "Erreur PDO : " . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => "Erreur : " . $e->getMessage()]);
}