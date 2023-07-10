<?php
$host = 'localhost';
$port = '5432';
$dbname = 'five';
$username = 'postgres';
$password = 'toto';

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupération des terrains loués avec leurs détails
    $stmt = $pdo->query('SELECT location.id, client.nom, location.date_location, location.horaire, location.score 
                         FROM location 
                         JOIN client ON location.client_id = client.id');
    $locations = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo 'Erreur de connexion à la base de données : ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Location des terrains</title>
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

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
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
<header>
    <nav>
        <a href="ajout_terrain.php">Terrains</a>
        <a href="stat_admin.php">Statistiques complètes</a>
        <a href="loc_en_cours.php">Locations en cours</a>
        <a href="login.php">Déconnexion</a>
        <a href="register_admin.php">New Admin</a>
    </nav>
</header>
<body>
<div class="container">
    <h1>Location des terrains</h1>
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Nom du client</th>
            <th>Date de location</th>
            <th>Horaire</th>
            <th>Score actuel</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($locations as $location) : ?>
            <tr>
                <td><?php echo $location['id']; ?></td>
                <td><?php echo $location['nom']; ?></td>
                <td><?php echo $location['date_location']; ?></td>
                <td><?php echo $location['horaire']; ?></td>
                <td><?php echo $location['score']; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
