<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $host = 'localhost';
    $port = '5432';
    $db   = 'five';
    $user = 'postgres';
    $pass = 'toto';
    $nom = $_POST['nom'];
    $capacite = $_POST['capacite'];
    $typeterrain = $_POST['typeterrain'];
    $etat = $_POST['etat'];
    $adresse = $_POST['adresse'];
    $success = false;
    $images = "";

    // Gérer le téléchargement des images
    if (isset($_FILES['images'])) {
        $file_tmp = $_FILES['images']['tmp_name'];
        $file_name = $_FILES['images']['name'];
        $images = "uploads/" . $file_name;
        move_uploaded_file($file_tmp, $images);
    }

    try {
        // Connexion à la base de données
        $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$db", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Requête d'insertion du terrain dans la base de données
        $stmt = $pdo->prepare('INSERT INTO terrain (nom, capacite, typeterrain, etat, images, adresse) VALUES (?, ?, ?, ?, ?, ?)');
        $success = $stmt->execute([$nom, $capacite, $typeterrain, $etat, $images, $adresse]);
    } catch (PDOException $e) {
        echo 'Erreur de connexion à la base de données : ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ajouter un terrain</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            padding: 50px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            color: #4CAF50;
            font-size: 36px;
        }

        .form-container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);
            max-width: 500px;
            margin: auto;
        }

        .form-field {
            margin-bottom: 10px;
        }

        .form-field label {
            display: block;
            margin-bottom: 5px;
        }

        .form-field input {
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

        .error {
            color: red;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="header">
    <h1>Ajouter un terrain</h1>
</div>
<div class="form-container">
    <form method="post" action="" enctype="multipart/form-data">
        <div class="form-field">
            <label for="nom">Nom du terrain:</label>
            <input type="text" id="nom" name="nom" required>
        </div>
        <div class="form-field">
            <label for="capacite">Capacité:</label>
            <input type="number" id="capacite" name="capacite">
        </div>
        <div class="form-field">
            <label for="typeterrain">Type de terrain:</label>
            <input type="text" id="typeterrain" name="typeterrain">
        </div>
        <div class="form-field">
            <label for="etat">État:</label>
            <input type="text" id="etat" name="etat">
        </div>
        <div class="form-field">
            <label for="images">Images:</label>
            <input type="file" id="images" name="images">
        </div>
        <div class="form-field">
            <label for="adresse">Adresse:</label>
            <input type="text" id="adresse" name="adresse" required>
        </div>
        <div class="form-field">
            <input type="submit" value="Ajouter">
        </div>
    </form>
</div>
</body>
</html>
