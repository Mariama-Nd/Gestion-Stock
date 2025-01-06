<?php
session_start();
try {
    include "../config/db.php";
    
    // Récupération des données du formulaire avec sécurisation
    $nom = htmlspecialchars($_POST["nom"]);
    $prenom = htmlspecialchars($_POST["prenom"]);
    $email = htmlspecialchars($_POST["email"]);
    $pass = password_hash($_POST["pass"], PASSWORD_DEFAULT);
    $telephone = htmlspecialchars($_POST["telephone"]);

    // Vérifier si l'email existe déjà
    $requette = "SELECT mail_admin FROM administrateur WHERE mail_admin = :email";
    $prepare = $connexion->prepare($requette);
    $prepare->execute([':email' => $email]);
    $reponse = $prepare->fetch(PDO::FETCH_ASSOC);

    if ($reponse) {
        $_SESSION["etat"] = "existant";
        header("Location: index.php");
        exit();
    }

    // Préparer et exécuter l'insertion
    $r = "INSERT INTO administrateur (nom_admin, prenom_admin, mail_admin, telephone_admin, pass_admin) 
          VALUES (:nom, :prenom, :email, :telephone, :pass)";
    $prepare = $connexion->prepare($r);
    $success = $prepare->execute([
        ':nom' => $nom,
        ':prenom' => $prenom,
        ':email' => $email,
        ':telephone' => $telephone,
        ':pass' => $pass
    ]);

    // Vérifier si l'insertion a réussi
    if ($success) {
        header("Location: Connection.php");
        exit();
    } else {
        echo '<script>alert("Erreur lors de l\'insertion.");</script>';
    }
} catch (PDOException $e) {
    // Affichage des erreurs PDO
    echo '<script>alert("Erreur: ' . $e->getMessage() . '");</script>';
}
