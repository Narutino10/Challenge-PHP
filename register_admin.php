<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $host = 'localhost';
    $port = '5432';
    $db   = 'five';
    $user = 'postgres';
    $pass = 'toto';

    $dsn = "pgsql:host=$host;port=$port;dbname=$db";
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    $mail = $_POST['mail'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role']; // Récupérer le rôle de l'utilisateur

    $stmt = $pdo->prepare('INSERT INTO client (Mail, Nom, Prenom, MDP, role) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$mail, $nom, $prenom, $password, $role]);

    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>FiveZone - Inscription</title>
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

        .error {
            color: red;
            margin-bottom: 20px;
        }

        .switch {
            text-align: center;
            margin-top: 20px;
        }

        .switch a {
            color: #4CAF50;
            text-decoration: none;
        }

        .switch a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="header">
    <h1>FiveZone</h1>
</div>
<div class="form-container">
    <form method="post" action="">
        <div class="form-field">
            <label for="mail">Email:</label>
            <input type="text" id="mail" name="mail">
        </div>
        <div class="form-field">
            <label for="nom">Nom:</label>
            <input type="text" id="nom" name="nom">
        </div>
        <div class="form-field">
            <label for="prenom">Prenom:</label>
            <input type="text" id="prenom" name="prenom">
        </div>
        <div class="form-field">
            <label for="password">Mot de passe:</label>
            <input type="password" id="password" name="password">
        </div>
        <div class="form-field">
            <label for="role">Rôle:</label>
            <select id="role" name="role">
                <option value="client">Client</option>
                <option value="admin">Administrateur</option>
            </select>
        </div>
        <div class="form-field">
            <input type="submit" value="Inscription">
        </div>
    </form>
    <div class="switch">
        <a href="login.php">Vous avez déjà un compte ? Connectez-vous ici</a>
    </div>
</div>
</body>
</html>
