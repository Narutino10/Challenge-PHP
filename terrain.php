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

    // Récupération de tous les terrains
    $stmt = $pdo->query('SELECT * FROM terrain');
    $terrains = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo 'Erreur de connexion à la base de données : ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Location de terrains en ligne</title>
    <style>
        .terrain {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            background-color: #f9f9f9;
        }

        .terrain.loue {
            background-color: #f2f2f2;
        }

        .terrain .nom {
            font-size: 18px;
            font-weight: bold;
        }

        .terrain .etat {
            font-size: 14px;
            color: #888;
        }

        .terrain .location-button {
            display: inline-block;
            padding: 8px 12px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }

        .terrain.loue .location-button {
            background-color: #ccc;
            cursor: not-allowed;
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
    <h2>Tous les terrains</h2>

    <?php foreach ($terrains as $terrain) : ?>
        <div class="terrain <?php echo ($terrain['location'] === 'Loué') ? 'loue' : ''; ?>">
            <div class="nom"><?php echo $terrain['nom']; ?></div>
            <div class="etat">État : <?php echo $terrain['location']; ?></div>
            <?php if ($terrain['location'] === 'Disponible') : ?>
                <a href="reserve.php?terrain=<?php echo urlencode($terrain['nom']); ?>" class="location-button">Louer</a>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>
</body>
</html>