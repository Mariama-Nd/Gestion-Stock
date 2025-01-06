<?php
session_start();
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $idBL = $_GET["idBL"];
    $_SESSION["idBL"]=$idBL;
    $idBC = $_GET["idBC"];
    $_SESSION["idBC"]=$idBC;
    $nomBL = $_GET["nomBL"];

if (!$idBL || !$idBC || !$nomBL) {
    die("Les informations requises ne sont pas fournies.");
}

   // echo '<script>alert('.$idBC.')</script>';

    include '../../Categorie/Categorie/config/db.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../../css/bootstrap.min.css" rel="stylesheet">

    <title>Bon de Commande</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8f5;
            color: #333;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        form {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 100%;
            max-width: 600px;
        }
        h1, h2 {
            color: #2e7d32;
        }
        label, select, input[type="number"] {
            display: block;
            width: 100%;
            margin-bottom: 10px;
        }
        select, input[type="number"] {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }
        .product-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .product-item label {
            margin-left: 10px;
            flex: 1;
        }
        button, a {
            background-color: #2e7d32;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 35%;
            text-align: center;
            text-decoration: none;
            margin:10px;
        }
        button:hover {
            background-color: #1b5e20;
        }
        .product-list {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <form id="bonForm" method="post" action="add_to_BL.php">
        <a href='Liste_BL.php'>Retour</a><br><br>
        <h1>Bon de Livraison: <?php echo '<b>'.$nomBL.'</b>'; ?></h1>
        
        <div id="productList" class="product-list">
            <h2>Produits</h2>
            <?php
                $r = "SELECT DISTINCT * FROM product p,bon_livraison bl,bon_livraison_produit blp WHERE blp.idBL = bl.idBL AND blp.idP = p.idP AND  bl.idBL=$idBL";
                $requette = $connexion->prepare($r);
                $requette->execute();
                $reponse = $requette->fetchAll(PDO::FETCH_ASSOC);

                if (empty($reponse)) {
                    echo "<p>Aucun produit trouvé.</p>";
                } else {
                    foreach ($reponse as $ventes) {
                        $_SESSION["idBL"]= $ventes['idBL'];
                        ?>
                        <div class="product-item">
                            <label for="prod<?= $ventes["idP"] ?>"><?= $ventes["nomproduit"] ?></label>
                            <input type="number" name="quantity[]" min="0" disabled value="<?= $ventes["quantite"] ?>">
                        </div>
                <?php 
                    }
                } 
                ?>
        </div>

    </form>

    <script>
    /*document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const quantityInput = checkbox.parentElement.querySelector('input[type="number"]');
                quantityInput.disabled = !checkbox.checked;
            });
        });
    });*/

</script>

</body>
</html>