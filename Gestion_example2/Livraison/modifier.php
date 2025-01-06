<?php
session_start();
include '../../Categorie/Categorie/config/db.php';

$data = json_decode(file_get_contents('php://input'), true);
$idBL = $data['idBL'];
$idP = $data['idP'];
$quantity = $data['quantity'];
$prix = $data['prix'];

try {

    //Cette requette vas nous permettre de recuperer la date d'ajout du produit auquel on veut faire des modifications
    $requette_date = "SELECT dateadd FROM bon_livraison_produit WHERE idP = :idP AND idBL = :idBL";
    $rqt = $connexion->prepare($requette_date);
    $rqt->execute([':idP' => $idP, ':idBL' => $idBL]);
    $rep = $rqt->fetch(PDO::FETCH_ASSOC);
    $date = $rep['dateadd'];
/////////////////////////Fin////////////////////////


    // Récupérer la quantité du produit dans le bon de commande
    $q = "SELECT SUM(quantite) as somme2 FROM bon_commande_produit WHERE idP = :idP";
    $rqt = $connexion->prepare($q);
    $rqt->execute([':idP' => $idP]);
    $rep = $rqt->fetch(PDO::FETCH_ASSOC);
    $qte_bc = $rep['somme2'];

    if ($quantity > $qte_bc) {
        echo json_encode(['success' => false, 'message' => 'La quantité saisie dépasse la quantité restante  à livrer( '. $reste.' )']);
        exit;
    }else{

    $sql = "UPDATE bon_livraison_produit SET quantite = :quantite , prix_unitaire = :prix WHERE idBL = :idBL AND idP = :idP And dateadd = :dateadd";
    $stmt = $connexion->prepare($sql);
    $stmt->execute([
        ':quantite' => $quantity,
        ':prix' => $prix,
        ':idBL' => $idBL,
        ':dateadd' => $date,
        ':idP' => $idP
    ]);
    echo json_encode(['success' => true]);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}