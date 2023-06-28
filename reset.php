<?php
$host = 'localhost';
$port = '5432';
$db   = 'five';
$user = 'postgres';
$pass = 'toto';

$dsn = "pgsql:host=$host;port=$port;dbname=$db";
$pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

// Vérifier si le formulaire a été soumis
if (isset($_POST['submit'])) {
    // Récupérer le token et le nouveau mot de passe soumis
    $token = $_POST['token'];
    $newPassword = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    // Vérifier si le token existe dans la base de données
    if (tokenExists($token, $pdo)) {
        // Mettre à jour le mot de passe de l'utilisateur avec le nouveau mot de passe
        updatePassword($token, $newPassword, $pdo);

        // Supprimer le token de la base de données
        deleteToken($token, $pdo);

        // Afficher un message de succès
        echo 'Votre mot de passe a été réinitialisé avec succès.';

        // Rediriger l'utilisateur vers la page de connexion
        header('Location: login.php');
        exit();
    } else {
        // Le token est invalide ou a expiré
        echo 'Le lien de réinitialisation du mot de passe est invalide ou a expiré.';
    }
}

// Fonction pour vérifier si le token existe dans la base de données
function tokenExists($token, $pdo) {
    $stmt = $pdo->prepare('SELECT * FROM tokens WHERE token = ?');
    $stmt->execute([$token]);
    return $stmt->rowCount() > 0;
}

// Fonction pour mettre à jour le mot de passe de l'utilisateur avec le nouveau mot de passe
function updatePassword($token, $newPassword, $pdo) {
    $stmt = $pdo->prepare('UPDATE Client SET MDP = ? WHERE Mail = (SELECT Mail FROM tokens WHERE token = ?)');
    $stmt->execute([$newPassword, $token]);
}

// Fonction pour supprimer le token de la base de données
function deleteToken($token, $pdo) {
    $stmt = $pdo->prepare('DELETE FROM tokens WHERE token = ?');
    $stmt->execute([$token]);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>FiveZone - Réinitialisation du mot de passe</title>
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

        .form-field button {
            background: #4CAF50;
            border: 0;
            color: white;
            cursor: pointer;
            padding: 10px;
            border-radius: 4px;
            width: 100%;
        }

        .form-field button:hover {
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
    <form method="POST" action="">
        <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">
        <div class="form-field">
            <label for="new_password">Nouveau mot de passe:</label>
            <input type="password" name="new_password" required>
        </div>
        <div class="form-field">
            <button type="submit" name="submit">Réinitialiser le mot de passe</button>
        </div>
    </form>
    <div class="switch">
        <a href="login.php">Se rappeler du mot de passe? Retour à la connexion</a>
    </div>
</div>
</body>
</html>

