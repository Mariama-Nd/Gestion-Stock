<?php
session_start();
if (isset($_SESSION["reponse"]) && $_SESSION["reponse"] == "OK") {
    echo "<script>alert('Catégorie ajoutée avec succès');</script>";
    unset($_SESSION["reponse"]);
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Ajout de Catégorie</title>

    <style>
        /* Base styles */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 20px;
        }

        .page-wrapper {
            background: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
        }

        .card-heading {
            text-align: center;
            margin-bottom: 20px;
        }

        .card-heading h2 {
            font-size: 24px;
            color: #333;
        }

        .form-row {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 14px;
            color: #555;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .input--style-5 {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            color: #333;
            background: #f7f7f7;
            transition: all 0.3s ease;
        }

        .input--style-5:focus {
            background: #fff;
            border-color: #4caf50;
            outline: none;
            box-shadow: 0px 0px 5px rgba(76, 175, 80, 0.3);
        }

        .btn {
            display: inline-block;
            width: 100%;
            padding: 12px;
            background-color: #4caf50;
            color: #fff;
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }

        .btn:hover {
            background-color: #388e3c;
        }

        .btn-dark {
            text-decoration: none;
            display: inline-block;
            margin-bottom: 20px;
            color: #4caf50;
            border: 2px solid #4caf50;
            padding: 12px;
            text-align: center;
            border-radius: 8px;
            font-size: 14px;
            font-weight: bold;
            transition: all 0.3s ease-in-out;
        }

        .btn-dark:hover {
            background-color: #4caf50;
            color: #fff;
        }

        @media screen and (max-width: 768px) {
            .page-wrapper {
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="page-wrapper">
        <a href="../../accueil.php" class="btn-dark"><i class="bi bi-arrow-left"></i> Accueil</a>
        <div class="card-heading">
            <h2>Ajout de Catégorie</h2>
        </div>
        <form method="POST" action="Traitement.php">
            <div class="form-row">
                <label for="nomC">Nom de la Catégorie</label>
                <input class="input--style-5" type="text" name="nomC" id="nomC" placeholder="Entrez le nom de la catégorie" required>
            </div>
            <div class="form-row">
                <button class="btn" type="submit" name="add">Enregistrer</button>
            </div>
        </form>
    </div>
</body>

</html>
