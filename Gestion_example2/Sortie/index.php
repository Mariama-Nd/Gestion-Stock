<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Barre de Recherche Stylisée</title>
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
      width: 100%;
      max-width: 600px;
      background: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      box-sizing: border-box;
    }

    .search-container {
      position: relative;
      margin-bottom: 20px;
    }

    .search-input {
      width: 100%;
      padding: 15px 50px;
      border: 2px solid #28a745;
      border-radius: 30px;
      font-size: 16px;
      outline: none;
      transition: all 0.3s ease;
      box-sizing: border-box;
    }

    .search-input:focus {
      border-color: #218838;
      box-shadow: 0 0 5px rgba(40, 167, 69, 0.5);
    }

    .search-button {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      background-color: #28a745;
      border: none;
      border-radius: 30px;
      color: white;
      padding: 10px 12px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .search-button:hover {
      background-color: #218838;
    }

    .validate-button {
      display: block;
      width: 120px;
      padding: 8px;
      background-color: #28a745;
      border: none;
      border-radius: 30px;
      color: white;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s ease;
      margin: 0 auto;
      margin-top: 10px;
    }

    .validate-button:hover {
      background-color: #218838;
    }

    @media (max-width: 600px) {

      .search-input,
      .validate-button {
        padding: 10px;
        font-size: 14px;
      }

      .search-button {
        padding: 8px 10px;
      }
    }
  </style>
</head>

<body>
  <div class="form-container">
  <button class="validate-button" onclick="location.href='Liste_bon_sortie.php'">Retour</button><br>
    <div class="search-container">
      <input type="number" class="search-input" id="matricule-input" placeholder="Matricule de l'agent" required />
      <button class="search-button" onclick="appel()">🔍</button>
    </div>
    <div id="creation" hidden>
      <input type="text" class="search-input" id="service" inert />
      <button class="validate-button" onclick="valider()">Valider</button>
    </div>
  </div>

  <script>
    let nom 
    let prenom 
    const users = [
      {
        matricule: '230007',
        nom: 'Ndiaye',
        prenom: 'Mariama',
        Structure: 'DRIAT'
      },
      {
        matricule: '230449',
        nom: 'Said',
        prenom: 'Mohamed',
        Structure: 'Finances'
      },
      {
        matricule: '230347',
        nom: 'Seye',
        prenom: 'Robert',
        Structure: 'Academy'
      }
    ];

    function appel() {
      let matricule = document.getElementById('matricule-input').value;
      let input_info = document.getElementById('service');
      let div_cacher = document.getElementById('creation');

      // Vérification si l'input est vide
      if (!matricule) {
        alert("Veuillez d'abord saisir un matricule");
        div_cacher.hidden = true;
        return;
      }

      // Vérification si le matricule a une longueur différente de 6 chiffres
      if (matricule.length !== 6) {
        alert("Veuillez saisir un matricule valide");
        div_cacher.hidden = true;
        return;
      }
      let reponse = search(matricule);
      if (!reponse) {
        alert("Agent inexistant");
        div_cacher.hidden = true;
      } else {
        div_cacher.hidden = false;
        //alert("Service : " + reponse);
        input_info.value = reponse;
        document.getElementById('matricule-input').inert = true
      }
    }
    function search(matricule) {
      for (let i = 0; i < users.length; i++) {
        if (matricule === users[i].matricule) {
          nom= remplis(users[i].nom)
          prenom = remplis2(users[i].prenom)
          return users[i].Structure;
       

        }
      }
      return false;
    }

    function remplis(nom){
      //alert(nom)
      return nom

    }

    function remplis2(prenom){
   // alert(prenom)
    return prenom

}

    function valider() {
      let service = document.getElementById('service').value;
      let matricule = document.getElementById('matricule-input').value;
      let div_cacher = document.getElementById('creation');
    if(confirm("Voulez-vous Creer un nouveau Bon de Sortie ")){
      const xhr = new XMLHttpRequest();
      xhr.open('POST', 'Add_Bon_sortie.php', true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.send(`matricule=${matricule}&service=${service}&nom=${nom}&prenom=${prenom}`);

      // Handle response
      xhr.onload = function () {
        if (xhr.status === 200) {
          alert("Bon de Sortie Editer")
         location.reload()
         matricule = ''
         service = ''
         location.href = 'Liste_bon_sortie.php'
         
        } else {
          alert('Erreur lors de l\'enregistrement du produit');
        }
      };
    }else{
      div_cacher.hidden = true
      document.getElementById('matricule-input').inert = false
    }

    }
  </script>
</body>
</html>