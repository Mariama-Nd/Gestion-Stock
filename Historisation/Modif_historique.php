<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Formulaire de modification de produit</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .form-container {
      background-color: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
      max-width: 500px;
      width: 100%;
    }

    h1 {
      text-align: center;
      color: #333;
      margin-bottom: 30px;
    }

    .form-group {
      margin-bottom: 20px;
    }

    label {
      display: block;
      font-weight: bold;
      color: #333;
      margin-bottom: 5px;
    }

    select, input[type="date"], input[type="number"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 16px;
    }

    button {
      display: block;
      width: 100%;
      padding: 10px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
    }
    a {
      width: 20%;
      padding: 10px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
      text-decoration: none;
    }
    button:hover {
      background-color: #45a049;
    }

    @media (max-width: 767px) {
      .form-container {
        padding: 20px;
      }
    }
  </style>
</head>
<body>
  <div class="form-container">
    <h1>Modification de produit</h1>
    <form>
      <div class="form-group">
        <label for="categorie">Catégorie</label>
        <select id="categorie" name="categorie">
          <option value="">Sélectionnez une catégorie</option>
          <option value="cat1">Catégorie 1</option>
          <option value="cat2">Catégorie 2</option>
          <option value="cat3">Catégorie 3</option>
        </select>
      </div>
      <div class="form-group">
        <label for="sous-categorie">Sous-catégorie</label>
        <select id="sous-categorie" name="sous-categorie">
          <option value="">Sélectionnez une sous-catégorie</option>
          <option value="souscat1">Sous-catégorie 1</option>
          <option value="souscat2">Sous-catégorie 2</option>
          <option value="souscat3">Sous-catégorie 3</option>
        </select>
      </div>
      <div class="form-group">
        <label for="nom-produit">Nom du produit</label>
        <select id="nom-produit" name="nom-produit">
          <option value="">Sélectionnez un produit</option>
          <option value="prod1">Produit 1</option>
          <option value="prod2">Produit 2</option>
          <option value="prod3">Produit 3</option>
        </select>
      </div>
      <div class="form-group">
        <label for="date-modif">Date de modification</label>
        <input type="date" id="date-modif" name="date-modif">
      </div>
      <div class="form-group">
        <label for="stock">Stock</label>
        <input type="number" id="stock" name="stock" min="0">
      </div>
      <button type="submit" style="background-color: #4CAF50;">Enregistrer</button><br>
      <a href="historisation.php" type="submit" style="background-color: #4CAF50;">Retour</a>
    </form>
  </div>
</body>
</html>