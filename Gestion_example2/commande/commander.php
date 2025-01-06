<?php
session_start();
include "../../Categorie/Categorie/config/db.php";
if (isset($_GET["auto_gen"])) {
    $auto_gen = $_GET["auto_gen"];
    $name = $_GET["nomBC"];
    $r = "SELECT * FROM Bon_commande WHERE id_BC = (SELECT id_BC FROM Bon_commande ORDER BY id_BC DESC LIMIT 1)";
    $requette = $connexion->prepare($r);
    $requette->execute();
    $reponse = $requette->fetch(PDO::FETCH_ASSOC);

    if ($reponse) {
        $nomBC = $reponse["nomBC"];
        $_SESSION["date"] = $reponse["date"];
        $_SESSION["idBC"] = $reponse["id_BC"];
        $me = $_SESSION['idBC'];

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
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
        }

        .save-button {
            background-color: #4CAF50;
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
            <form id="productForm" action="add_to_BC.php" method="post">
                <div id="productsContainer">
                    <div class="product-form">
                        <h2>Bon de Commande :
                            <?php if (isset($name)) {
                                echo $name;
                            } ?>
                        </h2>
                        <h4>Produit 1</h4>
                        <label for="product-name">Sous Categorie</label><br>
                        <select class="form-select" id="sous-categorie" name="idSC"
                            aria-label="Sélectionnez une catégorie">
                            <option value="" selected>Sélectionnez une sous catégorie</option>
                            <?php
                            $sql = "SELECT c.id_categorie, c.nom_categorie, sc.idSC, sc.nom 
                        FROM categorie c
                        JOIN sousCategorie sc ON c.id_categorie = sc.id_categorie
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

                        <label>Nom du produit:</label><br>
                        <select class="form-select" id="produit" name="idP[]" required>
                            <option value="" selected>Sélectionnez un produit</option>
                        </select>

                        <br><br>
                        <label for="quantity">Quantité:</label>
                        <input type="number" id="quantity" name="quantite[]" min="1" required> <br><br>
                        <div class="boutton_partielle">
                            <button type="button" class="btn btn-info"
                                style="background:#00FF00;  padding: 10px 20px;cursor: pointer;margin:3px;border-radius: 5px;border: none; float:right;"
                                onclick="partielle_Add(this,<?= $_SESSION['idBC'] ?>)">Enregistrer</button>
                        </div><br><br>
                    </div>

                </div>

                <div class="buttons">
                    <button type="button" class="add-button" onclick="addProduct()">Plus</button>
                </div>
                <br><br>
                <button type="submit" class="submit-button" name="sauvegarder">Sauvegarder</button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('sous-categorie').addEventListener('change', function () {
            // Récupérer la valeur de la sous-catégorie sélectionnée
            var idSousCategorie = this.value;

            // Faire une requête AJAX pour récupérer les produits de la sous-catégorie sélectionnée
            fetch('../modal-05/liste_produit.php?id_sous_categorie=' + idSousCategorie)
                .then(response => response.json())
                .then(data => {
                    var produitSelect = document.getElementById('produit');
                    produitSelect.innerHTML = '<option selected disabled>Sélectionnez un produit</option>';

                    // Remplir le select des produits avec les données récupérées
                    data.forEach(produit => {
                        var option = document.createElement('option');
                        option.value = produit.idP;
                        option.text = produit.nomproduit;
                        produitSelect.add(option);
                    });
                })
                .catch(error => console.error('Erreur lors de la récupération des produits :', error));
        });
        id = []
        updating = 1
        const modal = document.getElementById("productModal");
        const closeButton = document.getElementsByClassName("close")[0];
        let productCount = 1;
        let compteur = 1;

        function addProduct() {
            productCount++;
            compteur ++ ;
            if (productCount <= 10) {
                const productContainer = document.getElementById('productsContainer');
                const newProductForm = document.createElement('div');
                newProductForm.className = 'product-form';
                newProductForm.innerHTML = `<br><br>
            <h4>Produit ${productCount}</h4>
            <label for="product-name">Sous Categorie</label><br>
            <select class="form-select" id="souscategorie${compteur}" name="idSC"
                aria-label="Sélectionnez une catégorie" onchange="filtreur(souscategorie${compteur},${compteur})">
                <option value="" selected>Sélectionnez une sous catégorie</option>
                <?php
                $sql = "SELECT c.id_categorie, c.nom_categorie, sc.idSC, sc.nom 
                    FROM categorie c
                    JOIN sousCategorie sc ON c.id_categorie = sc.id_categorie
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
            <select class="form-select" name="idP[]" id="produit${compteur}" required>
                <option value="" selected>Sélectionnez un produit</option>
                <?php
                $r = "SELECT * FROM product order by nomproduit";
                $requette = $connexion->prepare($r);
                $requette->execute();
                $reponse = $requette->fetchAll(PDO::FETCH_ASSOC);
                foreach ($reponse as $ventes) {
                    // echo "<option value='" . $ventes["idP"] . "'>" . $ventes["nomproduit"] . "</option>";
                }
                ?>
            </select><br><br>
            <label for="quantity">Quantité:</label>
            <input type="number" class="quantity" name="quantite[]" min="1" required>
            <br><br>
            <div class="boutton_partielle" hidden="true">
                <button type="button" id="update<?= $ventes['idP'] ?>" class="btn btn-info"
                    style="background:#964B00; padding: 10px 20px;cursor: pointer;margin:3px;border-radius: 5px;border: none; float:right;"
                    onclick="update(this,<?= $_SESSION['idBC'] ?>)">Modifier</button>
            </div>
            <div class="boutton_partielle">
                <button type="button" hidden="true" id="del<?= $ventes['idP'] ?>" class="btn btn-info"
                    style="background:#FF0000; padding: 10px 20px;margin:3px;cursor: pointer;border-radius: 5px;border: none; float:right;"
                    onclick="del(this,<?= $_SESSION['idBC'] ?>)">Supprimer</button>
            </div>
            <div class="boutton_partielle">
                <button type="button" class="btn btn-info" style="background:#00FF00; padding: 10px 20px;cursor: pointer;border-radius: 5px;border: none; float:right;" onclick="partielle_Add(this,<?= $_SESSION['idBC'] ?>)">Enregistrer</button>
            </div><br>
        `;
                productContainer.appendChild(newProductForm);

                // Attacher l'événement change à la nouvelle sous-catégorie
                
            } else {
                alert("Nombre d'ajout maximum atteint");
            }
        }



        function filtreur (idS,idP) {
                    var idSousCategorie = idS.value;
                    console.log(idS)
                    console.log(document.getElementById('produit'+idP))
                    var produitSelect = document.getElementById('produit'+idP);
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
                                    produitSelect.append(option);
                                });
                            })
                            .catch(error => console.error('Erreur lors de la récupération des produits :', error));
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
            //alert("Les Parametres: idbc : " + idbc + " idP : " + id + " quantite : " + quantite + " || Valeur Recuperer:idbc : " + idbc + " idP : " + productId + " quantite : " + quantity)
            if (confirm("Etes vous sure de vouloir effectuer des modification ?")) {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'Update.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.send(`idP=${productId}&quantite=${quantity}&idbc=${idbc}&id=${id}`);
                // Handle response
                xhr.onload = function () {
                    if (xhr.status === 200) {

                        //alert("Modification Accomplie")
                        delButton.inert = false
                    } else {
                        alert('Erreur lors de l\'enregistrement du produit');
                    }
                };
            }else {
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
            location.href = "Liste_bon_cmd.php";
        }

        window.onclick = function (event) {
            if (event.target == modal) {
                modal.style.display = "none";
                location.href = "Liste_bon_cmd.php";
            }
        }

        function partielle_Add(button, idbc) {
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
                xhr.send(`idP=${productId}&quantite=${quantity}&idbc=${idbc}`);
                // Handle response
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        productForm.querySelector('select[name="idP[]"]').inert = true
                        productForm.querySelector('input[name="quantite[]"]').inert = true
                        // Hide the "Enregistrer" button
                        button.hidden = true;
                        id.push(productId)
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
                            update(this, idbc, productId, delButton, quantity);
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