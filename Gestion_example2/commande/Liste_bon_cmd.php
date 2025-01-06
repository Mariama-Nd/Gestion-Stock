<?php
session_start();
if (isset($_SESSION["message"]) && $_SESSION["message"] == "Fait") {
    echo "
  <script>
    alert('Produit Ajouter avec success')
  </script>";
    unset($_SESSION["message"]);
}
if (isset($_SESSION["sauvegarde"]) && $_SESSION["sauvegarde"] == "Fait") {
    echo "
  <script>
    alert('Commande Sauvegarde !!!')
  </script>";
    unset($_SESSION["sauvegarde"]);
}
if (isset($_SESSION["cmd"]) && $_SESSION["cmd"] == "Valider") {
    echo "
  <script>
    alert('Commande Valider!!!')
  </script>";
    unset($_SESSION["cmd"]);
}
if (isset($_SESSION["cmd"]) && $_SESSION["cmd"] == "Deja_Fait") {
    echo "
  <script>
    alert('Cette Commande deja Valider .')
  </script>";
    unset($_SESSION["cmd"]);
}

if (isset($_SESSION["delete"]) && $_SESSION["delete"] == "Supprimer") {
    echo "
  <script>
    alert('Commande Supprimer.')
  </script>";
    unset($_SESSION["delete"]);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Gestion des Entrées</title>
    <style>
        /* Styles CSS */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f9f0;
        }

        .container {
            max-width: 1200px;
            /* Augmentation de la largeur de la div */
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        h1,
        h3 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th,
        table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #28a745;
            /* Vert */
            color: white;
        }

        table tr:hover {
            background-color: #d4edda;
            /* Vert clair */
        }

        select {
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
            margin-top: 5px;
            width: 100%;
        }

        select:focus {
            border-color: #28a745;
            /* Vert */
            outline: none;
        }

        button {
            background-color: #28a745;
            /* Vert */
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            margin: 10px 0;
        }

        button:hover {
            background-color: #218838;
            /* Vert foncé */
        }

        .context-menu {
            display: none;
            position: absolute;
            background-color: #fff;
            border: 1px solid #ccc;
            z-index: 1;
        }

        .context-menu .item {
            padding: 8px 16px;
            cursor: pointer;
        }

        .context-menu .item:hover {
            background-color: #f1f1f1;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Gestion de l'Université</h1>
            <button onclick="location.href='../../accueil.php'"
                style="background-color: #28a745; color: white; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer;">Accueil</button>
            <button onclick="creerBC()"
                style="background-color: #28a745; color: white; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer;margin-left:82%;">Nouvelle Commande</button>
        </div>
        <h3>Liste des Commandes</h3>
        <table id="sales-table">
            <thead>
                <tr>
                    <th>Nom </th>
                    <th></th>
                    <th>Date Creation</th>
                    <th>Etat de la Commande</th>
                    <th>Action</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                include '../../Categorie/Categorie/config/db.php';
                $r = "SELECT distinct * FROM bon_commande bc
                join status_commande sc on sc.id_status_cmd = bc.Etat_commander
                WHERE bc.Etat_commander != 4
                ORDER BY id_BC  ";
                $requette = $connexion->prepare($r);
                $requette->execute();
                $reponse = $requette->fetchAll(PDO::FETCH_ASSOC);
                foreach ($reponse as $ventes) {
                    ?>
                    <tr>
                        <td><?= $ventes["nomBC"] ?></td>
                        <td></td>
                        <td><?= $ventes["date"] ?></td>
                        <td><?= $ventes["nom_status_cmd"] ?></td>
                        <td>

                            <?php

                            if ($ventes["id_status_cmd"] == 1) {
                                $auto_gen = $ventes["idBC_gen"];
                                $nom = $ventes["nomBC"];
                                echo "
            <button onclick=\"location.href='commander.php?auto_gen=$auto_gen&nomBC=$nom'\"style='background-color: #964B00; color: white; border: none; border-radius: 5px; cursor: pointer;'>Editer</button>
                       ";
                            } elseif ($ventes["id_status_cmd"] == 2) {
                                $id_BC = $ventes["id_BC"];
                                echo "
            <button onclick=\"newWindows($id_BC)\" style='background-color: #45B3FA; color: white; border: none; border-radius: 5px; cursor: pointer;'>Consulter BC</button>
                       ";
                            } elseif ($ventes["id_status_cmd"] == 3) {
                                $id_BC = $ventes["id_BC"];
                                echo "
            <button onclick='location.href=' style='background-color: red; color: white; border: none; border-radius: 5px; cursor: pointer;'>Supprimer</button>
                       ";
                            } elseif ($ventes["id_status_cmd"] == 5) {
                                $id_BC = $ventes["id_BC"];
                                $nom_BC = $ventes["nomBC"];
                                echo "
                        <button onclick=\"location.href='poursuivre_cmd.php?idbon=$id_BC&nomBC=$nom_BC'\" style=\"background-color: orange; color: white; border: none; border-radius: 5px; cursor: pointer;\">Poursuivre</button>
                        ";
                            } else {
                                $id_BC = $ventes["id_BC"];
                                echo "
                        
            <button onclick='location.href=' style='background-color: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer;'>Valider</button>
                       ";
                            }
                            ?>

                        </td>
                        <td>
                            <?php
                             $id_BC = $ventes["id_BC"];
                             $nom_BC = $ventes["nomBC"];
                            $idbon_cmd = $ventes["id_BC"];
                            $nom_BC = $ventes["nomBC"];
                            $r = "SELECT distinct * FROM bon_commande_produit where idbc = '$idbon_cmd'";
                            $requette = $connexion->prepare($r);
                            $requette->execute();
                            $reponse = $requette->fetchAll(PDO::FETCH_ASSOC);
                            if (count($reponse) > 0 && $ventes["Etat_commander"] != 2) {
                                echo "
        <button onclick=\"validation_bon($id_BC)\" style='background-color: #34C759; color: white; border: none; border-radius: 5px; cursor: pointer;' name=\"valider\">Valider</button>
        ";
                            } elseif (count($reponse) > 0 && $ventes["Etat_commander"] == 2) {
                                echo "
                                <button onclick=\"location.href='Consulter_BC.php?idbon=$id_BC&nomBC=$nom_BC'\" style='background-color: #808080; color: white; border: none; border-radius: 5px; cursor: pointer;' name=\"valider\">Apperçu</button>
                                ";
                            }
                            ?>
                        </td>

                        <td>
                            <button  onclick="del(<?= $ventes['id_BC'] ?>)"
                                style='background-color: #FF0000; color: white; border: none; border-radius: 5px; cursor: pointer;'
                                name="Supprimer">Supprimer</button>
                        </td>

                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <div id="context-menu" class="context-menu">
            <div class="item" onclick="editProduct()">Éditer</div>
            <div class="item" onclick="deleteProduct()">Supprimer</div>
            <div class="item" onclick="shareProduct()">Partager</div>
            <div class="item" onclick="hideContextMenu()">Fermer</div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <script>

//Fonction pour la suppression d'un BC
        function del(idbon) {
            if (confirm("Voulez-vous vraiment supprimer cette commande ?")) {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'Supprimer_bon_cmd.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                // Envoyer la requête
                xhr.send(`idboncmd=${idbon}`)

                // Gérer la réponse
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        if (response) {
                            alert("Cette commande fait partie d'une livraison et ne peut pas être supprimé.");
                        } else {
                            alert("Commande supprimée avec succès.");
                            location.reload(); // Mettre à jour l'interface
                        }
                    } else {
                        alert("Erreur lors de la requête : " + xhr.status);
                    }
                };
            } else {
                alert("Action annulée");
            }
        }        



        function newWindows(id_bon_cmd) {
            window.open("../../test.php?id="+id_bon_cmd,"blank")
        }

        function validation_bon(id_bon_cmd) {
            if(confirm("Voulez-vous vraiment valider cette commande")){
                location.href='Validation_Bon.php?idbon='+id_bon_cmd
            }else{
                alert("Action annuler")
            }
        }

        //script pour la liste 

        function creerBC() {
            if (confirm("Voulez-vous éditer une Nouvelle Commande ?")) {
                let nom = prompt("Donnez le nom de la Commande :");
                if (nom) {
                    location.href = "bon_commande.php?nomBC=" + nom;
                }
            } else {
                alert("Opération annulée");
            }
        }

        $(document).ready(function () {
            const table = $('#sales-table').DataTable();

            // Events for context menu
            let selectedRow;

            $('#sales-table tbody').on('contextmenu', 'tr', function (e) {
                e.preventDefault();
                selectedRow = this;
                const menu = $('#context-menu');
                menu.css({
                    display: 'block',
                    left: e.pageX + 'px',
                    top: e.pageY + 'px'
                });
            });

            $(document).click(function () {
                $('#context-menu').hide();
            });

            // Actions for context menu
            function hideContextMenu() {
                $('#context-menu').hide();
            }

            function editProduct() {
                // Logique pour éditer le produit
                const product = $(selectedRow).find('td:eq(2)').text(); // Nom du produit
                alert('Édition du produit : ' + product);
                hideContextMenu();
            }

            function deleteProduct() {
                // Logique pour supprimer le produit
                const product = $(selectedRow).find('td:eq(2)').text(); // Nom du produit
                alert('Suppression du produit : ' + product);
                hideContextMenu();
            }

            function shareProduct() {
                // Logique pour partager le produit
                const product = $(selectedRow).find('td:eq(2)').text(); // Nom du produit
                alert('Partage du produit : ' + product);
                hideContextMenu();
            }

            // Remplir les sélecteurs avec les valeurs uniques
            const categoryOptions = new Set();
            const subcategoryOptions = new Set();
            const stockOptions = new Set();

            $('#sales-table tbody tr').each(function () {
                categoryOptions.add($(this).find('td:eq(0)').text());
                subcategoryOptions.add($(this).find('td:eq(1)').text());
                stockOptions.add($(this).find('td:eq(3)').text());
            });

            stockOptions.forEach(option => {
                $('#stock-select').append(`<option value="${option}">${option}</option>`);
            });

            // Filtrer les lignes selon les sélections
            $('select, #name-input').on('change input', function () {
                const categoryFilter = $('#cat-select').val();
                const subcategoryFilter = $('#subcat-select').val();
                const nameFilter = $('#name-input').val().toLowerCase();
                const stockFilter = $('#stock-select').val();

                table.rows().every(function () {
                    const data = this.data();
                    const row = $(this.node());
                    const categoryMatch = categoryFilter === "" || data[0].toLowerCase().includes(categoryFilter.toLowerCase());
                    const subcategoryMatch = subcategoryFilter === "" || data[1].toLowerCase().includes(subcategoryFilter.toLowerCase());
                    const nameMatch = nameFilter === "" || data[2].toLowerCase().includes(nameFilter);
                    const stockMatch = stockFilter === "" || data[3] === stockFilter;
                    if (categoryMatch && subcategoryMatch && nameMatch && stockMatch) {
                        row.show();
                    } else {
                        row.hide();
                    }
                });
            });
        });
    </script>
</body>
</html>