<?php
session_start();

if (isset($_SESSION["reponse"]) && $_SESSION["reponse"] == "OK") {
  echo "
<script>
  alert('Produit Ajouter avec success')
</script>";
  unset($_SESSION["reponse"]);
} else {
  echo "
  <script>
    alert('Impossible d'ajouter ce produit. Veuillez entrez de nouvelles inforation puis réessayer ')
  </script>";
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestion des Entrées</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <style>
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
      width: 100%;
      height: 100%;
    }

    label {
      font-weight: bold;
      margin-bottom: 5px;
      display: block;
    }

    input[type="text"],
    input[type="number"],
    select {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 3px;
      margin-bottom: 10px;
      box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    button {
      background-color: #007bff;
      color: #fff;
      border: none;
      padding: 10px 20px;
      border-radius: 3px;
      cursor: pointer;
      margin-top: 10px;
    }
    button:hover {
      background-color: #0056b3;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      background-color: #fff;
      margin-top: 20px;
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
      width: 80%;
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

    .buttons {
      margin-top: 10px;
    }

    .add-button,
    .remove-button,
    .submit-button {
      border: none;
      padding: 10px;
      cursor: pointer;
      margin-right: 10px;
      color: white;
    }

    .add-button {
      background-color: #4CAF50; /* Vert */
    }

    .remove-button {
      background-color: #f44336; /* Rouge */
    }

    .submit-button {
      background-color: #008CBA; /* Bleu */
    }

    .submit-button:hover,
    .add-button:hover,
    .remove-button:hover {
      opacity: 0.8;
    }
  </style>
</head>

<body>
  <div class="container">
    <a href="../accueil.php" class="btn btn-success">Accueil</a>
    <h1>Gestion de l'Université</h1>
    <h2>Ajout de Produits</h2>
    <form id="add-sale-form" method="post" action="Traitement/add.php">
      <label for="product-name">Sous Catégorie</label>
      <select class="form-select" id="product-name" name="idSC" aria-label="Sélectionnez une catégorie">
        <option value="" selected>Sélectionnez une catégorie</option>
        <?php
        include "../Categorie/Categorie/config/db.php";
        $sql = "SELECT c.id_categorie, c.nom_categorie, sc.idSC, sc.nom 
                FROM categorie c
                JOIN sousCategorie sc ON c.id_categorie = sc.id_categorie
                ORDER BY c.nom_categorie, sc.nom";
        $stmt = $connexion->prepare($sql);
        $stmt->execute();
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $currentCategory = null;

        foreach ($categories as $category) {
          if ($currentCategory != $category['id_categorie']) {
            if ($currentCategory !== null) {
              echo "</optgroup>";
            }
            echo "<optgroup label='" . $category['nom_categorie'] . "'>";
            $currentCategory = $category['id_categorie'];
          }
          echo "<option value='" . $category['idSC'] . "'>" . $category['nom'] . "</option>";
        }
        if ($currentCategory !== null) {
          echo "</optgroup>";
        }
        ?>
      </select>

      <label for="quantity">Nom du Produit</label>
      <input type="text" id="quantity" name="nomP" required>

      <label for="seuil">Seuil Limite</label>
      <input type="number" id="seuil" name="seuil" min="1" required>

      <button type="submit" class="btn btn-success" name="ajouter">Ajouter</button>
      <button type="button" id="openModalButton" class="btn btn-success">MultiAdd</button>
    </form>

    <div id="productModal" class="modal">
      <div class="modal-content">
        <span class="close">&times;</span>
        <form id="productForm" action="../Ajout_multiple/submit.php" method="post">
          <div id="productsContainer">
            <div class="product-form">
              <h4>Produit 1</h4>
              <label>Categorie:</label>
              <select class="form-select" name="idSC" required>
                <option value="" selected>Sélectionnez une catégorie</option>
                <?php
                // Repetition du même code pour les catégories
                include "../Categorie/Categorie/config/db.php";
                $sql = "SELECT c.id_categorie, c.nom_categorie, sc.idSC, sc.nom 
                        FROM categorie c
                        JOIN sousCategorie sc ON c.id_categorie = sc.id_categorie
                        ORDER BY c.nom_categorie, sc.nom";
                $stmt = $connexion->prepare($sql);
                $stmt->execute();
                $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $currentCategory = null;

                foreach ($categories as $category) {
                  if ($currentCategory != $category['id_categorie']) {
                    if ($currentCategory !== null) {
                      echo "</optgroup>";
                    }
                    echo "<optgroup label='" . $category['nom_categorie'] . "'>";
                    $currentCategory = $category['id_categorie'];
                  }
                  echo "<option value='" . $category['idSC'] . "'>" . $category['nom'] . "</option>";
                }
                if ($currentCategory !== null) {
                  echo "</optgroup>";
                }
                ?>
              </select>

              <label>Nom du produit:</label>
              <input type="text" name="productName[]" required>

              <label for="seuil">Seuil Limite</label>
              <input type="number" name="seuil[]" min="1" required>
            </div>
          </div>

          <div class="buttons">
            <button type="button" class="add-button" onclick="addProduct()">Plus</button>
          </div>

          <button type="submit" class="submit-button">Soumettre</button>
        </form>
      </div>
    </div>
  </div>

  <script>
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
      if (event.target == modal) {
        modal.style.display = "none";
      }
    }

    let productCount = 1;

    function addProduct() {
      productCount++;
      if (productCount <= 5) {
        const productContainer = document.getElementById('productsContainer');
        const newProductForm = document.createElement('div');
        newProductForm.className = 'product-form';
        newProductForm.innerHTML = `
          <h4>Produit ${productCount}</h4>
          <label>Nom du produit:</label>
          <input type="text" name="productName[]" required>
          <label>Prix Produit</label>
          <input type="number" name="price[]" required>
          <label for="seuil">Seuil Limite</label>
          <input type="number" name="seuil[]" min="1" required>
          <button type="button" class="remove-button" onclick="removeProduct(this)">Supprimer</button>
        `;
        productContainer.appendChild(newProductForm);
      } else {
        alert("Nombre d'ajout maximum atteint");
      }
    }

    function removeProduct(button) {
      productCount--;
      const productForm = button.parentElement;
      productForm.remove();
    }
  </script>
</body>

</html>