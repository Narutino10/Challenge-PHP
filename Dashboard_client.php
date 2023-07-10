<?php
// Connexion à la base de données
$host = 'localhost';
$port = '5432';
$dbname = 'five';
$username = 'postgres';
$password = 'toto';

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupération des terrains les plus utilisés avec leur nom et le nombre de locations
    $stmt1 = $pdo->query('SELECT terrain.nom, COUNT(*) AS nb_locations FROM locations
                          JOIN terrain ON locations.terrain_id = terrain.id
                          GROUP BY terrain.nom
                          ORDER BY nb_locations DESC
                          LIMIT 5');
    $terrainsUtilises = $stmt1->fetchAll(PDO::FETCH_ASSOC);

    // Récupération des statistiques du joueur connecté
    $stmt2 = $pdo->prepare('SELECT COUNT(*) AS total_locations FROM locations WHERE joueur_id = :joueur_id');
    $stmt2->bindParam(':joueur_id', $joueurId); // Remplacez $joueurId par l'identifiant du joueur connecté
    $stmt2->execute();
    $statistiquesJoueur = $stmt2->fetch(PDO::FETCH_ASSOC);

    // Récupération de la prochaine location du joueur connecté
    $stmt3 = $pdo->prepare('SELECT MIN(date_location) AS prochaine_location FROM locations WHERE joueur_id = :joueur_id');
    $stmt3->bindParam(':joueur_id', $joueurId); // Remplacez $joueurId par l'identifiant du joueur connecté
    $stmt3->execute();
    $prochaineLocation = $stmt3->fetch(PDO::FETCH_ASSOC)['prochaine_location'];

} catch (PDOException $e) {
    echo 'Erreur de connexion à la base de données : ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
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

        h2 {
            font-size: 20px;
            color: #333;
            margin: 0 0 10px;
            padding: 0;
        }

        p {
            font-size: 16px;
            color: #555;
            margin: 0 0 10px;
            padding: 0;
        }

        ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        li {
            margin-bottom: 5px;
            color: #555;
        }

        .message {
            padding: 10px;
            background-color: #2ecc71;
            color: #fff;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .message.error {
            background-color: #e74c3c;
        }

        form {
            margin-bottom: 10px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
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
    <h1>Tableau de bord</h1>

    <?php if (isset($_GET['message'])) : ?>
        <div class="message <?php echo ($_GET['success'] === '1') ? 'success' : 'error'; ?>">
            <?php echo $_GET['message']; ?>
        </div>
    <?php endif; ?>

    <div>
        <h2>Terrains les plus utilisés</h2>
        <?php if (count($terrainsUtilises) > 0) : ?>
            <ul>
                <?php foreach ($terrainsUtilises as $terrain) : ?>
                    <li>Terrain <?php echo $terrain['nom']; ?> (<?php echo $terrain['nb_locations']; ?> locations)</li>
                <?php endforeach; ?>
            </ul>
        <?php else : ?>
            <p>Aucun terrain utilisé pour le moment.</p>
        <?php endif; ?>
    </div>

    <div>
        <h2>Statistiques du joueur connecté</h2>
        <p>Total des locations : <?php echo $statistiquesJoueur['total_locations']; ?></p>
    </div>

    <div>
        <h2>Date de la prochaine location</h2>
        <?php if ($prochaineLocation) : ?>
            <p>Prochaine location le : <?php echo $prochaineLocation; ?></p>
        <?php else : ?>
            <p>Aucune location prévue pour le moment.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
