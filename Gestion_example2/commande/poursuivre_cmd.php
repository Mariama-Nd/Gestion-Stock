<?php
session_start();
$idbon = $_GET["idbon"];
include("../../Categorie/Categorie/config/db.php");
if (isset($_SESSION["poursuit"]) && $_SESSION["poursuit"] == "fait") {

    echo "
    <script>
    alert('Commande Valider')
</script>
    ";

    header("Location:Liste_bon_cmd.php");
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
            <form id="productForm" action="add_to_BC.php?idbc=<?= $_GET['idbon'] ?>" method="post">
                <div id="productsContainer">
                    <div class="product-form">
                        <h2>Bon de Commande :
                            <?php
                            $nomBC = $_GET['nomBC'];
                            if (isset($nomBC)) {
                                echo $nomBC;
                            } ?>
                        </h2>
                        <?php
                        $count = 1;
                        $r = "SELECT * FROM bon_commande_produit bcp 
                         join product p on p.idP = bcp.idP
                         join souscategorie sc on p.id_Sous_categorie = sc.idSC
                          WHERE idbc = :id ";
                        $r1 = "SELECT * FROM product ";
                        $requette = $connexion->prepare($r);
                        $requette->bindParam(':id', $_GET["idbon"], PDO::PARAM_INT);
                        //$requette->bindParam(":dateadd", $_SESSION["date"]);
                        $requette->execute();

                        $requette2 = $connexion->prepare($r1);
                        $requette2->execute();
                        //$nb = count($requette->fetchAll(PDO::FETCH_ASSOC));
                        $reponse = $requette->fetchAll(PDO::FETCH_ASSOC);
                        $reponse2 = $requette2->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($reponse as $ventes) {
                            ?>
                            <div>
                                <h4>Produit <?= $count ?> </h4>
                                <label for="product-name">Sous Categorie :</label>
                                <select class="form-select" id="sous-categorie<?= $count ?>" name="idSC"
                                    aria-label="Sélectionnez une catégorie"
                                    onchange="filterProducts(<?= $count ?>,<?= $ventes['idP'] ?>)">
                                    <option value="" selected><?= $ventes['nom'] ?></option>
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
                                <select class="form-select" id="select<?= $ventes['idP'] ?>" name="idP[]" required>
                                    <?php
                                    echo "<option selected disabled id='produit" . $ventes["idP"] . "'  value='" . $ventes["idP"] . "'>" . $ventes["nomproduit"] . "</option>";
                                    foreach ($reponse2 as $product) {
                                        echo "<option value='" . $product["idP"] . "'>" . $product["nomproduit"] . "</option>";
                                    }
                                    ?>
                                </select>

                                <br><br>
                                <label for="quantity">Quantité:</label>
                                <input type="number" id="quantity<?= $ventes['idP'] ?>" name="quantite[]" min="1"
                                    value="<?= $ventes["quantite"] ?>" required> <br><br>

                            </div>
                            <label for="">Info Product {idP:<?= $ventes['idP'] ?>,idBon:<?= $_GET["idbon"] ?>}</label>

                            <div style="float:right;">
                                <button type="button" class="submit-button" id="supp<?= $ventes['idP'] ?>"
                                    onclick="Supprimer(<?= $ventes['idP'] ?>,<?= $_GET['idbon'] ?>)"
                                    style="background-color:#FF0000;color:black;" name="supprimer">Supprimer</button>
                                <button type="button"
                                    onclick="Modifier(<?= $_GET['idbon'] ?>,<?= $ventes['idP'] ?>,<?= $ventes['quantite'] ?>)"
                                    class="submit-button" style="background-color:orange;color:black;"
                                    name="modifier">Modifier</button>
                            </div>

                            <br><br><br>
                            <?php $count++;
                        } ?>
                    </div>

                </div>
                <div class="buttons">
                    <button type="button" class="add-button" onclick="addProduct()">Plus</button>
                </div>
                <br><br>
                <button type="submit" class="submit-button" name="sauvegarder" hidden="true">Sauvegarder</button>
            </form>
        </div>
    </div>
    <script>
        let compteur = 2;
        let productCount = 1;

        function addProduct() {
            productCount++;
            compteur++;
            position = compteur
            if (productCount <= 10) {
                const productContainer = document.getElementById('productsContainer');
                const newProductForm = document.createElement('div');
                newProductForm.className = 'product-form';
                newProductForm.innerHTML = `<br><br>
            <h4>Produit ${productCount}</h4>
            <label for="product-name">Sous Categorie :</label>
            <select class="form-select" id="sousCategorie${compteur}" name="idSC" aria-label="Sélectionnez une catégorie" onchange="filtreur(sousCategorie${compteur},${compteur})">
                <option value="" selected>Sélectionnez une sous catégorie</option>
                <?php // Votre code PHP pour récupérer les sous-catégories
                
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
            </select><br><br>
            <label for="quantity">Quantité:</label>
            <input type="number" class="quantity" name="quantite[]" min="1" required>
            <br><br>
            <div class="boutton_partielle">
                <button type="button" class="btn btn-info" style="background:#00FF00; padding: 10px 20px;cursor: pointer;border-radius: 5px;border: none; float:right;" onclick="partielle_Add(this, <?= $_GET['idbon'] ?>)">Enregistrer</button>
            </div><br>
        `;
                productContainer.appendChild(newProductForm);
                const sousCategorieSelect = document.getElementById('sousCategorie' + compteur);
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

        function retour() {
            alert("Commande Sauvegarder !!!")
            location.href = "Liste_bon_cmd.php"
        }
        function filterProducts(count, idP) {
            const selectSubCategory = document.getElementById(`sous-categorie${count}`);
            const selectedSubCatId = selectSubCategory.value;
            const productSelect = document.getElementById(`select${idP}`);

            // Afficher ou masquer les options en fonction de la sous-catégorie sélectionnée
            fetch('../modal-05/liste_produit.php?id_sous_categorie=' + selectedSubCatId)
                .then(response => response.json())
                .then(data => {
                    productSelect.innerHTML = '<option selected disabled>Sélectionnez un produit</option>';
                    // Remplir le select des produits avec les données récupérées
                    data.forEach(produit => {
                        var option = document.createElement('option');
                        option.value = produit.idP;
                        option.text = produit.nomproduit;
                        productSelect.append(option);
                        // console.log(option)
                    });
                })
                .catch(error => console.error('Erreur lors de la récupération des produits :', error));
            productSelect.value = ""; // Réinitialiser la sélection du produit
        }
        updating = 1
        const modal = document.getElementById("productModal");
        const closeButton = document.getElementsByClassName("close")[0];


        function Supprimer(idP, idbc) {
            //alert("C'est supprimer qui vient d'etre appeler")
            productName = document.getElementById("produit" + idP).textContent
            if (confirm("Le produit <<" + productName + ">> est sur le point d'etre supprimer etes vous certains de continuer ?")) {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'Delete.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.send(`idP=${idP}&idbc=${idbc}`);
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        alert("Produit Supprimer de la Commande")
                        location.reload()
                    } else {
                        alert('Erreur lors de l\'enregistrement du produit');
                    }
                };
            } else {
                alert("Action Interrompue")
            }
        }
        function Modifier(idbc, exIdP, quantity) {
            ex = document.getElementById("select" + exIdP).textContent
            if (confirm("Etes vous sure d'effectuer des modification sur le produit <<>>")) {
                // Get the product form data
                let quantite = document.getElementById("quantity" + exIdP).value
                let newIdP = document.getElementById("select" + exIdP).value
                alert("Bon : " + idbc + " exIP : " + exIdP + " exQuantite: " + quantity + "newIdP: " + newIdP + " newQuantite: " + quantite)
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'Update.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.send(`idP=${newIdP}&quantite=${quantite}&idbc=${idbc}&id=${exIdP}`);
                // Handle response
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        //alert("Modification Accomplie")J'ai enlever l'alerte qui informe le monsieur que le job ai ete fait 
                    } else {
                        alert('Erreur lors de la modification du produit . Veuillez reessayer');
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
            //alert("Les Parametres: idbc : " + idbc + " idP : " + id + " quantite : " + quantite + " || Valeur Recuperer:idbc : " + idbc + " idP : " + productId + " quantite : " + quantity)
            if (confirm("Etes vous sure de vouloir effectuer des modification ?")) {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'Update.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.send(`idP=${productId}&quantite=${quantity}&idbc=${idbc}&id=${id}`);
                // Handle response
                xhr.onload = function () {
                    if (xhr.status === 200) {

                        alert("Modification Accomplie")
                        updating = 1
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
            location.href = "Liste_bon_cmd.php";
        }
        window.onclick = function (event) {
            if (event.target == modal) {
                modal.style.display = "none";
                location.href = "Liste_bon_cmd.php";
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