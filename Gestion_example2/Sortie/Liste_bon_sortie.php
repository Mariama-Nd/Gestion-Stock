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
    alert('Bon de Sortie Sauvegarde !!!')
  </script>";
    unset($_SESSION["sauvegarde"]);
}
if (isset($_SESSION["cmd"]) && $_SESSION["cmd"] == "Valider") {
    echo "
  <script>
    alert('Bon de Sortie Valider!!!')
  </script>";
    unset($_SESSION["cmd"]);
}
if (isset($_SESSION["cmd"]) && $_SESSION["cmd"] == "Deja_Fait") {
    echo "
  <script>
    alert('Ce Bon de Sortie est deja Valider .')
  </script>";
    unset($_SESSION["cmd"]);
}

if (isset($_SESSION["delete"]) && $_SESSION["delete"] == "Supprimer") {
    echo "
  <script>
    alert('Bon de Sortie Supprimer.')
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
            <button onclick="creerBS()"
                style="background-color: #28a745; color: white; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer;margin-left:82%;">Créer
                BS</button>
        </div>
        <h3>Liste des Bons de Sorties</h3>
        <table id="sales-table">
            <thead>
                <tr>
                    <th>Prenom Agent </th>
                    <th>Nom Agent</th>
                    <th>Nom Service</th>
                    <th>Date Creation</th>
                    <th>Etat du Bon de Sortie</th>
                    <th>Action</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                include '../../Categorie/Categorie/config/db.php';
                $r = "SELECT distinct * FROM bon_sortie bs
                join status_commande sc on sc.id_status_cmd = bs.Etat_bon_sortie
                WHERE bs.Etat_bon_sortie != 4
                ORDER BY date_creation DESC";
                $requette = $connexion->prepare($r);
                $requette->execute();
                $reponse = $requette->fetchAll(PDO::FETCH_ASSOC);
                foreach ($reponse as $ventes) {
                    ?>
                    <tr>
                        <td><?= $ventes["prenom"] ?></td>
                        <td><?= $ventes["nom"] ?></td>
                        <td><?= $ventes["structure"] ?></td>
                        <td><?= $ventes["date_creation"] ?></td>
                        <td><?= $ventes["nom_status_cmd"] ?></td>
                        <td>

                            <?php
                            if ($ventes["Etat_bon_sortie"] == 1) {
                                $user = $ventes["user"];
                                $idbon = $ventes["idBS"];
                                echo "
            <button onclick=\"location.href='add_product_to_bs.php?idBon=$idbon'\"style='background-color: #964B00; color: white; border: none; border-radius: 5px; cursor: pointer;'>Editer</button>
                       
                       ";
                            } elseif ($ventes["Etat_bon_sortie"] == 2) {
                                $idbon = $ventes["idBS"];
                                echo "
            <button onclick=\"newWindows($idbon)\" style='background-color: #808080; color: white; border: none; border-radius: 5px; cursor: pointer;'>Voir le PDF</button>
                       
                       ";
                            } elseif ($ventes["Etat_bon_sortie"] == 3) {
                                $idbon = $ventes["idBS"];
                                echo "
            <button onclick='location.href=' style='background-color: red; color: white; border: none; border-radius: 5px; cursor: pointer;'>Supprimer</button>
                       ";
                            } elseif ($ventes["Etat_bon_sortie"] == 5) {
                                $idbon = $ventes["idBS"];
                                $user = $ventes["user"];
                                echo "
                        <button onclick=\"location.href='poursuivre_sortie.php?idBon=$idbon'\" style=\"background-color: orange; color: white; border: none; border-radius: 5px; cursor: pointer;\">Poursuivre</button>
                        ";
                            } else {
                                $idbon = $ventes["idBS"];
                                echo "
                        
            <button onclick=\"validation_Sortie($idbon)\" style='background-color: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer;'>Valider</button>
                       
                       ";
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            $idbon = $ventes["idBS"];
                            $user = $ventes["user"];
                            $r = "SELECT distinct * FROM bon_sortie_produit where idS = '$idbon'";
                            $requette = $connexion->prepare($r);
                            $requette->execute();
                            $reponse = $requette->fetchAll(PDO::FETCH_ASSOC);
                            if (count($reponse) > 0 && $ventes["Etat_bon_sortie"] != 2) {
                                echo "
                                <button onclick=\"validation_Sortie($idbon)\" style='background-color: #34C759; color: white; border: none; border-radius: 5px; cursor: pointer;' name=\"valider\">Valider</button>
                                ";
                            } elseif (count($reponse) > 0 && $ventes["Etat_bon_sortie"] == 2) {
                                $idbon = $ventes["idBS"];
                                echo "
                                <button onclick=\"location.href='Consulter_BS.php?idBon=$idbon'\" style='background-color: #45B3FA; color: white; border: none; border-radius: 5px; cursor: pointer;' name=\"valider\">Consulter BS</button>
                                ";
                            }
                            ?>
                        </td>
                        <td>
                            <button onclick="validation_Sortie(<?= $ventes['idBS'] ?>)"
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

        function newWindows(id_bon_cmd) {
            window.open("FPDF.php?id=" + id_bon_cmd, "blank")
        }

        function validation_Sortie(id_bon_cmd) {
            if (confirm("Voulez-vous vraiment valider ce bon de sortie")) {
                location.href = 'Validation_Sortie.php?idBon=' + id_bon_cmd
            } else {
                alert("Action annuler")
            }
        }

        //script pour la liste 

        function creerBS() {
            location.href = "index.php";
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