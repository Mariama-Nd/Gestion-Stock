<?php
session_start();
$idbon = $_GET["idBon"];
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
            padding: 20px;
            border-radius: 8px;
            max-width: 600px;
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

        .product-form h4 {
            margin-top: 0;
        }

        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }

        select,
        input[type="number"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border-radius: 4px;
            border: 1px solid #ccc;

        }

        .buttons {
            margin-top: 15px;
            text-align: center;
        }

        .add-button,
        .submit-button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
        }

        .add-button:hover,
        .submit-button:hover {
            background-color: #45a049;
        }

        .submit-button {
            background-color: #008CBA;
        }

        .remove-button {
            background-color: red;
            color: #fff;
            font-size: 15px;
        }

        .submit-button:hover {
            background-color: #007bb5;
        }
    </style>
</head>

<body>
    <div id="productModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <form id="productForm" action="add_to_BC.php?idbc=<?= $_GET['idBon'] ?>" method="post">
                <div id="productsContainer">
                    <div class="product-form">
                        <h2>Bon de Commande :
                            <?php
                            $nomBC = $_GET['idBon'];
                            if (isset($nomBC)) {
                                echo $nomBC;
                            } ?>
                        </h2>
                        
                        <?php
                        $count = 1;
                         $r = "SELECT * FROM bon_sortie_produit bcp 
                         join product p on p.idP = bcp.idP
                          WHERE idS = :id ";
                         $requette = $connexion->prepare($r);
                         $requette->bindParam(':id', $_GET["idBon"], PDO::PARAM_INT);
                         $requette->execute();
                         //$nb = count($requette->fetchAll(PDO::FETCH_ASSOC));
                         $reponse = $requette->fetchAll(PDO::FETCH_ASSOC);
                         foreach ($reponse as $ventes) {
                        ?>
                        <div>
                            
                            <h4>Produit <?= $count ?> </h4>

                            <label>Nom du produit:</label>
                            <select class="form-select" name="idP[]" required>
                                <?php
                                    echo "<option value='" . $ventes["idP"] . "'>" . $ventes["nomproduit"] . "</option>";
                                ?>
                            </select>

                            <br><br>
                            <label for="quantity">Quantité:</label>
                            <input type="number" id="quantity" name="quantite[]" min="1" value="<?= $ventes["quantite"] ?>" inert required> <br><br>

                        </div>

                        <?php $count ++ ; } ?>
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
                echo "<option value='" . $ventes["idP"] . "'>" . $ventes["nomproduit"] . "</option>";
            }
            ?>
            </select><br><br>
            <label for="quantity">Quantité:</label>
            <input type="number" class="quantity" name="quantite[]" min="1" required>
            <br><br>
           
            <button type="button" class="remove-button" onclick="removeProduct(this)">Supprimer</button><br><br>

        `;
                productContainer.appendChild(newProductForm);
            } else {
                alert("Nombre d'ajout maximum atteint");
            }
        }
        function del(button, idbc) {
            if(confirm("Etes vous sure de vouloir supprimer ce produit de la commande ??")){
                const productForm = button.parentElement.parentElement;
            const productId = productForm.querySelector('select[name="idP[]"]').value;
            const quantity = productForm.querySelector('input[name="quantite[]"]').value;

            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'Delete.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send(`idP=${productId}&quantite=${quantity}&idbc=${idbc}`);

            xhr.onload = function () {
                if (xhr.status === 200) {
                    alert("Produit Supprimer de la Commande")
                    button.hidden = false
                } else {
                    alert('Erreur lors de l\'enregistrement du produit');
                }
            };
            }else{
                alert("Action Interrompue")
            }
        }
        function update(button, idbc) {
            // Get the product form data
            const productForm = button.parentElement.parentElement;
            const productId = productForm.querySelector('select[name="idP[]"]').value;
            const quantity = productForm.querySelector('input[name="quantite[]"]').value;

            // Send AJAX request to save product
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'update.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send(`idP=${productId}&quantite=${quantity}&idbc=${idbc}`);

            // Handle response
            xhr.onload = function () {
                if (xhr.status === 200) {
                    alert("Produit Modifier")
                } else {
                    alert('Erreur lors de l\'enregistrement du produit');
                }
            };
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
            location.href = "Liste_bon_sortie.php";
        }

        window.onclick = function (event) {
            if (event.target == modal) {
                modal.style.display = "none";
                location.href = "Liste_bon_sortie.php";
            }
        }

        function partielle_Add(button, idbc) {
    // Get the product form data
    const productForm = button.parentElement.parentElement;
    const productId = productForm.querySelector('select[name="idP[]"]').value;
    const quantity = productForm.querySelector('input[name="quantite[]"]').value;

    // Send AJAX request to save product
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'Partielle_add.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send(`idP=${productId}&quantite=${quantity}&idbc=${idbc}`);

    // Handle response
    xhr.onload = function() {
        if (xhr.status === 200) {
            // Hide the "Enregistrer" button
            button.hidden = true;

            // Show the "Modifier" and "Supprimer" buttons
            const updateButton = document.createElement('button');
            updateButton.type = 'button';
            updateButton.className = 'btn btn-info';
            updateButton.style.background = '#964B00';
            updateButton.style.padding = '10px 20px';
            updateButton.style.cursor = 'pointer';
            updateButton.style.margin = '3px';
            updateButton.style.borderRadius = '5px';
            updateButton.style.border = 'none';
            updateButton.style.float = 'right';
            updateButton.onclick = function() {
                update(this, idbc);
            };
            updateButton.textContent = 'Modifier';

            const delButton = document.createElement('button');
            delButton.type = 'button';
            delButton.className = 'btn btn-info';
            delButton.style.background = '#FF0000';
            delButton.style.padding = '10px 20px';
            delButton.style.cursor = 'pointer';
            delButton.style.margin = '3px';
            delButton.style.borderRadius = '5px';
            delButton.style.border = 'none';
            delButton.style.float = 'right';
            delButton.onclick = function() {
                del(this, idbc);
            };
            delButton.textContent = 'Supprimer';

            const buttonContainer = document.createElement('div');
            buttonContainer.className = 'boutton_partielle';
            buttonContainer.appendChild(updateButton);
            buttonContainer.appendChild(delButton);

            productForm.appendChild(buttonContainer);
        } else {
            alert('Erreur lors de l\'enregistrement du produit');
        }
    };
}
    </script>
</body>
</html>