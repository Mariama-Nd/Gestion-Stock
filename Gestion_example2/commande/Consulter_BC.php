<?php
session_start();
$idbon = $_GET["idbon"];
include("../../Categorie/Categorie/config/db.php");

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
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-size: 20px;
            color: #333;
        }

        .modal {
            display: block;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fff;
            margin: 10% auto;
            padding: 30px;
            border-radius: 12px;
            max-width: 700px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: #000;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        h4 {
            margin-top: 0;
            color: #555;
        }

        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
            color: #555;
        }

        select,
        input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
            transition: border-color 0.3s ease;
            box-sizing: border-box;
        }

        select:focus,
        input[type="number"]:focus {
            border-color: #008CBA;
            outline: none;
        }

        .buttons {
            margin-top: 20px;
            text-align: center;
        }

        .add-button,
        .submit-button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 12px 25px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            font-size: 16px;
            margin: 5px;
        }

        .add-button:hover,
        .submit-button:hover {
            background-color: #45a049;
        }

        .submit-button {
            background-color: #008CBA;
        }

        .submit-button:hover {
            background-color: #007bb5;
        }

        .remove-button {
            background-color: #ff4c4c;
            color: #fff;
            font-size: 15px;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .remove-button:hover {
            background-color: #ff1c1c;
        }
    </style>
</head>

<body>
    <div id="productModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <form id="productForm" action="add_to_BC.php?idbc=<?= $_GET['idbon'] ?>" method="post">
                <div id="productsContainer">
                    <div class="product-form">
                        <h2>Bon de Commande :
                            <?php
                            $nomBC = $_GET['nomBC'];
                            if (isset($nomBC)) {
                                echo htmlspecialchars($nomBC);
                            } ?>
                        </h2>

                        <?php
                        $count = 1;
                        $r = "SELECT * FROM bon_commande_produit bcp 
                         join product p on p.idP = bcp.idP
                          WHERE idbc = :id ";
                        $requette = $connexion->prepare($r);
                        $requette->bindParam(':id', $_GET["idbon"], PDO::PARAM_INT);
                        $requette->execute();
                        $reponse = $requette->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($reponse as $ventes) {
                        ?>
                        <div>
                            <h4>Produit <?= $count ?> </h4>
                            <label>Nom du produit:</label><br>
                            <select class="form-select" name="idP[]" required>
                                <?php
                                    echo "<option value='" . $ventes["idP"] . "'>" . htmlspecialchars($ventes["nomproduit"]) . "</option>";
                                ?>
                            </select><br><br>

                            <label for="quantity">Quantité:</label>
                            <input type="number" id="quantity" name="quantite[]" min="1" value="<?= $ventes["quantite"] ?>" required inert><br><br>
                        </div>
                        <?php $count++; } ?>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById("productModal");
        const closeButton = document.getElementsByClassName("close")[0];
        let productCount = 1;

        function addProduct() {
            productCount++;
            if (productCount <= 10) {
                const productContainer = document.getElementById('productsContainer');
                const newProductForm = document.createElement('div');
                newProductForm.className = 'product-form';
                newProductForm.innerHTML = `
            <h4>Produit ${productCount}</h4>
            <label>Nom du produit:</label>
            <select class="form-select" name="idP[]" required>
            <?php
            $r = "SELECT * FROM product order by nomproduit";
            $requette = $connexion->prepare($r);
            $requette->execute();
            $reponse = $requette->fetchAll(PDO::FETCH_ASSOC);
            foreach ($reponse as $ventes) {
                echo "<option value='" . $ventes["idP"] . "'>" . htmlspecialchars($ventes["nomproduit"]) . "</option>";
            }
            ?>
            </select>
            <label for="quantity">Quantité:</label>
            <input type="number" class="quantity" name="quantite[]" min="1" required>
            <button type="button" class="remove-button" onclick="removeProduct(this)">Supprimer</button>
        `;
                productContainer.appendChild(newProductForm);
            } else {
                alert("Nombre d'ajout maximum atteint");
            }
        }

        function removeProduct(button) {
            productCount--;
            const productForm = button.parentElement;
            productForm.remove();
        }

        document.addEventListener('DOMContentLoaded', function () {
            modal.style.display = "block";
        });

        closeButton.onclick = function () {
            modal.style.display = "none";
            location.href = "Liste_bon_cmd.php";
        }

        window.onclick = function (event) {
            if (event.target == modal) {
                modal.style.display = "none";
                location.href = "Liste_bon_cmd.php";
            }
        }
    </script>
</body>

</html>