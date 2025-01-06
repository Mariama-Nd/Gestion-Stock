<?php
session_start();
$id1 = $_SESSION["id"];
if(isset($_SESSION["fait"]) && $_SESSION["fait"] == "Fait"){
   echo "
<script>
alert($id1)
</script>
   ";

unset($_SESSION["fait"]);
header("Location:Liste_bon_cmd.php?id=".$id1);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            max-width: 600px; /* Augmenté à 600px */
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
        button,a {
            background-color: #2e7d32;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }
        button:hover {
            background-color: #1b5e20;
        }
        .product-list {
            display: none;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <form id="bonForm" method="post" action="bon_commande_produit.php">
    <a href='Liste_bon_cmd.php' style="background-color: #28a745; color: white; ">Retour</a>
        <h1>Sélectionnez un Bon de Commande</h1>
        <label for="bonSelect">Bon de Commande :</label>
        <select id="bonSelect" name="bonSelect">
            <option value="">-- Sélectionnez --</option>
            <?php
             include '../../Categorie/Categorie/config/db.php';
            $_SESSION["id"] = $_GET["id"];
            $id = $_SESSION["id"] ;
            unset($_SESSION["id"]);
               
                $r = "SELECT * FROM Bon_commande 
                where id_BC = $id";
                $requette = $connexion->prepare($r);
                $requette->execute();
                $reponse = $requette->fetchAll(PDO::FETCH_ASSOC);
                foreach ($reponse as $ventes) {
                    ?>
                    <option value="<?= $ventes["id_BC"] ?>"><?= $ventes["idBC_gen"] ?></option>
                    <?php } ?>
        </select><br>
       

        <div id="productList" class="product-list">
            <h2>Produits</h2>
            <?php
                $r = "SELECT * FROM product ORDER BY nomproduit DESC";
                $requette = $connexion->prepare($r);
                $requette->execute();
                $reponse = $requette->fetchAll(PDO::FETCH_ASSOC);
                foreach ($reponse as $ventes) {
                    ?>
                    <div class="product-item">
                        <input type="checkbox" id="prod<?= $ventes["idP"] ?>" name="products[]" value="<?= $ventes["idP"] ?>">
                        <label for="prod<?= $ventes["idP"] ?>"><?= $ventes["nomproduit"] ?></label>
                        <input type="number" name="quantity[<?= $ventes["idP"] ?>]" min="0" placeholder="Quantité" disabled>
                    </div>
                    <?php } ?>
        </div>

        <button type="submit">Soumettre</button>
    </form>

    <script>
        const bonSelect = document.getElementById('bonSelect');
        const productList = document.getElementById('productList');
        const souscatSelect = document.getElementById('souscat');
        

        bonSelect.addEventListener('change', function() {
            if (bonSelect.value) {
                productList.style.display = 'block';
            } else {
                productList.style.display = 'none';
            }
        });
        /*souscatSelect.addEventListener('change', function() {
            alert(souscatSelect.value)
            
        });*/

        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const quantityInput = checkbox.parentElement.querySelector('input[type="number"]');
                quantityInput.disabled = !checkbox.checked;
            });
        });
    </script>
</body>
</html>