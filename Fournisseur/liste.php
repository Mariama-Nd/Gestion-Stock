<!DOCTYPE html>
<html lang="en">
<head>

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
    h2 {
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
      width: 90%;
      margin-left:5%;
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

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">

    <link rel="stylesheet" href="../Gestion_example2/modal-05/css/ionicons.min.css">
    <link rel="stylesheet" href="../Gestion_example2/modal-05/css/style.css">
    <title>Liste des Fournisseurs</title>
</head>
<body>
<a href="../accueil.php" class="btn btn-success" style="margin-top:10px; margin-left:4%;"><i class="bi bi-arrow-left"></i> Accueil</a>
<a href="ajout.php" class="btn btn-success" style="margin-left:82%;margin-top:10px;"><i class="bi bi-arrow-left"></i> Nouveau</a>
<h2>Liste des Fournisseurs</h2>
    <table id="sales-table">
      <thead>
        <tr class="bg-success">
          <th>Nom</th>
          <th>Prénom</th>
          <th>Adresse</th>
          <th>Téléphone</th>
          <th>Email</th>
        </tr>
      </thead>
      <tbody>
        <?php
        include '../Categorie/Categorie/config/db.php';
        $r = "SELECT * FROM fournisseur where nomF <>'pas de fournisseur'";
        $requette = $connexion->prepare($r);
        $requette->execute();
        $reponse = $requette->fetchAll(PDO::FETCH_ASSOC);
        foreach ($reponse as $ventes) {


        ?>
          <tr>
            <td><?= $ventes["nomF"] ?></td>
            <td><?= $ventes["prenomF"] ?></td>
            <td><?= $ventes["adresseF"] ?></td>
            <td><?= $ventes["telF"] ?></td>
            <td><?= $ventes["emailF"] ?></td>
          </tr>

        <?php } ?>
      </tbody>
    </table>
</body>
</html>