<?php
session_start();
if (isset($_SESSION["etat"]) && $_SESSION["etat"] == "existant") {
    echo "<script>
    alert('Ce compte existe déjà !! Veuillez vous reconnecter.')
</script>";
    unset($_SESSION["etat"]);
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Formulaire d'Inscription</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        body {
            background: #f5f5f5; /* Light gray background */
            font-family: 'Roboto', sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }

        .form-container {
            width: 500px;
            padding: 40px;
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 30px;
            font-weight: bold;
            color: #333;
        }

        .form-row {
            margin-bottom: 20px;
        }

        .form-label {
            font-size: 16px;
            color: #555;
            margin-bottom: 8px;
        }

        .form-control {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            font-size: 16px;
            background: #f9f9f9;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            border-color: #29741f;
            background: #ffffff;
            box-shadow: 0 0 5px rgba(41, 116, 31, 0.3);
        }

        .register-btn {
            display: block;
            width: 100%;
            padding: 15px;
            background-color: #29741f;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }

        .register-btn:hover {
            background-color: #1d5316;
        }

        .secondary-btn {
            display: block;
            width: 100%;
            padding: 15px;
            text-align: center;
            color: #29741f;
            background-color: transparent;
            border: 2px solid #29741f;
            border-radius: 8px;
            margin-top: 10px;
            font-size: 18px;
            font-weight: bold;
            transition: all 0.3s ease-in-out;
        }

        .secondary-btn:hover {
            background-color: #29741f;
            color: #fff;
        }

        #voir_pass-label {
            font-size: 14px;
            cursor: pointer;
            color: #555;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h2>Créer un compte</h2>
        <form method="post" action="Traitement_inscrit.php">
            <div class="form-row">
                <label class="form-label" for="full_name">Nom</label>
                <input type="text" name="nom" id="full_name" class="form-control" required>
            </div>
            <div class="form-row">
                <label class="form-label" for="prenom">Prénom</label>
                <input type="text" name="prenom" id="prenom" class="form-control" required>
            </div>
            <div class="form-row">
                <label class="form-label" for="email">E-Mail</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="form-row">
                <label class="form-label" for="telephone">Téléphone</label>
                <input type="number" name="telephone" id="telephone" class="form-control" required>
            </div>
            <div class="form-row">
                <label class="form-label" for="pass">Mot de Passe</label>
                <input type="password" name="pass" id="pass" class="form-control" required>
                <input type="checkbox" id="voir_pass" onclick="show()">
                <label id="voir_pass-label" for="voir_pass">Afficher le mot de passe</label>
            </div>
            <div class="form-row">
                <input type="submit" name="ajouter" class="register-btn" value="S'inscrire">
            </div>
            <div class="form-row">
                <a href="Connection.php" class="secondary-btn">Connexion</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function show() {
            let voir_pass = document.getElementById("voir_pass");
            let pass = document.getElementById("pass");
            if (voir_pass.checked && pass.type == "password") {
                pass.type = "text";
            } else {
                pass.type = "password";
            }
        }
    </script>
</body>

</html>
