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
    $stmt = $pdo->query('SELECT * FROM terrain');
    $terrains = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo 'Erreur de connexion à la base de données : ' . $e->getMessage();
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

    <form method="post" action="reservation_handler.php">
        <div class="form-field">
            <label for="terrain">Terrain:</label>
            <select id="terrain" name="terrain">
                <?php foreach ($terrains as $terrain) : ?>
                    <option value="<?php echo $terrain['id']; ?>"><?php echo $terrain['nom']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-field">
            <label for="date">Date:</label>
            <input type="date" id="date" name="date">
        </div>

        <div class="form-field">
            <label for="time">Heure:</label>
            <input type="time" id="time" name="time">
        </div>

        <div class="form-field">
            <input type="submit" value="Réserver">
        </div>
    </form>
</div>

</body>
</html>
