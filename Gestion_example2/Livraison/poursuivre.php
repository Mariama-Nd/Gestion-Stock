<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$_SESSION["idBL"] = $_GET["idBL"];
$idBC = $_GET["idBC"];
$nomBL = $_GET["nomBL"];

if (!$_GET["idBL"] || !$idBC || !$nomBL) {
    die("Les informations requises ne sont pas fournies.");
}

include '../../Categorie/Categorie/config/db.php';

function reste($idP, $idBL, $idbc, $connexion)
{
    $r = "SELECT SUM(quantite) as somme1 FROM bon_livraison_produit WHERE idP = :idP AND idBL = :idbl";
    $requette = $connexion->prepare($r);
    $requette->execute([':idP' => $idP, ':idbl' => $idBL]);
    $resultat = $requette->fetch(PDO::FETCH_ASSOC);
    $qte_bl = $resultat['somme1'] ?? 0;

    $q = "SELECT quantite FROM bon_commande_produit WHERE idP = :idP AND idbc= :idbc";
    $rqt = $connexion->prepare($q);
    $rqt->execute([':idP' => $idP, ':idbc' => $idbc]);
    $rep = $rqt->fetch(PDO::FETCH_ASSOC);
    $qte_bc = $rep['quantite'] ?? 0;
    $reste = $qte_bc - $qte_bl;
    return $reste;
    ;

}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
    <title>Poursuivre Approvisionnement</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7f9;
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
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 100%;
            max-width: 1100px;
            box-sizing: border-box;
            transition: all 0.3s ease;
        }

        h1,
        h3 {
            color: #2e7d32;
            font-family: 'Helvetica Neue', sans-serif;
            margin-bottom: 20px;
        }

        label,
        select,
        input[type="number"] {
            display: block;
            width: 100%;
            margin-bottom: 15px;
        }

        select,
        input[type="number"] {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            box-sizing: border-box;
        }

        .product-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            /* Augmenter l'espace entre les éléments */
            padding: 15px;
            background-color: #f9fafb;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            box-sizing: border-box;
        }

        /* Réduire l'espace entre le label et l'input pour la quantité */
        .product-item label {
            margin-right: 0px;
            /* Réduire l'espace entre le label et les inputs */
            margin-left: 10px;
        }

        .product-item input[type="number"] {
            margin-right: 15px;
            /* Espace entre l'input et les boutons */
        }

        .product-item button {
            margin-left: 10px;
            /* Espace entre les boutons */
        }

        button,
        a {
            background-color: #2e7d32;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            text-align: center;
            text-decoration: none;
            margin: 10px 0;
            transition: background-color 0.3s ease;
        }

        button:hover,
        a:hover {
            background-color: #1b5e20;
        }

        .btn-secondary {
            background-color: #28a745;
            /* Vert */
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            text-align: center;
            text-decoration: none;
            margin: 10px 0;
            transition: background-color 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: #218838;
            /* Vert foncé */
        }

        button.modify {
            background-color: #ffa726;
        }

        button.modify:hover {
            background-color: #fb8c00;
        }

        button.delete {
            background-color: #e53935;
        }

        button.delete:hover {
            background-color: #d32f2f;
        }

        .product-list {
            margin-top: 30px;
        }

        @media (max-width: 768px) {
            form {
                padding: 20px;
                max-width: 100%;
            }

            .product-item {
                flex-direction: column;
                align-items: flex-start;
            }

            button,
            a {
                width: 100%;
                margin: 10px 0;
            }
        }
    </style>
</head>

<body>
    <form id="bonForm" method="post">
        <a href='Liste_BL.php' class="btn-secondary">Retour</a><br><br>
        <h1>Bon de Livraison: <?php echo '<b>' . $nomBL . '</b>'; ?></h1>
        <div id="productList" class="product-list">
            <h3>Produits Enregistrés</h3>
            <?php

            $r = "SELECT blp.quantite,blp.idBL,blp.idP,bcp.idbc,p.nomproduit,blp.prix_unitaire
                FROM bon_livraison_produit blp
                INNER JOIN product p ON blp.idP = p.idP
                INNER JOIN bon_commande_produit bcp ON bcp.idP = p.idP
                WHERE blp.idBL = :idBL
                ";

            $requette = $connexion->prepare($r);
            $requette->bindParam(':idBL', $_GET['idBL'], PDO::PARAM_INT);
            $requette->execute();
            $reponse = $requette->fetchAll(PDO::FETCH_ASSOC);
            foreach ($reponse as $ventes) {
                $reste = reste($ventes['idP'], $_GET['idBL'], $ventes["idbc"], $connexion);
                if ($reste == 0) {
                    ?>
                    <span id="idPorduit<?= $ventes["idP"] ?>" hidden="true"><?= $ventes["idP"] ?></span>
                    <span id="idBLproduit<?= $ventes["idP"] ?>" hidden="true"><?= $_GET['idBL'] ?></span>
                    <span id="quantite_product<?= $ventes["idP"] ?>" hidden="true"><?= $ventes["quantite"] ?></span>
                    <span id="prix_product<?= $ventes["idP"] ?>" hidden="true"><?= $ventes["prix_unitaire"] ?></span>
                    <div class="product-item" data-idp="<?= $ventes['idP'] ?>">
                        <label for="prod<?= $ventes["idP"] ?>"><b><?= $ventes["nomproduit"] ?> <br>
                                (Reste:<?= reste($ventes['idP'], $_GET['idBL'], $ventes["idbc"], $connexion) ?>)</b></label><br>
                        <input type="number" name="quantity[]" min="0" id="quantity<?= $ventes['idP'] ?>"
                            value="<?= $ventes["quantite"] ?>">
                        <label for="prod<?= $ventes["idP"] ?>"><b>Prix Unitaire (CFA)</b></label>
                        <input type="number" name="prix[]" min="0" id="prix<?= $ventes['idP'] ?>"
                            value="<?= $ventes["prix_unitaire"] ?>">
                        <button type="button" class="modify">Modifier</button>
                        <button type="button" class="delete">Supprimer</button>
                    </div>
                    <?php
                }
            }
            ?>
            <h3>Autres produits du Bon de Commande</h3>
            <?php
            $r = "SELECT DISTINCT *  
            FROM bon_commande bc
            INNER JOIN bon_commande_produit bcp ON bcp.idbc = bc.id_BC
            INNER JOIN product p ON bcp.idP = p.idP
            INNER JOIN bon_livraison bl ON bl.id_bc = bc.id_BC
            WHERE bl.idBL = " . $_GET['idBL'];
            $requette = $connexion->prepare($r);
            $requette->execute();
            $reponse = $requette->fetchAll(PDO::FETCH_ASSOC);
            foreach ($reponse as $ventes) {
                $reste = reste($ventes['idP'], $_GET['idBL'], $ventes["idbc"], $connexion);
                if ($reste != 0) {
                    ?>
                    <div class="product-item" data-idp="<?= $ventes['idP'] ?>">
                        <input type="checkbox" id="prod<?= $ventes["idP"] ?>" name="products[]" value="<?= $ventes["idP"] ?>">
                        <label for="prod<?= $ventes["idP"] ?>" class="product-name"><b><?= $ventes["nomproduit"] ?> <br> (Reste:
                                <?= reste($ventes['idP'], $_GET['idBL'], $ventes["idbc"], $connexion) ?>)</b></label><br>
                        <input type="number" name="quantity[]" min="0" disabled id="<?= $ventes['idP'] ?>"
                            placeholder="Quantite Recus">
                        <label for="prod<?= $ventes["idP"] ?>"><b>Prix Unitaire (CFA)</b></label>
                        <input type="number" name="prix[]" class="product-price" min="0" id="prix<?= $ventes['idP'] ?>" disabled
                            placeholder="Prix Produit">
                        <button type="button" class="partielle-save" data-idp="<?= $ventes["idP"] ?>">Enregistrer</button>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
        <button type="button" class="btn btn-success" id="enregistrerTout">Enregistrer Tout</button>
    </form>
    <script>
        // Activer/désactiver le champ de quantité lorsque la case est cochée/décochée
        document.addEventListener('DOMContentLoaded', function () {
            // Activation/désactivation des champs de quantité et prix
            document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    // Trouver le conteneur parent de l'élément checkbox
                    const productItem = checkbox.closest('.product-item');
                    // Sélectionner les champs de quantité et de prix dans ce conteneur
                    const quantityInput = productItem.querySelector('input[name="quantity[]"]');
                    const priceInput = productItem.querySelector('input[name="prix[]"]');
                    // Activer ou désactiver les champs en fonction de l'état de la case à cocher
                    const isChecked = checkbox.checked;
                    quantityInput.disabled = !isChecked;
                    priceInput.disabled = !isChecked;
                });
            });
        });

        function get_reste(params1, param2) {
            let input_check = document.getElementById('prod' + params1)
            let input_qte = document.getElementById(params1)
            if (input_check.checked) {

                xhr = new XMLHttpRequest()
                xhr.open('GET', 'get_reste.php?idP=' + params1 + '&idbc=' + param2, true)
                xhr.setRequestHeader('Content-Type', 'application/json');
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        let data = JSON.parse(xhr.responseText)
                        input_qte.value = data
                    } else {
                        alert('Erreur')
                    }
                }

                xhr.send()

                xhr.onerror = function () {
                    alert("Erreur de connexion au serveur.");

                };

            } else {
                input_qte.value = ''
            }
        }
        document.addEventListener('DOMContentLoaded', function () {
            // Décocher toutes les cases à cocher et désactiver les champs de quantité au chargement
            document.querySelectorAll('input[name="products[]"]').forEach(checkbox => {
                checkbox.checked = false;
                const quantityInput = checkbox.parentElement.querySelector('input[type="number"]');
                quantityInput.disabled = true;
                quantityInput.value = '';
            });



            // Fonction pour envoyer les données à enregistrer_tout.php via AJAX
            document.getElementById('enregistrerTout').addEventListener('click', function () {
                const selectedProducts = [];
                const quantities = [];
                const price = [];
                const invalidProducts = [];
                const invalidProducts_price = [];

                document.querySelectorAll('input[name="products[]"]:checked').forEach(checkbox => {
                    const productId = checkbox.value;
                    const quantityInput = checkbox.parentElement.querySelector('input[type="number"]');
                    const quantity = quantityInput.value.trim();
                    const productName = checkbox.parentElement.querySelector('.product-name').textContent;
                    const prix = checkbox.parentElement.querySelector('.product-price').value;

                    if (!quantity || isNaN(quantity) || parseFloat(quantity) <= 0 ) {
                        invalidProducts.push(productName);
                    } else {
                        selectedProducts.push(productId);
                        quantities.push(quantity);
                        price.push(prix);
                    }

                    if (!prix || isNaN(prix) || parseFloat(prix) <= 0) {
                        invalidProducts_price.push(productName);
                    }
                });


                if (invalidProducts.length > 0) {
                    alert("Veuillez entrer une Quantité valide pour les produits suivants :\n" + invalidProducts.join(', '));
                    return;
                }

                if (invalidProducts_price.length > 0) {
                    alert("Veuillez entrer un Prix valide pour les produits suivants :\n" + invalidProducts_price.join(', '));
                    return;
                }

                if (selectedProducts.length === 0) {
                    alert("Aucun produit sélectionné.");
                    return;
                }

                const data = {
                    products: selectedProducts,
                    quantity: quantities,
                    prix: price,
                    nomBL: '<?php echo $_GET["nomBL"]; ?>',
                    idBC: <?php echo $_GET["idBC"]; ?>,
                    idBL: <?php echo $_GET["idBL"]; ?>,
                    enregistrerTout: true
                };

                console.log("Données envoyées : ", JSON.stringify(data)); // Log des données avant l'envoi

                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'Enregistrer_tout.php', true);
                xhr.setRequestHeader('Content-Type', 'application/json');
                xhr.onload = function () {
                    console.log("Réponse du serveur : ", xhr.responseText); // Voir la réponse brute
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            alert("Enregistrement réussi.");
                            window.location.href = "Liste_BL.php";
                        } else {
                            if (response.produitsIncoherents && response.produitsIncoherents.length > 0) {
                                let message = "Incohérence de quantités pour les produits suivants :\n";
                                response.produitsIncoherents.forEach(prod => {
                                    message += `${prod.nomProduit} (Quantité saisie: ${prod.quantiteSaisie}, Reste à livrer: ${prod.reste})\n`;
                                });
                                alert(message);
                            } else {
                                alert("Erreur : " + (response.message || "Échec de l'enregistrement."));
                            }
                        }
                    } catch (e) {
                        alert("Erreur : Réponse non valide du serveur.");
                        console.error("Erreur de parsing JSON : ", e);
                    }
                };
                xhr.onerror = function () {
                    console.error("Erreur de connexion au serveur.");
                    alert("Erreur de connexion au serveur.");
                };
                xhr.send(JSON.stringify(data));
            });

            // Fonction pour enregistrer un produit partiellement
            function saveProduct(idP) {
                const productItem = document.querySelector(`.product-item[data-idp="${idP}"]`);
                if (!productItem) {
                    console.error("Produit non trouvé pour l'ID: " + idP);
                    return;
                }

                const checkbox = productItem.querySelector('input[type="checkbox"]');
                const quantityInput = productItem.querySelector('input[type="number"]');
                const prix = document.getElementById("prix" + idP).value.trim();

                if (!checkbox.checked) {
                    alert("Veuillez sélectionner le produit avant d'entrer une quantité.");
                    return;
                }


                const quantity = quantityInput.value.trim();
                if (quantityInput.disabled || !quantity || isNaN(quantity) || parseFloat(quantity) <= 0) {
                    alert("Veuillez entrer une quantité valide.");
                    return;
                }

                if (prix.trim() <= 0) {
                    alert("Veuillez entrer un Prix valide.");
                    return;
                }

                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'Partielle_save.php', true);
                xhr.setRequestHeader('Content-Type', 'application/json');
                xhr.onload = function () {
                    console.log(xhr.responseText); // Ajoute ceci pour voir la réponse brute
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            alert("Enregistremnet reussi")
                            location.reload();
                        } else {
                            alert("Erreur : " + (response.message || "Échec de l'enregistrement."));
                        }
                    } catch (e) {
                        alert("Erreur : Réponse non valide du serveur.");
                    }
                };
                xhr.onerror = function () {
                    alert("Erreur de connexion au serveur.");
                };

                xhr.send(JSON.stringify({
                    idBL: '<?php echo $_GET["idBL"]; ?>',
                    idBC: '<?php echo $_GET["idBC"]; ?>',
                    idP: idP,
                    prix: prix,
                    quantity: parseFloat(quantity)
                }));
            }

            // Ajouter les événements pour enregistrer partiellement un produit
            document.querySelectorAll('.partielle-save').forEach(button => {
                button.addEventListener('click', function () {
                    const idP = button.getAttribute('data-idp');
                    saveProduct(idP);
                });
            });


            // Fonction pour modifier un produit
            function modifyProduct(idP, idbl, quantity, prix) {
                const productItem = document.querySelector(`.product-item[data-idp="${idP}"]`);
                const quantityInput = productItem.querySelector('input[type="number"]');
                const currentQuantity = quantityInput.value;
                const current_price = document.getElementById("prix" + idP).value
                //alert(`Les donnes apres idP : ${idP} , idbl : ${idbl} , quantite : ${currentQuantity} , prix : ${current_price}`)

                // Demande à l'utilisateur de saisir une nouvelle quantité

                // Vérification des différentes conditions sur la saisie
                if (currentQuantity === null || current_price == null) {
                    // L'utilisateur a annulé l'opération, on ne fait rien
                    return;
                } else if (currentQuantity.trim() === "" || current_price.trim() == '') {
                    // Si l'entrée est vide
                    alert("L'entrée est vide. Veuillez entrer une valeur.");
                } else if (isNaN(currentQuantity) || parseFloat(currentQuantity) < 0 || isNaN(current_price) || parseFloat(current_price) < 0) {
                    // Si l'entrée n'est pas un nombre valide ou si elle est inférieure ou égale à 0
                    alert("Veuillez entrer un nombre valide supérieur à 0.");
                } else {
                    // Si la saisie est correcte, exécuter la requête AJAX pour modifier
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', 'modifier.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/json');

                    xhr.onload = function () {
                        const response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            alert("Modification réussie.");
                            quantityInput.value = currentQuantity;  // Met à jour la quantité dans l'interface
                            location.reload()
                        } else {
                            alert("Erreur : " + response.message);
                        }
                    };

                    xhr.onerror = function () {
                        alert("Erreur de connexion avec le serveur.");
                    };

                    // Envoie la nouvelle quantité avec l'ID du produit
                    xhr.send(JSON.stringify({
                        idBL: '<?php echo $_GET["idBL"]; ?>',
                        idP: idP,
                        quantity: currentQuantity,
                        prix: current_price
                    }));
                }
            }

            // Fonction pour empêcher la soumission du formulaire par défaut
            document.getElementById('bonForm').addEventListener('submit', function (event) {
                event.preventDefault(); // Empêche la soumission du formulaire
            });



            function deleteProduct(idP, quantity) {
                if (confirm("Êtes-vous sûr de vouloir supprimer ce produit?")) {
                    const xhr = new XMLHttpRequest();
                    xhr.open(
                        "GET",
                        "supprimer.php?idBL=<?php echo $_GET['idBL']; ?>&idP=" +
                        idP +
                        "&quantity=" +
                        quantity,
                        true
                    );
                    xhr.setRequestHeader("Content-Type", "application/json");
                    xhr.onload = function () {
                        if (xhr.status >= 200 && xhr.status < 300) {
                            const response = JSON.parse(xhr.responseText);
                            if (response.success) {
                                alert("Suppression réussie.");
                                const productItem = document.querySelector(
                                    `.product-item[data-idp="${idP}"]`
                                );
                                productItem.parentElement.removeChild(productItem);
                                location.reload();
                            } else {
                                alert("Erreur : " + response.message);
                            }
                        } else {
                            alert("Une erreur est survenue lors de la requête.");
                        }
                    };
                    xhr.send();
                }
            }
            // Ajouter des événements à chaque bouton de modification et suppression existant
            document.querySelectorAll('.modify').forEach(button => {
                button.addEventListener('click', function () {
                    const productItem = button.closest('.product-item');
                    const idP = productItem.getAttribute('data-idp');
                    const idbl = document.getElementById('idBLproduit' + idP).innerText
                    const quantity = document.getElementById('quantite_product' + idP).innerText;
                    const prix = document.getElementById('prix_product' + idP).innerText;
                    // alert(`Les donnes avant idP : ${idP} , idbl : ${idbl} , quantite : ${quantity} , prix : ${prix}`)
                    modifyProduct(idP, idbl, quantity, prix);
                });
            });

            document.querySelectorAll('.delete').forEach(button => {
                button.addEventListener('click', function () {
                    const productItem = button.closest('.product-item');
                    const idP = productItem.getAttribute('data-idp');
                    const quantity = document.getElementById('quantity' + idP).value
                    //alert(quantity)
                    deleteProduct(idP, quantity);
                });
            });
        });

    </script>
</body>

</html>