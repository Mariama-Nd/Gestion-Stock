<?php
// Connect to database
include("../../Categorie/Categorie/config/db.php");
// Get product data from AJAX request
$matricule = $_POST['matricule'];
$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$service = $_POST['service'];
$date = date('Y-m-d H:i:s');
// Save product data to database
$r = "INSERT INTO  bon_sortie(user,structure,nom,prenom,date_creation) VALUES(:matricule,:structure,:nom,:prenom,:date_add)";
$requette = $connexion->prepare($r);
$requette->bindParam(':matricule', $matricule);
$requette->bindParam(':structure', $service);
$requette->bindParam(':nom', $nom);
$requette->bindParam(':prenom', $prenom);
$requette->bindParam(':date_add', $date);
$requette->execute();

// Return success response
if($requette) {
    echo json_encode(true);
} else {
    echo json_encode(false);
}