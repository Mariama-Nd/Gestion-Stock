<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

include "../../Categorie/Categorie/config/db.php";

if (!$connexion) {
    echo json_encode(['success' => false, 'message' => 'Erreur de connexion à la base de données.']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);

$idBL = $data['idBL'] ?? null;
$products = $data['products'] ?? [];
$quantities = $data['quantity'] ?? [];

if (!$idBL || empty($products) || empty($quantities)) {
    echo json_encode(['success' => false, 'message' => 'Données manquantes.']);
    exit();
}

$produitsIncoherents = [];
$erreur = false;

foreach ($products as $key => $idP) {
    $qte = $quantities[$key];

    $query = "SELECT idP, quantite_restante, nomproduit FROM product WHERE idP = :idP";
    $stmt = $connexion->prepare($query);
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Erreur de préparation de la requête produit.']);
        exit();
    }

    $stmt->bindParam(':idP', $idP);
    $stmt->execute();
    $result = $stmt->get_result();
    $produit = $result->fetch_assoc();

    if ($produit['quantite_restante'] < $qte) {
        $produitsIncoherents[] = [
            'idP' => $produit['idP'],
            'nomProduit' => $produit['nom_produit'],
            'quantiteSaisie' => $qte,
            'reste' => $produit['quantite_restante']
        ];
        $erreur = true;
    }
}

if ($erreur) {
    echo json_encode([
        'success' => false,
        'incoherent' => true,
        'produitsIncoherents' => $produitsIncoherents
    ]);
    exit();
}

foreach ($products as $key => $idP) {
    $qte = $quantities[$key];
    $insertQuery = "INSERT INTO bon_livraison_produit (idBL, idP, quantite) VALUES (:idBL, :idP, :quantite)";
    $stmt = $connexion->prepare($insertQuery);
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la préparation de l\'insertion du produit.']);
        exit();
    }

    $stmt->bindParam(':idBL', $idBL);
    $stmt->bindParam(':idP', $idP);
    $stmt->bindParam(':quantite', $qte);
    if (!$stmt->execute()) {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'insertion du produit.']);
        exit();
    }
    $updateQuery = "UPDATE produit SET quantite_restante = quantite_restante - :quantite WHERE idP = :idP";
    $stmt = $connexion->prepare($updateQuery);
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la préparation de la mise à jour du produit.']);
        exit();
    }

    $stmt->bindParam(':quantite', $qte);
    $stmt->bindParam(':idP', $idP);
    if (!$stmt->execute()) {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour du produit.']);
        exit();
    }
}

$updateBonLivraisonQuery = "UPDATE bon_livraison SET Etat_Livraison = 5 WHERE idBL = :idBL";
$stmt = $connexion->prepare($updateBonLivraisonQuery);
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Erreur lors de la préparation de la mise à jour du bon de livraison.']);
    exit();
}

$stmt->bindParam(':idBL', $idBL);
if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour de l\'état du bon de livraison.']);
    exit();
}

echo json_encode(['success' => true]);
?>