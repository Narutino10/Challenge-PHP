<?php
$host = 'localhost';
$port = '5432';
$dbname = 'five';
$username = 'postgres';
$password = 'toto';

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupération des joueurs avec leurs statistiques
    $stmt = $pdo->query('SELECT client.nom, client.photo, client.matchjouer, client.matchgagner, stat.but, stat.passedecisive 
                         FROM client 
                         JOIN stat ON client.id = stat.id');
    $joueurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'Erreur de connexion à la base de données : ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Liste des joueurs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        h1 {
            font-size: 24px;
            color: #333;
            margin: 0 0 20px;
            padding: 0;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        select {
            width: 100%;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
            margin-bottom: 20px;
        }

        img {
            max-width: 200px;
            margin-bottom: 20px;
        }

        p {
            margin-bottom: 10px;
        }

        .stat {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .stat span {
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Liste des joueurs</h1>

    <label for="joueur">Sélectionnez un joueur :</label>
    <select id="joueur" name="joueur">
        <?php foreach ($joueurs as $joueur) : ?>
            <option value="<?php echo $joueur['id']; ?>"><?php echo $joueur['nom']; ?></option>
        <?php endforeach; ?>
    </select>

    <div id="joueur-details">
        <img src="" alt="" id="joueur-photo">
        <div id="joueur-stats">
            <p><span>Nom:</span> <span id="nom-joueur"></span></p>
            <p><span>Matchs joués:</span> <span id="matchs-joues"></span></p>
            <p><span>Matchs gagnés:</span> <span id="matchs-gagnes"></span></p>
            <p><span>Buts:</span> <span id="buts"></span></p>
            <p><span>Passes décisives:</span> <span id="passes-decisives"></span></p>
        </div>
    </div>
</div>

<script>
    const selectJoueur = document.getElementById('joueur');
    const joueurPhoto = document.getElementById('joueur-photo');
    const nomJoueur = document.getElementById('nom-joueur');
    const matchsJoues = document.getElementById('matchs-joues');
    const matchsGagnes = document.getElementById('matchs-gagnes');
    const buts = document.getElementById('buts');
    const passesDecisives = document.getElementById('passes-decisives');

    selectJoueur.addEventListener('change', (event) => {
        const joueurId = event.target.value;

        // Effectuer une requête AJAX pour récupérer les détails du joueur sélectionné
        fetch('get_joueur_details.php?id=' + joueurId)
            .then(response => response.json())
            .then(data => {
                // Mettre à jour les éléments HTML avec les détails du joueur
                joueurPhoto.src = data.photo;
                nomJoueur.textContent = data.nom;
                matchsJoues.textContent = data.matchjouer;
                matchsGagnes.textContent = data.matchgagner;
                buts.textContent = data.but;
                passesDecisives.textContent = data.passedecisive;
            })
            .catch(error => console.log(error));
    });
</script>
</body>
</html>
