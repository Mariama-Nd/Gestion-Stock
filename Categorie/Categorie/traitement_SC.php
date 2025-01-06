<?php
session_start();

    if (isset($_POST["add"])) {
        
    include "config/db.php";
    $nom = $_POST["nom"];
    $categorie = $_POST["categorie"];
    
    $r = "insert into sousCategorie(nom,id_categorie) values('$nom',$categorie)";
    $connexion->exec($r);
    if($r){
        echo '<script> alert("insertion reussie")</script>';
        $_SESSION["reponse"]= "OK";
        header("Location:sous_categorie.php");
    }
    }
    ?>