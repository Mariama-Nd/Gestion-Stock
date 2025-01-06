<?php
session_start();
header('Content-Type: application/json');
include '../../Categorie/Categorie/config/db.php';
$data = json_decode(file_get_contents('php://input'), true);

// Vérification des données nécessaires
if (!isset($data['idBL'], $data['idP'], $data['quantity'], $data['idBC'], $data['prix'])) {
    echo json_encode(['success' => false, 'message' => 'Les données nécessaires ne sont pas fournies.']);
    exit;
}

$date = date("Y-m-d H:i:s");
$idBL = $data['idBL'];
$idBC = $data['idBC'];
$idP = $data['idP'];
$prix = $data['prix'];
$quantity = $data['quantity'];

// Validation de la quantité
if (!is_numeric($quantity) || $quantity <= 0) {
    echo json_encode(['success' => false, 'message' => 'Quantité invalide.']);
    exit;
}
// Connexion à la base de données
try {
    // Récupérer la quantité totale livrée pour un produit
    $r = "SELECT SUM(quantite) as somme1 FROM bon_livraison_produit WHERE idP = :idP AND idBL = :idBL";
    $requette = $connexion->prepare($r);
    $requette->execute([':idP' => $idP, ':idBL' => $idBL]);
    $resultat = $requette->fetch(PDO::FETCH_ASSOC);
    $qte_bl = $resultat['somme1'] ?? 0;

    // Récupérer la quantité du produit dans le bon de commande
    $q = "SELECT quantite FROM bon_commande_produit WHERE idP = :idP AND idBC = :idBC";
    $rqt = $connexion->prepare($q);
    $rqt->execute([':idP' => $idP, ':idBC' => $idBC]);
    $rep = $rqt->fetch(PDO::FETCH_ASSOC);
    $qte_bc = $rep['quantite'] ?? 0;

    $reste = $qte_bc - $qte_bl;

    // Vérification de la quantité saisie
    if ($quantity > $reste) {
        echo json_encode(['success' => false, 'message' => 'La quantité saisie dépasse la quantité restante à livrer ('.$reste.')']);
        exit;
    }
    // Vérification de l'existence de l'enregistrement
    $q = "SELECT * FROM bon_livraison_produit WHERE idP = :idP AND idBL = :idBL";
    $rqt = $connexion->prepare($q);
    $rqt->execute([':idP' => $idP, ':idBL' => $idBL]);
    $rep = $rqt->fetchAll(PDO::FETCH_ASSOC);

    // Insertion ou mise à jour de l'enregistrement
    if (count($rep) <= 0) {
        $stmt = $connexion->prepare("INSERT INTO bon_livraison_produit (idBL, idP, quantite, prix_unitaire, dateadd) VALUES (:idBL, :idP, :quantity, :prix, :dateadd)");
        $stmt->bindParam(':idBL', $idBL);
        $stmt->bindParam(':idP', $idP);
        $stmt->bindParam(':prix', $prix);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':dateadd', $date);
        $stmt->execute();
    } else {
        $stmt = $connexion->prepare("UPDATE bon_livraison_produit SET quantite = quantite + :quantity, prix_unitaire = :prix WHERE idP = :idP AND idBL = :idBL");
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':prix', $prix);
        $stmt->bindParam(':idP', $idP);
        $stmt->bindParam(':idBL', $idBL);
        $stmt->execute();
    }
    // Mettre à jour l'état du bon de livraison
    $stmt = $connexion->prepare("UPDATE bon_livraison SET Etat_Livraison = 5 WHERE idBL = :idBL");
    $stmt->bindParam(':idBL', $idBL);
    $stmt->execute();
    // Mettre à jour la quantité du produit dans la table product
    $stmt = $connexion->prepare("UPDATE product SET Stock_actuel = Stock_actuel + :quantity WHERE idP = :idP");
    $stmt->bindParam(':quantity', $quantity);
    $stmt->bindParam(':idP', $idP);
    $stmt->execute();

    echo json_encode(['success' => true, 'message' => 'Produit enregistré avec succès.']);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur serveur : ' . $e->getMessage()]);
}