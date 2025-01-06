<?php

include "../../Categorie/Categorie/config/db.php";
    $idP=$_GET['idP'];
    $idbc=$_GET['idbc'];

    $r = "SELECT SUM(quantite) as somme FROM bon_livraison_produit WHERE idP = :idP";
    $requette = $connexion->prepare($r);
    $requette->execute([':idP' => $idP]);
    $resultat = $requette->fetch(PDO::FETCH_ASSOC);
    $qte_bl = $resultat['somme'] ?? 0;

    $q = "SELECT quantite FROM bon_commande_produit WHERE idP = :idP AND idbc= :idbc";
    $rqt = $connexion->prepare($q);
    $rqt->execute([':idP' => $idP, ':idbc' => $idbc]);
    $rep = $rqt->fetch(PDO::FETCH_ASSOC);
    $qte_bc = $rep['quantite'] ?? 0;

    $reste = $qte_bc - $qte_bl;

   echo json_encode($reste);
?>
