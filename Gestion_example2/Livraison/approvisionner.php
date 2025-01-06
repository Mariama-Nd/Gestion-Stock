<?php
session_start(); // Démarrage de la session pour récupérer les données stockées
// Récupération des variables de l'URL (si elles sont passées en GET)
$idBL = $_GET['idBL'];
$idBC = $_GET['idBC'];
$nomBL = $_GET['nomBL'];
if (isset($_SESSION["incoherent"]) && $_SESSION["incoherent"] == "ok") {
    $produitsIncoherents = $_SESSION["produitsIncoherents"];
    $jsonArray = json_encode($produitsIncoherents);
    echo '<script>alert("Quantité trop grande pour les produits suivants :\n" + ' . $jsonArray . '.map(function(item) { return "- " + item.nomProduit + " (quantité saisie : " + item.quantiteSaisie + ", reste à livrer: " + item.reste + ")"; }).join("\n"));</script>';
    unset($_SESSION["incoherent"]);
    unset($_SESSION["produitsIncoherents"]);
}

include "../../Categorie/Categorie/config/db.php";
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
        h2 {
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
            padding: 15px;
            background-color: #f9fafb;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            box-sizing: border-box;
        }

        .product-item label {
            margin-right: 0px;
            /* Réduire l'espace entre le label et l'input */
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
            <h2>Produits</h2>
            <?php
            // Prepare the SQL queries with parameterized queries
            $stmt = $connexion->prepare('
    SELECT DISTINCT p.idP, p.nomproduit, bcp.idbc 
    FROM bon_commande_produit bcp
    JOIN product p ON bcp.idP = p.idP
    JOIN bon_livraison bl ON bl.id_bc = bcp.idbc
    WHERE bcp.idbc = :idBC and bl.idBL = :idBL
');
            $stmt->execute(['idBC' => $idBC, 'idBL' => $idBL]);
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (empty($products)) {
                echo '<p>Aucun produit trouvé.</p>';
            } else {
                foreach ($products as $product) {
                    $stmt2 = $connexion->prepare('
            SELECT DISTINCT bcp.quantite
            FROM bon_commande_produit bcp
            WHERE idP = :id and idbc = :idbc
        ');
                    $stmt2->execute(['id' => $product['idP'], 'idbc' => $product['idbc']]);
                    $quantities = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($quantities as $quantity) {
                        ?>
                        <div class="product-item" data-idp="<?= $product['idP'] ?>">
                            <input type="checkbox" id="prod-<?= $product['idP'] ?>" name="products[]"
                                value="<?= $product['idP'] ?>">
                            <label for="prod-<?= $product['idP'] ?>" class="product-name"><b><?= htmlspecialchars($product['nomproduit']) ?> <br>
                                    (Commander:<?= $quantity['quantite'] ?>)</b></label><span
                                id="quantite_cmd<?= $product['idP'] ?>" hidden="true"><?= $quantity['quantite'] ?></span>
                            <input type="number" name="quantity[]" min="0" id="<?= $product['idP'] ?>" disabled
                                placeholder="Quantite Recus">
                            <label for="prod-<?= $product['idP'] ?>"><b>Prix Unitaire (CFA)</b></label>
                            <input type="number" name="prix[]" class="product-price" min="0" id="prix<?= $product['idP'] ?>" disabled
                                placeholder="Prix Produit">
                            <input type="hidden" name="idBL" value="<?php echo $idBL; ?>">
                            <script>
                                console.log(<?= $product['idP'] ?>)
                                console.log(<?= $quantity['quantite'] ?>)
                            </script>
                            <button type="button" class="partielle-save" data-idp="<?= $product['idP'] ?>">Enregistrer</button>
                        </div>
                        <?php
                    }
                }
            }
            ?>
        </div>
        <button type="button" class="btn btn-success" name="enregistrerTout" id="enregistrerTout">Enregistrer
            Tout</button>
    </form>
    <script>
        /* function get_reste(params1, param2) {
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
         */
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

        // Enregistrer le produit
        function saveProduct(idP) {
            const productItem = document.querySelector(`.product-item[data-idp="${idP}"]`);
            if (!productItem) {
                console.error("Produit non trouvé pour l'ID: " + idP);
                return;
            }
            const checkbox = productItem.querySelector('input[type="checkbox"]');
            const quantityInput = productItem.querySelector('input[type="number"]');
            const prix = document.getElementById('prix' + idP).value;
            let coherance_avec_Q_cmd = document.getElementById('quantite_cmd' + idP).innerText
            //alert(coherance_avec_Q_cmd)
            if (!checkbox.checked) {
                alert("Veuillez sélectionner le produit avant d'entrer une quantité.");
                return;
            }

            const quantity = quantityInput.value.trim();
            if (!quantity || isNaN(quantity) || parseFloat(quantity) <= 0 || quantity > coherance_avec_Q_cmd) {
                alert("Veuillez entrer une quantité valide.");
                return;
            }

            if (prix.trim() <= 0) {
                alert("Veuillez entrer un Prix valide.")
                return
            }

            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'Partielle_save.php', true);
            xhr.setRequestHeader('Content-Type', 'application/json');

            xhr.onload = function () {
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                       // alert("Produit enregistré.");

                        // Créer les boutons "Modifier" et "Supprimer"
                        const modifyButton = document.createElement('button');
                        modifyButton.textContent = 'Modifier';
                        modifyButton.className = 'modify';
                        modifyButton.style.backgroundColor = 'orange';
                        modifyButton.addEventListener('click', function () {
                            // alert(`Les donnes avant idP : ${idP} , idbl : ${idbl} , quantite : ${quantity} , prix : ${prix}`)
                            modifyProduct(idP, <?php echo $_GET["idBL"]; ?>, quantity, prix);
                        });
                        const deleteButton = document.createElement('button');
                        deleteButton.textContent = 'Supprimer';
                        deleteButton.className = 'delete';
                        deleteButton.style.backgroundColor = 'red';
                        deleteButton.addEventListener('click', function () {
                            deleteProduct(idP, quantity);
                        });
                        // Remplacer le bouton "Enregistrer" par les nouveaux boutons
                        const saveButton = productItem.querySelector('.partielle-save');
                        productItem.removeChild(saveButton);
                        productItem.appendChild(modifyButton);
                        productItem.appendChild(deleteButton);

                    } else if (response.reste) {
                        alert("Erreur : La quantité dépasse la quantité restante (" + response.reste + ").");
                    } else {
                        alert("Erreur : " + (response.message || "Échec de l'enregistrement."));
                    }
                } catch (e) {
                    alert("Erreur : Réponse non valide du serveur.");
                    console.error("Erreur de parsing JSON : ", e);
                }
            };

            xhr.onerror = function () {
                alert("Erreur de connexion au serveur.");
            };
            //alert(`Les donnees idP : ${idP} , prix : ${prix} , quantity : ${quantity}`)
            xhr.send(JSON.stringify({
                idBL: '<?php echo $_GET["idBL"]; ?>',
                idBC: '<?php echo $_GET["idBC"]; ?>',
                idP: idP,
                prix: prix,
                quantity: parseFloat(quantity)
            }));

        }

        // Ajouter l'événement de clic pour chaque bouton "Enregistrer"
        document.querySelectorAll('.partielle-save').forEach(button => {
            button.addEventListener('click', function () {
                const idP = button.getAttribute('data-idp');
                saveProduct(idP);
            });
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


        //fonction pour modifier la quantite d'un produit dans le BL 
        function modifyProduct(idP, idbl, quantity, prix) {
            const productItem = document.querySelector(`.product-item[data-idp="${idP}"]`);
            const quantityInput = productItem.querySelector('input[type="number"]');
            const currentQuantity = quantityInput.value;
            const current_price = document.getElementById("prix" + idP).value
            let coherance_avec_Q_cmd = document.getElementById('quantite_cmd' + idP).innerText  
            //alert(`Les donnes apres idP : ${idP} , idbl : ${idbl} , quantite : ${currentQuantity} , prix : ${current_price}`)

            // Demande à l'utilisateur de saisir une nouvelle quantité

            // Vérification des différentes conditions sur la saisie
            if (currentQuantity === null || current_price == null) {
                // L'utilisateur a annulé l'opération, on ne fait rien
                return;
            } else if (currentQuantity.trim() === "" || current_price.trim() == '') {
                // Si l'entrée est vide
                alert("L'entrée est vide. Veuillez entrer une valeur.");
            } else if (isNaN(currentQuantity) || parseFloat(currentQuantity) < 0 || isNaN(current_price) || parseFloat(current_price) < 0 || currentQuantity > coherance_avec_Q_cmd ) {
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
                       // location.reload()
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
        //fonction pour supprimer un produit du BL 
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
        //Décocher  tous les produits aprés rechargement de la page
        document.addEventListener('DOMContentLoaded', function () {
            // Décocher tous les produits
            document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                checkbox.checked = false;
            });
        });

    </script>
</body>

</html>