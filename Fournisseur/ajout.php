<?php
session_start();
if (isset($_SESSION["reponse"]) && $_SESSION["reponse"] == "OK") {
  echo "<script>alert('Fournisseur ajouté avec succes')</script>";
  unset($_SESSION["reponse"]);
}
if (isset($_SESSION["reponse"]) && $_SESSION["reponse"] == "nonTel") {
    echo "<script>alert('Phone number not exist')</script>";
    unset($_SESSION["reponse"]);
  }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Formulaire Fournisseur</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .form-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        .form-container h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #555555;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #cccccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .form-group input:focus {
            border-color: #007bff;
            outline: none;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: green;
            border: none;
            border-radius: 4px;
            color: #ffffff;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="form-container">
    <h2>Formulaire Fournisseur</h2>
    <form method="post" action = "php/ajout_traitement.php">
        <div class="form-group">
            <label for="nom">Nom Fournisseur</label>
            <input type="text" id="nom" name="nom" required>
        </div>
        <div class="form-group">
            <label for="prenom">Prénom Fournisseur</label>
            <input type="text" id="prenom" name="prenom" required>
        </div>
        <div class="form-group">
            <label for="adresse">Adresse Fournisseur</label>
            <input type="text" id="adresse" name="adresse" required>
        </div>
        <div class="form-group">
            <label for="mail">Mail Fournisseur</label>
            <input type="email" id="mail" name="mail" required>
        </div>
        <div class="form-group">
            <label for="telephone">Téléphone Fournisseur</label>
            <input type="number" id="telephone" name="telephone" required>
        </div>
        <button type="submit">Enregistrer</button><br>
<a href="Liste.php" class="btn btn-success" style="margin-left:4%;margin-top:10px;"><i class="bi bi-arrow-left"></i> Retour</a>
    </form>
</div>

</body>
</html>