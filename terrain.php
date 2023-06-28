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
        /* Votre CSS ici */
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
        }

        .terrain.loue .location-button {
            background-color: #ccc;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
<header>
    <h1>Location de terrains en ligne</h1>
</header>

<div class="container">
    <h2>Tous les terrains</h2>

    <?php foreach ($terrains as $terrain) : ?>
        <div class="terrain <?php echo ($terrain['etat'] === 'Loué') ? 'loue' : ''; ?>">
            <div class="nom"><?php echo $terrain['nom']; ?></div>
            <div class="etat">État : <?php echo $terrain['etat']; ?></div>
            <?php if ($terrain['etat'] === 'Disponible') : ?>
                <button class="location-button">Louer</button>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>
</body>
</html>
