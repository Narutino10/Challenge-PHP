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

    // Récupération des terrains disponibles
    $stmt = $pdo->query('SELECT * FROM terrain WHERE location = \'libre\'');
    $terrains = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo 'Erreur de connexion à la base de données : ' . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $terrainNom = $_POST['terrain'];
    $dateLocation = $_POST['date'];

    try {
        // Vérification de la disponibilité du terrain
        $stmt = $pdo->prepare('SELECT * FROM terrain WHERE nom = :terrain_nom AND location = \'libre\'');
        $stmt->bindParam(':terrain_nom', $terrainNom);
        $stmt->execute();
        $terrain = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$terrain) {
            echo 'Ce terrain n\'est pas disponible pour la réservation.';
            exit();
        }

        // Mise à jour de l'état du terrain dans la table "terrain"
        $updateStmt = $pdo->prepare('UPDATE terrain SET location = \'occupé\' WHERE nom = :terrain_nom');
        $updateStmt->bindParam(':terrain_nom', $terrainNom);
        $updateStmt->execute();

        echo 'Le terrain a été réservé avec succès !';

        // Redirection vers le dashboard client
        header('Location: dashboard_client.php');
        exit();

    } catch (PDOException $e) {
        echo 'Erreur lors de la réservation : ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>FiveZone - Réservation de terrain</title>
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

        form {
            margin-top: 20px;
        }

        .form-field {
            margin-bottom: 10px;
        }

        .form-field label {
            display: block;
            margin-bottom: 5px;
        }

        .form-field input,
        .form-field select {
            width: 100%;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        .form-field input[type="submit"] {
            background: #4CAF50;
            border: 0;
            color: white;
            cursor: pointer;
        }

        .form-field input[type="submit"]:hover {
            background: #45a049;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Réservation de terrain</h1>

    <form method="post" action="reserve.php">
        <div class="form-field">
            <label for="terrain">Terrain:</label>
            <select id="terrain" name="terrain">
                <?php foreach ($terrains as $terrain) : ?>
                    <option value="<?php echo $terrain['nom']; ?>"><?php echo $terrain['nom']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-field">
            <label for="date">Date:</label>
            <input type="date" id="date" name="date">
        </div>

        <div class="form-field">
            <input type="submit" value="Réserver">
        </div>
    </form>

</div>

</body>
</html>
