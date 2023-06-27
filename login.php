<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['mail'], $_POST['nom'], $_POST['prenom'], $_POST['password'])) {
        $host = 'localhost';
        $port = '5432'; // Le port par défaut de PostgreSQL est généralement 5432, mais cela peut varier en fonction de votre configuration
        $db   = 'five';
        $user = 'postgres';
        $pass = 'toto';

        $dsn = "pgsql:host=$host;port=$port;dbname=$db";
        $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

        $mail = $_POST['mail'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $stmt = $pdo->prepare('INSERT INTO Client (Mail, Nom, Prenom, MDP) VALUES (?, ?, ?, ?)');
        $stmt->execute([$mail, $nom, $prenom, $password]);

        header("Location: login.php");
        exit();
    } else {
        echo 'Veuillez remplir tous les champs.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Page d'Inscription</title>
</head>
<body>
<form method="post" action="">
    <label for="mail">Email:</label><br>
    <input type="text" id="mail" name="mail"><br>
    <label for="nom">Nom:</label><br>
    <input type="text" id="nom" name="nom"><br>
    <label for="prenom">Prenom:</label><br>
    <input type="text" id="prenom" name="prenom"><br>
    <label for="password">Mot de passe:</label><br>
    <input type="password" id="password" name="password"><br><br>
    <input type="submit" value="Inscription">
</form>
</body>
</html>
