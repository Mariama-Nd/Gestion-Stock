<?php
session_start();
include "../../Categorie/Categorie/config/db.php";
if (isset($_GET["idBon"])) {
    $auto_gen = $_GET["idBon"];
    $r = "SELECT * FROM bon_sortie WHERE idBS = (SELECT idBS FROM bon_sortie ORDER BY idBS DESC LIMIT 1)";
    $requette = $connexion->prepare($r);
    $requette->execute();
    $reponse = $requette->fetch(PDO::FETCH_ASSOC);

    if ($reponse) {
        $name = $reponse["date_creation"];
        /* 
         $_SESSION["date"] = $reponse["date"];
         $_SESSION["idBC"] = $reponse["id_BC"];
      */

    } else {
        // Gérer le cas où aucun enregistrement n'est trouvé
        echo "No record found.";
    }
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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-size: 18px;
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
            margin: 5% auto;
            padding: 30px;
            border-radius: 12px;
            max-width: 600px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 24px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.3s ease;
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
            margin-top: 20px;
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

        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }

        .boutton_partielle {
            text-align: right;
            margin-top: 10px;
        }

        .btn-info {
            background-color: #00FF00;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .btn-info:hover {
            background-color: #32CD32;
        }
    </style>
</head>

<body>
    <div id="productModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <form id="productForm" action="add_to_BS.php?idBon=<?= $_GET["idBon"] ?>" method="post">
                <div id="productsContainer">
                    <div class="product-form">
                        <h2>Bon de Sortie :
                            <?php if (isset($name)) {
                                echo htmlspecialchars($name);
                            } ?>
                        </h2>
                        <h4>Produit 1</h4>
                        <label for="sous-categorie">Sous Categorie</label>
                        <select class="form-select" id="sous-categorie" name="idSC"
                            aria-label="Sélectionnez une catégorie">
                            <option value="" selected>Sélectionnez une sous catégorie</option>
                            <?php
                            $sql = "SELECT c.id_categorie, c.nom_categorie, sc.idSC, sc.nom 
                            FROM categorie c
                            JOIN sousCategorie sc ON c.id_categorie = sc.id_categorie
                            JOIN product p ON sc.idSC = p.id_Sous_categorie
                            WHERE p.Stock_actuel > 0
                            GROUP BY c.id_categorie, c.nom_categorie, sc.idSC, sc.nom
                            HAVING COUNT(p.idP) > 0
                            ORDER BY c.nom_categorie, sc.nom";
                            $stmt = $connexion->prepare($sql);
                            $stmt->execute();
                            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            $currentCategory = null;
                            foreach ($categories as $category) {
                                if ($currentCategory != $category['id_categorie']) {
                                    if ($currentCategory !== null) {
                                        echo "</optgroup>";
                                    }
                                    echo "<optgroup label='" . htmlspecialchars($category['nom_categorie']) . "'>";
                                    $currentCategory = $category['id_categorie'];
                                }
                                echo "<option value='" . htmlspecialchars($category['idSC']) . "'>" . htmlspecialchars($category['nom']) . "</option>";
                            }
                            if ($currentCategory !== null) {
                                echo "</optgroup>";
                            }
                            ?>
                        </select>

                        <label for="produit0">Nom du produit:</label>
                        <select class="form-select" id="produit0" name="idP[]" required>
                            <option value="" selected>Sélectionnez un produit</option>
                        </select>
                        
                        <div><br>
                            <span>Stock actuel du Produit : </span><b id="stock"></b>
                        </div>

                        <label for="quantiteInput0">Quantité:</label>
                        <input type="number" id="quantiteInput0" name="quantite[]" min="1" required
                            oninput="checkQuantity(0,0)">

                        <div class="error-message" id="errorMessage0"></div>

                        <div class="boutton_partielle" id="boutton_partielle0">
                            <button type="button" class="btn-info"
                                onclick="partielle_Add(this,<?= $_GET['idBon'] ?>)">Enregistrer</button>
                        </div>
                    </div>
                </div>

                <div class="buttons">
                    <button type="button" class="add-button" onclick="addProduct()" id="plus">Plus</button>
                </div>

                <button type="submit" class="submit-button" name="sauvegarder" id="sauvegarde">Sauvegarder</button>
            </form>
        </div>
    </div>


    <script>
        let quantite
        document.getElementById('sous-categorie').addEventListener('change', function () {
            // Récupérer la valeur de la sous-catégorie sélectionnée
            var idSousCategorie = this.value;
            let input = document.getElementById("quantiteInput0")
            var produitSelect = document.getElementById('produit0');
            let stock = document.getElementById("stock")
            fetch('liste_produit.php?id_sous_categorie=' + idSousCategorie)
                .then(response => response.json())
                .then(data => {
                    produitSelect.innerHTML = '<option selected disabled>Sélectionnez un produit</option>';
                    // Remplir le select des produits avec les données récupérées
                    data.forEach(produit => {
                        var option = document.createElement('option');
                        option.value = produit.idP;
                        option.text = produit.nomproduit;
                        produitSelect.append(option);
                        // console.log(option)
                    });
                })
                .catch(error => console.error('Erreur lors de la récupération des produits :', error));

            produitSelect.addEventListener('change', function () {
                idP = produitSelect.value
                fetch('stock_actuel.php?id_produit=' + idP)
                    .then(response => response.json())
                    .then(data => {
                        // Remplir le select des produits avec les données récupérées
                       // input.value = data.Stock_actuel
                        quantite = data.Stock_actuel
                        stock.innerText = data.Stock_actuel
                        // console.log(option)
                    })
                    .catch(error => console.error('Erreur lors de la récupération des produits :', error));
            })
            // Faire une requête AJAX pour récupérer les produits de la sous-catégorie sélectionnée
        });
        updating = 1
        const modal = document.getElementById("productModal");
        const closeButton = document.getElementsByClassName("close")[0];
        let productCount = 1;
        let compteur = 1;
        function addProduct() {
            productCount++;
            compteur++;
            if (productCount <= 10) {
                const productContainer = document.getElementById('productsContainer');
                const newProductForm = document.createElement('div');
                newProductForm.className = 'product-form';
                newProductForm.innerHTML = `<br><br>
            <h4>Produit ${productCount}</h4>
            <label for="product-name">Sous Categorie</label><br>
            <select class="form-select" id="sousCategorie${compteur}" name="idSC"
                aria-label="Sélectionnez une catégorie" onchange="filtreur(sousCategorie${compteur},${compteur})">
                <option value="" selected>Sélectionnez une sous catégorie</option>
                <?php
              $sql = "SELECT c.id_categorie, c.nom_categorie, sc.idSC, sc.nom 
              FROM categorie c
              JOIN sousCategorie sc ON c.id_categorie = sc.id_categorie
              JOIN product p ON sc.idSC = p.id_Sous_categorie
              WHERE p.Stock_actuel > 0
              GROUP BY c.id_categorie, c.nom_categorie, sc.idSC, sc.nom
              HAVING COUNT(p.idP) > 0
              ORDER BY c.nom_categorie, sc.nom";
                $stmt = $connexion->prepare($sql);
                $stmt->execute();
                $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $currentCategory = null;
                foreach ($categories as $category) {
                    if ($currentCategory != $category['id_categorie']) {
                        if ($currentCategory !== null) {
                            echo "</optgroup>";
                        }
                        echo "<optgroup label='" . $category['nom_categorie'] . "'>";
                        $currentCategory = $category['id_categorie'];
                    }
                    echo "<option value='" . $category['idSC'] . "'>" . $category['nom'] . "</option>";
                }
                if ($currentCategory !== null) {
                    echo "</optgroup>";
                }
                ?>
            </select><br><br>
            <label>Nom du produit:</label>
            <select class="form-select" name="idP[]" id="produit${compteur}" required onchange="checkQuantity(${compteur},${compteur})">
                <option value="" selected>Sélectionnez un produit</option>
                <?php
                $r = "SELECT * FROM product order by nomproduit";
                $requette = $connexion->prepare($r);
                $requette->execute();
                $reponse = $requette->fetchAll(PDO::FETCH_ASSOC);
                foreach ($reponse as $ventes) {
                    //echo "<option value='" . $ventes["idP"] . "' data-quantity='" . $ventes["quantite"] . "'>" . $ventes["nomproduit"] . "</option>"; 
                }
                ?>
            </select><br>
            <br>
            <span>Stock actuel du Produit : </span><b id="stock${compteur}"></b>
            <br><br>
            <label for="quantity">Quantité:</label>
            <input type="number" class="quantity" name="quantite[]" min="1" id="quantiteInput${compteur}" oninput="checkQuantity(${compteur},${compteur})" required>
            <br><br>
            <div class="error-message" id="errorMessage${compteur}"></div>
            <div class="boutton_partielle" id="boutton_partielle${compteur}">
                <button type="button" class="btn btn-info" style="background:#00FF00; padding: 10px 20px;cursor: pointer;border-radius: 5px;border: none; float:right;" onclick="partielle_Add(this,<?= $_GET['idBon'] ?>)">Enregistrer</button>
            </div><br>
        `;
                productContainer.appendChild(newProductForm);
            } else {
                alert("Nombre d'ajout maximum atteint");
            }
        }
        function checkQuantity(compteur, id) {
            const quantityInput = document.getElementById(`quantiteInput${compteur}`);
            const errorMessage = document.getElementById(`errorMessage${compteur}`);
            const btn_add = document.getElementById(`boutton_partielle${compteur}`);
            const selectedProductId = document.getElementById(`produit${id}`);
            const plus = document.getElementById(`plus`);
            const sauvegarde = document.getElementById(`sauvegarde`);
            sauvegarde.hidden = true
            // Récupérer la quantité disponible depuis l'attribut data
            //alert(quantite)
            //const selectedProductOption = document.querySelector(`#produit${compteur} option[value="${selectedProductId}"]`);
            //const availableQuantity = selectedProductOption ? parseInt(selectedProductOption.getAttribute('data-quantity')) : 0;
            if (quantityInput.value > quantite || quantityInput.value == 0 || quantityInput.value < 0) {
                btn_add.hidden = true
                plus.hidden = true
                //alert("Je fonctionne")
                errorMessage.textContent = "Quantité saisie supérieure à la quantité disponible!";
                quantityInput.setCustomValidity("Quantité saisie supérieure à la quantité disponible!"); // Empêche la soumission du formulaire
            } else {
                plus.hidden = false
                btn_add.hidden = false
                sauvegarde.hidden = false
                errorMessage.textContent = "";
                quantityInput.setCustomValidity(""); // Rétablit la validité du champ
            }
        }
        function filtreur(idS, idP) {
            var idSousCategorie = idS.value;
            console.log(idS)
            console.log(document.getElementById('produit' + idP))
            var produitSelect = document.getElementById('produit' + idP);
            let input = document.getElementById("quantiteInput" + idP)
            let stock = document.getElementById("stock" + idP)
            produitSelect.innerHTML = '<option selected disabled>Sélectionnez un produit</option>'; // Réinitialiser le select
            if (idSousCategorie) {
                // Faire une requête AJAX pour récupérer les produits de la sous-catégorie sélectionnée
                fetch('liste_produit.php?id_sous_categorie=' + idSousCategorie)
                    .then(response => response.json())
                    .then(data => {
                        // Remplir le select des produits avec les données récupérées
                        data.forEach(produit => {
                            var option = document.createElement('option');
                            option.value = produit.idP;
                            option.text = produit.nomproduit;
                            quantite = produit.Stock_actuel
                            produitSelect.append(option);
                        });
                    })
                    .catch(error => console.error('Erreur lors de la récupération des produits :', error));
                produitSelect.addEventListener('change', function () {
                    idP = produitSelect.value
                    fetch('stock_actuel.php?id_produit=' + idP)
                        .then(response => response.json())
                        .then(data => {
                            // Remplir le select des produits avec les données récupérées
                            //input.value = data.Stock_actuel
                            stock.innerText = data.Stock_actuel
                            // console.log(option)
                        })
                        .catch(error => console.error('Erreur lors de la récupération des produits :', error));
                })
            } else {
                console.log('Aucune sous-catégorie sélectionnée.');
            }
        };
        function del(button, idbc) {
            if (confirm("Etes vous sure de vouloir supprimer ce produit de la commande ??")) {
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
                        // if (productCount != 1) {
                        removeProduct(button)
                        //} else {
                        //   remove(button)
                        //}
                    } else {
                        alert('Erreur lors de l\'enregistrement du produit');
                    }
                };
            } else {
                alert("Action Interrompue")
            }
        }
        function update(button, idbc, id, delButton, quantite) {
            // Get the product form data

            const productForm = button.parentElement.parentElement;
            const productId = productForm.querySelector('select[name="idP[]"]').value;
            const quantity = productForm.querySelector('input[name="quantite[]"]').value;
            if (confirm("Etes vous sure de vouloir effectuer des modification ?")) {

                alert("Bon :" + idbc + " exIdP :" + id + " newIdP :" + productId + " exQuantite :" + quantite + " newQuantite :" + quantity)
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'Update.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.send(`idP=${productId}&quantite=${quantity}&idbc=${idbc}&id=${id}`);

                // Handle response
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        delButton.inert = false
                    } else {
                        alert('Erreur lors de l\'enregistrement du produit');
                    }
                };
            } else {
                alert("Action Interrompue")
                delButton.inert = false
            }
        }
        function removeProduct(button) {
            productCount--; // Décrémente le compteur de produits
            const productForm = button.closest('.product-form'); // Trouve le formulaire produit le plus proche
            if (productForm) {
                productForm.remove(); // Retire le formulaire du DOM
            } else {
                console.error("Produit non trouvé dans le DOM.");
            }
        }

        function remove(button) {
            productCount--; // Décrémente le compteur de produits
            const productForm = button.parentElement; // Accède à l'élément parent
            if (productForm) {
                productForm.remove(); // Retire le formulaire du DOM
            } else {
                console.error("Produit non trouvé dans le DOM.");
            }
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

        function partielle_Add(button, idbs) {
            // Get the product form data
            const productForm = button.parentElement.parentElement;
            const productId = productForm.querySelector('select[name="idP[]"]').value;
            const quantity = productForm.querySelector('input[name="quantite[]"]').value;
            // Send AJAX request to save product
            if (productId == "" || quantity == "" || quantity == 0) {
                alert("Veuillez Renseigner de bonne information")
            } else {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'Partielle_add.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.send(`idP=${productId}&quantite=${quantity}&idbc=${idbs}`);
                // Handle response
                //alert("Id bon sortie :"+idbs+" idP : "+productId+" quantity :"+quantity)
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        // Hide the "Enregistrer" button
                        button.hidden = true;
                        const updateButton = document.createElement('button');
                        updateButton.type = 'button';
                        updateButton.className = 'btn btn-info';
                        updateButton.style.background = 'orange';
                        updateButton.style.padding = '10px 20px';
                        updateButton.style.cursor = 'pointer';
                        updateButton.style.margin = '3px';
                        updateButton.style.borderRadius = '5px';
                        updateButton.style.border = 'none';
                        updateButton.style.float = 'right';
                        updateButton.onclick = function () {
                            // if (confirm("Voulez-vous effectuer des modification ?")) {
                            update(this, idbs, productId, delButton, quantity);
                            delButton.inert = true
                            // } else {
                            //    alert("Action Annuler")
                            //}
                        };
                        productForm.querySelector('select[name="idP[]"]').addEventListener('change', function () {
                            updateButton.inert = false
                        })
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
                        delButton.onclick = function () {
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
        }
    </script>
</body>

</html>