<?php
session_start();
$host = 'localhost';
$port = '5432';
$dbname = 'five';
$username = 'postgres';
$password = 'toto';

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupération des joueurs avec leurs statistiques
    $stmt = $pdo->query('SELECT client.id, client.nom, client.images, stat.matchjouer, stat.matchgagner, stat.but, stat.passedecisive 
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
        header {
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        nav {
            margin-bottom: 20px;
        }

        nav a {
            margin-right: 10px;
            color: #333;
            text-decoration: none;
        }

        nav a:hover {
            color: #888;
        }

        .top-players {
            margin-top: 40px;
        }

        .top-players h2 {
            margin-bottom: 20px;
        }

        .player-card {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .player-card img {
            width: 50px;
            height: 50px;
            margin-right: 10px;
            object-fit: cover;
            border-radius: 50%;
        }

        .player-card .player-info {
            flex: 1;
        }
    </style>
</head>
<body>
<header>
    <nav>
        <a href="terrain.php">Location terrain</a>
        <a href="stat_client.php">Statistiques complètes</a>
        <a href="loc_en_cours.php">Prochaine location</a>
        <a href="login.php">Déconnexion</a>
    </nav>
</header>
<div class="container">
    <h1>Liste des joueurs</h1>
    <p>Bienvenue, <?php echo isset($_SESSION['nom']) ? $_SESSION['nom'] : 'Invité'; ?></p>
    <div id="joueur-details">
        <img src="" alt="" id="joueur-photo">
        <h2 id="nom-joueur"></h2>
        <div id="joueur-stats">
            <p><span>Matchs joués:</span> <span id="matchs-joues"></span></p>
            <p><span>Matchs gagnés:</span> <span id="matchs-gagnes"></span></p>
            <p><span>Buts:</span> <span id="buts"></span></p>
            <p><span>Passes décisives:</span> <span id="passes-decisives"></span></p>
        </div>
    </div>

    <div class="top-players">
        <h2>Top 3 des meilleurs joueurs</h2>
        <?php
        // Trier les joueurs par nombre de buts décroissant
        usort($joueurs, function ($a, $b) {
            return $b['but'] - $a['but'];
        });

        // Afficher uniquement les 3 meilleurs joueurs
        for ($i = 0; $i < min(count($joueurs), 3); $i++) {
            $joueur = $joueurs[$i];
            ?>
            <div class="player-card">
                <img src="<?php echo $joueur['images']; ?>" alt="Photo du joueur">
                <div class="player-info">
                    <p><span>Nom:</span> <?php echo $joueur['nom']; ?></p>
                    <p><span>Matchs gagnés:</span> <?php echo $joueur['matchgagner']; ?></p>
                    <p><span>Buts:</span> <?php echo $joueur['but']; ?></p>
                </div>
            </div>
        <?php } ?>
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
                joueurPhoto.src = data.images;
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
