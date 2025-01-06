<?php

session_start();

include '../../Categorie/Categorie/config/db.php';
$_SESSION["reponse"] = "";
$id_produit = htmlspecialchars($_POST['idproduit']);
$quantite = htmlspecialchars($_POST['product-quantity']);

$r = "insert into Bon_commande(id_produit,quantite) values('$id_produit',$quantite)";
$connexion->query($r);
if ($r) {
    $_SESSION["reponse"] = "OK";
    header("Location:bon_commande.php");
} else {
    $_SESSION["reponse"] = "nonOK";
    header("Location:bon_commande.php");
}