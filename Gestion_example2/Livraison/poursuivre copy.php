<?php
session_start();
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $_SESSION["idBL"]=$_GET["idBL"];
    $idBC = $_GET["idBC"];
    $_SESSION["idBC"]=$idBC;
    $nomBL = $_GET["nomBL"];

if (!$_GET["idBL"] || !$idBC || !$nomBL) {
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
        <h1>Bon de Livraison</h1>
        <label for="bonSelect">Bon de Livraison: <?php echo '<b>'.$nomBL.'</b>'; ?></label>

        <div id="productList" class="product-list">
            <h2>Produits</h2>
            <?php
                $r = "SELECT DISTINCT blp.idBL, blp.idP, p.nomproduit, blp.quantite
                FROM bon_livraison_produit blp
                INNER JOIN product p ON blp.idP = p.idP
                WHERE blp.idBL = ".$_GET['idBL'];
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
                            <input type="checkbox" id="prod<?= $ventes["idP"] ?>" name="products[]" value="<?= $ventes["idP"] ?>">
                            <label for="prod<?= $ventes["idP"] ?>"><?= $ventes["nomproduit"] ?></label>
                            <input type="number" name="quantity[]" min="0" disabled value="<?= $ventes["quantite"] ?>">
                            <button id="modify" style="background-color:orange;">Modifieer</button>
                            <button id="delete" style="background-color:red;">Supprimer</button>
                        </div>
                <?php 
                unset($_SESSION["idBL"]);
                    }
                } 
                ?>

             <h2>Autres produits du BC</h2>

            <?php
                $r = "SELECT DISTINCT bl.idBL, bcp.idP, p.nomproduit
                FROM bon_commande_produit bcp
                INNER JOIN product p ON bcp.idP = p.idP
                INNER JOIN bon_livraison bl ON bcp.idbc = bl.id_bc
                WHERE bcp.idP not in(select idP from bon_livraison_produit where idBL=".$_GET['idBL'].") and bl.id_bc=bcp.idbc";
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
                            <input type="checkbox" id="prod<?= $ventes["idP"] ?>" name="products[]" value="<?= $ventes["idP"] ?>">
                            <label for="prod<?= $ventes["idP"] ?>"><?= $ventes["nomproduit"] ?></label>
                            <input type="number" name="quantity[]" min="0" disabled>
                            <button class="partielle-save" data-idp="<?= $ventes["idP"] ?>">Enregistrer</button>
                        </div>
                <?php 
                    }
                } 
            ?>

        </div>

        <button type="submit" class="btn btn-success" name="enregistrerTout">Enregistrer Tout</button>
    </form>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const quantityInput = checkbox.parentElement.querySelector('input[type="number"]');
                quantityInput.disabled = !checkbox.checked;
            });
        });

        var buttons = document.querySelectorAll('.partielle-save');
        buttons.forEach(button => {
            button.addEventListener('click', function() {
                var idP = button.getAttribute('data-idp');
                var idBL = '<?php echo $_GET["idBL"]; ?>';
                alert(idBL)
                var quantity = button.parentElement.querySelector('input[type="number"]').value;
                var idBC = '<?php echo $_GET["idBC"]; ?>';
                var nomBL = '<?php echo $_GET["nomBL"]; ?>';

                if (!quantity) {
                    alert('Veuillez renseigner la quantité.');
                    return;
                }

                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'Partielle_save.php', true);
                xhr.setRequestHeader('Content-Type', 'application/json');
                xhr.send(JSON.stringify({ idBL: idBL, idP: idP, quantity: quantity, idBC: idBC, nomBL: nomBL }));

                xhr.onload = function() {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            button.style.display = 'none';

                            var modifyButton = document.createElement('button');
                            modifyButton.textContent = 'Modifier';
                            modifyButton.className = 'modify';
                            modifyButton.style.backgroundColor = 'orange';
                            modifyButton.addEventListener('click', function() {
                                modifyProduct(idP, idBL, quantity);
                            });

                            var deleteButton = document.createElement('button');
                            deleteButton.textContent = 'Supprimer';
                            deleteButton.className = 'delete';
                            deleteButton.style.backgroundColor = 'red';
                            deleteButton.addEventListener('click', function() {
                                deleteProduct(idP, idBL);
                            });

                            button.parentElement.appendChild(modifyButton);
                            button.parentElement.appendChild(deleteButton);
                        } else{
                            alert("Quantité trop elever par rapport au reste à livrer: "+response.reste);
                        }
                        
                    }
                };


                // fonctionnalités Modifier et Supprimer pour produits qui ont deja ete enregistrés
                var modify = document.getElementById('modify');
                var delete = document.getElementById('delete');

                modify.addEventListener('click', function() {
                    modifyProduct(idP, idBL, quantity);
                });

                delete.addEventListener('click', function() {
                    deleteProduct(idP, idBL);
                });


            });

        });

        

        function modifyProduct(idP, idBL, quantity) {
            var newQuantity = prompt("Nouvelle quantité:", quantity);
            if (newQuantity !== null) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'modifier.php', true);
                xhr.setRequestHeader('Content-Type', 'application/json');
                xhr.send(JSON.stringify({ idBL: idBL, idP: idP, quantity: newQuantity }));

                xhr.onload = function() {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            alert("Modification réussie.");
                            location.href="poursuivre.php";
                        } else {
                            alert("Erreur : " + response.message);
                        }
                    }
                };
            }
        }

        function deleteProduct(idP, idBL) {
            if (confirm("Êtes-vous sûr de vouloir supprimer ce produit?")) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'supprimer.php', true);
                xhr.setRequestHeader('Content-Type', 'application/json');
                xhr.send(JSON.stringify({ idBL: idBL, idP: idP }));

                xhr.onload = function() {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            alert("Suppression réussie.");
                            // Mettre à jour la liste des produits
                            var productList = document.getElementById('productList');
                            var productItem = document.querySelector(`div.product-item input[type="checkbox"][value="${idP}"]`).parentElement;
                            productList.removeChild(productItem);
                            location.href="poursuivre.php";
                            //location.reload();
                        } else {
                            alert("Erreur : " + response.message);
                        }
                    }
                };
            }
       }
    });
</script>
</body>
</html>