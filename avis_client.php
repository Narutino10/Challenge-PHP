<?php
session_start();

// Connexion à la base de données
$host = 'localhost';
$port = '5432';
$dbname = 'five';
$username = 'postgres';
$password = 'toto';

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Vérifier si l'utilisateur est connecté
    $client_id = $_SESSION['client_id'] ?? null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Enregistrer l'avis dans la base de données
        $note = $_POST['note'];
        $message = $_POST['message'];

        $stmt = $pdo->prepare('INSERT INTO avis (client_id, note, message) VALUES (?, ?, ?)');
        $stmt->execute([$client_id, $note, $message]);
    }

    // Récupérer tous les avis
    $stmt = $pdo->query('SELECT avis.note, avis.message, client.nom FROM avis JOIN client ON avis.client_id = client.id');
    $avis = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo 'Erreur de connexion à la base de données : ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Avis</title>
    <style>
        form {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        textarea {
            width: 100%;
            height: 100px;
        }
        .avis {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            background-color: #f9f9f9;
        }
        .avis .nom {
            font-size: 16px;
            font-weight: bold;
        }
        .avis .note {
            font-size: 14px;
            color: #888;
        }
        .avis .message {
            margin-top: 5px;
        }
    </style>
</head>
<body>
<h1>Avis</h1>

<?php if ($client_id) : ?>
    <form method="POST" action="avis.php">
        <label for="note">Note :</label>
        <select id="note" name="note">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
        </select>
        <br>
        <label for="message">Message :</label>
        <textarea id="message" name="message" rows="4" cols="50"></textarea>
        <br>
        <input type="submit" value="Envoyer">
    </form>
<?php endif; ?>

<h2>Avis existants :</h2>
<?php foreach ($avis as $avisItem) : ?>
    <div class="avis">
        <div class="nom"><?php echo $avisItem['nom']; ?></div>
        <div class="note">Note : <?php echo $avisItem['note']; ?></div>
        <div class="message"><?php echo $avisItem['message']; ?></div>
    </div>
<?php endforeach; ?>
</body>
</html>
