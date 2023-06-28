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
    $images = $_POST['images'];
    $adresse = $_POST['adresse'];


    try {
        // Connexion à la base de données
        $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$db", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Requête d'insertion du terrain dans la base de données
        $stmt = $pdo->prepare('INSERT INTO terrain (nom, capacite, typeterrain, etat, images, adresse) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([$nom, $capacite, $typeterrain, $etat, $images, $adresse]);

        // Message de réussite pour la fenêtre modale
        $successMessage = "Le terrain a été ajouté avec succès !";
    } catch (PDOException $e) {
        echo 'Erreur de connexion à la base de données : ' . $e->getMessage();
    }
}

// Redirection vers la page de tableau de bord après l'ajout du terrain
if (isset($successMessage)) {
    header('Location: dashboard.php?message=' . urlencode($successMessage));
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ajouter un terrain</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
        }

        h1 {
            color: #fff;
            text-align: center;
            background-color: #333;
            padding: 20px;
            margin: 0;
        }

        .container {
            max-width: 500px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button[type="submit"] {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
    <script>
        // Afficher le message de réussite dans une fenêtre modale
        window.addEventListener('DOMContentLoaded', (event) => {
            <?php if (isset($_GET['message'])) : ?>
            alert('<?php echo $_GET['message']; ?>');
            <?php endif; ?>
        });
    </script>
</head>
<body>
<h1>Ajouter un terrain</h1>

<div>
    <form method="post" action="ajouter_terrain.php">
        <label for="nom">Nom du terrain:</label>
        <input type="text" id="nom" name="nom" required>

        <label for="capacite">Capacité:</label>
        <input type="number" id="capacite" name="capacite">

        <label for="typeterrain">Type de terrain:</label>
        <input type="text" id="typeterrain" name="typeterrain">

        <label for="etat">État:</label>
        <input type="text" id="etat" name="etat">

        <label for="images">Images:</label>
        <input type="text" id="images" name="images">

        <label for="adresse">Adresse:</label>
        <input type="text" id="adresse" name="adresse" required>

        <button type="submit" onclick="redirectToDashboard()">Créer</button>
    </form>
</div>

<script>
    function redirectToDashboard() {
        window.location.href = 'dashboard.php?message=<?php echo urlencode($successMessage); ?>';
    }
</script>
</body>
</html>
