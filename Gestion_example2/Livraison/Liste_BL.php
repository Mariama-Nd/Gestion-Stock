<?php
session_start();
if (isset($_SESSION["creer"]) && $_SESSION["creer"] == "Fait") {
    echo "
  <script>
    alert('Le bon de livraison a été créé')
  </script>";
    unset($_SESSION["creer"]);
  }
  if (isset($_SESSION["message"]) && $_SESSION["message"] == "Fait") {
    echo "
  <script>
    alert('Produit Ajouter avec success')
  </script>";
    unset($_SESSION["message"]);
  }
  if (isset($_SESSION["enregistrer"]) && $_SESSION["enregistrer"] == "Fait") {
    echo "
  <script>
    alert('Livraison enregistree !!!')
  </script>";
    unset($_SESSION["enregistrer"]);
  }else{
    echo "
  <script>
    alert('Erreur lors de l'enregistrement !!!')
  </script>";
    unset($_SESSION["enregistrer"]);
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
            max-width: 1200px; /* Augmentation de la largeur de la div */
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        h1, h3 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background-color: #28a745; /* Vert */
            color: white;
        }
        table tr:hover {
            background-color: #d4edda; /* Vert clair */
        }
        select {
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
            margin-top: 5px;
            width: 100%;
        }
        select:focus {
            border-color: #28a745; /* Vert */
            outline: none;
        }
        button {
            background-color: #28a745; /* Vert */
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            margin: 10px 0;
        }
        button:hover {
            background-color: #218838; /* Vert foncé */
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Gestion de l'Université</h1>
            <button onclick="location.href='../../accueil.php'" style="background-color: #28a745; color: white; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer;">Accueil</button>
            <button id="openModalButton" class="btn btn-success" style="background-color: #28a745; color: white; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer;margin-left:82%;">Nouvelle Réception</button>
        </div>
        <h3>Liste des Bons de Livraison</h3>
        <table id="sales-table">
            <thead>
                <tr>
                    <th>Nom BL</th>
                    <th></th>
                    <th>Date Creation</th>
                    <th>Bon de Commande</th>
                    <th>Etat de la Livraison</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include '../../Categorie/Categorie/config/db.php';
                $r = "SELECT distinct * FROM bon_livraison bl
                join status_commande sc on sc.id_status_cmd = bl.Etat_Livraison
                join bon_commande bc on bc.id_BC = bl.id_bc 
                where bl.Etat_Livraison <> 4 ";
                $requette = $connexion->prepare($r);
                $requette->execute();
                $reponse = $requette->fetchAll(PDO::FETCH_ASSOC);
                foreach ($reponse as $ventes) {
                ?>
                <tr>
                    <td><?= $ventes["nomBL"] ?></td>
                    <td></td>
                    <td><?= $ventes["date"] ?></td>
                    <td><?= $ventes["nomBC"] ?></td>
                    <td><?= $ventes["nom_status_cmd"] ?></td>
                    <td>
                    <?php 
                     $idBL = $ventes["idBL"];
                     $idBC = $ventes["id_bc"];
                     $nomBL = $ventes["nomBL"];

                     $r = "SELECT * FROM bon_livraison_produit WHERE idBL = :idBL";
                     $requette = $connexion->prepare($r);
                     $requette->bindParam(':idBL', $idBL, PDO::PARAM_INT);
                     $requette->execute();
                     $nbr = $requette->rowCount();
                    if($nbr<=0){
                      echo "
                      <button onclick=\"location.href='approvisionner.php?idBL=$idBL&idBC=$idBC&nomBL=$nomBL'\" style=\"background-color: green; color: white; border: none; border-radius: 5px; cursor: pointer;\">Approvisionner</button>
                      <button name=\"supprimer\" onclick=\"location.href='traitement_btn_supprimer.php?idBL=$idBL&idBC=$idBC&nomBL=$nomBL'\" style=\"background-color: red; color: white; border: none; border-radius: 5px; cursor: pointer;\">Supprimer</button>
                      ";

                    } else{
                        $q = "SELECT * FROM bon_livraison_produit WHERE idBL=:idBL";
                        $rqt = $connexion->prepare($q);
                        $rqt->execute([':idBL' => $idBL]);
                        $nb=$rqt->rowCount(); 
                        
                        if($nb>0 && $ventes["Etat_Livraison"] != 3 && $ventes["Etat_Livraison"] != 4  ){
                            echo "
                         
                            <button name=\"terminer\" onclick=\"location.href='traitement_btn_terminer.php?idBL=$idBL'\" style=\"background-color: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer;\">Valider</button>
                            <button name=\"supprimer\" onclick=\"location.href='traitement_btn_supprimer.php?idBL=$idBL'\" style=\"background-color: red; color: white; border: none; border-radius: 5px; cursor: pointer;\">Supprimer</button>
                            ";

                        }
                        if($ventes["Etat_Livraison"] == 3 ){
                            echo "
                            <button onclick=\"location.href='voir.php?idBL=$idBL&nomBL=$nomBL'\" style=\"background-color: #45B3FA; color: white; border: none; border-radius: 5px; cursor: pointer;\">Voir PDF</button>
                            <button onclick=\"location.href='consulter_bl.php?idBL=$idBL&idBC=$idBC&nomBL=$nomBL'\" style=\"background-color: #808080; color: white; border: none; border-radius: 5px; cursor: pointer;\">Consulter BL</button>
                            <button name=\"supprimer\" onclick=\"location.href='traitement_btn_supprimer.php?idBL=$idBL'\" style=\"background-color: red; color: white; border: none; border-radius: 5px; cursor: pointer;\">Supprimer</button>
                            ";
  
                        } 

                        if($ventes["Etat_Livraison"] == 5 ){
                            echo "
                            <button onclick=\"location.href='poursuivre.php?idBL=$idBL&idBC=$idBC&nomBL=$nomBL'\" style=\"background-color: orange; color: white; border: none; border-radius: 5px; cursor: pointer;\">Poursuivre</button>

                            ";
  
                        } 
                    }
                    
                    ?>

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
    <style>
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
    }

    .modal-content {
        background-color: white;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 50%; /* Reduced width */
        border-radius: 5px;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    .product-form {
        border: 1px solid #ccc;
        padding: 20px;
        margin-bottom: 10px;
        border-radius: 5px;
    }

    .product-form h4 {
        margin-top: 0;
    }

    .buttons {
        margin-top: 10px;
    }

    .add-button,
    .remove-button {
        background-color: #4CAF50;
        color: white;
        border: none;
        padding: 10px;
        cursor: pointer;
        margin-right: 10px;
    }

    .remove-button {
        background-color: #f44336;
    }

    .add-button:hover,
    .remove-button:hover {
        opacity: 0.8;
    }

    .submit-button {
        background-color: #008CBA;
        color: white;
        padding: 15px 20px;
        border: none;
        cursor: pointer;
        font-size: 16px;
    }

    .submit-button:hover {
        opacity: 0.8;
    }
</style>

<div id="productModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <form id="productForm" method="post" action="addBL.php">
            <label for="product-name"><b>Bon de Commande :</b></label>
            <select class="form-select" id="product-name" name="bc" aria-label="Sélectionnez une catégorie" required>
                <option value="" selected>Sélectionnez un Bon de Commande</option>
                <?php
                include "../../Categorie/Categorie/config/db.php";
                $r = "SELECT DISTINCT bc.id_BC, bc.nomBC FROM Bon_commande bc, bon_commande_produit bcp where bc.id_BC = bcp.idbc AND bc.Etat_commander = 2 ORDER BY nomBC";
                $stmt = $connexion->prepare($r);
                $stmt->execute();
                $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($categories as $category) {
                    echo "<option value='" . $category['id_BC'] . "'>" . $category['nomBC'] . "</option>";
                }
                ?>
            </select><br>

            <label for="nomBL"><b>Nom Bon_de_Livraison :</b></label>
            <input type="text" id="nomBL" name="nomBL" required><br><br>

            <label for="bordereau"><b>Numero Bordereau_de_Livraison :</b></label>
            <input type="number" id="bordereau" name="bordereau" required><br><br>

            <button type="submit" class="btn btn-success" name="creer">Créer</button>
        </form>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
        $(document).ready(function() {
            const table = $('#sales-table').DataTable();

            // Events for context menu
            let selectedRow;

            $('#sales-table tbody').on('contextmenu', 'tr', function(e) {
                e.preventDefault();
                selectedRow = this;
                const menu = $('#context-menu');
                menu.css({
                    display: 'block',
                    left: e.pageX + 'px',
                    top: e.pageY + 'px'
                });
            });

            $(document).click(function() {
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

            $('#sales-table tbody tr').each(function() {
                categoryOptions.add($(this).find('td:eq(0)').text());
                subcategoryOptions.add($(this).find('td:eq(1)').text());
                stockOptions.add($(this).find('td:eq(3)').text());
            });

            stockOptions.forEach(option => {
                $('#stock-select').append(`<option value="${option}">${option}</option>`);
            });

            // Filtrer les lignes selon les sélections
            $('select, #name-input').on('change input', function() {
                const categoryFilter = $('#cat-select').val();
                const subcategoryFilter = $('#subcat-select').val();
                const nameFilter = $('#name-input').val().toLowerCase();
                const stockFilter = $('#stock-select').val();
                
                table.rows().every(function() {
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

      //script pour le modal
    const modal = document.getElementById("productModal");
    const openModalButton = document.getElementById("openModalButton");
    const closeButton = document.getElementsByClassName("close")[0];

    openModalButton.onclick = function() {
      modal.style.display = "block";
    }

    closeButton.onclick = function() {
      modal.style.display = "none";
    }

    window.onclick = function(event) {
      if (event.target == modal){
        modal.style.display = "none";
      }
    }


</script>
<script src="js/jquery.min.js"></script>
<script src="js/popper.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/main.js"></script>

</body>
</html>