<?php
session_start();
$idbon = $_GET["idBon"];
include("../../Categorie/Categorie/config/db.php");
if (isset($_SESSION["poursuit"]) && $_SESSION["poursuit"] == "fait") {

    echo "
    <script>
    alert('Sortie Valider')
</script>
    ";

    header("Location:Liste_bon_sortie.php");
    unset($_SESSION["poursuit"]);
} else {
    echo "";
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bon de Sortie</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-size: 18px;
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
            background-color: rgba(0, 0, 0, 0.6);
        }

        .modal-content {
            background-color: #fff;
            margin: 10% auto;
            padding: 30px;
            border-radius: 10px;
            max-width: 700px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
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
            color: #333;
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
            background-color: #f9f9f9;
            transition: border-color 0.3s;
        }

        select:focus,
        input[type="number"]:focus {
            border-color: #007bff;
            outline: none;
        }

        .buttons {
            margin-top: 20px;
            text-align: center;
        }

        .add-button,
        .submit-button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 12px 24px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .add-button:hover,
        .submit-button:hover {
            background-color: #218838;
        }

        .submit-button {
            background-color: #007bff;
        }

        .remove-button {
            background-color: #dc3545;
            color: #fff;
            font-size: 15px;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .remove-button:hover {
            background-color: #c82333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-select {
            margin-bottom: 10px;
        }

        .error-message {
            color: #dc3545;
            font-size: 14px;
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
                        <h2>Bon de Sortie:
                            <?php
                            $nomBC = $_GET['idBon'];
                            if (isset($nomBC)) {
                                echo $nomBC;
                            } ?>
                        </h2>
                        <?php
                        $count = 1;
                        $r = "SELECT * FROM bon_sortie_produit bsp 
                         join product p on p.idP = bsp.idP
                         join souscategorie sc on p.id_Sous_categorie = sc.idSC
                          WHERE idS = :id ";
                        $r1 = "SELECT * FROM product ";
                        $requette = $connexion->prepare($r);
                        $requette->bindParam(':id', $_GET["idBon"], PDO::PARAM_INT);
                        $requette->execute();

                        $requette2 = $connexion->prepare($r1);
                        $requette2->execute();
                        $reponse = $requette->fetchAll(PDO::FETCH_ASSOC);
                        $reponse2 = $requette2->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($reponse as $ventes) {
                            ?>
                            <div class="form-group">
                                <h4>Produit <?= $count ?> </h4>
                                <label for="product-name">Sous Categorie</label>
                                <select class="form-select" id="sous-categorie<?= $count ?>" name="idSC"
                                    aria-label="Sélectionnez une catégorie"
                                    onchange="filterProducts(<?= $count ?>,<?= $ventes['idP'] ?>)">
                                    <option value="" selected><?= $ventes['nom'] ?></option>
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
                                </select>
                                <label>Nom du produit:</label>
                                <select class="form-select" id="select<?= $ventes['idP'] ?>" name="idP[]" required>
                                    <?php
                                    echo "<option selected disabled id='produit" . $ventes["idP"] . "' value='" . $ventes["idP"] . "'>" . $ventes["nomproduit"] . "</option>";
                                    foreach ($reponse2 as $product) {
                                        echo "<option value='" . $product["idP"] . "'>" . $product["nomproduit"] . "</option>";
                                    }
                                    ?>
                                </select><br><br>
                                <span>Stock actuel du Produit : </span><b id="stock<?= $ventes['idP'] ?>"><?= $ventes["quantite"] ?></b><br>
                                <label for="quantity">Quantité:</label>
                                <input type="number" id="quantity<?= $ventes['idP'] ?>" name="quantite[]" min="1"
                                    value="<?= $ventes["quantite"] ?>" required>
                                <div class="error-message" id="errorMessage0"></div>
                            </div>
                            <div style="text-align:right;">
                                <button type="button" class="remove-button" id="supp<?= $ventes['idP'] ?>"
                                    onclick="Supprimer(<?= $ventes['idP'] ?>,<?= $_GET['idBon'] ?>,<?= $ventes['quantite'] ?>)">Supprimer</button>
                                <button type="button"
                                    onclick="Modifier(<?= $_GET['idBon'] ?>,<?= $ventes['idP'] ?>,<?= $ventes['quantite'] ?>)"
                                    class="submit-button" style="background-color:orange;">Modifier</button>
                            </div>
                            <br><br>
                            <?php $count++;
                        } ?>
                    </div>
                </div>
                <div class="buttons">
                    <button type="button" class="add-button" onclick="addProduct()" id="plus">Plus</button>
                </div>
                <br>
                <button type="submit" class="submit-button" name="sauvegarder" hidden="true">Sauvegarder</button>
            </form>
        </div>
    </div>
    <script>
         updating = 1
        const modal = document.getElementById("productModal");
        const closeButton = document.getElementsByClassName("close")[0];
        let productCount = 1;
        let compteur = 2;
        let quantite
        function retour() {
            alert("Commande Sauvegarder !!!")
            location.href = "Liste_bon_sortie.php"
        }

        function filterProducts(count, idP) {
            const selectSubCategory = document.getElementById(`sous-categorie${count}`);
            const selectedSubCatId = selectSubCategory.value;
            const productSelect = document.getElementById(`select${idP}`);
            let stock = document.getElementById("stock"+idP)
            // Afficher ou masquer les options en fonction de la sous-catégorie sélectionnée
            fetch('liste_produit.php?id_sous_categorie=' + selectedSubCatId)
                .then(response => response.json())
                .then(data => {
                    productSelect.innerHTML = '<option selected disabled>Sélectionnez un produit</option>';
                    // Remplir le select des produits avec les données récupérées
                    data.forEach(produit => {
                        var option = document.createElement('option');
                        option.value = produit.idP;
                        option.text = produit.nomproduit;
                        productSelect.append(option);
                        quantite = produit.Stock_actuel
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
                            //input.value = data.Stock_actuel
                            stock.innerText = data.Stock_actuel
                            // console.log(option)
                        })
                        .catch(error => console.error('Erreur lors de la récupération des produits :', error));
                })

            productSelect.value = ""; // Réinitialiser la sélection du produit
        }
       
        
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
            </select><br><br>
            <span>Stock actuel du Produit : </span><b id="stock${compteur}"></b>
            <br>
            <br>
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

        function checkQuantity(compteur,id) {
            const quantityInput = document.getElementById(`quantiteInput${compteur}`);
            const errorMessage = document.getElementById(`errorMessage${compteur}`);
            const btn_add = document.getElementById(`boutton_partielle${compteur}`);
            const selectedProductId = document.getElementById(`produit${id}`);
            const plus = document.getElementById(`plus`);
           
            // Récupérer la quantité disponible depuis l'attribut data
               // alert(quantite)
            //const selectedProductOption = document.querySelector(`#produit${compteur} option[value="${selectedProductId}"]`);
            //const availableQuantity = selectedProductOption ? parseInt(selectedProductOption.getAttribute('data-quantity')) : 0;
            if (quantityInput.value > document.getElementById("stock"+compteur).textContent || quantityInput.value == 0 || quantityInput.value < 0) {
                btn_add.hidden = true
                plus.hidden = true
                //alert("Je fonctionne")
                errorMessage.textContent = "Quantité saisie supérieure à la quantité disponible!";
                quantityInput.setCustomValidity("Quantité saisie supérieure à la quantité disponible!"); // Empêche la soumission du formulaire
            } else {
                plus.hidden = false
                btn_add.hidden = false
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
            let stock = document.getElementById("stock"+idP)
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
        function Supprimer(idP, idbc,quantite) {
            //alert("C'est supprimer qui vient d'etre appeler")
            productName = document.getElementById("produit" + idP).textContent
            if (confirm("Le produit <<" + productName + ">> est sur le point d'etre supprimer etes vous certains de continuer ?")) {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'Delete.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.send(`idP=${idP}&idbc=${idbc}&quantite=${quantite}`);
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        alert("Produit Supprimer de la Commande")
                        location.reload()
                    } else {
                        alert('Erreur lors de la suppression du produit');
                    }
                };
            } else {
                alert("Action Interrompue")
            }
        }
        function Modifier(idbs, exIdP, quantity) {
            ex = document.getElementById("select" + exIdP).textContent
            if (confirm("Etes vous sure d'effectuer des modification sur ce produit")) {
                // Get the product form data
                let quantite = document.getElementById("quantity" + exIdP).value
                let newIdP = document.getElementById("select" + exIdP).value
                //  alert("Bon : " + idbc + " exIP : " + exIdP + " exQuantite: " + quantity + "newIdP: " + newIdP + " newQuantite: " + quantite)
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'Update.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.send(`idP=${newIdP}&quantite=${quantite}&idbc=${idbs}&id=${exIdP}`);
                // Handle response
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        // alert("Modification Accomplie")
                    } else {
                        alert('Erreur lors de l\'enregistrement du produit');
                    }
                };
            } else {
                alert("Action Interrompue")
                document.getElementById("supp" + exIdP).inert = false
            }
        }

        function del(button, idbc) {
            // alert("C'est delete qui vient d'etre appeler")
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
                        removeProduct(button)
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
            }else {
                alert("Action Interrompue")
                delButton.inert = false
            }
        }
        function removeProduct(button) {
            productCount--;
            const productForm = button.parentElement.parentElement;
            productForm.remove();
        }
        function remove(button) {
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
            //alert(idbc)
            const productForm = button.parentElement.parentElement;
            const productId = productForm.querySelector('select[name="idP[]"]').value;
            const quantity = productForm.querySelector('input[name="quantite[]"]').value;
            if (productId == "" || quantity == "" || quantity == 0) {
                alert("Veuillez Renseigner de bonne information")
            } else {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'Partielle_add.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.send(`idP=${productId}&quantite=${quantity}&idbc=${idbc}`);
                xhr.onload = function () {
                    if (xhr.status === 200) {
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
 