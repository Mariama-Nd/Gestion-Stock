<?php
session_start();
$erruer = "";
if (isset($_POST["connection"])) {
    include '../../../Categorie/Categorie/config/db.php';
    $email = htmlspecialchars($_POST["email"]);
    $pass = $_POST["pass"];
    $requette = "select nom_admin,mail_admin,pass_admin from administrateur";
    $prepare = $connexion->prepare($requette);
    $prepare->execute();
    $reponse = $prepare->fetchAll(PDO::FETCH_ASSOC);
    foreach ($reponse as $seydou) {
        if ($email == $seydou["mail_admin"] && password_verify($pass, $seydou["pass_admin"])) {
            $_SESSION["nom_admin"] = $seydou["nom_admin"];
            header("Location:../../../accueil.php");
        }
    }
    $erruer = "Information Incorrecte";
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Connexion</title>
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
            margin-bottom: 25px;
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
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.2);
        }

        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .login-btn {
            display: block;
            width: 100%;
            padding: 15px;
            background-color: #29741f;
            color: #fff;
            border: 2px solid #29741f;
            border-radius: 8px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }

        .login-btn:hover {
            background-color: #fff;
			color: #29741f ;
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
        <h2>Connexion</h2>
        <?php if (!empty($erruer)) : ?>
            <div class="error-message"><?= $erruer ?></div>
        <?php endif; ?>
        <form method="post" action="">
            <div class="form-row">
                <label class="form-label" for="email">E-Mail</label>
                <input type="text" name="email" id="email" class="form-control" required>
            </div>
            <div class="form-row">
                <label class="form-label" for="pass">Mot de Passe</label>
                <input type="password" name="pass" id="pass" class="form-control" required>
                <input type="checkbox" id="voir_pass" onclick="show()">
                <label id="voir_pass-label" for="voir_pass">Afficher le mot de passe</label>
            </div>
            <div class="form-row">
                <input type="submit" name="connection" class="login-btn" value="Connexion">
            </div>
            <div class="form-row">
                <a href="index.php" class="secondary-btn">Créer un compte</a>
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
