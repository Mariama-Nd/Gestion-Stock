<?php
session_start();
    include "config/db.php";
    $nomc = $_POST["nomC"];
    $r = "insert into categorie(nom_categorie) values('$nomc')";
    $connexion->exec($r);
    if($r){
        $_SESSION["reponse"] = "OK";
        header("Location:index.php");
    }

    ?>