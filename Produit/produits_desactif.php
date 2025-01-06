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
  
  <div class="container">
  <a href="Liste_Produit.php" class="btn btn-success"><i class="bi bi-arrow-left"></i>Retour</a>

    <h3>Liste des produits désactivés</h3>

    <table id="sales-table">
      <thead>
        <tr class="bg-success">
          <th>Categorie</th>
          <th>Sous Categorie</th>
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
        $r = "Select * from  product p join fournisseur f on p.idF=f.idF join souscategorie sc on p.id_Sous_categorie = sc.idSC join categorie c on c.id_categorie = sc.id_categorie where id_statut=2 order by idP desc ";
        $requette = $connexion->prepare($r);
        $requette->execute();
        $reponse = $requette->fetchAll(PDO::FETCH_ASSOC);
        foreach ($reponse as $ventes) {
        ?>
          <tr>
            <td> <?= $ventes["nom_categorie"] ?> </td>
            <td><?= $ventes["nom"] ?></td>
            <td><?= $ventes["nomproduit"] ?></td>
            <td><?= $ventes["Stock_actuel"] ?></td>
            <td><?= $ventes["Total"] ?></td>
            <td><?= $ventes["retrait"] ?></td>
            <td><button class="btn btn-success" onclick="activer(<?= $ventes['idP'] ?>)">Activer</button>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table> 
  </div>

  <script>
      function activer(valeur){
        let xhr = new XMLHttpRequest()
        xhr.open("GET","http://localhost/Gestion_Stock/Produit/Traitement/Activer_produit.php?idP="+valeur)
        xhr.onload = function (){
            let repon = xhr.responseText
            if(repon){
            alert("Produit réactiver avec Success")
            location.reload()
            }else{
              alert("Probleme Technique")
            }
        }

      xhr.send()
    }
  </script>
</body>

</html>