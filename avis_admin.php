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

    // Récupération des avis
    $stmt = $pdo->query('SELECT * FROM avis');
    $avis = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo 'Erreur de connexion à la base de données : ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestion des avis</title>
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

        .avis {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            background-color: #f9f9f9;
        }

        .avis .auteur {
            font-size: 18px;
            font-weight: bold;
        }

        .avis .note {
            margin-top: 10px;
        }

        .avis .message {
            margin-top: 10px;
        }

        .reponse {
            margin-top: 10px;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }

        .reponse textarea {
            width: 100%;
            padding: 5px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        .reponse button {
            margin-top: 10px;
            padding: 5px 10px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Gestion des avis</h1>

    <?php foreach ($avis as $avis) : ?>
        <div class="avis">
            <div class="auteur"><?php echo $avis['auteur']; ?></div>
            <div class="note">Note : <?php echo $avis['note']; ?>/5</div>
            <div class="message"><?php echo $avis['message']; ?></div>
            <?php if (empty($avis['reponse'])) : ?>
                <div class="reponse">
                    <strong>Répondre à l'avis :</strong><br>
                    <textarea id="reponse-<?php echo $avis['id']; ?>"></textarea><br>
                    <button onclick="repondre(<?php echo $avis['id']; ?>)">Envoyer</button>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

    <script>
        function repondre(id) {
            var textarea = document.getElementById('reponse-' + id);
            var reponse = textarea.value.trim();
            }
    </script>
</div>
</body>
</html>
