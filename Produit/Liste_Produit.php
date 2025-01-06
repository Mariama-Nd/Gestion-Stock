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
    </style>

    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Gestion de l'Université</h1>
            <button onclick="location.href='../accueil.php'" style="background-color: #28a745; color: white; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer;">Accueil</button>
        </div>

        <h3>Liste de tous les produits</h3>
        <table id="sales-table">
    <thead>
        <tr>
            <th>
                Catégorie
                <select id="cat-select">
                    <option value="">Tous</option>
                    <?php 
                        include '../Categorie/Categorie/config/db.php';
                        $query = "SELECT DISTINCT nom_categorie FROM categorie order by nom_categorie";
                        $requete = $connexion->prepare($query);
                        $requete->execute();
                        $categories = $requete->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($categories as $categorie): ?>
                            <option value="<?= $categorie['nom_categorie'] ?>"><?= $categorie['nom_categorie'] ?></option>
                        <?php endforeach; ?>
                </select>
            </th>
            <th>
                Sous-catégorie
                <select id="subcat-select">
                    <option value="">Tous</option>
                    <?php 
                        $query = "SELECT DISTINCT nom FROM sousCategorie order by nom";
                        $requete = $connexion->prepare($query);
                        $requete->execute();
                        $souscategories = $requete->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($souscategories as $souscategorie): ?>
                            <option value="<?= $souscategorie['nom'] ?>"><?= $souscategorie['nom'] ?></option>
                        <?php endforeach; ?>
                </select>
            </th>
            <th>
                Nom du produit
                <input type="text" id="name-input" hidden="true" placeholder="Rechercher...">
            </th>
            <th>
                Stock actuel
                <select id="stock-select">
                    <option value="">Tous</option>
                </select>
            </th>
            <th>Total</th>
            <th>Retrait</th>
            <th>
            Etat
                <select id="etat-select" hidden = "true">
                    <option value="">Tous</option>
                    <?php 
                        $query = "SELECT DISTINCT nom_statut FROM statut";
                        $requete = $connexion->prepare($query);
                        $requete->execute();
                        $souscategories = $requete->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($souscategories as $souscategorie): ?>
                            <option value="<?= $souscategorie['nom_statut'] ?>"><?= $souscategorie['nom_statut'] ?></option>
                        <?php endforeach; ?>
                </select>
            </th>
        </tr>
    </thead>
    <tbody>
        <?php
        $r = "SELECT * FROM product p 
        JOIN statut s ON p.id_statut=s.id_statut 
        JOIN sousCategorie sc ON p.id_Sous_categorie = sc.idSC 
        JOIN categorie c ON c.id_categorie = sc.id_categorie ORDER BY idP DESC";
        $requette = $connexion->prepare($r);
        $requette->execute();
        $reponse = $requette->fetchAll(PDO::FETCH_ASSOC);
        foreach ($reponse as $ventes) {
        ?>
        <tr data-stock="<?= $ventes["Stock_actuel"] ?>" data-min-threshold="<?= $ventes["Seuil_limite"] ?>">
            <td><?= $ventes["nom_categorie"] ?></td>
            <td><?= $ventes["nom"] ?></td>
            <td><?= $ventes["nomproduit"] ?></td>
            <td><?= $ventes["Stock_actuel"] ?></td>
            <td><?= $ventes["Total"] ?></td>
            <td><?= $ventes["retrait"] ?></td>
            <td><?= $ventes["nom_statut"] ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        const table = $('#sales-table').DataTable();
        // Remplir les sélecteurs avec les valeurs uniques
        const categoryOptions = new Set();
        const subcategoryOptions = new Set();
        const stockOptions = new Set();
        const etatOptions = new Set();

        $('#sales-table tbody tr').each(function() {
            categoryOptions.add($(this).find('td:eq(0)').text());
            subcategoryOptions.add($(this).find('td:eq(1)').text());
            stockOptions.add($(this).find('td:eq(3)').text());
            etatOptions.add($(this).find('td:eq(1)').text());
        });
       
       
        stockOptions.forEach(option => {
            $('#stock-select').append(`<option value="${option}">${option}</option>`);
        });
        etatOptions.forEach(option => {
            $('#etat-select').append(`<option value="${option}">${option}</option>`);
        });
        // Filtrer les lignes selon les sélections
        $('select, #name-input').on('change input', function() {
            const categoryFilter = $('#cat-select').val();
            const subcategoryFilter = $('#subcat-select').val();
            const nameFilter = $('#name-input').val().toLowerCase();
            const stockFilter = $('#stock-select').val();
            const etatFilter = $('#etat-select').val();
            table.rows().every(function() {
                const data = this.data();
                const row = $(this.node());
                const categoryMatch = categoryFilter === "" || data[0].toLowerCase().includes(categoryFilter.toLowerCase());
                const subcategoryMatch = subcategoryFilter === "" || data[1].toLowerCase().includes(subcategoryFilter.toLowerCase());
                const nameMatch = nameFilter === "" || data[2].toLowerCase().includes(nameFilter);
                const stockMatch = stockFilter === "" || data[3] === stockFilter;
                const etatMatch = etatFilter === "" || data[4] === etatFilter;
                if (categoryMatch && subcategoryMatch && nameMatch && stockMatch && etatMatch) {
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