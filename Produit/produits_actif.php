<?php
$reponse = $_GET["reponse"];
if($reponse == "ok"){
echo "
<script>
  alert('Produit ajouter avec success')
</script>";
}else{
  echo "
  <script>
    alert('Impossible d'ajouter ce produit. Veuillez entrez de nouvelles inforation puis réessayer ')
  </script>";

}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Gestion des Entrées</title>
  <style>
    /* CSS Styles */
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f5f5f5;
    }

    .container {
      max-width: 800px;
      margin: 0 auto;
      padding: 20px;
    }

    h1,
    h3 {
      text-align: center;
      color: #333;
    }

    form {
      background-color: #fff;
      padding: 20px;
      border-radius: 5px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
    }

    input[type="text"],
    input[type="number"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 3px;
      margin-bottom: 10px;
    }

    button {
      background-color: #007bff;
      color: #fff;
      border: none;
      padding: 10px 20px;
      border-radius: 3px;
      cursor: pointer;
    }

    button:hover {
      background-color: #0056b3;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background-color: #fff;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    table th,
    table td {
      padding: 10px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }

    table th {
      background-color: #f2f2f2;
    }

    table button {
      background-color: #dc3545;
      color: #fff;
      border: none;
      padding: 5px 10px;
      border-radius: 3px;
      cursor: pointer;
    }

    table button:hover {
      background-color: #c82333;
    }
  </style>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
  
<!DOCTYPE html>
<html>
<head>
    <title>Liste des Produits Actifs</title>
    <style>
        /* Styles CSS */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        h1, h3 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        table th, table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #f2f2f2;
        }

        .btn {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 3px;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-success {
            background-color: #28a745;
        }

        .btn-warning {
            background-color: #ffc107;
        }

        .btn:hover {
            opacity: 0.9;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <a href="Liste_Produit.php" class="btn btn-success"><i class="bi bi-arrow-left"></i>Retour</a>

        <h3>Liste des produits actifs</h3>

        <table id="sales-table">
            <thead>
                <tr class="bg-success">
                    <th>Catégorie</th>
                    <th>Sous-catégorie</th>
                    <th>Nom</th>
                    <th>Stock</th>
                    <th>Total</th>
                    <th>Retrait</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include '../Categorie/Categorie/config/db.php';
                $r = "SELECT * FROM product p JOIN fournisseur f ON p.idF = f.idF JOIN souscategorie sc ON p.id_Sous_categorie = sc.idSC JOIN categorie c ON c.id_categorie = sc.id_categorie WHERE id_statut = 1 ORDER BY idP DESC";
                $requette = $connexion->prepare($r);
                $requette->execute();
                $reponse = $requette->fetchAll(PDO::FETCH_ASSOC);

                foreach ($reponse as $ventes) {
                    if ($ventes["Stock_actuel"] == 0) {
                        // Appeler la fonction de désactivation directement
                        $idP = $ventes['idP'];
                        $desactiverProduit = $connexion->prepare("UPDATE product SET id_statut = 2 WHERE idP = :idP");
                        $desactiverProduit->execute([':idP' => $idP]);
                        continue; // Passer à l'itération suivante
                    }
                ?>
                    <tr>
                        <td><?= $ventes["nom_categorie"] ?></td>
                        <td><?= $ventes["nom"] ?></td>
                        <td><?= $ventes["nomproduit"] ?></td>
                        <td><?= $ventes["Stock_actuel"] ?></td>
                        <td><?= $ventes["Total"] ?></td>
                        <td><?= $ventes["retrait"] ?></td>
                        <td>
                            <button class="btn btn-warning" onclick="confirmerDesactivation(<?= $ventes['idP'] ?>)">Désactiver</button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table> 
    </div>

    <script>
        function confirmerDesactivation(idP) {
            if (confirm("Êtes-vous sûr de vouloir désactiver ce produit ?")) {
                desactiver(idP);
            }
        }

        function desactiver(idP) {
            let xhr = new XMLHttpRequest();
            xhr.open("GET", "http://localhost/Gestion_Stock/Produit/Traitement/Desactiver_produit.php?idP=" + idP);
            xhr.onload = function() {
                let reponse = xhr.responseText;
                if (reponse) {
                    alert("Produit désactivé");
                    location.reload();
                } else {
                    alert("Problème technique");
                }
            };
            xhr.send();
        }
    </script>
</body>
</html>