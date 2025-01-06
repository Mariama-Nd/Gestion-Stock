<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historisation des Ventes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
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
            color: white;
        }

        table tr:hover {
            background-color: #d4edda;
        }

        select,
        input {
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
            margin-top: 5px;
            width: 100%;
        }

        button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            margin: 10px 0;
        }

        button:hover {
            background-color: #218838;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="header" style="display: flex; justify-content: space-between; align-items: center;">
            <button onclick="location.href='../accueil.php'" style="margin-right: 20px;">Accueil</button>
            <h1 style="text-align: center; flex-grow: 1;margin-right:7%;">Historisation des Ventes</h1>
        </div>
        <h4 style="text-align: center;">Historiques des Entrées/Sorties</h4>
        <table id="sales-table" class="display">
            <thead>
                <tr>
                    <th>Nom de l'historisation
                        <select id="historisation-select">
                            <option value="">Tous</option>
                            <?php 
                                include "../Categorie/Categorie/config/db.php";
                                $query = "SELECT DISTINCT nom_historisation FROM historisation ORDER BY nom_historisation";
                                $requete = $connexion->prepare($query);
                                $requete->execute();
                                $historisations = $requete->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($historisations as $historisation): ?>
                                    <option value="<?= $historisation['nom_historisation'] ?>"><?= $historisation['nom_historisation'] ?></option>
                                <?php endforeach; ?>
                        </select>
                    </th>
                    <th>Date de création
                        <input type="date" id="date-input">
                    </th>
                    <th>Dernière modification
                        <input type="date" id="modif-input">
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $query = "SELECT * FROM historisation ORDER BY date_creation DESC";
                    $statement = $connexion->prepare($query);
                    $statement->execute();
                    $results = $statement->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($results as $row) {
                ?>
                    <tr>
                        <td><?= $row["nom_historisation"] ?></td>
                        <td><?= $row["date_creation"] ?></td>
                        <td><?= $row["Dernier_modif"] ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            const table = $('#sales-table').DataTable();

            function filterTable() {
                const historisationFilter = $('#historisation-select').val();
                const dateFilter = $('#date-input').val();
                const modifFilter = $('#modif-input').val();
                
                table.rows().every(function() {
                    const data = this.data();
                    const row = $(this.node());
                    const historisationMatch = historisationFilter === "" || data[0] === historisationFilter;
                    const dateMatch = dateFilter === "" || new Date(data[1]).toLocaleDateString() === new Date(dateFilter).toLocaleDateString();
                    const modifMatch = modifFilter === "" || new Date(data[2]).toLocaleDateString() === new Date(modifFilter).toLocaleDateString();
                    
                    if (historisationMatch && dateMatch && modifMatch) {
                        row.show();
                    } else {
                        row.hide();
                    }
                });
            }

            $('select, input').on('change', filterTable);
        });
    </script>
</body>

</html>
